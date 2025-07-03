<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure guru and class_level tables have entries
        if (DB::table('guru')->count() == 0) {
            $this->call(GuruSeeder::class);
        }

        if (DB::table('class_level')->count() == 0) {
            $this->call(ClassLevelSeeder::class);
        }

        // Sample data for kelas (classes)
        $kelasData = [
            [
                'mata_pelajaran' => 'Matematika Dasar',
                'class_level_id' => 1, // Kelas 1
                'tahun_ajaran' => '2025/2026',
                'guru_id' => 1, // KH. Abdullah Hakim
            ],
            [
                'mata_pelajaran' => 'Bahasa Arab',
                'class_level_id' => 1, // Kelas 1
                'tahun_ajaran' => '2025/2026',
                'guru_id' => 2, // Ustadz Muhammad Yusuf
            ],
            [
                'mata_pelajaran' => 'Fiqih',
                'class_level_id' => 2, // Kelas 2
                'tahun_ajaran' => '2025/2026',
                'guru_id' => 3, // Ustadzah Siti Aminah
            ],
            [
                'mata_pelajaran' => 'Tahfidz Al-Quran',
                'class_level_id' => 2, // Kelas 2
                'tahun_ajaran' => '2025/2026',
                'guru_id' => 4, // Ustadz Ahmad Husaini
            ],
            [
                'mata_pelajaran' => 'Hadits',
                'class_level_id' => 3, // Kelas 3
                'tahun_ajaran' => '2025/2026',
                'guru_id' => 5, // Ustadzah Fatimah Zahra
            ],
            [
                'mata_pelajaran' => 'Akidah Akhlak',
                'class_level_id' => 3, // Kelas 3
                'tahun_ajaran' => '2025/2026',
                'guru_id' => 1, // KH. Abdullah Hakim
            ],
            [
                'mata_pelajaran' => 'Matematika Lanjutan',
                'class_level_id' => 4, // Kelas 4
                'tahun_ajaran' => '2025/2026',
                'guru_id' => 2, // Ustadz Muhammad Yusuf
            ],
            [
                'mata_pelajaran' => 'Bahasa Inggris',
                'class_level_id' => 4, // Kelas 4
                'tahun_ajaran' => '2025/2026',
                'guru_id' => 3, // Ustadzah Siti Aminah
            ],
            [
                'mata_pelajaran' => 'Tafsir Al-Quran',
                'class_level_id' => 5, // Kelas 5
                'tahun_ajaran' => '2025/2026',
                'guru_id' => 4, // Ustadz Ahmad Husaini
            ],
            [
                'mata_pelajaran' => 'Sejarah Islam',
                'class_level_id' => 6, // Kelas 6
                'tahun_ajaran' => '2025/2026',
                'guru_id' => 5, // Ustadzah Fatimah Zahra
            ],
        ];

        // Create kelas records
        foreach ($kelasData as $data) {
            Kelas::create($data);
        }
        
        // Assign users to classes
        // We'll assign users of each class level to the appropriate classes
        for ($classLevel = 1; $classLevel <= 6; $classLevel++) {
            // Get users in this class level
            $userIds = DB::table('users')
                ->where('class_level_id', $classLevel)
                ->pluck('id')
                ->toArray();
                
            // Get classes for this level
            $kelasIds = DB::table('kelas')
                ->where('class_level_id', $classLevel)
                ->pluck('id')
                ->toArray();
                
            // Assign each user to all classes in their level
            foreach ($userIds as $userId) {
                foreach ($kelasIds as $kelasId) {
                    // Check if this relationship already exists
                    $exists = DB::table('kelas_user')
                        ->where('user_id', $userId)
                        ->where('kelas_id', $kelasId)
                        ->exists();
                        
                    if (!$exists) {
                        DB::table('kelas_user')->insert([
                            'user_id' => $userId,
                            'kelas_id' => $kelasId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }
}