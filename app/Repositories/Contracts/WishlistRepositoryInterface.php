<?php

namespace App\Repositories\Contracts;

use App\Models\Wishlist;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface WishlistRepositoryInterface
{
    public function getByUser(int $userId, int $perPage = 10): LengthAwarePaginator;

    public function findByUserAndProduct(int $userId, int $productId): ?Wishlist;

    public function create(array $data): Wishlist;

    public function delete(Wishlist $wishlist): bool;

    public function deleteByUserAndProduct(int $userId, int $productId): bool;

    public function countByUser(int $userId): int;
}
