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

    public function index(Request $request)
    {
        $store = auth()->user()->store;

        $query = Transaction::where('store_id', $store->id)
            ->with(['buyer.user', 'transactionDetails.product'])
            ->latest();


        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }


        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->paginate(20);

        return view('seller.orders.index', compact('orders'));
    }


    public function show(Transaction $order)
    {

        if ($order->store_id !== auth()->user()->store->id) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        $order->load(['buyer.user', 'transactionDetails.product']);

        return view('seller.orders.show', compact('order'));
    }


    public function update(Request $request, Transaction $order)
    {

        if ($order->store_id !== Auth::user()->store->id) {
            abort(403);
        }


        if ($order->order_status === 'cancelled') {
            return back()->with('error', 'Pesanan yang sudah dibatalkan tidak dapat diubah statusnya.');
        }


        if ($order->order_status === 'pending') {
            return back()->with('error', 'Pesanan menunggu pembayaran tidak dapat diubah statusnya. Tunggu verifikasi admin.');
        }


        if ($order->order_status === 'shipped' && $request->order_status === 'cancelled') {
            return back()->with('error', 'Pesanan yang sudah dikirim tidak dapat dibatalkan.');
        }


        $validated = $request->validate([
            'order_status' => 'required|in:processing,shipped,cancelled',
            'tracking_number' => 'nullable|string|max:255',
        ]);


        if ($request->order_status === 'shipped' && empty($request->tracking_number) && empty($order->tracking_number)) {
            return back()->with('error', 'Nomor resi wajib diisi untuk status "Dikirim".');
        }

        try {
            DB::beginTransaction();

            $oldStatus = $order->order_status;
            $newStatus = $validated['order_status'];


            if ($request->has('tracking_number')) {
                $order->tracking_number = $request->tracking_number;
            }


            if ($newStatus === 'cancelled') {



                if ($order->payment_status === 'paid') {
                    $order->payment_status = 'refunded';
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


                    $order->balance_credited_at = null;
                }

                $order->order_status = 'cancelled';
                $order->save();

            } else {



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
