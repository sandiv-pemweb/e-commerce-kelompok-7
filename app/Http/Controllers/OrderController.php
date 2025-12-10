<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Buyer;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\StoreBalance;
use App\Models\StoreBalanceHistory;


class OrderController extends Controller
{

    public function index()
    {

        $buyer = Buyer::where('user_id', Auth::id())->first();

        if (!$buyer) {
            $orders = collect();
        } else {
            $orders = Transaction::where('buyer_id', $buyer->id)
                ->with(['store', 'transactionDetails.product.productImages'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('orders.index', compact('orders'));
    }

    public function show(Transaction $order)
    {

        $buyer = Buyer::where('user_id', Auth::id())->first();

        if (!$buyer || $order->buyer_id !== $buyer->id) {
            abort(403, 'Unauthorized access to this order');
        }

        $order->load(['store', 'transactionDetails.product.productImages']);

        return view('orders.show', compact('order'));
    }

    public function complete(Transaction $order)
    {

        $buyer = Buyer::where('user_id', Auth::id())->first();

        if (!$buyer || $order->buyer_id !== $buyer->id) {
            abort(403, 'Unauthorized access to this order');
        }

        if ($order->order_status !== 'shipped') {
            return back()->with('error', 'Hanya pesanan yang sedang dikirim yang dapat diselesaikan.');
        }

        try {
            DB::transaction(function () use ($order) {

                $lockedOrder = Transaction::where('id', $order->id)->lockForUpdate()->first();


                if ($lockedOrder->order_status !== 'shipped') {
                    throw new Exception('Pesanan tidak dalam status dikirim.');
                }

                $lockedOrder->update(['order_status' => 'completed']);


                $order = $lockedOrder;


                $productSubtotal = $order->grand_total - $order->shipping_cost - $order->tax;
                $platformCommission = $productSubtotal * 0.03;
                $sellerEarnings = $productSubtotal + $order->shipping_cost - $platformCommission;


                if ($order->balance_credited_at === null) {
                    $store = $order->store;
                    $storeBalance = StoreBalance::firstOrCreate(
                        ['store_id' => $store->id],
                        ['balance' => 0]
                    );


                    $storeBalance->increment('balance', $sellerEarnings);


                    StoreBalanceHistory::create([
                        'store_balance_id' => $storeBalance->id,
                        'type' => 'income',
                        'amount' => $sellerEarnings,
                        'reference_id' => $order->id,
                        'reference_type' => Transaction::class,
                        'remarks' => "Pesanan Diterima User #{$order->code} (Produk: Rp " . number_format($productSubtotal, 0, ',', '.') . " + Ongkir: Rp " . number_format($order->shipping_cost, 0, ',', '.') . " - Komisi 3%: Rp " . number_format($platformCommission, 0, ',', '.') . ")",
                    ]);


                    $order->balance_credited_at = now();
                    $order->save();
                }
            });

            return back()->with('success', 'Pesanan berhasil diselesaikan. Terima kasih telah berbelanja!');
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyelesaikan pesanan.');
        }
    }
}
