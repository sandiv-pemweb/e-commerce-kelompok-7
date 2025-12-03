<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Buyer;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{

    public function index()
    {
        // Get buyer
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
        // Ensure user owns this order
        $buyer = Buyer::where('user_id', Auth::id())->first();
        
        if (!$buyer || $order->buyer_id !== $buyer->id) {
            abort(403, 'Unauthorized access to this order');
        }

        $order->load(['store', 'transactionDetails.product.productImages']);

        return view('orders.show', compact('order'));
    }
}
