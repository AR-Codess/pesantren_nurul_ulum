<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Absensi;
use PDF;

class RekapAbsensiController extends Controller
{
    public function downloadKumulatif($kelasId)
    {
        $kelas = Kelas::with(['classLevels', 'users'])->findOrFail($kelasId);
        $absensi = Absensi::where('kelas_id', $kelasId)->get();
        $rekap = [];
        foreach ($kelas->users as $user) {
            $rekap[] = [
                'nama' => $user->nama_santri ?? $user->name,
                'jenjang' => $user->classLevel->level ?? '-',
                'hadir' => $absensi->where('user_id', $user->id)->where('status', 'hadir')->count(),
                'izin' => $absensi->where('user_id', $user->id)->where('status', 'izin')->count(),
                'sakit' => $absensi->where('user_id', $user->id)->where('status', 'sakit')->count(),
                'alpha' => $absensi->where('user_id', $user->id)->where('status', 'alpha')->count(),
            ];
        }
        $guru = optional($kelas->guru)->nama_pendidik ?? 'guru';
        $jenjang = $kelas->classLevels->pluck('level')->join('-');
        $namaKelas = $kelas->nama_kelas ?? $kelas->mata_pelajaran;
        $filename = 'RekapAbsensi_' . str_replace(' ', '_', $namaKelas) . '_' . str_replace(' ', '_', $guru) . '_' . str_replace(' ', '_', $jenjang) . '.pdf';
        $pdf = PDF::loadView('pdf.rekap_kumulatif', compact('kelas', 'rekap'));
        return $pdf->download($filename);
    }
}
