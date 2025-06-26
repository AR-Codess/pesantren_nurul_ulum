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
        $santri = Role::create(['name' => 'santri']);

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

        // Buat user santri
        $santriUser = User::create([
            'name' => 'Santri Pesantren',
            'email' => 'santri@pondok.test',
            'password' => bcrypt('12345678'),
        ]);
        $santriUser->assignRole('santri');
    }
}
