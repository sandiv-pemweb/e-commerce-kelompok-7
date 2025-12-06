<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\StoreBalance;
use App\Models\StoreBalanceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SellerOrderController extends Controller
{
    /**
     * Display a listing of orders for the seller's store.
     */
    public function index(Request $request)
    {
        $store = auth()->user()->store;

        $query = Transaction::where('store_id', $store->id)
                           ->with(['buyer.user', 'transactionDetails.product'])
                           ->latest();

        // Filter by order status if provided
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // Filter by payment status if provided
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->paginate(20);

        return view('seller.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Transaction $order)
    {
        // Ensure the order belongs to the seller's store
        if ($order->store_id !== auth()->user()->store->id) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        $order->load(['buyer.user', 'transactionDetails.product']);

        return view('seller.orders.show', compact('order'));
    }

    /**
     * Update the order status and tracking number.
     */
    public function update(Request $request, Transaction $order)
    {
        // 1. Authorization & Basic Checks
        if ($order->store_id !== Auth::user()->store->id) {
            abort(403);
        }

        // 2. Lock Check: Prevent editing if already cancelled
        if ($order->order_status === 'cancelled') {
            return back()->with('error', 'Pesanan yang sudah dibatalkan tidak dapat diubah statusnya.');
        }

        // Prevent update if status is pending (must wait for admin)
        if ($order->order_status === 'pending') {
            return back()->with('error', 'Pesanan menunggu pembayaran tidak dapat diubah statusnya. Tunggu verifikasi admin.');
        }

        // Prevent cancelling if already shipped
        if ($order->order_status === 'shipped' && $request->order_status === 'cancelled') {
            return back()->with('error', 'Pesanan yang sudah dikirim tidak dapat dibatalkan.');
        }

        // 3. Validation
        $validated = $request->validate([
            'order_status' => 'required|in:processing,shipped,cancelled',
            'tracking_number' => 'nullable|string|max:255',
        ]);

        // Require tracking number if status is shipped
        if ($request->order_status === 'shipped' && empty($request->tracking_number) && empty($order->tracking_number)) {
            return back()->with('error', 'Nomor resi wajib diisi untuk status "Dikirim".');
        }

        try {
            DB::beginTransaction();

            $oldStatus = $order->order_status;
            $newStatus = $validated['order_status'];

            // 4. Update tracking number if provided
            if ($request->has('tracking_number')) {
                $order->tracking_number = $request->tracking_number;
            }

            // 5. Status Transition Logic
            if ($newStatus === 'cancelled') {
                // HANDLE CANCELLATION
                
                // A. Refund Logic: If paid, switch to refunded
                if ($order->payment_status === 'paid') {
                    $order->payment_status = 'refunded';
                }

                // B. Balance Deduction Logic
                // Only deduct if it was previously credited to avoid double deduction
                if ($order->balance_credited_at) {
                    $platformCommissionRate = 0.03; // 3%
                    $productSubtotal = $order->transactionDetails->sum('subtotal');
                    $shippingCost = $order->shipping_cost;
                    
                    // Seller Income = (Product Subtotal + Shipping) - (Commission on Product)
                    $commissionAmount = $productSubtotal * $platformCommissionRate;
                    $sellerIncome = ($productSubtotal + $shippingCost) - $commissionAmount;

                    // Deduct from wallet
                    StoreBalance::where('store_id', $order->store_id)->decrement('balance', $sellerIncome);

                    // Record deduction history
                    StoreBalanceHistory::create([
                        'store_balance_id' => $order->store->storeBalance->id,
                        'type' => 'withdrawal', // Using 'withdrawal' as a debit operation
                        'amount' => $sellerIncome,
                        'reference_id' => $order->id,
                        'reference_type' => Transaction::class,
                        'remarks' => 'Pengurangan saldo # ' . $order->code . ' (Pesanan Dibatalkan)'
                    ]);

                    // Reset credited flag to allow re-credit if flow allows (though cancelled is final here)
                    $order->balance_credited_at = null;
                }

                $order->order_status = 'cancelled';
                $order->save();

            } else {
                // NORMAL STATUS UPDATE (Processing / Shipped)
                
                // Prevent transition to completed (Seller cannot complete)
                if ($newStatus === 'completed') {
                     throw new \Exception("Penjual tidak dapat mengubah status menjadi Selesai secara manual.");
                }

                $order->order_status = $newStatus;
                $order->save();
            }

            DB::commit();
            return back()->with('success', 'Status pesanan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
