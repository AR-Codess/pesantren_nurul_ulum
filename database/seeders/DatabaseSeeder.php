<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat roles yang dibutuhkan oleh aplikasi
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $guruRole = Role::firstOrCreate(['name' => 'guru']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Menjalankan seeder lainnya
        $this->call([
            AdminSeeder::class,
            GuruSeeder::class,
            UserSeeder::class,
        ]);
    }
}
