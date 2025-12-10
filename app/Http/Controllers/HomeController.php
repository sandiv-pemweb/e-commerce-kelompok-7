<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\CategoryService;

class HomeController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected CategoryService $categoryService
    ) {}

    public function index()
    {
        $heroProduct = $this->productService->getHeroProduct();
        $featuredProducts = $this->productService->getFeaturedProducts(8);
        $categories = $this->categoryService->getTopCategories(6);

        return view('home', compact('heroProduct', 'featuredProducts', 'categories'));
    }
}
