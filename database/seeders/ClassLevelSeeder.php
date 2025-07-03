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
                'level' => 'Kelas 1',
                'spp' => 250000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'Kelas 2',
                'spp' => 275000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'Kelas 3',
                'spp' => 300000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'Kelas 4',
                'spp' => 325000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'Kelas 5',
                'spp' => 350000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'Kelas 6',
                'spp' => 375000,
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
