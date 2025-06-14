<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat satu user admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'phone' => '081234567890',
            'address' => '123 Coffee Street',
            'password' => Hash::make('00000000'), // Ganti 'password' dengan password yang aman
            'role' => 'admin',
        ]);
    }
}