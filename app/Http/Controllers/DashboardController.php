<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guru;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Provides combined data for santri and guru growth chart
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCombinedGrowth()
    {
        // Get data for the last 12 months
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        // Generate all month labels for the last 12 months
        $labels = [];
        $defaultData = [];
        
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths(11 - $i);
            $labels[] = $date->format('M Y');
            $defaultData[$date->format('Y-m')] = 0;
        }

        // Query the database for monthly santri registrations
        $santriCounts = User::role('user')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->select(DB::raw('COUNT(*) as count'), DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'))
            ->groupBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();
            
        // Merge with default data to ensure all months are represented
        $santriData = array_merge($defaultData, $santriCounts);
        ksort($santriData);
        
        // Query the database for monthly guru registrations
        $guruCounts = Guru::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->select(DB::raw('COUNT(*) as count'), DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'))
            ->groupBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();
            
        // Merge with default data to ensure all months are represented
        $guruData = array_merge($defaultData, $guruCounts);
        ksort($guruData);
        
        return response()->json([
            'labels' => $labels,
            'santri' => array_values($santriData),
            'guru' => array_values($guruData)
        ]);
    }
    
    /**
     * Provides data for payment summary by month chart
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentSummaryByMonth()
    {
        // Get data for the last 12 months
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        // Generate month labels for the last 12 months
        $labels = [];
        
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths(11 - $i);
            $labels[] = $date->format('M Y');
        }
        
        // Get summary of payment statuses by month
        $paymentSummary = Pembayaran::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->select(
                'status',
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('status', 'month')
            ->get();
            
        // Initialize datasets with default values
        $datasets = [];
        $statusTypes = ['lunas', 'belum_lunas', 'belum_bayar', 'menunggu_pembayaran', 'confirmed', 'pending', 'rejected'];
        $statusLabels = [
            'lunas' => 'Lunas',
            'belum_lunas' => 'Belum Lunas',
            'belum_bayar' => 'Belum Bayar',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'confirmed' => 'Dikonfirmasi',
            'pending' => 'Pending',
            'rejected' => 'Ditolak'
        ];
        
        // Color mapping for each status
        $colors = [
            'lunas' => 'rgba(16, 185, 129, 0.8)',          // Hijau
            'belum_lunas' => 'rgba(245, 158, 11, 0.8)',    // Kuning
            'belum_bayar' => 'rgba(239, 68, 68, 0.8)',     // Merah
            'menunggu_pembayaran' => 'rgba(107, 114, 128, 0.8)', // Abu-abu
            'confirmed' => 'rgba(59, 130, 246, 0.8)',      // Biru
            'pending' => 'rgba(79, 70, 229, 0.8)',         // Ungu
            'rejected' => 'rgba(236, 72, 153, 0.8)'        // Pink
        ];

        // Generate month keys for the last 12 months
        $monthKeys = [];
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths(11 - $i);
            $monthKeys[] = $date->format('Y-m');
        }
        
        // Prepare datasets
        $statusCounts = [];
        foreach ($statusTypes as $status) {
            $statusCounts[$status] = array_fill_keys($monthKeys, 0);
        }
        
        // Populate with actual data
        foreach ($paymentSummary as $summary) {
            if (in_array($summary->month, $monthKeys) && array_key_exists($summary->status, $statusCounts)) {
                $statusCounts[$summary->status][$summary->month] = $summary->count;
            }
        }
        
        // Format datasets for Chart.js
        $datasets = [];
        foreach ($statusTypes as $status) {
            if (array_key_exists($status, $statusCounts)) {
                $datasets[] = [
                    'label' => $statusLabels[$status] ?? ucfirst($status),
                    'data' => array_values($statusCounts[$status]),
                    'backgroundColor' => $colors[$status] ?? 'rgba(107, 114, 128, 0.8)'  // Default to gray
                ];
            }
        }
        
        return response()->json([
            'labels' => $labels,
            'datasets' => $datasets
        ]);
    }
    
    /**
     * Provides data for lunas payment chart showing completed payments by month
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLunasPayments()
    {
        // Get data for the last 12 months
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        // Generate month labels for the last 12 months
        $labels = [];
        $monthKeys = [];
        
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths(11 - $i);
            $labels[] = $date->format('M Y');
            $monthKeys[$date->format('Y-m')] = 0; // Initialize with zeros
        }
        
        // Get counts of lunas payments by month
        $lunasPayments = Pembayaran::where('status', 'lunas')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->get();
            
        // Populate data array with actual counts
        $data = $monthKeys;
        foreach ($lunasPayments as $payment) {
            if (isset($data[$payment->month])) {
                $data[$payment->month] = $payment->count;
            }
        }
        
        // Return formatted data for chart
        return response()->json([
            'labels' => $labels,
            'values' => array_values($data)
        ]);
    }
}