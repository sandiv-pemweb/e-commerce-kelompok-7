<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Auth;
use Exception;

class SellerCategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService)
    {
    }

    public function index()
    {
        $categories = $this->categoryService->getStoreCategories(Auth::user()->store->id);
        return view('seller.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('seller.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->categoryService->createCategory(Auth::user()->store, $request->validated());
        return redirect()->route('seller.categories.index')
            ->with('success', 'Kategori produk berhasil dibuat.');
    }

    public function edit(ProductCategory $category)
    {
        if ($category->store_id !== Auth::user()->store->id) {
            abort(403, 'Anda tidak memiliki akses ke kategori ini.');
        }

        return view('seller.categories.edit', compact('category'));
    }

    public function update(StoreCategoryRequest $request, ProductCategory $category)
    {
        try {
            $this->categoryService->updateCategory($category, Auth::user()->store, $request->validated());
            return redirect()->route('seller.categories.index')
                ->with('success', 'Kategori produk berhasil diperbarui.');
        } catch (Exception $e) {
             abort($e->getCode() ?: 403, $e->getMessage());
        }
    }

    public function destroy(ProductCategory $category)
    {
        try {
            $this->categoryService->deleteCategory($category, Auth::user()->store);
            return redirect()->route('seller.categories.index')
                ->with('success', 'Kategori produk berhasil dihapus.');
        } catch (Exception $e) {
             return back()->with('error', $e->getMessage());
        }
    }
}
