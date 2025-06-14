<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Memanggil seeder yang sudah kita buat
        $this->call([
            AdminUserSeeder::class,
            // Anda juga bisa memanggil seeder lain di sini jika ada. Contoh:
            // MenuSeeder::class, 
        ]);
    }
}