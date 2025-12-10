<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductService;
use App\Http\Requests\StoreProductImageRequest;
use Illuminate\Support\Facades\Auth;
use Exception;

class SellerProductImageController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    public function store(StoreProductImageRequest $request, Product $product)
    {
        if ($product->store_id !== Auth::user()->store->id) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }

        if ($request->hasFile('images')) {
            $this->productService->addProductImages($product, $request->file('images'));
        }

        return back()->with('success', 'Gambar produk berhasil ditambahkan.');
    }

    public function destroy(Product $product, ProductImage $image)
    {
        if ($product->store_id !== Auth::user()->store->id) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }

        try {
            $this->productService->removeProductImage($product, $image);
            return back()->with('success', 'Gambar produk berhasil dihapus.');
        } catch (Exception $e) {
            abort($e->getCode() ?: 403, $e->getMessage());
        }
    }
}
