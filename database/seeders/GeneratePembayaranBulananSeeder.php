<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pembayaran;
use Carbon\Carbon;

class GeneratePembayaranBulananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahun = date('Y');
        $users = User::all();
        foreach ($users as $user) {
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $periode = Carbon::create($tahun, $bulan, 1)->format('Y-m');
                $exists = Pembayaran::where('user_id', $user->id)
                    ->where('periode_pembayaran', $periode)
                    ->exists();
                if (!$exists) {
                    Pembayaran::create([
                        'user_id' => $user->id,
                        'total_tagihan' => $user->spp_bulanan ?? 0,
                        'periode_pembayaran' => $periode,
                        'status' => 'UNPAID',
                        'is_cicilan' => false,
                        'admin_id_pembuat' => 1,
                    ]);
                }
            }
        }
    }
}
