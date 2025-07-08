<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(auth()->user()->hasRole('admin'))
                    <h1 class="text-3xl font-bold mb-6 text-red-600">Admin Dashboard</h1>
                    @elseif(auth()->user()->hasRole('guru'))
                    <h1 class="text-3xl font-bold mb-6 text-blue-600">Guru Dashboard</h1>
                    @elseif(auth()->user()->hasRole('user'))
                    <!-- Tidak ada judul User Dashboard di sini -->
                    @else
                    <h1 class="text-3xl font-bold mb-6 text-gray-600">User Dashboard</h1>
                    @endif

                    @if(auth()->user()->hasRole('user'))
                    <div class="mb-8">
                        <span class="text-xl md:text-2xl font-semibold text-green-700">Selamat datang di Website Pondok Pesantren Nurul Ulum</span>
                        <p class="mt-2 text-gray-700 text-base md:text-lg">Pantau status tagihan dan absensi Anda setiap bulan dengan mudah dan nyaman.</p>
                    </div>
                    @endif

                    @if(auth()->user()->hasRole('admin'))
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg border mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Informasi Akun</h3>
                        <div class="flex items-center mb-2">
                            <span class="font-medium mr-2">Nama:</span>
                            <span>{{ auth()->user()->name ?? '-' }}</span>
                        </div>
                        <div class="flex items-center mb-2">
                            <span class="font-medium mr-2">Email:</span>
                            <span>{{ auth()->user()->email }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-medium mr-2">Role:</span>
                            <span class="px-3 py-1 text-xs text-white bg-red-500 rounded-full">Admin</span>
                        </div>
                    </div>
                    @elseif(auth()->user()->hasRole('user'))
                    <!-- Informasi Akun UI Baru untuk User -->
                    <div class="bg-gradient-to-br from-green-400 to-blue-500 p-6 rounded-xl shadow-lg mb-8">
                        <div class="flex items-center mb-4">
                            <svg class="w-10 h-10 text-white mr-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <h3 class="text-2xl font-bold text-white mb-1">Informasi Akun</h3>
                                <span class="px-3 py-1 text-xs text-white bg-green-600 rounded-full">User</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="bg-white rounded-lg p-4 shadow flex flex-col">
                                <span class="font-medium text-gray-700 mb-1">Nama:</span>
                                <span class="text-gray-900">{{ auth()->user()->nama_santri }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow flex flex-col">
                                <span class="font-medium text-gray-700 mb-1">NIS:</span>
                                <span class="text-gray-900">{{ auth()->user()->nis }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow flex flex-col">
                                <span class="font-medium text-gray-700 mb-1">Email:</span>
                                <span class="text-gray-900">{{ auth()->user()->email }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow flex flex-col">
                                <span class="font-medium text-gray-700 mb-1">Tempat, Tanggal Lahir:</span>
                                <span class="text-gray-900">{{ auth()->user()->tempat_lahir }}, {{ auth()->user()->tanggal_lahir ? auth()->user()->tanggal_lahir->format('d M Y') : '-' }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow flex flex-col">
                                <span class="font-medium text-gray-700 mb-1">No. HP:</span>
                                <span class="text-gray-900">{{ auth()->user()->no_hp }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Role-specific dashboard content -->
                    @if(auth()->user()->hasRole('admin'))
                    <livewire:admin-dashboard />
                    @elseif(auth()->user()->hasRole('guru'))
                    <!-- Informasi Akun UI Baru untuk Guru -->
                    <div class="bg-gradient-to-br from-blue-400 to-green-400 p-6 rounded-xl shadow-lg mb-8">
                        <div class="flex items-center mb-4">
                            <svg class="w-10 h-10 text-white mr-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <h3 class="text-2xl font-bold text-white mb-1">Informasi Akun</h3>
                                <span class="px-3 py-1 text-xs text-white bg-blue-600 rounded-full">Guru</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="bg-white rounded-lg p-4 shadow flex flex-col">
                                <span class="font-medium text-gray-700 mb-1">Nama:</span>
                                <span class="text-gray-900">{{ auth()->user()->nama_pendidik ?? auth()->user()->name }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow flex flex-col">
                                <span class="font-medium text-gray-700 mb-1">NIK:</span>
                                <span class="text-gray-900">{{ auth()->user()->nik ?? '-' }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow flex flex-col">
                                <span class="font-medium text-gray-700 mb-1">Email:</span>
                                <span class="text-gray-900">{{ auth()->user()->email }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow flex flex-col">
                                <span class="font-medium text-gray-700 mb-1">Tempat, Tanggal Lahir:</span>
                                <span class="text-gray-900">{{ auth()->user()->tempat_lahir ?? '-' }}, {{ auth()->user()->tanggal_lahir ? (is_string(auth()->user()->tanggal_lahir) ? \Carbon\Carbon::parse(auth()->user()->tanggal_lahir)->format('d M Y') : auth()->user()->tanggal_lahir->format('d M Y')) : '-' }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow flex flex-col">
                                <span class="font-medium text-gray-700 mb-1">No. HP:</span>
                                <span class="text-gray-900">{{ auth()->user()->no_telepon ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- List Kelas yang Harus Diabsen -->
                    <div class="bg-white p-4 rounded-lg shadow mb-8">
                        <h3 class="font-bold text-lg mb-4 text-blue-700">Daftar Kelas yang Harus Diabsen</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
                                <thead class="bg-blue-100">
                                    <tr>
                                        <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Nama Kelas</th>
                                        <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Hari</th>
                                        <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Jumlah Murid</th>
                                        <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $hariIni = [
                                    'Sunday' => 'Minggu',
                                    'Monday' => 'Senin',
                                    'Tuesday' => 'Selasa',
                                    'Wednesday' => 'Rabu',
                                    'Thursday' => 'Kamis',
                                    'Friday' => 'Jumat',
                                    'Saturday' => 'Sabtu',
                                    ];
                                    $jadwalHari = $hariIni[date('l')];
                                    $kelasList = \App\Models\Kelas::where('guru_id', auth()->user()->id)
                                    ->where('jadwal_hari', $jadwalHari)
                                    ->get();
                                    @endphp
                                    @forelse($kelasList as $kelas)
                                    @php
                                    $sudahAbsen = \App\Models\Absensi::where('kelas_id', $kelas->id)
                                    ->where('guru_id', auth()->user()->id)
                                    ->whereDate('tanggal', date('Y-m-d'))
                                    ->exists();
                                    @endphp
                                    <tr class="border-b">
                                        <td class="py-2 px-4">{{ $kelas->nama_kelas ?? $kelas->mata_pelajaran }} - {{ optional($kelas->classLevel)->level ?? '-' }} </td>
                                        <td class="py-2 px-4">{{ $kelas->jadwal_hari ?? '-' }}</td>
                                        <td class="py-2 px-4">{{ $kelas->users()->count() }}</td>
                                        <td class="py-2 px-4">
                                            @if($sudahAbsen)
                                            <button class="px-3 py-1 bg-gray-400 text-white rounded text-xs cursor-not-allowed" disabled>Sudah Absen</button>
                                            @else
                                            <a href="{{ route('absensi.index', ['kelas_id' => $kelas->id]) }}" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-xs">Absen Kelas</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="py-2 px-4 text-center text-gray-500">Tidak ada kelas yang harus diabsen.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Rekapitulasi Absensi Kelas Hari Ini -->
                    <div class="bg-white p-6 rounded-lg shadow mb-8 border border-green-200">
                        <h3 class="font-bold text-lg mb-4 text-green-700 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 018 0v2M12 7a4 4 0 100 8 4 4 0 000-8zm0 0V3m0 8v8m8-8a8 8 0 11-16 0 8 8 0 0116 0z" />
                            </svg>
                            Rekapitulasi Absensi Kelas Hari Ini
                        </h3>
                        @php
                        $tanggal = date('Y-m-d');
                        $kelasIdList = \App\Models\Kelas::where('guru_id', auth()->user()->id)->pluck('id');
                        $kelasMap = \App\Models\Kelas::whereIn('id', $kelasIdList)->get()->keyBy('id');
                        $absensiHariIni = \App\Models\Absensi::whereIn('kelas_id', $kelasIdList)
                        ->whereDate('tanggal', $tanggal)
                        ->get();
                        $rekap = [];
                        foreach ($absensiHariIni as $absen) {
                        $kid = $absen->kelas_id;
                        if (!isset($rekap[$kid])) {
                        $rekap[$kid] = [
                        'kelas' => $kelasMap[$kid] ?? null,
                        'hadir' => 0,
                        'izin' => 0,
                        'sakit' => 0,
                        'alpha' => 0,
                        ];
                        }
                        $rekap[$kid][$absen->status]++;
                        }
                        @endphp
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
                                <thead class="bg-green-100">
                                    <tr>
                                        <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                                        <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Nama Kelas</th>
                                        <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Jumlah Murid</th>
                                        <th class="py-2 px-4 text-center text-sm font-semibold text-gray-700">Hadir</th>
                                        <th class="py-2 px-4 text-center text-sm font-semibold text-gray-700">Izin</th>
                                        <th class="py-2 px-4 text-center text-sm font-semibold text-gray-700">Sakit</th>
                                        <th class="py-2 px-4 text-center text-sm font-semibold text-gray-700">Alpha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rekap as $row)
                                    @php
                                    $kelasObj = $row['kelas'];
                                    $namaKelas = $kelasObj ? ($kelasObj->nama_kelas ?? $kelasObj->mata_pelajaran) : '-';
                                    $level = $kelasObj && isset($kelasObj->classLevel) ? optional($kelasObj->classLevel)->level : '-';
                                    $jumlahSantri = $kelasObj ? $kelasObj->users()->count() : '-';
                                    @endphp
                                    <tr class="border-b hover:bg-green-50 transition-all">
                                        <td class="py-2 px-4">{{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y') }}</td>
                                        <td class="py-2 px-4">{{ $namaKelas }} - {{ $level }}</td>
                                        <td class="py-2 px-4">{{ $jumlahSantri }}</td>
                                        <td class="py-2 px-4 text-center font-semibold text-green-700">{{ $row['hadir'] }}</td>
                                        <td class="py-2 px-4 text-center font-semibold text-yellow-700">{{ $row['izin'] }}</td>
                                        <td class="py-2 px-4 text-center font-semibold text-blue-700">{{ $row['sakit'] }}</td>
                                        <td class="py-2 px-4 text-center font-semibold text-red-700">{{ $row['alpha'] }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="py-2 px-4 text-center text-gray-500">Belum ada absensi hari ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2 text-xs text-gray-500">* Rekap absensi kelas ini untuk tanggal {{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y') }}.</div>
                    </div>

                    <!-- History Rekapitulasi Absensi Kelas (Semua Hari) -->
                    <div class="bg-white p-6 rounded-lg shadow border border-blue-200">
                        <h3 class="font-bold text-lg mb-4 text-blue-700 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 17l4 4 4-4m-4-5v9" />
                            </svg>
                            History Rekapitulasi Absensi Kelas
                        </h3>
                        @php
                        $kelasIdList = \App\Models\Kelas::where('guru_id', auth()->user()->id)->pluck('id');
                        $kelasMap = \App\Models\Kelas::whereIn('id', $kelasIdList)->get()->keyBy('id');
                        $absensiHistory = \App\Models\Absensi::whereIn('kelas_id', $kelasIdList)
                        ->orderBy('tanggal', 'desc')
                        ->get()
                        ->groupBy(function($item) { return $item->tanggal . '-' . $item->kelas_id; });
                        @endphp
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-lg shadow overflow-hidden mb-4">
                                <thead class="bg-blue-100">
                                    <tr>
                                        <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                                        <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Nama Kelas</th>
                                        <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Jumlah Murid</th>
                                        <th class="py-2 px-4 text-center text-sm font-semibold text-gray-700">Hadir</th>
                                        <th class="py-2 px-4 text-center text-sm font-semibold text-gray-700">Izin</th>
                                        <th class="py-2 px-4 text-center text-sm font-semibold text-gray-700">Sakit</th>
                                        <th class="py-2 px-4 text-center text-sm font-semibold text-gray-700">Alpha</th>
                                        <th class="py-2 px-4 text-center text-sm font-semibold text-gray-700">Detail Siswa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($absensiHistory as $key => $absensiGroup)
                                    @php
                                    $first = $absensiGroup->first();
                                    $kelasObj = $kelasMap[$first->kelas_id] ?? null;
                                    $namaKelas = $kelasObj ? ($kelasObj->nama_kelas ?? $kelasObj->mata_pelajaran) : '-';
                                    $level = $kelasObj && isset($kelasObj->classLevel) ? optional($kelasObj->classLevel)->level : '-';
                                    $jumlahSantri = $kelasObj ? $kelasObj->users()->count() : '-';
                                    $tanggal = $first->tanggal;
                                    $hadir = $absensiGroup->where('status', 'hadir')->count();
                                    $izin = $absensiGroup->where('status', 'izin')->count();
                                    $sakit = $absensiGroup->where('status', 'sakit')->count();
                                    $alpha = $absensiGroup->where('status', 'alpha')->count();
                                    @endphp
                                    <tr class="border-b hover:bg-blue-50 transition-all">
                                        <td class="py-2 px-4">{{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y') }}</td>
                                        <td class="py-2 px-4">{{ $namaKelas }} - {{ $level }}</td>
                                        <td class="py-2 px-4">{{ $jumlahSantri }}</td>
                                        <td class="py-2 px-4 text-center font-semibold text-green-700">{{ $hadir }}</td>
                                        <td class="py-2 px-4 text-center font-semibold text-yellow-700">{{ $izin }}</td>
                                        <td class="py-2 px-4 text-center font-semibold text-blue-700">{{ $sakit }}</td>
                                        <td class="py-2 px-4 text-center font-semibold text-red-700">{{ $alpha }}</td>
                                        <td class="py-2 px-4 text-center">
                                            <button type="button" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs" onclick="document.getElementById('detail-{{ $key }}').classList.toggle('hidden')">Lihat Siswa</button>
                                        </td>
                                    </tr>
                                    <tr id="detail-{{ $key }}" class="hidden">
                                        <td colspan="8" class="bg-blue-50 px-4 py-2">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full text-xs">
                                                    <thead>
                                                        <tr>
                                                            <th class="py-1 px-2 text-left">Nama Siswa</th>
                                                            <th class="py-1 px-2 text-left">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($absensiGroup as $absen)
                                                        <tr>
                                                            <td class="py-1 px-2">{{ optional($absen->user)->nama_santri ?? optional($absen->user)->name ?? '-' }}</td>
                                                            <td class="py-1 px-2">
                                                                <span class="px-2 py-1 text-xs rounded-full
                                                                                                @if($absen->status == 'hadir') bg-green-100 text-green-700
                                                                                                @elseif($absen->status == 'izin') bg-yellow-100 text-yellow-700
                                                                                                @elseif($absen->status == 'sakit') bg-blue-100 text-blue-700
                                                                                                @else bg-red-100 text-red-700 @endif">
                                                                    {{ ucfirst($absen->status) }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="py-2 px-4 text-center text-gray-500">Belum ada history rekap absensi.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2 text-xs text-gray-500">* History rekap absensi kelas, klik &quot;Lihat Siswa&quot; untuk detail siswa dan statusnya.</div>
                    </div>

                    @elseif(auth()->user()->hasRole('user'))
                    <div class="mt-6 grid grid-cols-1 gap-6">
                        <!-- Tagihan Bulanan UI -->
                        <div class="bg-gradient-to-br from-green-400 to-blue-500 p-6 rounded-xl shadow-lg mb-8">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 8v8m8-8a8 8 0 11-16 0 8 8 0 0116 0z" />
                                </svg>
                                <h3 class="text-2xl font-bold text-white">Tagihan Bulanan</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
                                    <thead class="bg-blue-100">
                                        <tr>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Bulan</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Deskripsi</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Jumlah</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Status</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $user = \App\Models\User::find(auth()->id());
                                        $pembayaranList = \App\Models\Pembayaran::where('user_id', $user->id)->orderBy('periode_pembayaran')->get();
                                        $bulanList = [
                                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                        ];
                                        @endphp
                                        @foreach($pembayaranList as $pembayaran)
                                            @php
                                                $bulan = (int)date('n', strtotime($pembayaran->periode_pembayaran));
                                                $tahun = date('Y', strtotime($pembayaran->periode_pembayaran));
                                                $isLunas = ($pembayaran->status === 'lunas' || $pembayaran->status === 'PAID') && $pembayaran->detailPembayaran->sum('jumlah_dibayar') >= $pembayaran->total_tagihan;
                                                $sisaTagihan = $pembayaran->total_tagihan - $pembayaran->detailPembayaran->sum('jumlah_dibayar');
                                            @endphp
                                            <tr class="border-b">
                                                <td class="py-2 px-4">{{ $bulanList[$bulan] }} {{ $tahun }}</td>
                                                <td class="py-2 px-4">{{ $pembayaran->deskripsi ?? '-' }}</td>
                                                <td class="py-2 px-4">Rp {{ number_format($pembayaran->total_tagihan,0,',','.') }}</td>
                                                <td class="py-2 px-4">
                                                    @if($isLunas)
                                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-600 rounded-full">Lunas</span>
                                                    @else
                                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded-full">Belum Lunas</span>
                                                    @endif
                                                </td>
                                                <td class="py-2 px-4">
                                                    @if($isLunas)
                                                        <span class="px-3 py-1 bg-gray-300 text-gray-600 rounded text-xs cursor-not-allowed">Sudah Dibayar</span>
                                                        <span class="ml-2 text-xs text-green-700">Sisa: Rp 0</span>
                                                    @else
                                                        <a href="{{ route('tagihan.bayar', ['id' => $pembayaran->id, 'periode' => $pembayaran->periode_pembayaran]) }}" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-xs">Bayar Sekarang</a>
                                                        <span class="ml-2 text-xs text-red-700">Sisa: Rp {{ number_format($sisaTagihan,0,',','.') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 text-sm text-white opacity-80">* Silakan lakukan pembayaran sebelum tanggal 10 setiap bulan.</div>
                        </div>

                        <!-- Rekap Absensi UI -->
                        <div class="bg-gradient-to-br from-blue-400 to-green-400 p-6 rounded-xl shadow-lg">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 018 0v2M12 7a4 4 0 100 8 4 4 0 000-8zm0 0V3m0 8v8m8-8a8 8 0 11-16 0 8 8 0 0116 0z" />
                                </svg>
                                <h3 class="text-2xl font-bold text-white">Rekap Absensi Bulanan</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
                                    <thead class="bg-green-100">
                                        <tr>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Bulan</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Hadir</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Izin</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Sakit</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Alpha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Contoh data statis, ganti dengan data dinamis jika sudah ada -->
                                        <tr class="border-b">
                                            <td class="py-2 px-4">Juli 2025</td>
                                            <td class="py-2 px-4"><span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">24</span></td>
                                            <td class="py-2 px-4"><span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">2</span></td>
                                            <td class="py-2 px-4"><span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">1</span></td>
                                            <td class="py-2 px-4"><span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">0</span></td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4">Juni 2025</td>
                                            <td class="py-2 px-4"><span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">22</span></td>
                                            <td class="py-2 px-4"><span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">1</span></td>
                                            <td class="py-2 px-4"><span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">0</span></td>
                                            <td class="py-2 px-4"><span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">2</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 text-sm text-white opacity-80">* Data rekap diambil dari absensi harian Anda setiap bulan.</div>
                        </div>

                        <!-- Rekapitulasi Tagihan 12 Bulan Terakhir -->
                        <div class="bg-white p-6 rounded-lg shadow mb-8 border border-blue-200">
                            <h3 class="font-bold text-lg mb-4 text-blue-700 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
                                </svg>
                                Rekapitulasi Tagihan 12 Bulan Terakhir
                            </h3>
                            @php
                            $userId = auth()->user()->id;
                            $bulanSekarang = \Carbon\Carbon::now();
                            $rekapTagihan = [];
                            for ($i = 11; $i >= 0; $i--) {
                                $bulan = $bulanSekarang->copy()->subMonths($i);
                                $periode = $bulan->format('Y-m-01');
                                $tagihan = \App\Models\Pembayaran::where('user_id', $userId)
                                    ->where('periode_pembayaran', $periode)
                                    ->first();
                                $rekapTagihan[] = [
                                    'bulan' => $bulan->isoFormat('MMMM YYYY'),
                                    'jumlah' => $tagihan ? $tagihan->total_tagihan : 0,
                                    'status' => $tagihan ? $tagihan->status : 'tidak_ada',
                                    'deskripsi' => $tagihan ? $tagihan->deskripsi : '-',
                                    'sudah_dibayar' => $tagihan ? $tagihan->detailPembayaran->sum('jumlah_dibayar') : 0,
                                    'sisa' => $tagihan ? max(0, $tagihan->total_tagihan - $tagihan->detailPembayaran->sum('jumlah_dibayar')) : 0,
                                ];
                            }
                            @endphp
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
                                    <thead class="bg-blue-100">
                                        <tr>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Bulan</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Jumlah Tagihan</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rekapTagihan as $row)
                                        <tr class="border-b hover:bg-blue-50 transition-all">
                                            <td class="py-2 px-4">{{ $row['bulan'] }}</td>
                                            <td class="py-2 px-4">Rp {{ number_format($row['jumlah'], 0, ',', '.') }}</td>
                                            <td class="py-2 px-4">
                                                @if($row['status'] == 'lunas')
                                                    <span class="px-2 py-1 text-xs text-white bg-green-600 rounded">Lunas</span>
                                                @elseif($row['status'] == 'menunggu_pembayaran')
                                                    <span class="px-2 py-1 text-xs text-white bg-yellow-600 rounded">Menunggu Pembayaran</span>
                                                @elseif($row['status'] == 'belum_lunas')
                                                    <span class="px-2 py-1 text-xs text-white bg-yellow-500 rounded">Belum Lunas</span>
                                                    @if($row['sisa'] > 0)
                                                        <span class="ml-2 px-2 py-1 text-xs text-red-600 bg-red-100 rounded">Sisa: Rp {{ number_format($row['sisa'], 0, ',', '.') }}</span>
                                                    @endif
                                                @elseif($row['status'] == 'tidak_ada')
                                                    <span class="px-2 py-1 text-xs text-gray-600 bg-gray-200 rounded">Tidak Ada Tagihan</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs text-white bg-gray-400 rounded">{{ ucfirst($row['status']) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>

@if(auth()->user()->hasRole('user'))
<script>
    function refreshDashboardData() {
        fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                // Ganti tabel tagihan bulanan
                const newTable = doc.querySelector('table.min-w-full.bg-white.rounded-lg.shadow.overflow-hidden');
                const oldTable = document.querySelector('table.min-w-full.bg-white.rounded-lg.shadow.overflow-hidden');
                if (newTable && oldTable) {
                    oldTable.innerHTML = newTable.innerHTML;
                }
                // Ganti rekap tagihan 12 bulan terakhir
                const newRekap = doc.querySelectorAll('div.bg-white.p-6.rounded-lg.shadow.mb-8.border.border-blue-200')[1];
                const oldRekap = document.querySelectorAll('div.bg-white.p-6.rounded-lg.shadow.mb-8.border.border-blue-200')[1];
                if (newRekap && oldRekap) {
                    oldRekap.innerHTML = newRekap.innerHTML;
                }
            });
    }
    setInterval(refreshDashboardData, 10000); // 10 detik
</script>
@endif