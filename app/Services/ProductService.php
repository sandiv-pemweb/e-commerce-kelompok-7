<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Store;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function listProducts(array $filters = []): LengthAwarePaginator
    {
        return $this->productRepository->all($filters);
    }

    public function getProduct(int $id): ?Product
    {
        return $this->productRepository->find($id);
    }

    public function createProduct(Store $store, array $data, array $images = []): Product
    {
        return DB::transaction(function () use ($store, $data, $images) {
            $product = $store->products()->create($data); // Or use repository->create with store_id merged

            if (!empty($images)) {
                $this->uploadImages($product, $images);
            }

            return $product;
        });
    }

    public function updateProduct(Product $product, array $data): Product
    {
        return $this->productRepository->update($product, $data);
    }

    public function deleteProduct(Product $product): bool
    {
        // Delete images from storage if necessary
        foreach ($product->productImages as $appImage) {
            Storage::disk('public')->delete($appImage->image);
        }
        $product->productImages()->delete();

        return $this->productRepository->delete($product);
    }

    public function getRelatedProducts(Product $product): Collection
    {
        return $this->productRepository->getRelated($product);
    }

    public function getStoreProducts(Store $store): LengthAwarePaginator
    {
        return $this->productRepository->getByStore($store->id);
    }

    public function getCategories(): Collection
    {
        return $this->productRepository->getCategories();
    }

    public function getPriceRange(): array
    {
        return $this->productRepository->getPriceRange();
    }

    public function getHeroProduct(): ?Product
    {
        return $this->productRepository->getHeroProduct();
    }

    public function getFeaturedProducts(int $limit = 8)
    {
        return $this->productRepository->getFeaturedProducts($limit);
    }

    public function addProductImages(Product $product, array $images): void
    {
        $imagePaths = [];
        foreach ($images as $image) {
            if ($image instanceof UploadedFile) {
                $imagePaths[] = $image->store('product-images', 'public');
            }
        }
        
        if (!empty($imagePaths)) {
            $this->productRepository->addImages($product, $imagePaths);
        }
    }

    public function removeProductImage(Product $product, \App\Models\ProductImage $image): void
    {
        if ($image->product_id !== $product->id) {
             throw new \Exception('Gambar tidak ditemukan pada produk ini.', 403);
        }

        if ($image->image && Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        $this->productRepository->removeImage($image);
    }

    protected function uploadImages(Product $product, array $images)
    {
        $this->addProductImages($product, $images);
    }
}
