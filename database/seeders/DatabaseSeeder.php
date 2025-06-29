<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan RoleSeeder untuk membuat users dengan roles
        $this->call([
            RoleSeeder::class,
            // Tambahkan seeder baru kita
            UserSeeder::class,
            GuruSeeder::class,
        ]);
    }
}
