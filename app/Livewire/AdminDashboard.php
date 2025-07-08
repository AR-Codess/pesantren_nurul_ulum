<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Pembayaran;
use App\Models\Guru;
use Carbon\Carbon;

class AdminDashboard extends Component
{
    public function render()
    {
        // 1. Ambil semua tahun yang memiliki status 'lunas' dari database
        $dbYears = Pembayaran::where('status', 'lunas')
            ->select('periode_tahun')
            ->distinct()
            ->pluck('periode_tahun'); // Hasilnya adalah sebuah Collection, misal: [2023, 2021]

        // 2. Ambil tahun kalender sekarang
        $currentCalendarYear = Carbon::now()->year; // Hasilnya: 2025

        // 3. Gabungkan tahun dari database dengan tahun sekarang,
        //    urutkan dari yang terbaru, dan pastikan tidak ada duplikat.
        $availableYears = $dbYears->push($currentCalendarYear) // Tambahkan tahun sekarang ke daftar
                                  ->unique()                  // Hapus duplikat jika ada
                                  ->sortDesc()                // Urutkan dari terbesar (terbaru)
                                  ->values();                 // Reset keys agar urut

        // 4. Atur tahun default yang terpilih adalah tahun pertama (terbaru) dari daftar
        $currentYear = $availableYears->first() ?? $currentCalendarYear;

        // 5. Ambil data statistik untuk kartu-kartu di dashboard
        $totalUsers = User::role('user')->count();
        $totalGuru = User::role('guru')->count() ?: Guru::count();
        $confirmedPayments = Pembayaran::where('status', 'lunas')->count();
        $pendingPayments = Pembayaran::where('status', '!=', 'lunas')->count();

        // 6. Kirim semua data yang sudah disiapkan ke view
        return view('livewire.admin-dashboard', [
            'availableYears' => $availableYears,
            'currentYear' => $currentYear,
            'totalUsers' => $totalUsers,
            'totalGuru' => $totalGuru,
            'confirmedPayments' => $confirmedPayments,
            'pendingPayments' => $pendingPayments,
        ]);
    }
}