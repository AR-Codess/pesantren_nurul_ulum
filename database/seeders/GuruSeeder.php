<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Guru;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data for guru (teachers)
        $guruData = [
            [
                'name' => 'KH. Abdullah Hakim',
                'email' => 'abdullah@pondok.test',
                'password' => '12345678',
                'nip' => 'G2025001',
                'alamat' => 'Jl. Pesantren No. 1, Surabaya',
                'no_hp' => '081234567001',
                'bidang' => 'Tahfidz Al-Quran',
            ],
            [
                'name' => 'Ustadz Muhammad Yusuf',
                'email' => 'yusuf@pondok.test',
                'password' => '12345678',
                'nip' => 'G2025002',
                'alamat' => 'Jl. Masjid No. 15, Surabaya',
                'no_hp' => '081234567002',
                'bidang' => 'Fiqih',
            ],
            [
                'name' => 'Ustadzah Siti Aminah',
                'email' => 'aminah@pondok.test',
                'password' => '12345678',
                'nip' => 'G2025003',
                'alamat' => 'Jl. Santri No. 23, Sidoarjo',
                'no_hp' => '081234567003',
                'bidang' => 'Bahasa Arab',
            ],
            [
                'name' => 'Ustadz Ahmad Husaini',
                'email' => 'husaini@pondok.test',
                'password' => '12345678',
                'nip' => 'G2025004',
                'alamat' => 'Jl. Kyai No. 45, Surabaya',
                'no_hp' => '081234567004',
                'bidang' => 'Hadits',
            ],
            [
                'name' => 'Ustadzah Fatimah Zahra',
                'email' => 'fatimahz@pondok.test',
                'password' => '12345678',
                'nip' => 'G2025005',
                'alamat' => 'Jl. Al-Hikmah No. 56, Gresik',
                'no_hp' => '081234567005',
                'bidang' => 'Aqidah',
            ],
            [
                'name' => 'Ustadz Ibrahim Hasan',
                'email' => 'ibrahim@pondok.test',
                'password' => '12345678',
                'nip' => 'G2025006',
                'alamat' => 'Jl. Nurul Iman No. 67, Surabaya',
                'no_hp' => '081234567006',
                'bidang' => 'Tafsir',
            ],
            [
                'name' => 'Ustadz Ali Zainal',
                'email' => 'alizainal@pondok.test',
                'password' => '12345678',
                'nip' => 'G2025007',
                'alamat' => 'Jl. Al-Ikhlas No. 78, Jombang',
                'no_hp' => '081234567007',
                'bidang' => 'Matematika',
            ],
            [
                'name' => 'Ustadzah Khadijah Putri',
                'email' => 'khadijah@pondok.test',
                'password' => '12345678',
                'nip' => 'G2025008',
                'alamat' => 'Jl. Barokah No. 89, Surabaya',
                'no_hp' => '081234567008',
                'bidang' => 'IPA',
            ],
        ];

        // Create users with guru role and their related guru profile
        foreach ($guruData as $data) {
            // Check if user with this email already exists
            $existingUser = User::where('email', $data['email'])->first();
            if ($existingUser) {
                // Skip creating this user
                continue;
            }
            
            // Store bidang (subject) temporarily, we'll need to remove it since it's not in the gurus table
            $bidang = $data['bidang'];
            unset($data['bidang']);
            
            $nip = $data['nip']; // Store NIP temporarily
            unset($data['nip']);  // Remove from user data
            
            // Extract guru-specific data
            $alamat = $data['alamat'];
            $no_hp = $data['no_hp'];
            
            // Remove guru-specific data from user array
            unset($data['alamat']);
            unset($data['no_hp']);
            
            // Set NIP as NIS in User model
            $data['nis'] = $nip;
            
            // Hash the password
            $password = $data['password'];
            $data['password'] = Hash::make($password);
            
            // Create user
            $user = User::create($data);
            $user->assignRole('guru');
            
            // Create related guru profile with the fields that exist in the table
            Guru::create([
                'user_id' => $user->id,
                'nip' => $nip,
                'alamat' => $alamat,
                'no_hp' => $no_hp
            ]);
        }
    }
}
