<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        ]);
        
        // Ini dinonaktifkan karena kita sudah membuat user di RoleSeeder
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
