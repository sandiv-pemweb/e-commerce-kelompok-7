<?php

namespace App\Repositories\Eloquent;

use App\Models\ProductCategory;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function getByStore(int $storeId, int $perPage = 20): LengthAwarePaginator
    {
        return ProductCategory::where('store_id', $storeId)
            ->withCount('products')
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): ProductCategory
    {
        return ProductCategory::create($data);
    }

    public function update(ProductCategory $category, array $data): ProductCategory
    {
        $category->update($data);
        return $category;
    }

    public function delete(ProductCategory $category): bool
    {
        return $category->delete();
    }

    public function find(int $id): ?ProductCategory
    {
        return ProductCategory::find($id);
    }

    public function getTopCategories(int $limit = 6): Collection
    {
        return ProductCategory::whereNull('parent_id')
            ->withCount('products')
            ->limit($limit)
            ->get();
    }
}
