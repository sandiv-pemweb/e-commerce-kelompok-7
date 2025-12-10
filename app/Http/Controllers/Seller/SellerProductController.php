<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

use App\Http\Requests\StoreProductRequest;

class SellerProductController extends Controller
{

    public function index()
    {
        $store = auth()->user()->store;
        $products = Product::where('store_id', $store->id)
            ->with('productCategory')
            ->latest()
            ->paginate(20);

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



        $product = $store->products()->create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $product->productImages()->create([
                    'image' => $image->store('product-images', 'public')
                ]);
            }
        }

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



        $product->update($validated);

        return redirect()->route('seller.products.edit', $product)
            ->with('success', 'Produk berhasil diperbarui.');
    }


    public function destroy(Product $product)
    {
        $store = auth()->user()->store;


        if ($product->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }

        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
