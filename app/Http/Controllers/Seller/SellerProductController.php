<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

use App\Http\Requests\StoreProductRequest;
use App\Services\ProductService;

class SellerProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    public function index()
    {
        $store = auth()->user()->store;
        $products = $this->productService->getStoreProducts($store);

        return view('seller.products.index', compact('products'));
    }


    public function create()
    {
        $store = auth()->user()->store;
        $categories = ProductCategory::where('store_id', $store->id)->get();

        return view('seller.products.create', compact('categories'));
    }


    public function store(StoreProductRequest $request)
    {
        $store = auth()->user()->store;
        $validated = $request->validated();

        $images = $request->file('images') ?? [];

        $product = $this->productService->createProduct($store, $validated, $images);

        return redirect()->route('seller.products.edit', $product)
            ->with('success', 'Produk berhasil dibuat.');
    }


    public function edit(Product $product)
    {
        $store = auth()->user()->store;

        if ($product->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }

        $categories = ProductCategory::where('store_id', $store->id)->get();
        // Ensure images are loaded. Find by ID caches or just loads.
        // Product binding gives product.
        $product->load('productImages');

        return view('seller.products.edit', compact('product', 'categories'));
    }


    public function update(StoreProductRequest $request, Product $product)
    {
        $store = auth()->user()->store;

        if ($product->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }

        $validated = $request->validated();

        $this->productService->updateProduct($product, $validated);

        return redirect()->route('seller.products.edit', $product)
            ->with('success', 'Produk berhasil diperbarui.');
    }


    public function destroy(Product $product)
    {
        $store = auth()->user()->store;

        if ($product->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }

        $this->productService->deleteProduct($product);

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
