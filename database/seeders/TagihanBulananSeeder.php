<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tagihan;
use Carbon\Carbon;

class TagihanBulananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahun = date('Y');
        $users = User::where('is_beasiswa', false)->get();
        foreach ($users as $user) {
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $namaBulan = Carbon::create()->month($bulan)->locale('id')->isoFormat('MMMM');
                $deskripsi = 'Tagihan Bulan ' . ucfirst($namaBulan) . ' ' . $tahun;
                $createdAt = Carbon::create($tahun, $bulan, 1);
                // Cek jika sudah ada tagihan bulan tsb
                $exists = Tagihan::where('user_id', $user->id)
                    ->whereYear('created_at', $tahun)
                    ->whereMonth('created_at', $bulan)
                    ->exists();
                if (!$exists) {
                    Tagihan::create([
                        'user_id' => $user->id,
                        'deskripsi' => $deskripsi,
                        'jumlah' => $user->spp_bulanan ?? 0,
                        'status' => 'UNPAID',
                        'created_at' => $createdAt,
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
