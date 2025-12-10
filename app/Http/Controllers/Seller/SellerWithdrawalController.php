<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWithdrawalRequest;
use App\Models\Withdrawal;

class SellerWithdrawalController extends Controller
{

    public function create()
    {
        $store = auth()->user()->store;
        $storeBalance = $store->storeBalance;


        if (!$storeBalance) {
            $storeBalance = $store->storeBalance()->create([
                'balance' => 0,
            ]);
        }

        return view('seller.withdrawals.create', compact('store', 'storeBalance'));
    }


    public function store(StoreWithdrawalRequest $request)
    {
        $store = auth()->user()->store;
        $storeBalance = $store->storeBalance;

        if (!$storeBalance) {
            return back()->with('error', 'Saldo toko tidak ditemukan.');
        }


        $hasPendingWithdrawal = $storeBalance->withdrawals()
            ->where('status', 'pending')
            ->exists();

        if ($hasPendingWithdrawal) {
            return back()->with('error', 'Anda masih memiliki pengajuan penarikan yang sedang diproses. Harap tunggu hingga disetujui atau ditolak terlebih dahulu.');
        }


        $validated = $request->validated();


        $bankName = $store->bank_name;
        $bankAccountName = $store->bank_account_name;
        $bankAccountNumber = $store->bank_account_number;


        if (!$bankName || !$bankAccountName || !$bankAccountNumber) {
            return back()->with('error', 'Harap lengkapi informasi rekening bank di profil toko Anda terlebih dahulu.');
        }


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


    public function destroy(Withdrawal $withdrawal)
    {
        $store = auth()->user()->store;
        $storeBalance = $store->storeBalance;


        if ($withdrawal->store_balance_id !== $storeBalance->id) {
            abort(403, 'Anda tidak memiliki akses ke pengajuan penarikan ini.');
        }


        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan penarikan dengan status "pending" yang dapat dibatalkan.');
        }


        \Illuminate\Support\Facades\DB::transaction(function () use ($withdrawal, $storeBalance) {

            // Lock the withdrawal record to prevent race conditions (e.g. Admin rejecting at same time)
            $lockedWithdrawal = \App\Models\Withdrawal::where('id', $withdrawal->id)->lockForUpdate()->first();

            // Double check status after lock
            if ($lockedWithdrawal && $lockedWithdrawal->status === 'pending') {
                $storeBalance->increment('balance', $lockedWithdrawal->amount);
                $lockedWithdrawal->delete();
            }
        });

        return redirect()->route('seller.balance.index')
            ->with('success', 'Pengajuan penarikan berhasil dibatalkan dan saldo telah dikembalikan.');
    }
}
