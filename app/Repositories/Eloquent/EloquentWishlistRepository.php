<?php

namespace App\Repositories\Eloquent;

use App\Models\Wishlist;
use App\Repositories\Contracts\WishlistRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentWishlistRepository implements WishlistRepositoryInterface
{
    public function getByUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return Wishlist::where('user_id', $userId)
            ->with('product.store')
            ->latest()
            ->paginate($perPage);
    }

    public function findByUserAndProduct(int $userId, int $productId): ?Wishlist
    {
        return Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
    }

    public function create(array $data): Wishlist
    {
        return Wishlist::create($data);
    }

    public function delete(Wishlist $wishlist): bool
    {
        return $wishlist->delete();
    }

    public function deleteByUserAndProduct(int $userId, int $productId): bool
    {
        $wishlist = $this->findByUserAndProduct($userId, $productId);
        if ($wishlist) {
            return $wishlist->delete();
        }
        return false;
    }

    public function countByUser(int $userId): int
    {
        return Wishlist::where('user_id', $userId)->count();
    }
}
