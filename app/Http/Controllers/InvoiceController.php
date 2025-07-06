<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use PDF;

class InvoiceController extends Controller
{
    /**
     * Generate and download a PDF invoice for a specific payment
     *
     * @param Pembayaran $pembayaran
     * @return \Illuminate\Http\Response
     */
    public function generatePDF(Pembayaran $pembayaran)
    {
        // Eager load the related user (with their classLevel) and all detailPembayaran records
        $pembayaran->load(['user.classLevel', 'detailPembayaran.adminPencatat']);
        
        // Calculate total amount paid
        $totalPaid = $pembayaran->detailPembayaran->sum('jumlah_dibayar');
        
        // Calculate remaining balance
        $remainingBalance = max(0, $pembayaran->total_tagihan - $totalPaid);
        
        // Format the invoice number
        $invoiceNumber = 'INV-' . str_pad($pembayaran->id, 6, '0', STR_PAD_LEFT);
        
        // Get the month name based on the periode_bulan field (integer 1-12)
        $bulanNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        // Use the periode_bulan and periode_tahun fields directly
        $bulanNum = $pembayaran->periode_bulan;
        $bulanName = $bulanNames[$bulanNum] ?? 'Unknown';
        $tahun = $pembayaran->periode_tahun;
        $periodText = $bulanName . ' ' . $tahun;
        
        // Prepare data to pass to the view
        $data = [
            'pembayaran' => $pembayaran,
            'user' => $pembayaran->user,
            'invoiceNumber' => $invoiceNumber,
            'detailPembayaran' => $pembayaran->detailPembayaran,
            'totalPaid' => $totalPaid,
            'remainingBalance' => $remainingBalance,
            'periodText' => $periodText,
            'invoiceDate' => now()->format('d M Y')
        ];
        
        // Generate PDF using DomPDF
        $pdf = PDF::loadView('invoices.pdf', $data);
        
        // Set some PDF options
        $pdf->setPaper('a4');
        
        // Generate a dynamic filename
        $filename = 'INV-' . $pembayaran->id . '-' . $pembayaran->user->nama_santri . '.pdf';
        
        // Return the PDF as a download
        return $pdf->download($filename);
    }
}