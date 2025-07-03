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
                ->orWhere('periode_pembayaran', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%');
            })
            ->when($bulan, function ($query, $bulan) {
                // Convert month name to month number
                $bulanAngka = [
                    'Januari' => '01', 'Februari' => '02', 'Maret' => '03', 'April' => '04',
                    'Mei' => '05', 'Juni' => '06', 'Juli' => '07', 'Agustus' => '08',
                    'September' => '09', 'Oktober' => '10', 'November' => '11', 'Desember' => '12'
                ];
                
                if (isset($bulanAngka[$bulan])) {
                    return $query->whereMonth('periode_pembayaran', $bulanAngka[$bulan]);
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
            'tanggal_bayar' => 'required|date',
            'metode_pembayaran' => 'required|string',
        ]);

        // Get the user and their class level for SPP
        $user = User::with('classLevel')->findOrFail($request->user_id);
        $sppBulanan = $user->classLevel->spp;

        // Validate payment amount matches SPP if not an installment payment
        if (!$request->has('is_cicilan') && $request->jumlah_dibayar != $sppBulanan) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['jumlah_dibayar' => 'Jumlah pembayaran harus sama dengan nilai SPP bulanan (Rp ' . number_format($sppBulanan, 0, ',', '.') . ') jika bukan pembayaran cicilan.']);
        }
        
        // Prepare payment data
        $data = $request->all();
        $data['total_tagihan'] = $sppBulanan;
        
        // Set admin_id_pembuat based on who is creating the payment
        $isAdminPayment = false;
        if (Auth::guard('admin')->check()) {
            $data['admin_id_pembuat'] = Auth::guard('admin')->id();
            $isAdminPayment = true;
            
            // Force payment method to Tunai if admin is creating
            $data['metode_pembayaran'] = 'Tunai';
        } else {
            // If regular user is creating payment
            $admin = Admin::first();
            if ($admin) {
                $data['admin_id_pembuat'] = $admin->id;
            } else {
                return redirect()->back()->with('error', 'Tidak ada admin yang tersedia untuk membuat pembayaran.');
            }
            
            // Set status to awaiting payment if created by user
            $data['status'] = 'menunggu_pembayaran';
        }
        
        // Extract month name from payment date
        $paymentDate = \Carbon\Carbon::parse($request->tanggal_bayar);
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
        $bulanNum = $paymentDate->format('m');
        $data['bulan'] = $bulanNames[$bulanNum] ?? 'Januari';
        
        // Set nilai periode_pembayaran to the first day of the month from payment date
        $data['periode_pembayaran'] = $paymentDate->format('Y-m-01');
        
        // Tentukan status pembayaran berdasarkan pembuat dan jumlah
        if ($isAdminPayment) {
            // Admin membuat pembayaran
            if (isset($data['is_cicilan']) && $data['is_cicilan']) {
                // Pembayaran cicilan
                if ($request->jumlah_dibayar >= $sppBulanan) {
                    $data['status'] = 'lunas';
                } else {
                    $data['status'] = 'belum_lunas';
                }
            } else {
                // Pembayaran langsung (non-cicilan)
                $data['status'] = 'lunas';
            }
        } else {
            // User membuat pembayaran (menunggu konfirmasi)
            $data['status'] = 'menunggu_pembayaran';
        }
        
        // Buat record pembayaran
        $pembayaran = Pembayaran::create($data);
        
        // Buat detail pembayaran untuk semua jenis pembayaran (cicilan maupun langsung)
        DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'jumlah_dibayar' => $request->jumlah_dibayar,
            'tanggal_bayar' => $request->tanggal_bayar,
            'metode_pembayaran' => $data['metode_pembayaran'],
            'admin_id_pencatat' => $data['admin_id_pembuat'],
        ]);

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembayaran $pembayaran)
    {
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
        $sppBulanan = $user->classLevel->spp;
        
        // Validate payment amount matches SPP if not an installment payment
        if (!$request->has('is_cicilan') && $request->jumlah_dibayar != $sppBulanan) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['jumlah_dibayar' => 'Jumlah pembayaran harus sama dengan nilai SPP bulanan (Rp ' . number_format($sppBulanan, 0, ',', '.') . ') jika bukan pembayaran cicilan.']);
        }
        
        // Update total_tagihan if user_id changes
        $data = $request->all();
        if ($pembayaran->user_id != $request->user_id) {
            $data['total_tagihan'] = $sppBulanan;
        }
        
        // Set admin_id_pembuat if there are changes
        $isAdminPayment = false;
        if (Auth::guard('admin')->check()) {
            $isAdminPayment = true;
            
            // Force payment method to Tunai if admin is editing
            $data['metode_pembayaran'] = 'Tunai';
        }
        
        // Extract month name from payment date
        $paymentDate = \Carbon\Carbon::parse($request->tanggal_bayar);
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
        $bulanNum = $paymentDate->format('m');
        $data['bulan'] = $bulanNames[$bulanNum] ?? 'Januari';
        
        // Set periode_pembayaran to the first day of the month from payment date
        $data['periode_pembayaran'] = $paymentDate->format('Y-m-01');

        // Tentukan status pembayaran berdasarkan pembuat dan jumlah
        if ($isAdminPayment) {
            // Admin mengedit pembayaran
            if (isset($data['is_cicilan']) && $data['is_cicilan']) {
                // Jika cicilan, periksa jumlah detail pembayaran atau jumlah pembayaran ini
                if ($pembayaran->is_cicilan) {
                    // Hitung total yang sudah dibayar termasuk detail pembayaran
                    $totalDibayar = $pembayaran->detailPembayaran->sum('jumlah_dibayar');
                    if ($totalDibayar >= $sppBulanan) {
                        $data['status'] = 'lunas';
                    } else {
                        $data['status'] = 'belum_lunas';
                    }
                } else {
                    // Jika sebelumnya bukan cicilan, maka ini adalah cicilan pertama
                    if ($request->jumlah_dibayar >= $sppBulanan) {
                        $data['status'] = 'lunas';
                    } else {
                        $data['status'] = 'belum_lunas';
                    }
                    
                    // Buat entri detail pembayaran untuk cicilan pertama
                    DetailPembayaran::create([
                        'pembayaran_id' => $pembayaran->id,
                        'jumlah_dibayar' => $request->jumlah_dibayar,
                        'tanggal_bayar' => $request->tanggal_bayar,
                        'metode_pembayaran' => $data['metode_pembayaran'],
                        'admin_id_pencatat' => Auth::guard('admin')->id(),
                    ]);
                }
            } else {
                // Pembayaran langsung (non-cicilan)
                $data['status'] = 'lunas';
                
                // If converting from installment to non-installment or creating a new payment detail
                // First check if there are no payment details yet
                if ($pembayaran->detailPembayaran->isEmpty()) {
                    // Create a detail payment record for this non-installment payment
                    DetailPembayaran::create([
                        'pembayaran_id' => $pembayaran->id,
                        'jumlah_dibayar' => $request->jumlah_dibayar,
                        'tanggal_bayar' => $request->tanggal_bayar,
                        'metode_pembayaran' => $data['metode_pembayaran'],
                        'admin_id_pencatat' => Auth::guard('admin')->id(),
                    ]);
                } else {
                    // Update the existing payment detail
                    $detailPembayaran = $pembayaran->detailPembayaran->first();
                    $detailPembayaran->update([
                        'jumlah_dibayar' => $request->jumlah_dibayar,
                        'tanggal_bayar' => $request->tanggal_bayar,
                        'metode_pembayaran' => $data['metode_pembayaran'],
                    ]);
                }
            }
        } else {
            // User mengedit pembayaran (tetap menunggu konfirmasi)
            $data['status'] = 'menunggu_pembayaran';
        }

        $pembayaran->update($data);

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
        $payment = Pembayaran::where('user_id', $userId)
                    ->where('bulan', $month)
                    ->latest()
                    ->first();
        
        // Get user with class level to access SPP amount            
        $user = User::with('classLevel')->findOrFail($userId);
        $sppBulanan = $user->classLevel->spp;
                    
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