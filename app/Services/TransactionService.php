<?php

namespace App\Services;

use App\Models\Buyer;
use App\Models\Cart;
use App\Models\StoreBalance;
use App\Models\StoreBalanceHistory;
use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class TransactionService
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository
    ) {}

    public function getBuyerOrders(int $userId)
    {
        $buyer = Buyer::where('user_id', $userId)->first();
        if (!$buyer) {
            return collect();
        }
        return $this->transactionRepository->getByBuyer($buyer->id);
    }

    public function getOrder(int $orderId, int $userId): Transaction
    {
        $order = $this->transactionRepository->find($orderId);
        $buyer = Buyer::where('user_id', $userId)->first();

        if (!$order || !$buyer || $order->buyer_id !== $buyer->id) {
            throw new Exception('Unauthorized access to this order');
        }

        return $order;
    }

    public function checkout(int $userId, array $data): void
    {
        DB::transaction(function () use ($userId, $data) {
            $buyer = Buyer::firstOrCreate(
                ['user_id' => $userId],
                ['phone_number' => \App\Models\User::find($userId)->email] // Assuming email as placeholder phone if not set
            );

            foreach ($data['stores'] as $storeData) {
                $storeId = $storeData['store_id'];

                // Get Cart Items for this store
                $cartItems = Cart::where('user_id', $userId)
                    ->whereHas('product', function ($q) use ($storeId) {
                        $q->where('store_id', $storeId);
                    })
                    ->with('product')
                    ->get();

                if ($cartItems->isEmpty()) {
                    continue;
                }

                // Check Stock
                foreach ($cartItems as $cartItem) {
                    if (!$cartItem->product->isAvailable($cartItem->quantity)) {
                        throw new Exception("Produk {$cartItem->product->name} stok tidak mencukupi");
                    }
                }

                $subtotal = $cartItems->sum('subtotal');
                $shippingCost = $storeData['shipping_cost'];
                $tax = $subtotal * 0.11;
                $grandTotal = $subtotal + $shippingCost + $tax;

                $transactionCode = 'TRX-' . strtoupper(Str::random(10));

                $transaction = $this->transactionRepository->create([
                    'code' => $transactionCode,
                    'buyer_id' => $buyer->id,
                    'store_id' => $storeId,
                    'address' => $data['address'],
                    'address_id' => 'ADDR-' . time(),
                    'city' => $data['city'],
                    'postal_code' => $data['postal_code'],
                    'shipping' => $storeData['shipping_type'],
                    'shipping_type' => $storeData['shipping_type'],
                    'shipping_cost' => $shippingCost,
                    'tax' => $tax,
                    'grand_total' => $grandTotal,
                    'payment_status' => 'unpaid',
                    'order_status' => 'pending',
                ]);

                foreach ($cartItems as $cartItem) {
                    $this->transactionRepository->createDetail([
                        'transaction_id' => $transaction->id,
                        'product_id' => $cartItem->product_id,
                        'qty' => $cartItem->quantity,
                        'subtotal' => $cartItem->subtotal,
                    ]);

                    $cartItem->product->decrement('stock', $cartItem->quantity);
                }

                // Clear these items from cart
                Cart::where('user_id', $userId)
                    ->whereIn('id', $cartItems->pluck('id'))
                    ->delete();
            }
        });
    }

    public function completeOrder(Transaction $order, int $userId): void
    {
        $buyer = Buyer::where('user_id', $userId)->first();

        if (!$buyer || $order->buyer_id !== $buyer->id) {
            throw new Exception('Unauthorized access to this order');
        }

        if ($order->order_status !== 'shipped') {
            throw new Exception('Hanya pesanan yang sedang dikirim yang dapat diselesaikan.');
        }

        DB::transaction(function () use ($order) {
            $lockedOrder = $this->transactionRepository->findLocked($order->id);

            if ($lockedOrder->order_status !== 'shipped') {
                throw new Exception('Pesanan tidak dalam status dikirim.');
            }

            $lockedOrder->update(['order_status' => 'completed']);

            // Calculate Earnings
            $productSubtotal = $lockedOrder->grand_total - $lockedOrder->shipping_cost - $lockedOrder->tax;
            $platformCommission = $productSubtotal * 0.03;
            $sellerEarnings = $productSubtotal + $lockedOrder->shipping_cost - $platformCommission;

            if ($lockedOrder->balance_credited_at === null) {
                $store = $lockedOrder->store;
                $storeBalance = StoreBalance::firstOrCreate(
                    ['store_id' => $store->id],
                    ['balance' => 0]
                );

                $storeBalance->increment('balance', $sellerEarnings);

                StoreBalanceHistory::create([
                    'store_balance_id' => $storeBalance->id,
                    'type' => 'income',
                    'amount' => $sellerEarnings,
                    'reference_id' => $lockedOrder->id,
                    'reference_type' => Transaction::class,
                    'remarks' => "Pesanan Diterima User #{$lockedOrder->code} (Produk: Rp " . number_format($productSubtotal, 0, ',', '.') . " + Ongkir: Rp " . number_format($lockedOrder->shipping_cost, 0, ',', '.') . " - Komisi 3%: Rp " . number_format($platformCommission, 0, ',', '.') . ")",
                ]);

                $lockedOrder->update(['balance_credited_at' => now()]);
            }
        });
    }

    public function uploadPaymentProof(Transaction $transaction, int $userId, $file): void
    {
        $buyer = Buyer::where('user_id', $userId)->first();

        if (!$buyer || $transaction->buyer_id !== $buyer->id) {
            throw new Exception('Unauthorized access to this order');
        }

        if ($transaction->payment_proof) {
            \Illuminate\Support\Facades\Storage::delete('public/' . $transaction->payment_proof);
        }

        $path = $file->store('payment_proofs', 'public');

        $this->transactionRepository->update($transaction, [
            'payment_proof' => $path,
            'payment_status' => 'waiting',
        ]);
    }
    public function getStoreOrders(int $userId, array $filters = [])
    {
        $store = \App\Models\Store::where('user_id', $userId)->firstOrFail();
        return $this->transactionRepository->getByStore($store->id, $filters);
    }

    public function updateOrderStatus(Transaction $order, int $userId, string $newStatus, ?string $trackingNumber = null): void
    {
        if ($order->store_id !== \App\Models\Store::where('user_id', $userId)->value('id')) {
            throw new Exception('Unauthorized access', 403);
        }

        if ($order->order_status === 'cancelled') {
             throw new Exception('Pesanan yang sudah dibatalkan tidak dapat diubah statusnya.');
        }

        if ($order->order_status === 'pending') {
             throw new Exception('Pesanan menunggu pembayaran tidak dapat diubah statusnya. Tunggu verifikasi admin.');
        }

        if ($order->order_status === 'shipped' && $newStatus === 'cancelled') {
             throw new Exception('Pesanan yang sudah dikirim tidak dapat dibatalkan.');
        }
        
        if ($newStatus === 'completed') {
             throw new Exception("Penjual tidak dapat mengubah status menjadi Selesai secara manual.");
        }

        DB::transaction(function () use ($order, $newStatus, $trackingNumber) {
            $updateData = ['order_status' => $newStatus];

            if ($trackingNumber) {
                $updateData['tracking_number'] = $trackingNumber;
            }

            if ($newStatus === 'cancelled') {
                 if ($order->payment_status === 'paid') {
                    $updateData['payment_status'] = 'refunded';
                }

                if ($order->balance_credited_at) {
                    $platformCommissionRate = 0.03;
                    $productSubtotal = $order->transactionDetails->sum('subtotal');
                    $shippingCost = $order->shipping_cost;

                    $commissionAmount = $productSubtotal * $platformCommissionRate;
                    $sellerIncome = ($productSubtotal + $shippingCost) - $commissionAmount;

                    StoreBalance::where('store_id', $order->store_id)->decrement('balance', $sellerIncome);

                    StoreBalanceHistory::create([
                        'store_balance_id' => $order->store->storeBalance->id,
                        'type' => 'withdrawal',
                        'amount' => $sellerIncome,
                        'reference_id' => $order->id,
                        'reference_type' => Transaction::class,
                        'remarks' => 'Pengurangan saldo # ' . $order->code . ' (Pesanan Dibatalkan)'
                    ]);

                    $updateData['balance_credited_at'] = null;
                }
            }

            $this->transactionRepository->update($order, $updateData);
        });
    }
}
