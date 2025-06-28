<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data for santri (users)
        $santriData = [
            [
                'name' => 'Ahmad Fahri',
                'email' => 'ahmad.fahri@pondok.test',
                'password' => '12345678',
                'nis' => '2025001',
                'alamat' => 'Jl. Imam Bonjol No. 45, Surabaya',
                'no_hp' => '081234567890',
            ],
            [
                'name' => 'Fatimah Azzahra',
                'email' => 'fatimah@pondok.test',
                'password' => '12345678',
                'nis' => '2025002',
                'alamat' => 'Jl. Pahlawan No. 12, Sidoarjo',
                'no_hp' => '082345678901',
            ],
            [
                'name' => 'Muhammad Ridwan',
                'email' => 'ridwan@pondok.test',
                'password' => '12345678', 
                'nis' => '2025003',
                'alamat' => 'Jl. Hasyim Asy\'ari No. 78, Gresik',
                'no_hp' => '083456789012',
            ],
            [
                'name' => 'Aisyah Putri',
                'email' => 'aisyah@pondok.test',
                'password' => '12345678',
                'nis' => '2025004',
                'alamat' => 'Jl. KH. Wahid Hasyim No. 23, Malang',
                'no_hp' => '084567890123',
            ],
            [
                'name' => 'Zainul Abidin',
                'email' => 'zainul@pondok.test',
                'password' => '12345678',
                'nis' => '2025005',
                'alamat' => 'Jl. Diponegoro No. 56, Surabaya',
                'no_hp' => '085678901234',
            ],
            [
                'name' => 'Nur Hidayah',
                'email' => 'nurhidayah@pondok.test',
                'password' => '12345678',
                'nis' => '2025006',
                'alamat' => 'Jl. Ahmad Yani No. 90, Mojokerto',
                'no_hp' => '086789012345',
            ],
            [
                'name' => 'Rizki Ramadhan',
                'email' => 'rizki@pondok.test',
                'password' => '12345678',
                'nis' => '2025007',
                'alamat' => 'Jl. Gajah Mada No. 34, Surabaya',
                'no_hp' => '087890123456',
            ],
            [
                'name' => 'Siti Khadijah',
                'email' => 'siti@pondok.test',
                'password' => '12345678',
                'nis' => '2025008',
                'alamat' => 'Jl. Veteran No. 67, Lamongan',
                'no_hp' => '088901234567',
            ],
            [
                'name' => 'Abdul Rahman',
                'email' => 'abdulrahman@pondok.test',
                'password' => '12345678',
                'nis' => '2025009',
                'alamat' => 'Jl. Sudirman No. 45, Surabaya',
                'no_hp' => '089012345678',
            ],
            [
                'name' => 'Zahra Amelia',
                'email' => 'zahra@pondok.test',
                'password' => '12345678',
                'nis' => '2025010',
                'alamat' => 'Jl. Merdeka No. 28, Jombang',
                'no_hp' => '081122334455',
            ],
        ];

        // Create users and assign 'user' role
        foreach ($santriData as $data) {
            // Check if user with this email already exists
            $existingUser = User::where('email', $data['email'])->first();
            if ($existingUser) {
                // Skip creating this user
                continue;
            }
            
            $password = $data['password']; // Store temporarily
            $data['password'] = Hash::make($password); // Hash the password
            
            $user = User::create($data);
            $user->assignRole('user');
        }
    }
}
