<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['store', 'productCategory', 'productImages', 'productReviews'])
            ->available();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Category filter (Array support)
        if ($request->has('categories') && is_array($request->categories)) {
            $query->whereIn('product_category_id', $request->categories);
        } elseif ($request->has('category') && $request->category) {
            // Fallback for single category link
            $query->where('product_category_id', $request->category);
        }

        // Price Range Filter
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
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
                    $query->latest(); // Default to popularity/newest
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();
        
        $categories = ProductCategory::whereNull('parent_id')
            ->with('children')
            ->get();

        // Get min and max price for slider limits
        $minPrice = Product::min('price') ?? 0;
        $maxPrice = Product::max('price') ?? 1000000;

        return view('products.index', compact('products', 'categories', 'minPrice', 'maxPrice'));
    }

    public function show(Store $store, Product $product)
    {
        // Ensure the product belongs to the store (Scoped binding check)
        if ($product->store_id !== $store->id) {
            abort(404);
        }
        $product->load(['store', 'productCategory', 'productImages', 'productReviews.transaction.buyer.user']);
        
        // Calculate average rating
        $averageRating = $product->productReviews()->avg('rating') ?? 0;
        $totalReviews = $product->productReviews()->count();

        // Get related products from same category
        $relatedProducts = Product::with(['store', 'productImages'])
            ->where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->available()
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'averageRating', 'totalReviews', 'relatedProducts'));
    }
}
