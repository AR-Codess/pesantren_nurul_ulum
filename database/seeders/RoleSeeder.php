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
        // Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $guruRole = Role::firstOrCreate(['name' => 'guru']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Check if admin user exists
        $adminUser = User::where('email', 'admin@pondok.test')->first();
        
        if (!$adminUser) {
            // Create admin user if it doesn't exist
            $adminUser = User::create([
                'name' => 'Admin Pesantren',
                'email' => 'admin@pondok.test',
                'password' => bcrypt('12345678'),
            ]);
            $adminUser->assignRole('admin');
        }

        // Check if guru user exists
        $guruUser = User::where('email', 'guru@pondok.test')->first();
        
        if (!$guruUser) {
            // Create guru user if it doesn't exist
            $guruUser = User::create([
                'name' => 'Guru Pesantren',
                'email' => 'guru@pondok.test',
                'password' => bcrypt('12345678'),
            ]);
            $guruUser->assignRole('guru');
        }

        // Check if regular user exists
        $regularUser = User::where('email', 'user@pondok.test')->first();
        
        if (!$regularUser) {
            // Create regular user if it doesn't exist
            $regularUser = User::create([
                'name' => 'User Pesantren',
                'email' => 'user@pondok.test',
                'password' => bcrypt('12345678'),
            ]);
            $regularUser->assignRole('user');
        }
    }
}
