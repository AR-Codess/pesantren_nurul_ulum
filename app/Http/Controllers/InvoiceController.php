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
        
        // Get the month name from periode_pembayaran
        $bulanNames = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
        $bulanNum = $pembayaran->periode_pembayaran->format('m');
        $bulanName = $bulanNames[$bulanNum] ?? 'Unknown';
        $tahun = $pembayaran->periode_pembayaran->format('Y');
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