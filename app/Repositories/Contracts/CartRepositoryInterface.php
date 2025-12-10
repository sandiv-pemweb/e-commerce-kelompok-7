<?php

namespace App\Repositories\Contracts;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;

interface CartRepositoryInterface
{
    public function getByUser(int $userId): Collection;

    public function find(int $id): ?Cart;

    public function findByUserAndProduct(int $userId, int $productId): ?Cart;

    public function create(array $data): Cart;

    public function update(Cart $cart, array $data): Cart;

    public function delete(Cart $cart): bool;

    public function clearByUser(int $userId, ?int $storeId = null): void;

    public function countByUser(int $userId): int;
}
