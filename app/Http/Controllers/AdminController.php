<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display admin dashboard with summary information
     */
    public function index()
    {
        // Count statistics for dashboard
        $totalUsers = User::role('user')->count();
        $totalGurus = User::role('guru')->count();
        
        // Get payment summary for the current month
        $currentMonth = Carbon::now()->format('Y-m');
        $paymentStats = Pembayaran::selectRaw('status, COUNT(*) as count')
                        ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$currentMonth])
                        ->groupBy('status')
                        ->get()
                        ->pluck('count', 'status')
                        ->toArray();
        
        // Set default values if not exists
        $confirmedPayments = $paymentStats['confirmed'] ?? 0;
        $pendingPayments = $paymentStats['pending'] ?? 0;
        $rejectedPayments = $paymentStats['rejected'] ?? 0;
        
        return view('admin.index', compact(
            'totalUsers', 
            'totalGurus', 
            'confirmedPayments', 
            'pendingPayments', 
            'rejectedPayments'
        ));
    }
    
    /**
     * Display financial report page
     */
    public function financialReport(Request $request)
    {
        $period = $request->period ?? 'monthly';
        $year = $request->year ?? Carbon::now()->year;
        $month = $request->month ?? Carbon::now()->month;
        
        if ($period === 'monthly') {
            // Get monthly report for selected year and month
            $payments = Pembayaran::whereYear('tanggal', $year)
                        ->whereMonth('tanggal', $month)
                        ->get();
                        
            $totalAmount = $payments->where('status', 'confirmed')->sum('jumlah');
            $pendingAmount = $payments->where('status', 'pending')->sum('jumlah');
            
            return view('admin.financial-report', compact(
                'payments', 
                'totalAmount', 
                'pendingAmount', 
                'period', 
                'year', 
                'month'
            ));
        } else {
            // Get yearly report for selected year
            $monthlyData = Pembayaran::selectRaw('MONTH(tanggal) as month, SUM(CASE WHEN status = "confirmed" THEN jumlah ELSE 0 END) as confirmed_amount')
                        ->whereYear('tanggal', $year)
                        ->groupBy(DB::raw('MONTH(tanggal)'))
                        ->orderBy('month')
                        ->get();
            
            $totalYearlyAmount = Pembayaran::whereYear('tanggal', $year)
                               ->where('status', 'confirmed')
                               ->sum('jumlah');
            
            return view('admin.financial-report', compact(
                'monthlyData', 
                'totalYearlyAmount', 
                'period', 
                'year'
            ));
        }
    }
    
    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,rejected',
            'catatan' => 'nullable|string',
        ]);
        
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->status = $request->status;
        $pembayaran->catatan = $request->catatan;
        $pembayaran->save();
        
        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui');
    }
}
