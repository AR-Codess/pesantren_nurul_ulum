<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin role for the admin guard if it doesn't exist
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'admin' // Changed from 'web' to 'admin' to match the Admin model's guard
        ]);

        // Sample data for admin accounts
        $adminData = [
            [
                'name' => 'Admin Pesantren',
                'email' => 'admin@pondok.test',
                'password' => '12345678',
            ],
            [
                'name' => 'Sekretaris Pesantren',
                'email' => 'sekretaris@pondok.test',
                'password' => '12345678',
            ],
            [
                'name' => 'Bendahara Pesantren',
                'email' => 'bendahara@pondok.test',
                'password' => '12345678',
            ],
        ];

        // Create admin accounts
        foreach ($adminData as $data) {
            // Check if admin with this email already exists
            $existingAdmin = Admin::where('email', $data['email'])->first();
            if ($existingAdmin) {
                // Skip creating this admin
                continue;
            }
            
            $password = $data['password']; // Store temporarily
            $data['password'] = Hash::make($password); // Hash the password
            
            $admin = Admin::create($data);
            // Assign the 'admin' role with the correct guard
            $admin->assignRole($adminRole);
        }
    }
}
