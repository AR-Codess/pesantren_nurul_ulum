<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\User;

class GuruAbsenController extends Controller
{
    public function index()
    {
        $guru = auth('guru')->user();
        $today = date('Y-m-d');
        // Ambil jadwal hari ini untuk guru ini
        $jadwalHariIni = Jadwal::with('kelas')
            ->where('guru_id', $guru->id)
            ->where('tanggal', $today)
            ->get();
        // Default: ambil santri dari kelas pertama jadwal hari ini
        $santri = collect();
        if ($jadwalHariIni->count() > 0) {
            $kelasId = $jadwalHariIni->first()->kelas_id;
            $santri = \App\Models\User::where('kelas_id', $kelasId)->get();
        }
        return view('guru.absen', compact('jadwalHariIni', 'santri'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'absensi' => 'required|array',
        ]);
        $tanggal = date('Y-m-d');
        foreach ($request->absensi as $userId => $data) {
            \App\Models\Absensi::updateOrCreate(
                [
                    'user_id' => $userId,
                    'tanggal' => $tanggal,
                ],
                [
                    'status' => $data['status'],
                    'kelas_id' => $request->kelas_id,
                    'guru_id' => auth('guru')->user()->id,
                ]
            );
        }
        return redirect()->route('guru.absen.index')->with('success', 'Absensi berhasil disimpan!');
    }

    public function jadwal()
    {
        $guru = auth('guru')->user();
        $hari = \Carbon\Carbon::now()->isoFormat('dddd');
        // Ambil semua jadwal hari ini untuk guru
        $jadwalHariIni = Jadwal::with('kelas')
            ->where('guru_id', $guru->id)
            ->where('hari', $hari)
            ->get();
        return view('guru.jadwal', compact('jadwalHariIni'));
    }

    public function input($jadwal_id)
    {
        // Data dummy santri kelas 1A
        $santri = [
            (object)['id' => 1, 'name' => 'Budi', 'nis' => '2025001'],
            (object)['id' => 2, 'name' => 'Siti', 'nis' => '2025002'],
            (object)['id' => 3, 'name' => 'Ahmad', 'nis' => '2025003'],
        ];
        $jadwalHariIni = [
            (object)[
                'kelas' => (object)['id' => 1, 'nama_kelas' => '1A'],
                'mapel' => 'Matematika',
            ]
        ];
        return view('guru.absen', [
            'santri' => $santri,
            'jadwalHariIni' => $jadwalHariIni,
        ]);
    }
}