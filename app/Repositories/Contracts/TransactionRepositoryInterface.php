<?php

namespace App\Repositories\Contracts;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TransactionRepositoryInterface
{
    public function getByBuyer(int $buyerId, int $perPage = 10): LengthAwarePaginator;

    public function find(int $id): ?Transaction;

    public function findLocked(int $id): ?Transaction;

    public function create(array $data): Transaction;

    public function update(Transaction $transaction, array $data): Transaction;

    public function getByStore(int $storeId, array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function createDetail(array $data): TransactionDetail;
}
