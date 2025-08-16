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
        // Bersihkan data lama agar tidak duplikat
        DB::table('kelas_user')->truncate();
        DB::table('kelas_class_level')->truncate();
        DB::table('kelas')->delete();

        // Ensure guru and class_level tables have entries
        if (DB::table('guru')->count() == 0) {
            $this->call(GuruSeeder::class);
        }

        if (DB::table('class_level')->count() == 0) {
            $this->call(ClassLevelSeeder::class);
        }

        // 6 kelas, beberapa punya lebih dari 1 jenjang
        $kelasData = [
            [
                'mata_pelajaran' => 'Matematika Dasar',
                'jadwal_hari' => 'Senin',
                'tahun_ajaran' => '2025/2026',
                'guru_id' => 1,
                'class_levels' => [1, 2], // multi jenjang
            ],
            [
                'mata_pelajaran' => 'Bahasa Arab',
                'jadwal_hari' => 'Selasa',
                'tahun_ajaran' => '2025/2026',
                'guru_id' => 2,
                'class_levels' => [1],
            ],
            [
                'mata_pelajaran' => 'Fiqih',
                'jadwal_hari' => 'Rabu',
                'tahun_ajaran' => '2025/2026',
                'guru_id' => 3,
                'class_levels' => [2, 3], // multi jenjang
            ],
            [
                'mata_pelajaran' => 'Tahfidz Al-Quran',
                'jadwal_hari' => 'Kamis',
                'tahun_ajaran' => '2025/2026',
                'guru_id' => 4,
                'class_levels' => [3],
            ],
            [
                'mata_pelajaran' => 'Hadits',
                'jadwal_hari' => 'Jumat',
                'tahun_ajaran' => '2025/2026',
                'guru_id' => 5,
                'class_levels' => [4, 5], // multi jenjang
            ],
            [
                'mata_pelajaran' => 'Akidah Akhlak',
                'jadwal_hari' => 'Sabtu',
                'tahun_ajaran' => '2025/2026',
                'guru_id' => 1,
                'class_levels' => [6],
            ],
        ];

        // Create kelas records and sync class levels
        foreach ($kelasData as $data) {
            $classLevels = $data['class_levels'];
            unset($data['class_levels']);
            $kelas = Kelas::create($data);
            $kelas->classLevels()->sync($classLevels);
        }

        // Assign users to classes: setiap kelas dapat santri sesuai jenjang
        foreach ($kelasData as $data) {
            $classLevels = $data['class_levels'];
            $kelas = Kelas::where('mata_pelajaran', $data['mata_pelajaran'])
                ->where('jadwal_hari', $data['jadwal_hari'])
                ->first();
            if ($kelas) {
                $userIds = DB::table('users')
                    ->whereIn('class_level_id', $classLevels)
                    ->pluck('id')
                    ->toArray();
                $kelas->users()->sync($userIds);
            }
        }
    }
}
