<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Set user ID 1 sebagai admin
        User::where('id', 1)->update([
            'role' => 'admin'
        ]);

        // 2. (OPSIONAL) Buat admin baru jika mau
        User::create([
            'name' => 'Ikhsan Naufal',
            'email' => 'user@ex.com',
            'password' => Hash::make('12345678'),
            'role' => 'member'
        ]);
    }
}
