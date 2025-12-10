<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 12): LengthAwarePaginator;

    public function find(int $id): ?Product;

    public function create(array $data): Product;

    public function update(Product $product, array $data): Product;

    public function delete(Product $product): bool;

    public function getRelated(Product $product, int $limit = 4): Collection;
    
    public function getByStore(int $storeId, int $perPage = 20): LengthAwarePaginator;

    public function getCategories(): Collection;

    public function getPriceRange(): array;

    public function getHeroProduct(): ?Product;

    public function getFeaturedProducts(int $limit = 8);

    public function addImages(Product $product, array $imagePaths): void;

    public function removeImage(ProductImage $image): bool;
}
