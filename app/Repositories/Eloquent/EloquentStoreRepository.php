<?php

namespace App\Repositories\Eloquent;

use App\Models\Store;
use App\Repositories\Contracts\StoreRepositoryInterface;

class EloquentStoreRepository implements StoreRepositoryInterface
{
    public function findBySlug(string $slug): ?Store
    {
        return Store::where('slug', $slug)
            ->where('is_verified', true)
            ->firstOrFail();
    }

    public function findByUser(int $userId, bool $withTrashed = false): ?Store
    {
        $query = Store::where('user_id', $userId);
        
        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query->first();
    }

    public function create(array $data): Store
    {
        return Store::create($data);
    }

    public function update(Store $store, array $data): Store
    {
        $store->update($data);
        return $store;
    }

    public function delete(Store $store): bool
    {
        return $store->delete();
    }

    public function restore(Store $store): bool
    {
        return $store->restore();
    }
}
