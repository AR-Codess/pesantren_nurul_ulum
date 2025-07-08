<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tagihan;
use Carbon\Carbon;

class TagihanBeasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahun = date('Y');
        $users = User::where('is_beasiswa', true)->get();
        foreach ($users as $user) {
            // Contoh: admin hanya membuat 1 tagihan beasiswa custom
            $deskripsi = 'Tagihan Beasiswa SPP ' . $tahun;
            $createdAt = Carbon::create($tahun, 7, 1);
            $exists = Tagihan::where('user_id', $user->id)
                ->where('deskripsi', $deskripsi)
                ->exists();
            if (!$exists) {
                Tagihan::create([
                    'user_id' => $user->id,
                    'deskripsi' => $deskripsi,
                    'jumlah' => 0,
                    'status' => 'UNPAID',
                    'created_at' => $createdAt,
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
