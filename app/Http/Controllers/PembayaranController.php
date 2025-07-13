<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Pembayaran;
use App\Models\DetailPembayaran;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Storage;
// ============== TAMBAHKAN USE STATEMENT UNTUK MIDTRANS ==============
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PembayaranController extends Controller
{
    // ============== TAMBAHKAN CONSTRUCTOR UNTUK KONFIGURASI MIDTRANS ==============
    /**
     * Constructor to set up Midtrans configuration.
     */
    public function __construct()
    {
        // Set your Merchant Server Key
        Config::$serverKey = config('services.midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment.
        Config::$isProduction = config('services.midtrans.is_production', false);
        // Set sanitization on (default)
        Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        Config::$is3ds = true;
    }

    /**
 * Membuat record pembayaran jika belum ada, lalu redirect ke halaman pembayaran.
 *
 * @param int $year
 * @param int $month
 * @return \Illuminate\Http\RedirectResponse
 */
public function createAndPay($year, $month)
{
    $user = auth()->user()->load('classLevel'); // Load relasi classLevel

    // Cek apakah user punya classLevel
    if (!$user->classLevel) {
        return redirect()->route('dashboard')->with('error', 'Data kelas Anda tidak ditemukan. Hubungi admin.');
    }
    
    // Tentukan jumlah SPP
    $sppBulanan = $user->is_beasiswa && isset($user->classLevel->spp_beasiswa)
        ? $user->classLevel->spp_beasiswa
        : $user->classLevel->spp;

    // Gunakan firstOrCreate untuk membuat tagihan jika belum ada
    $pembayaran = Pembayaran::firstOrCreate(
        [
            'user_id' => $user->id,
            'periode_bulan' => $month,
            'periode_tahun' => $year,
        ],
        [
            'total_tagihan' => $sppBulanan,
            'status' => 'belum_lunas', // Status awal
            'is_cicilan' => 0,
            // Asumsikan ada admin default atau ambil admin pertama
            'admin_id_pembuat' => \App\Models\Admin::first()->id ?? 1, 
            'deskripsi' => 'Tagihan SPP Bulan ' . \Carbon\Carbon::createFromDate($year, $month)->isoFormat('MMMM YYYY'),
        ]
    );

    // Redirect ke halaman pembayaran Midtrans dengan ID tagihan yang sudah pasti ada
    return redirect()->route('pembayaran.pay_midtrans', $pembayaran->id);
}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $search = $request->input('search');
    $bulan = $request->input('bulan');
    $status = $request->input('status');
    $tahun = $request->input('tahun');
    $startDate = $request->input('start_date'); // [BARU] Ambil input tanggal mulai
    $endDate = $request->input('end_date');     // [BARU] Ambil input tanggal akhir
    $perPage = $request->input('per_page', 10);

    $pembayarans = Pembayaran::with(['user', 'detailPembayaran']) // Eager load relasi
        ->when($search, function ($query, $search) {
            return $query->whereHas('user', function($q) use ($search) {
                $q->where('nama_santri', 'like', '%' . $search . '%')
                  ->orWhere('nis', 'like', '%' . $search . '%');
            });
        })
        ->when($bulan, function ($query, $bulan) {
            $bulanAngka = [
                'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
                'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
                'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
            ];
            return isset($bulanAngka[$bulan]) ? $query->where('periode_bulan', $bulanAngka[$bulan]) : $query;
        })
        ->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->when($tahun, function ($query, $tahun) {
            return $query->where('periode_tahun', $tahun);
        })
        // [BARU] Logika untuk filter rentang tanggal
        ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            // Filter berdasarkan tanggal pembayaran dibuat (created_at)
            // Mengubah endDate ke akhir hari agar data pada tanggal tersebut ikut terfilter
            $endDateCarbon = \Carbon\Carbon::parse($endDate)->endOfDay();
            return $query->whereBetween('created_at', [$startDate, $endDateCarbon]);
        })
        ->latest()
        ->paginate($perPage)
        ->withQueryString();

    return view('pembayaran.index', compact('pembayarans'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::role('user')->get();
        return view('pembayaran.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jumlah_dibayar' => 'required|numeric',
            'metode_pembayaran' => 'required|string',
            'periode_bulan' => 'required|integer|min:1|max:12',
            'periode_tahun' => 'required|integer',
        ]);

        // Get the user and their class level for SPP
        $user = User::with('classLevel')->findOrFail($request->user_id);
        // Check if student has scholarship and use appropriate SPP amount
        $sppBulanan = $user->is_beasiswa && $user->classLevel->spp_beasiswa !== null 
            ? $user->classLevel->spp_beasiswa 
            : $user->classLevel->spp;

        // Validate payment amount matches SPP if not an installment payment
        if (!$request->has('is_cicilan') && $request->jumlah_dibayar != $sppBulanan) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['jumlah_dibayar' => 'Jumlah pembayaran harus sama dengan nilai SPP bulanan (Rp ' . number_format($sppBulanan, 0, ',', '.') . ') jika bukan pembayaran cicilan.']);
        }
        
        // Check if payment for this period already exists and is fully paid
        $bulanNames = [
            '1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April',
            '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus',
            '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        
        $existingPayment = Pembayaran::where('user_id', $request->user_id)
            ->where('periode_bulan', $request->periode_bulan)
            ->where('periode_tahun', $request->periode_tahun)
            ->where('status', 'lunas')
            ->first();
        
        if ($existingPayment) {
            $monthName = $bulanNames[$request->periode_bulan] ?? "Bulan {$request->periode_bulan}";
            return redirect()->back()
                ->withInput()
                ->withErrors(['periode_bulan' => "Pembayaran untuk {$monthName} {$request->periode_tahun} sudah lunas."]);
        }
        
        // Set admin_id_pembuat based on who is creating the payment
        $admin_id_pembuat = null;
        $isAdminPayment = false;
        
        if (Auth::guard('admin')->check()) {
            $admin_id_pembuat = Auth::guard('admin')->id();
            $isAdminPayment = true;
            $metode_pembayaran = 'Tunai';
        } else {
            // If regular user is creating payment
            $admin = Admin::first();
            if ($admin) {
                $admin_id_pembuat = $admin->id;
            } else {
                return redirect()->back()->with('error', 'Tidak ada admin yang tersedia untuk membuat pembayaran.');
            }
            $metode_pembayaran = $request->metode_pembayaran;
        }
        
        // Determine payment status
        $status = 'menunggu_pembayaran';
        if ($isAdminPayment) {
            if ($request->has('is_cicilan')) {
                $status = ($request->jumlah_dibayar >= $sppBulanan) ? 'lunas' : 'belum_lunas';
            } else {
                $status = 'lunas';
            }
        }
        
        // Siapkan data untuk disimpan secara eksplisit
        $dataToCreate = [
            'user_id' => $request->user_id,
            'total_tagihan' => $sppBulanan,
            'periode_bulan' => $request->periode_bulan, // <-- WAJIB DITAMBAHKAN
            'periode_tahun' => $request->periode_tahun, // <-- WAJIB DITAMBAHKAN
            'status' => $status,
            'is_cicilan' => $request->has('is_cicilan') ? 1 : 0,
            'admin_id_pembuat' => $admin_id_pembuat,
        ];
        
        // Buat record pembayaran dengan data yang sudah lengkap
        $pembayaran = Pembayaran::create($dataToCreate);
        
        // Buat detail pembayaran untuk semua jenis pembayaran (cicilan maupun langsung)
        DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'jumlah_dibayar' => $request->jumlah_dibayar,
            'tanggal_bayar' => now(),
            'metode_pembayaran' => $metode_pembayaran,
            'admin_id_pencatat' => $admin_id_pembuat,
            // SOLUSI: Tambahkan baris ini untuk menyimpan URL download invoice
            'bukti_pembayaran' => route('invoice.download', ['pembayaran' => $pembayaran->id]),
        ]);

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil ditambahkan. Invoice siap diunduh.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembayaran $pembayaran)
    {
        // Muat relasi 'user' dan 'detailPembayaran' pada objek $pembayaran
        $pembayaran->load(['user', 'detailPembayaran']);

        return view('pembayaran.show', compact('pembayaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembayaran $pembayaran)
    {
        $users = User::role('user')->get();
        return view('pembayaran.edit', compact('pembayaran', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jumlah_dibayar' => 'required|numeric',
            'tanggal_bayar' => 'required|date',
            'metode_pembayaran' => 'required|string',
        ]);

        // Get user with classLevel for SPP amount
        $user = User::with('classLevel')->findOrFail($request->user_id);
        // Check if student has scholarship and use appropriate SPP amount
        $sppBulanan = $user->is_beasiswa && $user->classLevel->spp_beasiswa !== null 
            ? $user->classLevel->spp_beasiswa 
            : $user->classLevel->spp;
        
        // Validate payment amount matches SPP if not an installment payment
        if (!$request->has('is_cicilan') && $request->jumlah_dibayar != $sppBulanan) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['jumlah_dibayar' => 'Jumlah pembayaran harus sama dengan nilai SPP bulanan (Rp ' . number_format($sppBulanan, 0, ',', '.') . ') jika bukan pembayaran cicilan.']);
        }
        
        // Prepare data to update
        $dataToUpdate = [
            'user_id' => $request->user_id,
            'total_tagihan' => $sppBulanan,
            'is_cicilan' => $request->has('is_cicilan') ? 1 : 0,
        ];
        
        // Set admin_id_pembuat if there are changes
        $isAdminPayment = false;
        if (Auth::guard('admin')->check()) {
            $isAdminPayment = true;
            
            // Force payment method to Tunai if admin is editing
            $metode_pembayaran = 'Tunai';
        } else {
            $metode_pembayaran = $request->metode_pembayaran;
        }
        
        // Extract month and year from payment date
        $paymentDate = \Carbon\Carbon::parse($request->tanggal_bayar);
        
        // Update periode_bulan and periode_tahun from payment date
        $dataToUpdate['periode_bulan'] = (int)$paymentDate->format('m'); 
        $dataToUpdate['periode_tahun'] = (int)$paymentDate->format('Y');

        // Tentukan status pembayaran berdasarkan pembuat dan jumlah
        if ($isAdminPayment) {
            // Admin mengedit pembayaran
            if (isset($dataToUpdate['is_cicilan']) && $dataToUpdate['is_cicilan']) {
                // Jika cicilan, periksa jumlah detail pembayaran atau jumlah pembayaran ini
                if ($pembayaran->is_cicilan) {
                    // Hitung total yang sudah dibayar termasuk detail pembayaran
                    $totalDibayar = $pembayaran->detailPembayaran->sum('jumlah_dibayar');
                    if ($totalDibayar >= $sppBulanan) {
                        $dataToUpdate['status'] = 'lunas';
                    } else {
                        $dataToUpdate['status'] = 'belum_lunas';
                    }
                } else {
                    // Jika sebelumnya bukan cicilan, maka ini adalah cicilan pertama
                    if ($request->jumlah_dibayar >= $sppBulanan) {
                        $dataToUpdate['status'] = 'lunas';
                    } else {
                        $dataToUpdate['status'] = 'belum_lunas';
                    }
                    
                    // Buat entri detail pembayaran untuk cicilan pertama
                    DetailPembayaran::create([
                        'pembayaran_id' => $pembayaran->id,
                        'jumlah_dibayar' => $request->jumlah_dibayar,
                        'tanggal_bayar' => $request->tanggal_bayar,
                        'metode_pembayaran' => $metode_pembayaran,
                        'admin_id_pencatat' => Auth::guard('admin')->id(),
                    ]);
                }
            } else {
                // Pembayaran langsung (non-cicilan)
                $dataToUpdate['status'] = 'lunas';
                
                // If converting from installment to non-installment or creating a new payment detail
                // First check if there are no payment details yet
                if ($pembayaran->detailPembayaran->isEmpty()) {
                    // Create a detail payment record for this non-installment payment
                    DetailPembayaran::create([
                        'pembayaran_id' => $pembayaran->id,
                        'jumlah_dibayar' => $request->jumlah_dibayar,
                        'tanggal_bayar' => $request->tanggal_bayar,
                        'metode_pembayaran' => $metode_pembayaran,
                        'admin_id_pencatat' => Auth::guard('admin')->id(),
                    ]);
                } else {
                    // Update the existing payment detail
                    $detailPembayaran = $pembayaran->detailPembayaran->first();
                    $detailPembayaran->update([
                        'jumlah_dibayar' => $request->jumlah_dibayar,
                        'tanggal_bayar' => $request->tanggal_bayar,
                        'metode_pembayaran' => $metode_pembayaran,
                    ]);
                }
            }
        } else {
            // User mengedit pembayaran (tetap menunggu konfirmasi)
            $dataToUpdate['status'] = 'menunggu_pembayaran';
        }

        $pembayaran->update($dataToUpdate);

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }

    /**
     * Display payments for the authenticated user.
     */
    public function userIndex()
    {
        $pembayarans = Pembayaran::where('user_id', Auth::id())
            ->latest()
            ->get();
        
        return view('pembayaran.user', compact('pembayarans'));
    }

    /**
     * Store installment payment for a bill.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeInstallment(Request $request)
    {
        // Validate request
        $request->validate([
            'pembayaran_id' => 'required|exists:pembayaran,id',
            'jumlah_dibayar' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|string|in:Tunai',
        ]);

        // Get the main payment record
        $pembayaran = Pembayaran::findOrFail($request->pembayaran_id);
        // Set is_cicilan flag to true
        $pembayaran->is_cicilan = true;
        
        // Create detail payment record
        $detailPembayaran = DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'jumlah_dibayar' => $request->jumlah_dibayar,
            'tanggal_bayar' => now(),
            'metode_pembayaran' => $request->metode_pembayaran,
            // SOLUSI: Ganti 'null' dengan URL download invoice
            'bukti_pembayaran' => route('invoice.download', ['pembayaran' => $pembayaran->id]),
            'admin_id_pencatat' => Auth::guard('admin')->check() ? Auth::guard('admin')->id() : null,
        ]);

        // Calculate total paid amount for this payment
        $totalDibayar = DetailPembayaran::where('pembayaran_id', $pembayaran->id)
                            ->sum('jumlah_dibayar');
        
        // Update payment status based on total paid
        if ($totalDibayar >= $pembayaran->total_tagihan) {
            $pembayaran->status = 'lunas';
        } elseif ($totalDibayar > 0) {
            $pembayaran->status = 'belum_lunas';
        } else {
            $pembayaran->status = 'belum_bayar';
        }
        
        $pembayaran->save();

        // Redirect back to the installment page with a success message
        return redirect()->route('pembayaran.installment.show', $pembayaran->id)
            ->with('success', 'Pembayaran cicilan berhasil disimpan');
    }

    /**
     * Get installment history for a payment
     * 
     * @param int $id Payment ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInstallmentHistory($id)
    {
        $pembayaran = Pembayaran::with('detailPembayaran')->findOrFail($id);
        $totalDibayar = $pembayaran->detailPembayaran->sum('jumlah_dibayar');
        $sisa_tagihan = max(0, $pembayaran->total_tagihan - $totalDibayar);
        
        return response()->json([
            'success' => true,
            'data' => [
                'pembayaran' => $pembayaran,
                'detail' => $pembayaran->detailPembayaran,
                'total_dibayar' => $totalDibayar,
                'sisa_tagihan' => $sisa_tagihan
            ]
        ]);
    }

    /**
     * Show the installment payment page for a specific payment.
     *
     * @param int $id Payment ID
     * @return \Illuminate\View\View
     */
    public function showInstallmentPage($id)
    {
        $pembayaran = Pembayaran::with('user')->findOrFail($id);
        $detailPembayaran = DetailPembayaran::where('pembayaran_id', $id)
                                ->orderBy('tanggal_bayar', 'desc')
                                ->get();
                                
        $totalDibayar = $detailPembayaran->sum('jumlah_dibayar');
        $sisa_tagihan = max(0, $pembayaran->total_tagihan - $totalDibayar);
        
        return view('pembayaran.installment', compact(
            'pembayaran',
            'detailPembayaran',
            'totalDibayar',
            'sisa_tagihan'
        ));
    }

    /**
     * API endpoint to check payment status for a user in a specific month
     * 
     * @param int $userId User ID
     * @param string $month Month name
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPaymentStatus($userId, $month)
    {
        // Convert month name to month number
        $bulanAngka = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
            'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
            'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
        ];
        
        $monthNumber = isset($bulanAngka[$month]) ? $bulanAngka[$month] : null;
        
        if (!$monthNumber) {
            return response()->json([
                'exists' => false,
                'error' => 'Invalid month name'
            ], 400);
        }
        
        $currentYear = date('Y');
        
        $payment = Pembayaran::where('user_id', $userId)
                    ->where('periode_bulan', $monthNumber)
                    ->where('periode_tahun', $currentYear)
                    ->latest()
                    ->first();
        
        // Get user with class level to access SPP amount            
        $user = User::with('classLevel')->findOrFail($userId);
        // Check if student has scholarship and use appropriate SPP amount
        $sppBulanan = $user->is_beasiswa && $user->classLevel->spp_beasiswa !== null 
            ? $user->classLevel->spp_beasiswa 
            : $user->classLevel->spp;
                    
        if (!$payment) {
            return response()->json([
                'exists' => false,
                'spp_amount' => $sppBulanan // Return SPP amount even when no payment exists
            ]);
        }
        
        $totalPaid = 0;
        $remaining = 0;
        
        if ($payment->is_cicilan) {
            $totalPaid = $payment->detailPembayaran->sum('jumlah_dibayar');
            $remaining = max(0, $sppBulanan - $totalPaid);
        } else {
            // For non-installment payments, check the detail payment
            $firstPayment = $payment->detailPembayaran->first();
            $totalPaid = $firstPayment ? $firstPayment->jumlah_dibayar : 0;
            $remaining = max(0, $sppBulanan - $totalPaid);
        }
        
        return response()->json([
            'exists' => true,
            'payment' => $payment,
            'total_paid' => $totalPaid,
            'remaining' => $remaining,
            'spp_amount' => $sppBulanan
        ]);
    }

    /**
     * Redirect user to Midtrans payment page.
     *
     * @param int $id The ID of the Pembayaran record.
     * @return \Illuminate\View\View
     */
    public function payWithMidtrans($id)
{
    $pembayaran = Pembayaran::with('user')->findOrFail($id);
    
    // ================== TAMBAHKAN KODE PENGAMAN INI ==================
    // Cek jika total tagihan valid sebelum melanjutkan
    if ($pembayaran->total_tagihan <= 0) {
        // Redirect kembali dengan pesan error yang jelas
        return redirect()->route('dashboard')
            ->with('error', 'Gagal memproses pembayaran. Jumlah tagihan tidak boleh nol.');
    }
    // =================================================================

    $user = $pembayaran->user;

    // Jika tagihan sudah lunas, kembalikan ke dashboard
    if (in_array(strtolower($pembayaran->status), ['lunas', 'paid'])) {
        return redirect()->route('dashboard')->with('info', 'Tagihan ini sudah lunas.');
    }

    // Buat Order ID unik untuk Midtrans
    $orderId = 'SPP-' . $pembayaran->id . '-' . time();
    $pembayaran->midtrans_order_id = $orderId;
    $pembayaran->save();

    // Siapkan parameter untuk Midtrans
    $params = [
        'transaction_details' => [
            'order_id' => $orderId,
            'gross_amount' => $pembayaran->total_tagihan,
        ],
        'item_details' => [[
            'id' => 'PEMBAYARAN-' . $pembayaran->id,
            'price' => $pembayaran->total_tagihan,
            'quantity' => 1,
            'name' => $pembayaran->deskripsi ?? 'Pembayaran SPP ' . optional($pembayaran->periode_pembayaran)->format('F Y'),
        ]],
        'customer_details' => [
            'first_name' => $user->nama_santri ?? $user->name,
            'email' => $user->email,
            'phone' => $user->no_hp ?? '',
        ],
    ];

    try {
        $snapToken = Snap::getSnapToken($params);
        return view('pembayaran.midtrans_payment', compact('snapToken', 'pembayaran'));
    } catch (\Exception $e) {
        Log::error('Midtrans Snap Token Error: ' . $e->getMessage());
        return redirect()->route('dashboard')->with('error', 'Gagal terhubung ke gateway pembayaran. Coba lagi nanti.');
    }
}

   /**
 * Handle notification from Midtrans (Webhook).
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function midtransNotificationHandler(Request $request)
{
    try {
        // 1. Ambil Server Key dari config untuk validasi
        $serverKey = config('services.midtrans.server_key');
        
        // 2. Buat hash dari data notifikasi untuk dicocokkan dengan signature_key
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        // 3. Validasi signature key (keamanan)
        if ($hashed != $request->signature_key) {
            \Log::warning('[Midtrans Webhook] Invalid signature key.', ['request' => $request->all()]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // --- Signature Key Valid, Lanjutkan Proses ---

        $orderId = $request->order_id;
        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status;

        $pembayaran = \App\Models\Pembayaran::where('midtrans_order_id', $orderId)->first();

        // Jika pembayaran tidak ditemukan di DB, kirim respons error
        if (!$pembayaran) {
            \Log::warning('[Midtrans Webhook] Pembayaran tidak ditemukan untuk order_id: ' . $orderId);
            return response()->json(['message' => 'Payment not found'], 404);
        }
        
        // Hindari proses duplikat jika status sudah lunas
        if ($pembayaran->status === 'lunas') {
            return response()->json(['message' => 'Notification for this order already processed.']);
        }

        // === Di sinilah logika utama Anda berjalan ===
        if ($transactionStatus == 'settlement') {
            // Status dari Midtrans adalah 'settlement', kita update menjadi 'lunas'
            $pembayaran->status = 'lunas';
        } else if ($transactionStatus == 'pending') {
            // Status 'pending' juga kita catat
            $pembayaran->status = 'pending';
        } else if ($transactionStatus == 'expire') {
            // Status 'expire' juga kita catat
            $pembayaran->status = 'expired';
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny') {
            // Status 'cancel' atau 'deny' juga kita catat
            $pembayaran->status = 'dibatalkan';
        }

        // Perbaikan: Gunakan Log::info() dengan benar
        \Log::info('Data Pembayaran Sebelum Disimpan:', $pembayaran->toArray());

        // Simpan perubahan status ke database
        $pembayaran->save();

        // Jika pembayaran lunas, buat juga detailnya sebagai arsip
        if ($pembayaran->status === 'lunas') {
            Log::info('[Midtrans Webhook] Status lunas terdeteksi untuk order_id: ' . $orderId . '. Mencoba membuat detail pembayaran.');

            // Cek sekali lagi untuk menghindari duplikasi detail pembayaran dari Midtrans
            if ($pembayaran->detailPembayaran()->where('metode_pembayaran', 'like', 'Midtrans%')->doesntExist()) {
                try {
                    // 1. Siapkan data bukti pembayaran yang terstruktur
                    $buktiPembayaran = [
                        'Tipe Pembayaran'    => $request->input('payment_type'),
                        'ID Transaksi'       => $request->input('transaction_id'),
                        'Waktu Transaksi'    => $request->input('transaction_time'),
                        'Waktu Penyelesaian' => $request->input('settlement_time'),
                        'Jumlah'             => 'Rp ' . number_format((float)$request->input('gross_amount'), 0, ',', '.'),
                    ];

                    // 2. Tambahkan detail spesifik berdasarkan tipe pembayaran
                    if ($request->input('payment_type') == 'bank_transfer' && $request->has('va_numbers.0.bank')) {
                        $buktiPembayaran['Bank'] = strtoupper($request->input('va_numbers.0.bank'));
                        $buktiPembayaran['Nomor VA'] = $request->input('va_numbers.0.va_number');
                    } elseif ($request->input('payment_type') == 'cstore') {
                        $buktiPembayaran['Toko'] = ucfirst($request->input('store'));
                        $buktiPembayaran['Kode Pembayaran'] = $request->input('payment_code');
                    }

                    // 3. Format nama metode pembayaran
                    $metodePembayaran = 'Midtrans - ' . Str::title(str_replace('_', ' ', $request->input('payment_type', 'N/A')));

                   // 4. Buat record detail pembayaran
                    DetailPembayaran::create([
                        'pembayaran_id'       => $pembayaran->id,
                        'jumlah_dibayar'      => (float)$request->input('gross_amount'),
                        'tanggal_bayar'       => \Carbon\Carbon::parse($request->input('settlement_time', now())),
                        'metode_pembayaran'   => $metodePembayaran,
                        'admin_id_pencatat'   => $pembayaran->admin_id_pembuat,
                        // SOLUSI: Ganti json_encode dengan URL download invoice
                        'bukti_pembayaran'    => route('invoice.download', ['pembayaran' => $pembayaran->id]),
                    ]);

                    
                    Log::info('[Midtrans Webhook] Detail pembayaran berhasil dibuat untuk order_id: ' . $orderId);

                } catch (\Exception $e) {
                    Log::error('[Midtrans Webhook] Gagal membuat detail pembayaran untuk order_id: ' . $orderId . ' | Error: ' . $e->getMessage());
                }
            } else {
                Log::info('[Midtrans Webhook] Detail pembayaran untuk order_id: ' . $orderId . ' sudah ada, proses dilewati.');
            }
        }

        return response()->json(['message' => 'Notification processed successfully.']);
        
    } catch (\Exception $e) {
        \Log::error('[Midtrans Webhook] Error processing notification: ' . $e->getMessage(), [
            'request' => $request->all(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json(['message' => 'Internal server error'], 500);
    }
}
}