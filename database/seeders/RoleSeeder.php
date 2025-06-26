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

    $admin = Role::create(['name' => 'admin']);
    $guru = Role::create(['name' => 'guru']);
    $santri = Role::create(['name' => 'santri']);

    $user = User::create([
        'name' => 'Admin',
        'email' => 'admin@pondok.test',
        'password' => bcrypt('12345678'),
    ]);
    $user->assignRole('admin');
    $guru->assignRole('guru');
    $santri->assignRole('santri');
    }
}
