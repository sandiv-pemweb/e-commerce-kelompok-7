<?php

namespace App\Repositories\Contracts;

use App\Models\ProductCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function getByStore(int $storeId, int $perPage = 20): LengthAwarePaginator;
    
    public function create(array $data): ProductCategory;
    
    public function update(ProductCategory $category, array $data): ProductCategory;
    
    public function delete(ProductCategory $category): bool;
    
    public function find(int $id): ?ProductCategory;

    public function getTopCategories(int $limit = 6): Collection;
}
