<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\StoreBalance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        // Seller 1 - Verified Electronics Store
        $seller1 = User::create([
            'name' => 'Siti Aminah',
            'email' => 'seller1@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $store1 = Store::create([
            'user_id' => $seller1->id,
            'name' => 'Toko Elektronik Jaya',
            'logo' => 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?w=200&q=80',
            'about' => 'Toko elektronik terpercaya dengan harga bersaing. Menyediakan berbagai macam perangkat elektronik dari brand ternama.',
            'phone' => '081234567890',
            'address_id' => 'ADR001',
            'city' => 'Jakarta',
            'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
            'postal_code' => '10220',
            'is_verified' => true,
            'bank_name' => 'Bank BCA',
            'bank_account_name' => 'Toko Elektronik Jaya',
            'bank_account_number' => '1234567890',
        ]);

        StoreBalance::create([
            'store_id' => $store1->id,
            'balance' => 5000000,
        ]);

        // Seller 2 - Verified Fashion Store
        $seller2 = User::create([
            'name' => 'Rudi Hartono',
            'email' => 'seller2@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $store2 = Store::create([
            'user_id' => $seller2->id,
            'name' => 'Fashion Corner',
            'logo' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=200&q=80',
            'about' => 'Toko fashion dengan koleksi terbaru dan terlengkap. Menjual pakaian, sepatu, dan aksesoris untuk pria dan wanita.',
            'phone' => '081234567891',
            'address_id' => 'ADR002',
            'city' => 'Bandung',
            'address' => 'Jl. Dago No. 45, Bandung',
            'postal_code' => '40135',
            'is_verified' => true,
            'bank_name' => 'Bank Mandiri',
            'bank_account_name' => 'Fashion Corner',
            'bank_account_number' => '9876543210',
        ]);

        StoreBalance::create([
            'store_id' => $store2->id,
            'balance' => 3500000,
        ]);

        // Seller 3 - Pending Verification
        $seller3 = User::create([
            'name' => 'Dewi Sartika',
            'email' => 'seller3@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        Store::create([
            'user_id' => $seller3->id,
            'name' => 'Toko Buku Cerdas',
            'logo' => 'https://images.unsplash.com/photo-1507842217121-9e93c8aaf27c?w=200&q=80',
            'about' => 'Menyediakan berbagai macam buku dari genre fiksi, non-fiksi, komik, dan edukatif.',
            'phone' => '081234567892',
            'address_id' => 'ADR003',
            'city' => 'Surabaya',
            'address' => 'Jl. Pemuda No. 78, Surabaya',
            'postal_code' => '60271',
            'is_verified' => false,
            'bank_name' => 'Bank BRI',
            'bank_account_name' => 'Toko Buku Cerdas',
            'bank_account_number' => '5555666677',
        ]);
    }
}
