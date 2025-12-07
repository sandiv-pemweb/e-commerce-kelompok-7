<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\StoreBalance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;

class SellerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates seller accounts with their stores.
     * Note: Sellers are identified by having a Store relationship, not by role.
     * All sellers have 'member' role - this allows them to also be buyers.
     */
    public function run(): void
    {
        // Seller 1 - BookLand Official (Verified)
        $seller1 = User::create([
            'name' => 'SanDiv Admin',
            'email' => 'seller1@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $store1 = Store::create([
            'user_id' => $seller1->id,
            'name' => 'SanDiv Official',
            'slug' => Str::slug('SanDiv Official'),
            'logo' => 'https://down-id.img.susercontent.com/file/63e90bfb81c7967dbb1750bab788bec8',
            'about' => 'Toko resmi SanDiv. Menyediakan koleksi buku terbaik langsung dari penerbit.',
            'phone' => '081234567890',
            'address_id' => 'ADR001',
            'city' => 'Jakarta',
            'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
            'postal_code' => '10220',
            'is_verified' => true,
            'bank_name' => 'Bank BCA',
            'bank_account_name' => 'SanDiv Official',
            'bank_account_number' => '1234567890',
        ]);

        StoreBalance::create([
            'store_id' => $store1->id,
            'balance' => 0,
        ]);

        // Seller 2 - Gramedia Digital (Verified)
        $seller2 = User::create([
            'name' => 'Gramedia Admin',
            'email' => 'seller2@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $store2 = Store::create([
            'user_id' => $seller2->id,
            'name' => 'Gramedia Digital',
            'slug' => Str::slug('Gramedia Digital'),
            'logo' => 'https://siplah.blibli.com/data/images/SGVB-0002-00404/d6fcb09b-b71a-4a80-a767-50f54b7c2b08.jpg',
            'about' => 'Toko buku terbesar di Indonesia. Menyediakan berbagai macam buku berkualitas.',
            'phone' => '081234567891',
            'address_id' => 'ADR002',
            'city' => 'Jakarta',
            'address' => 'Jl. Palmerah Barat No. 29-37, Jakarta Pusat',
            'postal_code' => '10270',
            'is_verified' => true,
            'bank_name' => 'Bank Mandiri',
            'bank_account_name' => 'Gramedia Digital',
            'bank_account_number' => '9876543210',
        ]);

        StoreBalance::create([
            'store_id' => $store2->id,
            'balance' => 0,
        ]);

        // Seller 3 - Periplus (Verified)
        $seller3 = User::create([
            'name' => 'Periplus Admin',
            'email' => 'seller3@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $store3 = Store::create([
            'user_id' => $seller3->id,
            'name' => 'Periplus',
            'slug' => Str::slug('Periplus'),
            'logo' => 'https://images.seeklogo.com/logo-png/49/1/periplus-logo-png_seeklogo-492362.png',
            'about' => 'Pengecer buku impor terkemuka di Indonesia.',
            'phone' => '081234567892',
            'address_id' => 'ADR003',
            'city' => 'Jakarta',
            'address' => 'Jl. Jend. Sudirman Kav. 52-53, Jakarta Selatan',
            'postal_code' => '12190',
            'is_verified' => true,
            'bank_name' => 'Bank BCA',
            'bank_account_name' => 'Periplus',
            'bank_account_number' => '5555666677',
        ]);

        StoreBalance::create([
            'store_id' => $store3->id,
            'balance' => 0,
        ]);

        // Seller 4 - Indie Book Corner (Pending)
        $seller4 = User::create([
            'name' => 'Indie Admin',
            'email' => 'seller4@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $store4 = Store::create([
            'user_id' => $seller4->id,
            'name' => 'Indie Book Corner',
            'slug' => Str::slug('Indie Book Corner'),
            'logo' => 'https://pbs.twimg.com/profile_images/762518434261323776/J0njY8aw.jpg',
            'about' => 'Tempatnya buku-buku indie berkualitas.',
            'phone' => '081234567893',
            'address_id' => 'ADR004',
            'city' => 'Yogyakarta',
            'address' => 'Jl. Gejayan No. 12, Yogyakarta',
            'postal_code' => '55281',
            'is_verified' => false,
            'bank_name' => 'Bank BNI',
            'bank_account_name' => 'Indie Book Corner',
            'bank_account_number' => '1122334455',
        ]);

        // Add StoreBalance for seller 4 (was missing before)
        StoreBalance::create([
            'store_id' => $store4->id,
            'balance' => 0, // Start with 0 since it's pending verification
        ]);
    }
}
