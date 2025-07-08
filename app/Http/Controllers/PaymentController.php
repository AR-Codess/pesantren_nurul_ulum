<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Atur konfigurasi Midtrans dari config Laravel
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction(Request $request, $id = null)
    {
        $user = Auth::user();
        $periode = $request->input('periode'); // format: YYYY-MM
        if (!$periode && $id) {
            // Jika tidak ada input, ambil dari pembayaran admin yang sudah ada
            $pembayaranAdmin = \App\Models\Pembayaran::find($id);
            if ($pembayaranAdmin) {
                $periode = $pembayaranAdmin->periode_pembayaran;
            }
        }
        if (!$periode) {
            // Fallback ke bulan-tahun saat ini
            $periode = date('Y-m');
        }
        $jumlahTagihan = $request->input('jumlah') ?? 0;
        $deskripsi = $request->input('deskripsi') ?? 'Pembayaran Bulanan';
        $jenis = $request->input('jenis_pembayaran') ?? 'SPP';
        // Buat pembayaran jika belum ada
        $pembayaran = \App\Models\Pembayaran::firstOrCreate([
            'user_id' => $user->id,
            'periode_pembayaran' => $periode,
        ], [
            'total_tagihan' => $jumlahTagihan,
            'status' => 'UNPAID',
            'is_cicilan' => false,
            'admin_id_pembuat' => 1,
            'deskripsi' => $deskripsi,
            'jenis_pembayaran' => $jenis,
        ]);
        $orderId = 'ORDER-' . $pembayaran->id . '-' . $periode . '-' . time();
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $pembayaran->total_tagihan,
            ],
            'item_details' => [
                [
                    'id' => 'PEMBAYARAN-' . $pembayaran->id,
                    'price' => $pembayaran->total_tagihan,
                    'quantity' => 1,
                    'name' => $deskripsi,
                ],
            ],
            'customer_details' => [
                'first_name' => $user->nama_santri ?? $user->name,
                'email' => $user->email,
                'phone' => $user->no_hp ?? '',
            ],
        ];
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $pembayaran->midtrans_order_id = $orderId;
        $pembayaran->save();

        // Tambahan: fallback update status jika user reload setelah pembayaran sukses (cek ke Midtrans langsung)
        if ($pembayaran->midtrans_order_id) {
            try {
                $status = \Midtrans\Transaction::status($pembayaran->midtrans_order_id);
                if (isset($status->transaction_status) && in_array($status->transaction_status, ['settlement', 'capture'])) {
                    $pembayaran->status = 'lunas';
                    $pembayaran->save();
                    if ($pembayaran->detailPembayaran()->count() == 0) {
                        $pembayaran->detailPembayaran()->create([
                            'jumlah_dibayar' => $pembayaran->total_tagihan,
                            'tanggal_bayar' => now(),
                            'metode_pembayaran' => 'Midtrans',
                            'admin_id_pencatat' => $pembayaran->admin_id_pembuat,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Gagal cek status Midtrans: ' . $e->getMessage());
            }
        }
        return view('payment', compact('snapToken', 'pembayaran', 'periode'));
    }

    public function notificationHandler(Request $request)
    {
        // Ambil notifikasi dari Midtrans
        $notif = new \Midtrans\Notification();
        $transaction = $notif->transaction_status;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        // Cari pembayaran berdasarkan midtrans_order_id, jika tidak ketemu cari berdasarkan id pembayaran di order_id
        $pembayaran = \App\Models\Pembayaran::where('midtrans_order_id', $orderId)->first();
        if (!$pembayaran) {
            // Coba cari berdasarkan id pembayaran di order_id
            $orderParts = explode('-', $orderId);
            $pembayaranId = isset($orderParts[1]) ? $orderParts[1] : null;
            if ($pembayaranId) {
                $pembayaran = \App\Models\Pembayaran::find($pembayaranId);
            }
        }
        if ($pembayaran) {
            // Update midtrans_order_id agar selalu sinkron
            $pembayaran->midtrans_order_id = $orderId;
            // Update status jika pembayaran sukses
            if ($transaction === 'settlement' || $transaction === 'capture') {
                $pembayaran->status = 'lunas';
                $pembayaran->save();
                // Tambahkan detail pembayaran otomatis jika belum ada
                if ($pembayaran->detailPembayaran()->count() == 0) {
                    $pembayaran->detailPembayaran()->create([
                        'jumlah_dibayar' => $pembayaran->total_tagihan,
                        'tanggal_bayar' => now(),
                        'metode_pembayaran' => 'Midtrans',
                        'admin_id_pencatat' => $pembayaran->admin_id_pembuat,
                    ]);
                }
            } elseif ($transaction === 'pending') {
                $pembayaran->status = 'pending';
                $pembayaran->save();
            } elseif ($transaction === 'expire' || $transaction === 'cancel') {
                $pembayaran->status = 'expired';
                $pembayaran->save();
            }
        }
        \Log::info('[MIDTRANS WEBHOOK]', [
            'transaction_status' => $transaction,
            'order_id' => $orderId,
            'fraud_status' => $fraud,
            'payload' => $notif,
        ]);
        return response()->json(['message' => 'Notification processed']);
    }
}
