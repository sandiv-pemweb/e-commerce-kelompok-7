<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentTransactionRepository implements TransactionRepositoryInterface
{
    public function getByBuyer(int $buyerId, int $perPage = 10): LengthAwarePaginator
    {
        return Transaction::where('buyer_id', $buyerId)
            ->with(['store', 'transactionDetails.product.productImages'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function find(int $id): ?Transaction
    {
        return Transaction::with(['store', 'transactionDetails.product.productImages'])->find($id);
    }

    public function findLocked(int $id): ?Transaction
    {
        return Transaction::where('id', $id)->lockForUpdate()->first();
    }

    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }

    public function update(Transaction $transaction, array $data): Transaction
    {
        $transaction->update($data);
        return $transaction;
    }

    public function getByStore(int $storeId, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Transaction::where('store_id', $storeId)
            ->with(['buyer.user', 'transactionDetails.product'])
            ->latest();

        if (isset($filters['status'])) {
            $query->where('order_status', $filters['status']);
        }

        if (isset($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        return $query->paginate($perPage);
    }

    public function createDetail(array $data): TransactionDetail
    {
        return TransactionDetail::create($data);
    }
}
