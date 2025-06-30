<?php

namespace Database\Seeders;

use App\Models\Guru;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create guru role for the guru guard if it doesn't exist
        $guruRole = Role::firstOrCreate([
            'name' => 'guru',
            'guard_name' => 'guru' // Using guru guard to match Guru model's guard
        ]);

        // Sample data for guru (teachers)
        $guruData = [
            [
                'nama_pendidik' => 'KH. Abdullah Hakim',
                'email' => 'abdullah@pondok.test',
                'password' => '12345678',
                'nik' => '3201011505750001', // 16 digit NIK unik
                'jenis_kelamin' => true, // Male
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1975-05-15',
                'pendidikan_terakhir' => 'S2 Universitas Al-Azhar',
                'riwayat_pendidikan_keagamaan' => 'Pondok Pesantren Tebuireng, Pondok Pesantren Gontor',
                'no_telepon' => '081234567001',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Surabaya',
                'alamat' => 'Jl. Pesantren No. 1, Surabaya',
            ],
            [
                'nama_pendidik' => 'Ustadz Muhammad Yusuf',
                'email' => 'yusuf@pondok.test',
                'password' => '12345678',
                'nik' => '3201012007800002', // 16 digit NIK unik
                'jenis_kelamin' => true, // Male
                'tempat_lahir' => 'Sidoarjo',
                'tanggal_lahir' => '1980-07-20',
                'pendidikan_terakhir' => 'S1 IAIN Surabaya',
                'riwayat_pendidikan_keagamaan' => 'Pondok Pesantren Sidogiri',
                'no_telepon' => '081234567002',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Surabaya',
                'alamat' => 'Jl. Masjid No. 15, Surabaya',
            ],
            [
                'nama_pendidik' => 'Ustadzah Siti Aminah',
                'email' => 'aminah@pondok.test',
                'password' => '12345678',
                'nik' => '3201011003850003', // 16 digit NIK unik
                'jenis_kelamin' => false, // Female
                'tempat_lahir' => 'Sidoarjo',
                'tanggal_lahir' => '1985-03-10',
                'pendidikan_terakhir' => 'S1 UIN Sunan Ampel',
                'riwayat_pendidikan_keagamaan' => 'Pondok Pesantren Langitan',
                'no_telepon' => '081234567003',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Sidoarjo',
                'alamat' => 'Jl. Santri No. 23, Sidoarjo',
            ],
            [
                'nama_pendidik' => 'Ustadz Ahmad Husaini',
                'email' => 'husaini@pondok.test',
                'password' => '12345678',
                'nik' => '3201012511780004', // 16 digit NIK unik
                'jenis_kelamin' => true, // Male
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1978-11-25',
                'pendidikan_terakhir' => 'S2 Universitas Al-Azhar',
                'riwayat_pendidikan_keagamaan' => 'Pondok Pesantren Lirboyo',
                'no_telepon' => '081234567004',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Surabaya',
                'alamat' => 'Jl. Kyai No. 45, Surabaya',
            ],
            [
                'nama_pendidik' => 'Ustadzah Fatimah Zahra',
                'email' => 'fatimahz@pondok.test',
                'password' => '12345678',
                'nik' => '3201010509820005', // 16 digit NIK unik
                'jenis_kelamin' => false, // Female
                'tempat_lahir' => 'Gresik',
                'tanggal_lahir' => '1982-09-05',
                'pendidikan_terakhir' => 'S1 IAIN Surabaya',
                'riwayat_pendidikan_keagamaan' => 'Pondok Pesantren Mambaul Ulum',
                'no_telepon' => '081234567005',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Gresik',
                'alamat' => 'Jl. Al-Hikmah No. 56, Gresik',
            ],
        ];

        // Create guru records
        foreach ($guruData as $data) {
            // Check if guru with this NIK already exists
            $existingGuru = Guru::where('nik', $data['nik'])->first();
            if ($existingGuru) {
                // Skip creating this guru
                continue;
            }
            
            $password = $data['password']; // Store temporarily
            $data['password'] = Hash::make($password); // Hash the password
            
            $guru = Guru::create($data);
            $guru->assignRole($guruRole);
        }
    }
}
