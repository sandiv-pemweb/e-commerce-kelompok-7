<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;

class HomeController extends Controller
{
    public function index()
    {

        $heroProduct = Product::with(['store', 'productImages', 'productCategory'])
            ->whereHas('store', function($query) {
                $query->where('is_verified', true)
                      ->whereNotNull('slug');
            })
            ->withCount('transactionDetails')
            ->orderBy('transaction_details_count', 'desc')
            ->first();


        $featuredProducts = Product::with(['store', 'productImages'])
            ->whereHas('store', function($query) {
                $query->where('is_verified', true)
                      ->whereNotNull('slug');
            })
            ->available()
            ->latest()
            ->limit(8)
            ->get();


        $categories = ProductCategory::whereNull('parent_id')
            ->withCount('products')
            ->limit(6)
            ->get();

        return view('home', compact('heroProduct', 'featuredProducts', 'categories'));
    }
}
