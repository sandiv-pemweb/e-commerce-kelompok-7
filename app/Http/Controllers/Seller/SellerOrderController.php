<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TransactionService;
use Exception;

class SellerOrderController extends Controller
{
    public function __construct(protected TransactionService $transactionService)
    {
    }

    public function index(Request $request)
    {
        $orders = $this->transactionService->getStoreOrders(Auth::id(), $request->all());
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
        $validated = $request->validate([
            'order_status' => 'required|in:processing,shipped,cancelled',
            'tracking_number' => 'nullable|string|max:255',
        ]);

        if ($request->order_status === 'shipped' && empty($request->tracking_number) && empty($order->tracking_number)) {
            return back()->with('error', 'Nomor resi wajib diisi untuk status "Dikirim".');
        }

        try {
            $this->transactionService->updateOrderStatus(
                $order, 
                Auth::id(), 
                $validated['order_status'], 
                $request->tracking_number
            );
            return back()->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
