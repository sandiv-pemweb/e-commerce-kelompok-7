<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Seeding order:
     * 1. Admin users
     * 2. Seller users with stores
     * 3. Regular member users (buyers)
     * 4. Products and categories
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            SellerUserSeeder::class,
            MemberUserSeeder::class,
            ProductSeeder::class,
            TransactionSeeder::class,
            WithdrawalSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@example.com', 'password'],
                ['Seller (verified)', 'seller1@example.com', 'password'],
                ['Seller (verified)', 'seller2@example.com', 'password'],
                ['Seller (pending)', 'seller3@example.com', 'password'],
                ['Member (buyer)', 'buyer1@example.com', 'password'],
                ['Member (buyer)', 'buyer2@example.com', 'password'],
                ['Member (buyer)', 'buyer3@example.com', 'password'],
            ]
        );
    }
}
