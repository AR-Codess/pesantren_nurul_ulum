<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
                'nama_santri' => 'Ahmad Fahri',
                'email' => 'ahmad.fahri@pondok.test',
                'password' => '12345678',
                'nis' => '2025001',
                'class_level_id' => 1, // Kelas 1
                'jenis_kelamin' => true,
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2010-05-15',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Surabaya',
                'alamat' => 'Jl. Imam Bonjol No. 45, Surabaya',
                'no_hp' => '081234567890',
                'is_beasiswa' => false,
            ],
            [
                'nama_santri' => 'Fatimah Azzahra',
                'email' => 'fatimah@pondok.test',
                'password' => '12345678',
                'nis' => '202500228573',
                'class_level_id' => 1, // Kelas 1
                'jenis_kelamin' => false,
                'tempat_lahir' => 'Sidoarjo',
                'tanggal_lahir' => '2011-03-20',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Sidoarjo',
                'alamat' => 'Jl. Pahlawan No. 12, Sidoarjo',
                'no_hp' => '082345678901',
                'is_beasiswa' => false,
            ],
        ];

        // Ensure class_level table has entries before adding users
        if (DB::table('class_level')->count() == 0) {
            $this->call(ClassLevelSeeder::class);
        }

        // Create users and assign 'user' role
        foreach ($santriData as $data) {
            // Check if user with this nis already exists
            $existingUser = User::where('nis', $data['nis'])->first();
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
