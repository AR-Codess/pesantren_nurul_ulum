<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Pembayaran;
use App\Models\Guru;
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
        // Count statistics for dashboard using direct queries to ensure accurate counts
        $this->totalUsers = User::role('user')->count();
        
        // Get guru count directly from the users table with the 'guru' role
        $this->totalGuru = User::role('guru')->count();
        
        // If guru count is still 0, try getting it from the guru table as a fallback
        if ($this->totalGuru == 0) {
            $this->totalGuru = Guru::count();
        }
        
        // Get payment summary for the current month
        $currentMonth = Carbon::now()->format('Y-m');
        
        // Get counts for each payment status
        $paymentStats = Pembayaran::selectRaw('status, COUNT(*) as count')
                    ->whereRaw("DATE_FORMAT(periode_pembayaran, '%Y-%m') = ?", [$currentMonth])
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray();
        
        // Set default values if not exists
        $this->confirmedPayments = $paymentStats['confirmed'] ?? 0;
        $this->pendingPayments = $paymentStats['pending'] ?? 0;
        $this->rejectedPayments = $paymentStats['rejected'] ?? 0;
        
        // If we still don't have data, let's query all payments regardless of date
        if ($this->confirmedPayments == 0 && $this->pendingPayments == 0) {
            $this->confirmedPayments = Pembayaran::where('status', 'confirmed')->count();
            $this->pendingPayments = Pembayaran::where('status', 'pending')->count();
            $this->rejectedPayments = Pembayaran::where('status', 'rejected')->count();
        }
        
        // Make sure we have at least zero values for all stats to prevent undefined errors
        $this->totalUsers = $this->totalUsers ?: 0;
        $this->totalGuru = $this->totalGuru ?: 0;
        $this->confirmedPayments = $this->confirmedPayments ?: 0;
        $this->pendingPayments = $this->pendingPayments ?: 0;
    }

    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
