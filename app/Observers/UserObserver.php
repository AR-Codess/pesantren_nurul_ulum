<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Pembayaran;
use App\Models\Admin;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class UserObserver
{
    /**
     * Menangani event "created" pada model User.
     * Fungsi ini akan berjalan otomatis SETELAH user baru berhasil disimpan ke database.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        // Pastikan user memiliki relasi classLevel, jika tidak, hentikan proses.
        if (!$user->classLevel) {
            return;
        }

        // 1. Tentukan jumlah SPP bulanan (termasuk logika beasiswa)
        $sppBulanan = $user->is_beasiswa && isset($user->classLevel->spp_beasiswa)
            ? $user->classLevel->spp_beasiswa
            : ($user->classLevel->spp ?? 0);

        // Jika biaya SPP adalah 0 atau tidak ada, tidak perlu buat tagihan.
        if ($sppBulanan <= 0) {
            return;
        }

        // 2. Tentukan periode pembuatan tagihan (dari user dibuat s.d. bulan sekarang)
        $mulai = Carbon::parse($user->created_at)->startOfMonth();
        $selesai = Carbon::now()->startOfMonth();
        
        // Antisipasi jika user dibuat di masa depan (jarang terjadi, tapi baik untuk ada)
        if ($mulai->greaterThan($selesai)) {
            return;
        }

        $periodeTagihan = CarbonPeriod::create($mulai, '1 month', $selesai);

        // 3. Siapkan data tagihan untuk dimasukkan ke database
        $billsToCreate = [];
        $adminPembuatId = Admin::first()->id ?? 1; // Ambil admin pertama sebagai fallback

        foreach ($periodeTagihan as $tanggal) {
            $billsToCreate[] = [
                'user_id'           => $user->id,
                'total_tagihan'     => $sppBulanan,
                'periode_bulan'     => $tanggal->month,
                'periode_tahun'     => $tanggal->year,
                'status'            => 'belum_bayar', // <-- Status default sesuai permintaan
                'deskripsi'         => 'Tagihan SPP ' . $tanggal->isoFormat('MMMM YYYY'),
                'is_cicilan'        => 0,
                'admin_id_pembuat'  => $adminPembuatId,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }

        // 4. Masukkan semua tagihan ke database dalam satu query (ini jauh lebih cepat & efisien)
        if (!empty($billsToCreate)) {
            Pembayaran::insert($billsToCreate);
        }
    }
}