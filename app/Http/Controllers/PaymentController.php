<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Services\TransactionService;
use Exception;

class PaymentController extends Controller
{
    public function __construct(protected TransactionService $transactionService)
    {
    }

    public function show(Transaction $transaction)
    {
        try {
            // Re-fetch to check authorization and load relations
            $transaction = $this->transactionService->getOrder($transaction->id, Auth::id());
            return view('payment.show', compact('transaction'));
        } catch (Exception $e) {
            abort(403, $e->getMessage());
        }
    }

    public function uploadProof(Request $request, Transaction $transaction)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $this->transactionService->uploadPaymentProof($transaction, Auth::id(), $request->file('payment_proof'));
            return redirect()->route('payment.show', $transaction)
                ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu konfirmasi Admin.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
