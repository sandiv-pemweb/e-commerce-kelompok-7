<?php

namespace App\Repositories\Contracts;

use App\Models\Withdrawal;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface WithdrawalRepositoryInterface
{
    public function create(array $data): Withdrawal;
    
    public function update(Withdrawal $withdrawal, array $data): Withdrawal;
    
    public function delete(Withdrawal $withdrawal): bool;
    
    public function find(int $id): ?Withdrawal;

    public function findPendingByBalanceId(int $balanceId): ?Withdrawal;
}
