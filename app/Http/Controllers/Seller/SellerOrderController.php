<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

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
        // Ensure the order belongs to the seller's store
        if ($order->store_id !== auth()->user()->store->id) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        $validated = $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid',
            'tracking_number' => 'nullable|string|max:255',
        ]);

        // If status is shipped, tracking number is required
        if ($validated['order_status'] === 'shipped' && empty($validated['tracking_number'])) {
            return back()->withErrors(['tracking_number' => 'Nomor resi wajib diisi saat status pesanan adalah "Dikirim".']);
        }

        $order->update($validated);

        return redirect()->route('seller.orders.show', $order)
                       ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
