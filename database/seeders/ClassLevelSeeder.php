<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data for class levels
        $classLevels = [
            [
                'level' => 'Kelas VII',
                'spp' => 250000,
                'spp_beasiswa' => 170000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'Kelas VIII',
                'spp' => 275000,
                'spp_beasiswa' => 195000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'Kelas IX',
                'spp' => 300000,
                'spp_beasiswa' => 220000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'Kelas X',
                'spp' => 325000,
                'spp_beasiswa' => 245000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'Kelas XI',
                'spp' => 350000,
                'spp_beasiswa' => 270000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'Kelas XII',
                'spp' => 375000,
                'spp_beasiswa' => 295000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert class levels if they don't exist
        foreach ($classLevels as $classLevel) {
            // Check if this level already exists
            $existingLevel = DB::table('class_level')
                ->where('level', $classLevel['level'])
                ->first();

            if (!$existingLevel) {
                DB::table('class_level')->insert($classLevel);
            }
        }
    }
}
