<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Services\ProductService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    public function show(Store $store)
    {
        $products = $this->productService->getStoreProducts($store);
        return view('stores.show', compact('store', 'products'));
    }
}
