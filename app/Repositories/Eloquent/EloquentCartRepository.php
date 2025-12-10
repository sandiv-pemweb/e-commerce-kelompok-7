<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentCartRepository implements CartRepositoryInterface
{
    public function getByUser(int $userId): Collection
    {
        return Cart::where('user_id', $userId)
            ->whereHas('product.store', function ($query) {
                $query->where('is_verified', true)
                    ->whereNotNull('slug');
            })
            ->with(['product.store', 'product.productImages'])
            ->get();
    }

    public function find(int $id): ?Cart
    {
        return Cart::find($id);
    }

    public function findByUserAndProduct(int $userId, int $productId): ?Cart
    {
        return Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
    }

    public function create(array $data): Cart
    {
        return Cart::create($data);
    }

    public function update(Cart $cart, array $data): Cart
    {
        $cart->update($data);
        return $cart;
    }

    public function delete(Cart $cart): bool
    {
        return $cart->delete();
    }

    public function clearByUser(int $userId, ?int $storeId = null): void
    {
        $query = Cart::where('user_id', $userId);

        if ($storeId) {
            $query->whereHas('product', function ($q) use ($storeId) {
                $q->where('store_id', $storeId);
            });
        }

        $query->delete();
    }

    public function countByUser(int $userId): int
    {
        return Cart::where('user_id', $userId)->sum('quantity');
    }
}
