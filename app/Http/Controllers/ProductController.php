<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    public function index(Request $request)
    {
        $products = $this->productService->listProducts($request->all());
        $categories = $this->productService->getCategories();
        $priceRange = $this->productService->getPriceRange();

        $minPrice = $priceRange['min'];
        $maxPrice = $priceRange['max'];

        return view('products.index', compact('products', 'categories', 'minPrice', 'maxPrice'));
    }

    public function show(Store $store, Product $product)
    {
        if ($product->store_id !== $store->id) {
            abort(404);
        }

        $product = $this->productService->getProduct($product->id);

        $averageRating = $product->productReviews()->avg('rating') ?? 0;
        $totalReviews = $product->productReviews()->count();

        $relatedProducts = $this->productService->getRelatedProducts($product);

        return view('products.show', compact('product', 'averageRating', 'totalReviews', 'relatedProducts'));
    }
}
