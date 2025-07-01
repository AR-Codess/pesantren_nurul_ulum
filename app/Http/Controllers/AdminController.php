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
        $totalGuru = User::role('guru')->count();
        
        // Get payment summary for the current month
        $currentMonth = Carbon::now()->format('Y-m');
        $paymentStats = Pembayaran::selectRaw('status, COUNT(*) as count')
                        ->whereRaw("DATE_FORMAT(periode_pembayaran, '%Y-%m') = ?", [$currentMonth])
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
            'totalGuru', 
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
            $payments = Pembayaran::whereYear('periode_pembayaran', $year)
                        ->whereMonth('periode_pembayaran', $month)
                        ->get();
                        
            $totalAmount = $payments->where('status', 'confirmed')->sum('total_tagihan');
            $pendingAmount = $payments->where('status', 'pending')->sum('total_tagihan');
            
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
            $monthlyData = Pembayaran::selectRaw('MONTH(periode_pembayaran) as month, SUM(CASE WHEN status = "confirmed" THEN total_tagihan ELSE 0 END) as confirmed_amount')
                        ->whereYear('periode_pembayaran', $year)
                        ->groupBy(DB::raw('MONTH(periode_pembayaran)'))
                        ->orderBy('month')
                        ->get();
            
            $totalYearlyAmount = Pembayaran::whereYear('periode_pembayaran', $year)
                               ->where('status', 'confirmed')
                               ->sum('total_tagihan');
            
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
