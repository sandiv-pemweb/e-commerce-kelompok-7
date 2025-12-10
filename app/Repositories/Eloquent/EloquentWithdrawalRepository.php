<?php

namespace App\Repositories\Eloquent;

use App\Models\Withdrawal;
use App\Repositories\Contracts\WithdrawalRepositoryInterface;

class EloquentWithdrawalRepository implements WithdrawalRepositoryInterface
{
    public function create(array $data): Withdrawal
    {
        return Withdrawal::create($data);
    }

    public function update(Withdrawal $withdrawal, array $data): Withdrawal
    {
        $withdrawal->update($data);
        return $withdrawal;
    }

    public function delete(Withdrawal $withdrawal): bool
    {
        return $withdrawal->delete();
    }

    public function find(int $id): ?Withdrawal
    {
        return Withdrawal::find($id);
    }

    public function findPendingByBalanceId(int $balanceId): ?Withdrawal
    {
        return Withdrawal::where('store_balance_id', $balanceId)
            ->where('status', 'pending')
            ->first();
    }
}
