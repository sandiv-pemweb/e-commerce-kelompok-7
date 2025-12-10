<?php

namespace App\Services;

use App\Models\Store;
use App\Models\StoreBalance;
use App\Models\Withdrawal;
use App\Repositories\Contracts\WithdrawalRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class FinanceService
{
    public function __construct(
        protected WithdrawalRepositoryInterface $withdrawalRepository
    ) {}

    public function getStoreBalance(Store $store): StoreBalance
    {
        return $store->storeBalance()->firstOrCreate(
            ['store_id' => $store->id],
            ['balance' => 0]
        );
    }

    public function getBalanceHistory(StoreBalance $balance, int $perPage = 10)
    {
        return $balance->storeBalanceHistories()->latest()->paginate($perPage, ['*'], 'history_page');
    }

    public function getWithdrawalHistory(StoreBalance $balance, int $perPage = 10)
    {
        return $balance->withdrawals()->latest()->paginate($perPage, ['*'], 'withdrawal_page');
    }

    public function calculatePendingBalance(Store $store): float
    {
        $pendingTransactions = $store->transactions()
            ->where('payment_status', 'paid')
            ->whereNotIn('order_status', ['completed', 'cancelled'])
            ->get();

        $pendingBalance = 0;
        foreach ($pendingTransactions as $transaction) {
            $productSubtotal = $transaction->grand_total - $transaction->shipping_cost - $transaction->tax;
            $platformCommission = $productSubtotal * 0.03;
            $sellerEarnings = $productSubtotal + $transaction->shipping_cost - $platformCommission;
            $pendingBalance += $sellerEarnings;
        }

        return $pendingBalance;
    }

    public function createWithdrawal(Store $store, array $data): void
    {
        $storeBalance = $this->getStoreBalance($store);

        if ($this->withdrawalRepository->findPendingByBalanceId($storeBalance->id)) {
            throw new Exception('Anda masih memiliki pengajuan penarikan yang sedang diproses.');
        }

        if (!$store->bank_name || !$store->bank_account_name || !$store->bank_account_number) {
            throw new Exception('Harap lengkapi informasi rekening bank di profil toko Anda.');
        }

        if ($storeBalance->balance < $data['amount']) {
            throw new Exception('Saldo tidak mencukupi.');
        }

        DB::transaction(function () use ($storeBalance, $data, $store) {
            $this->withdrawalRepository->create([
                'store_balance_id' => $storeBalance->id,
                'amount' => $data['amount'],
                'bank_name' => $store->bank_name,
                'bank_account_name' => $store->bank_account_name,
                'bank_account_number' => $store->bank_account_number,
                'status' => 'pending',
            ]);

            $storeBalance->decrement('balance', $data['amount']);
        });
    }

    public function cancelWithdrawal(int $withdrawalId, int $storeId): void
    {
        $withdrawal = $this->withdrawalRepository->find($withdrawalId);
        $store = Store::find($storeId);
        $storeBalance = $this->getStoreBalance($store);

        if (!$withdrawal || $withdrawal->store_balance_id !== $storeBalance->id) {
            throw new Exception('Anda tidak memiliki akses ke pengajuan ini.', 403);
        }

        if ($withdrawal->status !== 'pending') {
            throw new Exception('Hanya pengajuan status pending yang dapat dibatalkan.');
        }

        DB::transaction(function () use ($withdrawal, $storeBalance) {
             $withdrawal->delete();
             $storeBalance->increment('balance', $withdrawal->amount);
        });
    }

    public function approveWithdrawal(Withdrawal $withdrawal): void
    {
        if ($withdrawal->status !== 'pending') {
            throw new Exception('Hanya penarikan yang berstatus pending yang dapat disetujui.');
        }

        DB::transaction(function () use ($withdrawal) {
            // Re-fetch to lock
            $lockedWithdrawal = \App\Models\Withdrawal::where('id', $withdrawal->id)->lockForUpdate()->first();
            
            if ($lockedWithdrawal && $lockedWithdrawal->status === 'pending') {
                $this->withdrawalRepository->update($lockedWithdrawal, ['status' => 'approved']);
            }
        });
    }

    public function rejectWithdrawal(Withdrawal $withdrawal): void
    {
        if ($withdrawal->status !== 'pending') {
            throw new Exception('Hanya penarikan yang berstatus pending yang dapat ditolak.');
        }

        DB::transaction(function () use ($withdrawal) {
             $lockedWithdrawal = \App\Models\Withdrawal::where('id', $withdrawal->id)->lockForUpdate()->first();

             if ($lockedWithdrawal && $lockedWithdrawal->status === 'pending') {
                 $lockedWithdrawal->storeBalance->increment('balance', $lockedWithdrawal->amount);
                 $this->withdrawalRepository->update($lockedWithdrawal, ['status' => 'rejected']);
             }
        });
    }
}
