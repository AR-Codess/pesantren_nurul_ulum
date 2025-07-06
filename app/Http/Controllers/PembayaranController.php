<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pembayaran;
use App\Models\DetailPembayaran;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $bulan = $request->input('bulan');
        $status = $request->input('status');
        $perPage = $request->input('per_page', 4);

        $pembayarans = Pembayaran::with('user')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function($q) use ($search) {
                    $q->where('nama_santri', 'like', '%' . $search . '%')
                      ->orWhere('nis', 'like', '%' . $search . '%');
                })
                ->orWhere('bulan', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%');
            })
            ->when($bulan, function ($query, $bulan) {
                // Convert month name to month number
                $bulanAngka = [
                    'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
                    'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
                    'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
                ];
                
                if (isset($bulanAngka[$bulan])) {
                    return $query->where('periode_bulan', $bulanAngka[$bulan]);
                }
                return $query;
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('pembayaran.index', compact('pembayarans', 'search', 'bulan', 'status', 'perPage'));
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
            'tanggal_bayar' => now(), // Use current date and time
            'metode_pembayaran' => $metode_pembayaran,
            'admin_id_pencatat' => $admin_id_pembuat,
        ]);

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil ditambahkan.');
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
            'pembayaran_id' => $request->pembayaran_id,
            'jumlah_dibayar' => $request->jumlah_dibayar,
            'tanggal_bayar' => now(),
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran' => null,
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
}