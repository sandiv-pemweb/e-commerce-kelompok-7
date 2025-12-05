<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\StoreProductImageRequest;

class SellerProductImageController extends Controller
{
    /**
     * Store a newly created product image in storage.
     */
    public function store(StoreProductImageRequest $request, Product $product)
    {
        $store = auth()->user()->store;

        // Ensure product belongs to this store
        if ($product->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }

        // Validation is handled by StoreProductImageRequest
        // But we handle file upload manually as we need the path

        // Handle image upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('product-images', 'public');

                $product->productImages()->create([
                    'image' => $imagePath,
                ]);
            }
        }

        return back()->with('success', 'Gambar produk berhasil ditambahkan.');
    }

    /**
     * Remove the specified product image from storage.
     */
    public function destroy(Product $product, ProductImage $image)
    {
        $store = auth()->user()->store;

        // Ensure product belongs to this store
        if ($product->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }

        // Ensure image belongs to this product
        if ($image->product_id !== $product->id) {
            abort(403, 'Gambar tidak ditemukan pada produk ini.');
        }

        // Delete file from storage
        if ($image->image && Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return back()->with('success', 'Gambar produk berhasil dihapus.');
    }
}
