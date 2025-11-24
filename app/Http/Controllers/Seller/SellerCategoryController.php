<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

use App\Http\Requests\StoreCategoryRequest;

class SellerCategoryController extends Controller
{
    /**
     * Display a listing of the seller's product categories.
     */
    public function index()
    {
        $store = auth()->user()->store;
        $categories = ProductCategory::where('store_id', $store->id)
                                    ->withCount('products')
                                    ->latest()
                                    ->paginate(20);

        return view('seller.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new product category.
     */
    public function create()
    {
        return view('seller.categories.create');
    }

    /**
     * Store a newly created product category in storage.
     */
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

    /**
     * Show the form for editing the specified product category.
     */
    public function edit(ProductCategory $category)
    {
        $store = auth()->user()->store;

        // Ensure category belongs to this store
        if ($category->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke kategori ini.');
        }

        return view('seller.categories.edit', compact('category'));
    }

    /**
     * Update the specified product category in storage.
     */
    public function update(StoreCategoryRequest $request, ProductCategory $category)
    {
        $store = auth()->user()->store;

        // Ensure category belongs to this store
        if ($category->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke kategori ini.');
        }

        $validated = $request->validated();

        $category->update($validated);

        return redirect()->route('seller.categories.index')
                       ->with('success', 'Kategori produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product category from storage.
     */
    public function destroy(ProductCategory $category)
    {
        $store = auth()->user()->store;

        // Ensure category belongs to this store
        if ($category->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke kategori ini.');
        }

        // Check if category has products
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        $category->delete();

        return redirect()->route('seller.categories.index')
                       ->with('success', 'Kategori produk berhasil dihapus.');
    }
}
