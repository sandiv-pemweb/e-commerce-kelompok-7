<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

use App\Http\Requests\StoreCategoryRequest;

class SellerCategoryController extends Controller
{

    public function index()
    {
        $store = auth()->user()->store;
        $categories = ProductCategory::where('store_id', $store->id)
            ->withCount('products')
            ->latest()
            ->paginate(20);

        return view('seller.categories.index', compact('categories'));
    }


    public function create()
    {
        return view('seller.categories.create');
    }


    public function store(StoreCategoryRequest $request)
    {
        $store = auth()->user()->store;
        $validated = $request->validated();

        ProductCategory::create([
            'store_id' => $store->id,
            'name' => $validated['name'],
        ]);

        return redirect()->route('seller.categories.index')
            ->with('success', 'Kategori produk berhasil dibuat.');
    }


    public function edit(ProductCategory $category)
    {
        $store = auth()->user()->store;


        if ($category->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke kategori ini.');
        }

        return view('seller.categories.edit', compact('category'));
    }


    public function update(StoreCategoryRequest $request, ProductCategory $category)
    {
        $store = auth()->user()->store;


        if ($category->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke kategori ini.');
        }

        $validated = $request->validated();

        $category->update($validated);

        return redirect()->route('seller.categories.index')
            ->with('success', 'Kategori produk berhasil diperbarui.');
    }


    public function destroy(ProductCategory $category)
    {
        $store = auth()->user()->store;


        if ($category->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke kategori ini.');
        }


        if ($category->products()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        $category->delete();

        return redirect()->route('seller.categories.index')
            ->with('success', 'Kategori produk berhasil dihapus.');
    }
}
