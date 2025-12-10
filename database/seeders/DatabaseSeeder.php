<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

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
