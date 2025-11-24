<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates product categories and products for each store.
     * Generates slugs automatically from product/category names.
     */
    public function run(): void
    {
        $this->seedElectronicsStore();
        $this->seedFashionStore();
    }

    /**
     * Seed products for Electronics Store
     */
    private function seedElectronicsStore(): void
    {
        $store = Store::where('name', 'Toko Elektronik Jaya')->first();
        
        if (!$store) {
            return;
        }

        // Categories
        $laptop = ProductCategory::create([
            'store_id' => $store->id,
            'name' => 'Laptop',
            'slug' => Str::slug('Laptop'),
            'description' => 'Kategori produk laptop dari berbagai brand',
        ]);

        $smartphone = ProductCategory::create([
            'store_id' => $store->id,
            'name' => 'Smartphone',
            'slug' => Str::slug('Smartphone'),
            'description' => 'Kategori produk smartphone dan tablet',
        ]);

        $accessories = ProductCategory::create([
            'store_id' => $store->id,
            'name' => 'Aksesoris',
            'slug' => Str::slug('Aksesoris'),
            'description' => 'Aksesoris elektronik seperti mouse, keyboard, headset',
        ]);

        // Products
        $p1 = Product::create([
            'store_id' => $store->id,
            'product_category_id' => $laptop->id,
            'name' => 'Laptop ASUS ROG Strix G15',
            'slug' => Str::slug('Laptop ASUS ROG Strix G15'),
            'description' => 'Laptop gaming dengan processor AMD Ryzen 7, RAM 16GB, SSD 512GB, GPU RTX 3060. Cocok untuk gaming dan produktivitas.',
            'condition' => 'new',
            'price' => 15000000,
            'stock' => 10,
            'weight' => 2500,
        ]);
        $p1->productImages()->create(['image' => 'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?w=800&q=80']);

        $p2 = Product::create([
            'store_id' => $store->id,
            'product_category_id' => $laptop->id,
            'name' => 'Laptop Lenovo ThinkPad X1 Carbon',
            'slug' => Str::slug('Laptop Lenovo ThinkPad X1 Carbon'),
            'description' => 'Laptop bisnis premium dengan desain tipis dan ringan. Processor Intel Core i7, RAM 16GB, SSD 1TB.',
            'condition' => 'new',
            'price' => 20000000,
            'stock' => 5,
            'weight' => 1200,
        ]);
        $p2->productImages()->create(['image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&q=80']);

        $p3 = Product::create([
            'store_id' => $store->id,
            'product_category_id' => $smartphone->id,
            'name' => 'Samsung Galaxy S23 Ultra',
            'slug' => Str::slug('Samsung Galaxy S23 Ultra'),
            'description' => 'Smartphone flagship dengan kamera 200MP, layar Dynamic AMOLED 6.8 inch, RAM 12GB, Storage 256GB.',
            'condition' => 'new',
            'price' => 18000000,
            'stock' => 15,
            'weight' => 250,
        ]);
        $p3->productImages()->create(['image' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=800&q=80']);

        $p4 = Product::create([
            'store_id' => $store->id,
            'product_category_id' => $accessories->id,
            'name' => 'Mouse Logitech MX Master 3',
            'slug' => Str::slug('Mouse Logitech MX Master 3'),
            'description' => 'Mouse wireless premium dengan ergonomic design, sensor presisi tinggi, dan baterai tahan lama.',
            'condition' => 'new',
            'price' => 1500000,
            'stock' => 25,
            'weight' => 150,
        ]);
        $p4->productImages()->create(['image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800&q=80']);
    }

    /**
     * Seed products for Fashion Store
     */
    private function seedFashionStore(): void
    {
        $store = Store::where('name', 'Fashion Corner')->first();
        
        if (!$store) {
            return;
        }

        // Categories
        $menClothing = ProductCategory::create([
            'store_id' => $store->id,
            'name' => 'Pakaian Pria',
            'slug' => Str::slug('Pakaian Pria'),
            'description' => 'Koleksi pakaian untuk pria',
        ]);

        $womenClothing = ProductCategory::create([
            'store_id' => $store->id,
            'name' => 'Pakaian Wanita',
            'slug' => Str::slug('Pakaian Wanita'),
            'description' => 'Koleksi pakaian untuk wanita',
        ]);

        $shoes = ProductCategory::create([
            'store_id' => $store->id,
            'name' => 'Sepatu',
            'slug' => Str::slug('Sepatu'),
            'description' => 'Koleksi sepatu pria dan wanita',
        ]);

        // Products
        $p5 = Product::create([
            'store_id' => $store->id,
            'product_category_id' => $menClothing->id,
            'name' => 'Kemeja Pria Slimfit',
            'slug' => Str::slug('Kemeja Pria Slimfit'),
            'description' => 'Kemeja formal pria dengan bahan cotton premium, nyaman dipakai untuk acara formal maupun casual.',
            'condition' => 'new',
            'price' => 250000,
            'stock' => 50,
            'weight' => 300,
        ]);
        $p5->productImages()->create(['image' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&q=80']);

        $p6 = Product::create([
            'store_id' => $store->id,
            'product_category_id' => $womenClothing->id,
            'name' => 'Dress Wanita Elegant',
            'slug' => Str::slug('Dress Wanita Elegant'),
            'description' => 'Dress wanita dengan model elegant, cocok untuk acara pesta dan formal. Tersedia dalam berbagai warna.',
            'condition' => 'new',
            'price' => 450000,
            'stock' => 30,
            'weight' => 400,
        ]);
        $p6->productImages()->create(['image' => 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=800&q=80']);

        $p7 = Product::create([
            'store_id' => $store->id,
            'product_category_id' => $shoes->id,
            'name' => 'Sneakers Nike Air Max',
            'slug' => Str::slug('Sneakers Nike Air Max'),
            'description' => 'Sepatu sneakers original Nike dengan teknologi Air Max untuk kenyamanan maksimal saat berolahraga.',
            'condition' => 'new',
            'price' => 1800000,
            'stock' => 20,
            'weight' => 800,
        ]);
        $p7->productImages()->create(['image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80']);
    }
}
