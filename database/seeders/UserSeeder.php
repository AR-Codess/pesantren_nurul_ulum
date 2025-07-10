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
            [
                'nama_santri' => 'Muhammad Ridwan',
                'email' => 'ridwan@pondok.test',
                'password' => '12345678', 
                'nis' => '2025003',
                'class_level_id' => 2, // Kelas 2
                'jenis_kelamin' => true,
                'tempat_lahir' => 'GRESIK',
                'tanggal_lahir' => '2010-08-25',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'GRESIK',
                'alamat' => 'Jl. Hasyim Asy\'ari No. 78, Gresik',
                'no_hp' => '083456789012',
                'is_beasiswa' => false,
            ],
            [
                'nama_santri' => 'Aisyah Putri',
                'email' => 'aisyah@pondok.test',
                'password' => '12345678',
                'nis' => '2025004',
                'class_level_id' => 2, // Kelas 2
                'jenis_kelamin' => false,
                'tempat_lahir' => 'Malang',
                'tanggal_lahir' => '2011-12-10',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Malang',
                'alamat' => 'Jl. KH. Wahid Hasyim No. 23, Malang',
                'no_hp' => '084567890123',
                'is_beasiswa' => true,
            ],
            [
                'nama_santri' => 'Zainul Abidin',
                'email' => 'zainul@pondok.test',
                'password' => '12345678',
                'nis' => '2025005',
                'class_level_id' => 3, // Kelas 3
                'jenis_kelamin' => true,
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2010-02-14',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Surabaya',
                'alamat' => 'Jl. Diponegoro No. 56, Surabaya',
                'no_hp' => '085678901234',
                'is_beasiswa' => false,
            ],
            [
                'nama_santri' => 'Nur Hidayah',
                'email' => 'nurhidayah@pondok.test',
                'password' => '12345678',
                'nis' => '2025006',
                'class_level_id' => 3, // Kelas 3
                'jenis_kelamin' => false,
                'tempat_lahir' => 'Mojokerto',
                'tanggal_lahir' => '2010-11-30',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Mojokerto',
                'alamat' => 'Jl. Ahmad Yani No. 90, Mojokerto',
                'no_hp' => '086789012345',
                'is_beasiswa' => false,
            ],
            [
                'nama_santri' => 'Rizki Ramadhan',
                'email' => 'rizki@pondok.test',
                'password' => '12345678',
                'nis' => '2025007',
                'class_level_id' => 4, // Kelas 4
                'jenis_kelamin' => true,
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2010-07-19',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Surabaya',
                'alamat' => 'Jl. Gajah Mada No. 34, Surabaya',
                'no_hp' => '087890123456',
                'is_beasiswa' => false,
            ],
            [
                'nama_santri' => 'Siti Khadijah',
                'email' => 'siti@pondok.test',
                'password' => '12345678',
                'nis' => '2025008',
                'class_level_id' => 4, // Kelas 4
                'jenis_kelamin' => false,
                'tempat_lahir' => 'Lamongan',
                'tanggal_lahir' => '2011-01-05',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Lamongan',
                'alamat' => 'Jl. Veteran No. 67, Lamongan',
                'no_hp' => '088901234567',
                'is_beasiswa' => true,
            ],
            [
                'nama_santri' => 'Abdul Rahman',
                'email' => 'abdulrahman@pondok.test',
                'password' => '12345678',
                'nis' => '2025009',
                'class_level_id' => 5, // Kelas 5
                'jenis_kelamin' => true,
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2010-04-22',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Surabaya',
                'alamat' => 'Jl. Sudirman No. 45, Surabaya',
                'no_hp' => '089012345678',
                'is_beasiswa' => false,
            ],
            [
                'nama_santri' => 'Zahra Amelia',
                'email' => 'zahra@pondok.test',
                'password' => '12345678',
                'nis' => '2025010',
                'class_level_id' => 6, // Kelas 6
                'jenis_kelamin' => false,
                'tempat_lahir' => 'Jombang',
                'tanggal_lahir' => '2011-09-09',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Jombang',
                'alamat' => 'Jl. Merdeka No. 28, Jombang',
                'no_hp' => '081122334455',
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
