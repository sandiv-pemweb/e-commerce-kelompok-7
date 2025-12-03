<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Buyer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PaymentController extends Controller
{

    public function show(Transaction $transaction)
    {
        // Ensure user owns this transaction
        $buyer = Buyer::where('user_id', Auth::id())->first();
        
        if (!$buyer || $transaction->buyer_id !== $buyer->id) {
            abort(403, 'Unauthorized access to this order');
        }

        $transaction->load(['store', 'transactionDetails.product.productImages']);

        return view('payment.show', compact('transaction'));
    }

    public function uploadProof(Request $request, Transaction $transaction)
    {
        // Ensure user owns this transaction
        $buyer = Buyer::where('user_id', Auth::id())->first();
        
        if (!$buyer || $transaction->buyer_id !== $buyer->id) {
            abort(403, 'Unauthorized access to this order');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // For now, we'll add a note field to transactions table later
        // This is a placeholder for future implementation
        
        return redirect()->route('payment.show', $transaction)
            ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu konfirmasi penjual.');
    }
}
