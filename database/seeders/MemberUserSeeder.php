<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberUserSeeder extends Seeder
{

    public function run(): void
    {

        $user1 = User::create([
            'name' => 'Naufal Rabani',
            'email' => 'buyer1@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'email_verified_at' => now(),
        ]);
        \App\Models\Buyer::create(['user_id' => $user1->id]);


        $user2 = User::create([
            'name' => 'Putra Arviansyah',
            'email' => 'buyer2@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'email_verified_at' => now(),
        ]);
        \App\Models\Buyer::create(['user_id' => $user2->id]);


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
