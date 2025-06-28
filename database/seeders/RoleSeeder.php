<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat roles
        $admin = Role::create(['name' => 'admin']);
        $guru = Role::create(['name' => 'guru']);
        $user = Role::create(['name' => 'user']);

        // Buat user admin
        $adminUser = User::create([
            'name' => 'Admin Pesantren',
            'email' => 'admin@pondok.test',
            'password' => bcrypt('12345678'),
        ]);
        $adminUser->assignRole('admin');

        // Buat user guru
        $guruUser = User::create([
            'name' => 'Guru Pesantren',
            'email' => 'guru@pondok.test',
            'password' => bcrypt('12345678'),
        ]);
        $guruUser->assignRole('guru');

        // Buat user biasa
        $regularUser = User::create([
            'name' => 'User Pesantren',
            'email' => 'user@pondok.test',
            'password' => bcrypt('12345678'),
        ]);
        $regularUser->assignRole('user');
    }
}
