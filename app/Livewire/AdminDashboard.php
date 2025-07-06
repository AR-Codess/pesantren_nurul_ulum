<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Pembayaran;
use App\Models\Guru;
use App\Models\DetailPembayaran;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AdminDashboard extends Component
{
    public $totalUsers;
    public $totalGuru;
    public $confirmedPayments;
    public $pendingPayments;
    public $rejectedPayments;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Count statistics for dashboard using direct queries to ensure accurate counts
        $this->totalUsers = User::role('user')->count();
        
        // Get guru count directly from the users table with the 'guru' role
        $this->totalGuru = User::role('guru')->count();
        
        // If guru count is still 0, try getting it from the guru table as a fallback
        if ($this->totalGuru == 0) {
            $this->totalGuru = Guru::count();
        }

        // Get confirmed/lunas payments count
        $this->confirmedPayments = Pembayaran::where('status', 'lunas')->count();

        // Count all payments that are not fully paid yet
        // This includes payments with explicit status and those where the paid amount < total amount
        $this->pendingPayments = DB::table('pembayaran as p')
            ->leftJoin(DB::raw('(SELECT pembayaran_id, SUM(jumlah_dibayar) as total_dibayar FROM detail_pembayaran GROUP BY pembayaran_id) as dp'), 
                'dp.pembayaran_id', '=', 'p.id')
            ->whereRaw('(p.status IN ("belum_lunas", "belum_bayar", "pending") OR (COALESCE(dp.total_dibayar, 0) < p.total_tagihan))')
            ->count();

        // Count rejected payments
        $this->rejectedPayments = Pembayaran::where('status', 'rejected')->count();

        // Make sure we have at least zero values for all stats to prevent undefined errors
        $this->totalUsers = $this->totalUsers ?: 0;
        $this->totalGuru = $this->totalGuru ?: 0;
        $this->confirmedPayments = $this->confirmedPayments ?: 0;
        $this->pendingPayments = $this->pendingPayments ?: 0;
        
        // Emit event to refresh charts after data is loaded
        $this->dispatch('refreshCharts');
    }

    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
