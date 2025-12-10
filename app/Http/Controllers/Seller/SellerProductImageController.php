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

    public function store(StoreProductImageRequest $request, Product $product)
    {
        $store = auth()->user()->store;


        if ($product->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }


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


    public function destroy(Product $product, ProductImage $image)
    {
        $store = auth()->user()->store;


        if ($product->store_id !== $store->id) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }


        if ($image->product_id !== $product->id) {
            abort(403, 'Gambar tidak ditemukan pada produk ini.');
        }


        if ($image->image && Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return back()->with('success', 'Gambar produk berhasil dihapus.');
    }
}
