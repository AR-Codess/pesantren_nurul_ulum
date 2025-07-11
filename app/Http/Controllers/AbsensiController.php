<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiExport;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $absensi = Absensi::with('user')->latest()->get();
        return view('absensi.index', compact('absensi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::role('user')->get();
        return view('absensi.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input absensi harian
        $request->validate([
            'tanggal' => 'required|date',
            'kelas_id' => 'required|exists:kelas,id',
            'absensi' => 'required|array',
        ]);

        $tanggal = $request->tanggal;
        $kelasId = $request->kelas_id;
        $absensiData = $request->absensi;

        // Simpan absensi satu per user (tiap santri satu baris di tabel absensi)
        foreach ($absensiData as $userId => $data) {
            Absensi::create([
                'user_id' => $userId,
                'kelas_id' => $kelasId,
                'tanggal' => $tanggal,
                'status' => $data['status'] ?? 'hadir',
            ]);
        }

        // Redirect ke dashboard setelah absensi berhasil
        return redirect()->route('dashboard')
            ->with('success', 'Absensi harian berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Absensi $absensi)
    {
        return view('absensi.show', compact('absensi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absensi $absensi)
    {
        $users = User::role('user')->get();
        return view('absensi.edit', compact('absensi', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absensi $absensi)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpha',
        ]);

        $absensi->update($request->all());

        return redirect()->route('absensi.index')
            ->with('success', 'Absensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absensi $absensi)
    {
        $absensi->delete();

        return redirect()->route('absensi.index')
            ->with('success', 'Absensi berhasil dihapus.');
    }

    /**
     * Show attendance records for the authenticated user.
     */
    public function check()
    {
        $absensi = Absensi::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('absensi.check', compact('absensi'));
    }

    /**
     * Export absensi to PDF or Excel
     */
    public function export(Request $request, $format)
    {
        $tanggal = $request->tanggal ?? date('Y-m-d');
        $absensi = Absensi::with('user')->where('tanggal', $tanggal)->get();

        if ($format === 'excel') {
            return Excel::download(new AbsensiExport($tanggal), 'absensi_' . $tanggal . '.xlsx');
        } elseif ($format === 'pdf') {
            $pdf = PDF::loadView('absensi.export-pdf', compact('absensi', 'tanggal'));
            return $pdf->download('absensi_' . $tanggal . '.pdf');
        } else {
            abort(404);
        }
    }
}
