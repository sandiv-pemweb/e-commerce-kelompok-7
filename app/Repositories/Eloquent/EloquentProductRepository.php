<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = Product::with(['store', 'productCategory', 'productImages', 'productReviews'])
            ->whereHas('store', function ($query) {
                $query->where('is_verified', true)
                    ->whereNotNull('slug');
            })
            ->available();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['categories'])) {
            $query->whereIn('product_category_id', $filters['categories']);
        } elseif (!empty($filters['category'])) {
            $query->where('product_category_id', $filters['category']);
        }

        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Sorting
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        return $query->paginate($perPage);
    }

    public function find(int $id): ?Product
    {
        return Product::with(['store', 'productCategory', 'productImages', 'productReviews.transaction.buyer.user'])->find($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function getRelated(Product $product, int $limit = 4): Collection
    {
        return Product::with(['store', 'productImages'])
            ->whereHas('store', function ($query) {
                $query->where('is_verified', true)
                    ->whereNotNull('slug');
            })
            ->where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->available()
            ->limit($limit)
            ->get();
    }

    public function getByStore(int $storeId, int $perPage = 20): LengthAwarePaginator
    {
        return Product::where('store_id', $storeId)
            ->with(['productCategory'])
            ->latest()
            ->paginate($perPage);
    }

    public function getCategories(): Collection
    {
        return ProductCategory::whereNull('parent_id')
            ->with('children')
            ->get();
    }

    public function getPriceRange(): array
    {
        return [
            'min' => Product::min('price') ?? 0,
            'max' => Product::max('price') ?? 0,
        ];
    }

    public function getHeroProduct(): ?Product
    {
        return Product::with(['store', 'productImages', 'productCategory'])
            ->whereHas('store', function($query) {
                $query->where('is_verified', true)
                      ->whereNotNull('slug');
            })
            ->withCount('transactionDetails')
            ->orderBy('transaction_details_count', 'desc')
            ->first();
    }

    public function getFeaturedProducts(int $limit = 8)
    {
        return Product::with(['store', 'productImages'])
            ->whereHas('store', function($query) {
                $query->where('is_verified', true)
                      ->whereNotNull('slug');
            })
            ->available()
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function addImages(Product $product, array $imagePaths): void
    {
        foreach ($imagePaths as $path) {
            $product->productImages()->create(['image' => $path]);
        }
    }

    public function removeImage(ProductImage $image): bool
    {
        return $image->delete();
    }
}
