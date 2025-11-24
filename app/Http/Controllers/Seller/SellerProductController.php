<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

use App\Http\Requests\StoreProductRequest;

class SellerProductController extends Controller
{
    /**
     * Display a listing of the seller's products.
     */
    public function index()
    {
        $store = auth()->user()->store;
        $products = Product::where('store_id', $store->id)
                          ->with('productCategory')
                          ->latest()
                          ->paginate(20);

        return view('seller.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $store = auth()->user()->store;
        $categories = ProductCategory::where('store_id', $store->id)->get();

        return view('seller.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $store = auth()->user()->store;
        $validated = $request->validated();

        // Category validation is handled by StoreProductRequest

        $product = $store->products()->create($validated);

        return redirect()->route('seller.products.edit', $product)
                       ->with('success', 'Produk berhasil dibuat. Silakan tambahkan gambar produk.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $store = auth()->user()->store;

        // Ensure product belongs to this store
        if ($product->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }

        $categories = ProductCategory::where('store_id', $store->id)->get();
        $product->load('productImages');

        return view('seller.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(StoreProductRequest $request, Product $product)
    {
        $store = auth()->user()->store;

        // Ensure product belongs to this store
        if ($product->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }

        $validated = $request->validated();

        // Category validation is handled by StoreProductRequest

        $product->update($validated);

        return redirect()->route('seller.products.edit', $product)
                       ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $store = auth()->user()->store;

        // Ensure product belongs to this store
        if ($product->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }

        $product->delete();

        return redirect()->route('seller.products.index')
                       ->with('success', 'Produk berhasil dihapus.');
    }
}
