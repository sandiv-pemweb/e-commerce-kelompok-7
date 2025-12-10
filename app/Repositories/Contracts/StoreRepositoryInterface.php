<?php

namespace App\Repositories\Contracts;

use App\Models\Store;

interface StoreRepositoryInterface
{
    public function findBySlug(string $slug): ?Store;

    public function findByUser(int $userId, bool $withTrashed = false): ?Store;

    public function create(array $data): Store;

    public function update(Store $store, array $data): Store;

    public function delete(Store $store): bool;

    public function restore(Store $store): bool;
}
