<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates regular member accounts (buyers who don't have stores).
     */
    public function run(): void
    {
        // Regular member 1
        $user1 = User::create([
            'name' => 'Naufal Rabani',
            'email' => 'buyer1@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'email_verified_at' => now(),
        ]);
        \App\Models\Buyer::create(['user_id' => $user1->id]);

        // Regular member 2
        $user2 = User::create([
            'name' => 'Putra Arviansyah',
            'email' => 'buyer2@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'email_verified_at' => now(),
        ]);
        \App\Models\Buyer::create(['user_id' => $user2->id]);

        // Regular member 3
        $user3 = User::create([
            'name' => 'Eka Putra',
            'email' => 'buyer3@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'email_verified_at' => now(),
        ]);
        \App\Models\Buyer::create(['user_id' => $user3->id]);
    }
}
