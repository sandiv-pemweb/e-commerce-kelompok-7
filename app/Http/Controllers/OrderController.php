<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Services\TransactionService;
use Exception;

class OrderController extends Controller
{
    public function __construct(protected TransactionService $transactionService)
    {
    }

    public function index()
    {
        $orders = $this->transactionService->getBuyerOrders(Auth::id());
        return view('orders.index', compact('orders'));
    }

    public function show(Transaction $order)
    {
        try {
            $order = $this->transactionService->getOrder($order->id, Auth::id());
            return view('orders.show', compact('order'));
        } catch (Exception $e) {
            abort(403, $e->getMessage());
        }
    }

    public function complete(Transaction $order)
    {
        try {
            $this->transactionService->completeOrder($order, Auth::id());
            return back()->with('success', 'Pesanan berhasil diselesaikan. Terima kasih telah berbelanja!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
