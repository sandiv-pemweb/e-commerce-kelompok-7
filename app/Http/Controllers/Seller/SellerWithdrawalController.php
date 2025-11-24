<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWithdrawalRequest;
use App\Models\Withdrawal;

class SellerWithdrawalController extends Controller
{
    /**
     * Show the form for creating a new withdrawal request.
     */
    public function create()
    {
        $store = auth()->user()->store;
        $storeBalance = $store->storeBalance;

        // Create store balance if it doesn't exist
        if (!$storeBalance) {
            $storeBalance = $store->storeBalance()->create([
                'balance' => 0,
            ]);
        }

        return view('seller.withdrawals.create', compact('store', 'storeBalance'));
    }

    /**
     * Store a newly created withdrawal request in storage.
     */
    public function store(StoreWithdrawalRequest $request)
    {
        $store = auth()->user()->store;
        $storeBalance = $store->storeBalance;

        if (!$storeBalance) {
            return back()->with('error', 'Saldo toko tidak ditemukan.');
        }

        // Check if there's already a pending withdrawal
        $hasPendingWithdrawal = $storeBalance->withdrawals()
                                            ->where('status', 'pending')
                                            ->exists();

        if ($hasPendingWithdrawal) {
            return back()->with('error', 'Anda masih memiliki pengajuan penarikan yang sedang diproses. Harap tunggu hingga disetujui atau ditolak terlebih dahulu.');
        }

        // Validation is handled by StoreWithdrawalRequest
        $validated = $request->validated();

        // Get bank account from store or allow override
        $bankName = $store->bank_name;
        $bankAccountName = $store->bank_account_name;
        $bankAccountNumber = $store->bank_account_number;

        // Validate bank account information exists
        if (!$bankName || !$bankAccountName || !$bankAccountNumber) {
            return back()->with('error', 'Harap lengkapi informasi rekening bank di profil toko Anda terlebih dahulu.');
        }

        // Create withdrawal request and deduct balance in transaction
        \Illuminate\Support\Facades\DB::transaction(function () use ($storeBalance, $validated, $bankName, $bankAccountName, $bankAccountNumber) {
            $storeBalance->withdrawals()->create([
                'amount' => $validated['amount'],
                'bank_name' => $bankName,
                'bank_account_name' => $bankAccountName,
                'bank_account_number' => $bankAccountNumber,
                'status' => 'pending',
            ]);

            $storeBalance->decrement('balance', $validated['amount']);
        });

        return redirect()->route('seller.balance.index')
                       ->with('success', 'Pengajuan penarikan saldo berhasil dibuat. Saldo Anda telah dipotong sementara menunggu persetujuan admin.');
    }

    /**
     * Remove the specified withdrawal request from storage.
     * Only pending withdrawals can be cancelled.
     */
    public function destroy(Withdrawal $withdrawal)
    {
        $store = auth()->user()->store;
        $storeBalance = $store->storeBalance;

        // Ensure withdrawal belongs to this store
        if ($withdrawal->store_balance_id !== $storeBalance->id) {
            abort(403, 'Anda tidak memiliki akses ke pengajuan penarikan ini.');
        }

        // Only pending withdrawals can be cancelled
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan penarikan dengan status "pending" yang dapat dibatalkan.');
        }

        // Refund balance and delete withdrawal in transaction
        \Illuminate\Support\Facades\DB::transaction(function () use ($withdrawal, $storeBalance) {
            $storeBalance->increment('balance', $withdrawal->amount);
            $withdrawal->delete();
        });

        return redirect()->route('seller.balance.index')
                       ->with('success', 'Pengajuan penarikan berhasil dibatalkan dan saldo telah dikembalikan.');
    }
}
