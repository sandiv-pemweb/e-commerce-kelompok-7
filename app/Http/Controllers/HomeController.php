<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;

class HomeController extends Controller
{
    public function index()
    {
        // Get Hero Product (Best Seller by Sales)
        $heroProduct = Product::with(['store', 'productImages', 'productCategory'])
            ->withCount('transactionDetails')
            ->orderBy('transaction_details_count', 'desc')
            ->first();

        // Get featured products (latest 8 products with stock)
        $featuredProducts = Product::with(['store', 'productImages'])
            ->available()
            ->latest()
            ->limit(8)
            ->get();

        // Get main categories
        $categories = ProductCategory::whereNull('parent_id')
            ->withCount('products')
            ->limit(6)
            ->get();

        return view('home', compact('heroProduct', 'featuredProducts', 'categories'));
    }
}
