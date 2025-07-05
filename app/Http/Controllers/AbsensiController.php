<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use App\Models\Kelas;
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
    public function index(Request $request)
    {
        $kelasId = $request->input('kelas_id');
        $muridList = collect();
        
        if ($kelasId) {
            $kelas = Kelas::find($kelasId);
            if ($kelas) {
                $muridList = $kelas->users;
            }
        }
        
        $absensi = Absensi::with(['users', 'kelas'])->latest()->get();
        return view('absensi.index', compact('absensi', 'muridList'));
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

        // Create a new absensi record for this class and date
        $absensi = Absensi::create([
            'tanggal' => $request->tanggal,
            'kelas_id' => $request->kelas_id,
        ]);

        // Attach guru (teacher) to this absensi record
        $absensi->gurus()->attach(Auth::id());

        // Attach students with their attendance status
        $absensiData = $request->absensi;
        foreach ($absensiData as $userId => $data) {
            $absensi->users()->attach($userId, [
                'status' => $data['status'] ?? 'hadir',
            ]);
        }

        // Redirect to dashboard after successful attendance recording
        return redirect()->route('dashboard')
            ->with('success', 'Absensi harian berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Absensi $absensi)
    {
        $absensi->load(['users', 'gurus', 'kelas']);
        return view('absensi.show', compact('absensi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absensi $absensi)
    {
        $users = User::role('user')->get();
        $absensi->load(['users', 'kelas']);
        return view('absensi.edit', compact('absensi', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absensi $absensi)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => 'required|date',
            'user_statuses' => 'required|array',
        ]);

        // Update basic absensi information
        $absensi->update([
            'kelas_id' => $request->kelas_id,
            'tanggal' => $request->tanggal,
        ]);

        // Sync users with their attendance status
        $userStatuses = $request->user_statuses;
        $syncData = [];
        
        foreach ($userStatuses as $userId => $status) {
            $syncData[$userId] = ['status' => $status];
        }
        
        $absensi->users()->sync($syncData);

        return redirect()->route('absensi.index')
            ->with('success', 'Absensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absensi $absensi)
    {
        // This will automatically detach pivot relationships due to cascading delete
        $absensi->delete();

        return redirect()->route('absensi.index')
            ->with('success', 'Absensi berhasil dihapus.');
    }

    /**
     * Show attendance records for the authenticated user.
     */
    public function check()
    {
        $user = Auth::user();
        $absensi = $user->absensi()->latest()->get();

        return view('absensi.check', compact('absensi'));
    }

    /**
     * Export absensi to PDF or Excel
     */
    public function export(Request $request, $format)
    {
        $tanggal = $request->tanggal ?? date('Y-m-d');
        $absensi = Absensi::with(['users', 'kelas', 'gurus'])
            ->where('tanggal', $tanggal)
            ->get();

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
