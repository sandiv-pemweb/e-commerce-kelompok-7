<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['store', 'buyer.user'])->latest();


        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }


        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }


        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->search . '%')
                    ->orWhereHas('store', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Transaction $order)
    {
        $order->load(['store', 'buyer.user', 'transactionDetails.product.productImages']);
        return view('admin.orders.show', compact('order'));
    }

    public function verifyPayment(Transaction $order)
    {
        if ($order->payment_status === 'paid') {
            return back()->with('error', 'Pembayaran pesanan ini sudah diverifikasi.');
        }

        DB::transaction(function () use ($order) {
            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'processing',
            ]);
        });

        return back()->with('success', 'Pembayaran berhasil diverifikasi. Pesanan kini diproses.');
    }
}
