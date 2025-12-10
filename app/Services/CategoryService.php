<?php

namespace App\Services;

use App\Models\ProductCategory;
use App\Models\Store;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class CategoryService
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function getStoreCategories(int $storeId): LengthAwarePaginator
    {
        return $this->categoryRepository->getByStore($storeId);
    }

    public function createCategory(Store $store, array $data): ProductCategory
    {
        return $this->categoryRepository->create([
            'store_id' => $store->id,
            'name' => $data['name'],
        ]);
    }

    public function updateCategory(ProductCategory $category, Store $store, array $data): ProductCategory
    {
        if ($category->store_id !== $store->id) {
            throw new Exception('Anda tidak memiliki akses ke kategori ini.', 403);
        }

        return $this->categoryRepository->update($category, $data);
    }

    public function deleteCategory(ProductCategory $category, Store $store): bool
    {
        if ($category->store_id !== $store->id) {
            throw new Exception('Anda tidak memiliki akses ke kategori ini.', 403);
        }

        if ($category->products()->count() > 0) {
            throw new Exception('Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        return $this->categoryRepository->delete($category);
    }

    public function getTopCategories(int $limit = 6): Collection
    {
        return $this->categoryRepository->getTopCategories($limit);
    }
}
