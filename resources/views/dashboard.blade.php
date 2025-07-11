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
                            <div class="bg-white rounded-lg p-4 shadow flex flex-col">
                                <span class="font-medium text-gray-700 mb-1">Status Beasiswa:</span>
                                <span class="text-gray-900">{{ auth()->user()->is_beasiswa ? 'Penerimas Beasiswa' : 'Bukan Psenerima Beasiswa' }}</span>
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
                        $status = $absen->status ?? null;
                        if (!isset($rekap[$kid])) {
                        $rekap[$kid] = [
                        'kelas' => $kelasMap[$kid] ?? null,
                        'hadir' => 0,
                        'izin' => 0,
                        'sakit' => 0,
                        'alpha' => 0,
                        ];
                        }
                        if (in_array($status, ['hadir','izin','sakit','alpha'])) {
                        $rekap[$kid][$status]++;
                        }
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
                        <!-- Rekapitulasi Kehadiran Santri per Kelas (Kumulatif, Expand per Kelas) -->
                        <div class="mb-8">
                            <h4 class="font-bold text-base mb-2 text-indigo-700 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Rekap Kehadiran Santri per Kelas (Kumulatif)
                            </h4>
                            @php
                            $kelasListAll = \App\Models\Kelas::where('guru_id', auth()->user()->id)->get();
                            @endphp
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white rounded-lg shadow overflow-hidden mb-4">
                                    <thead class="bg-indigo-100">
                                        <tr>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Nama Kelas</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Level</th>
                                            <th class="py-2 px-4 text-center text-sm font-semibold text-gray-700">Jumlah Pertemuan</th>
                                            <th class="py-2 px-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kelasListAll as $kelas)
                                        <tr class="border-b hover:bg-indigo-50 transition-all">
                                            <td class="py-2 px-4">{{ $kelas->nama_kelas ?? $kelas->mata_pelajaran }}</td>
                                            <td class="py-2 px-4">{{ optional($kelas->classLevel)->level ?? '-' }}</td>
                                            <td class="py-2 px-4 text-center font-semibold text-indigo-700">
                                                @php
                                                // Hitung jumlah pertemuan (jumlah tanggal unik absensi kelas ini)
                                                $jumlahPertemuan = \App\Models\Absensi::where('kelas_id', $kelas->id)->distinct('tanggal')->count('tanggal');
                                                @endphp
                                                {{ $jumlahPertemuan }}
                                </td>
                                            <td class="py-2 px-4 text-center">
                                                <button type="button" class="px-3 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600 text-xs" onclick="document.getElementById('rekap-santri-{{ $kelas->id }}').classList.toggle('hidden')">Lihat Absensi</button>
                                            </td>
                                        </tr>
                                        <tr id="rekap-santri-{{ $kelas->id }}" class="hidden">
                                            <td colspan="3" class="bg-indigo-50 px-4 py-2">
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full text-xs">
                                                        <thead>
                                                            <tr>
                                                                <th class="py-1 px-2 text-left">Nama Siswa</th>
                                                                <th class="py-1 px-2 text-center">Hadir</th>
                                                                <th class="py-1 px-2 text-center">Izin</th>
                                                                <th class="py-1 px-2 text-center">Sakit</th>
                                                                <th class="py-1 px-2 text-center">Alpha</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $users = $kelas->users; @endphp
                                                            @forelse($users as $user)
                                                            @php
                                                            $hadir = \App\Models\Absensi::where('kelas_id', $kelas->id)
                                                            ->where('user_id', $user->id)
                                                            ->where('status', 'hadir')
                                                            ->count();
                                                            $izin = \App\Models\Absensi::where('kelas_id', $kelas->id)
                                                            ->where('user_id', $user->id)
                                                            ->where('status', 'izin')
                                                            ->count();
                                                            $sakit = \App\Models\Absensi::where('kelas_id', $kelas->id)
                                                            ->where('user_id', $user->id)
                                                            ->where('status', 'sakit')
                                                            ->count();
                                                            $alpha = \App\Models\Absensi::where('kelas_id', $kelas->id)
                                                            ->where('user_id', $user->id)
                                                            ->where('status', 'alpha')
                                                            ->count();
                                                            @endphp
                                                            <tr>
                                                                <td class="py-1 px-2">{{ $user->nama_santri ?? $user->name }}</td>
                                                                <td class="py-1 px-2 text-center font-semibold text-green-700">{{ $hadir }}</td>
                                                                <td class="py-1 px-2 text-center font-semibold text-yellow-700">{{ $izin }}</td>
                                                                <td class="py-1 px-2 text-center font-semibold text-blue-700">{{ $sakit }}</td>
                                                                <td class="py-1 px-2 text-center font-semibold text-red-700">{{ $alpha }}</td>
                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="5" class="py-1 px-2 text-center text-gray-500">Belum ada santri di kelas ini.</td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="py-2 px-4 text-center text-gray-500">Belum ada kelas yang Anda ampu.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2 text-xs text-gray-500">* Klik "Lihat Absensi" untuk melihat rekap kumulatif santri per kelas.</div>
                        </div>
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

                    {{-- Ganti seluruh blok @elseif(auth()->user()->hasRole('user')) dengan ini --}}

                    @elseif(auth()->user()->hasRole('user'))

                    <div class="mt-6 grid grid-cols-1 gap-6">
                        <div class="bg-gradient-to-br from-green-400 to-blue-500 p-6 rounded-xl shadow-lg">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V9a2 2 0 00-2-2h-3.382l-1.9-3.8A1 1 0 0012 2H8.382a1 1 0 00-.9.5L5.5 5H4a2 2 0 00-2 2v9a2 2 0 002 2h12z" />
                                </svg>
                                <h3 class="text-2xl font-bold text-white">Tagihan Bulanan Anda</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
                                    <thead class="bg-blue-100">
                                        <tr>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Periode Tagihan</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Jumlah</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Status</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Bukti Pembayaran</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $user = auth()->user();
                                        $sppBulanan = $user->classLevel->spp ?? 0;

                                        if ($user->is_beasiswa && isset($user->classLevel->spp_beasiswa)) {
                                            $sppBulanan = $user->classLevel->spp_beasiswa;
                                        }

                                        $mulai = \Carbon\Carbon::parse($user->created_at)->startOfMonth();
                                        $selesai = \Carbon\Carbon::now()->startOfMonth();
                                        $periodeTagihan = \Carbon\CarbonPeriod::create($mulai, '1 month', $selesai);

                                        $pembayaranTerdahulu = \App\Models\Pembayaran::with('detailPembayaran')
                                            ->where('user_id', $user->id)
                                            ->get()
                                            ->keyBy(function ($item) {
                                                return $item->periode_tahun . '-' . $item->periode_bulan;
                                            });
                                    @endphp

                                    @forelse ($periodeTagihan as $bulan)
                                        @php
                                            $key = $bulan->year . '-' . $bulan->month;
                                            $pembayaran = $pembayaranTerdahulu->get($key);
                                        @endphp
                                        <tr class="border-b">
                                            <td class="py-3 px-4">{{ $bulan->isoFormat('MMMM YYYY') }}</td>
                                            <td class="py-3 px-4">Rp {{ number_format($sppBulanan, 0, ',', '.') }}</td>
                                            <td class="py-3 px-4">
                                                @if ($pembayaran && strtolower($pembayaran->status) == 'lunas')
                                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full font-semibold">Lunas</span>
                                                @elseif ($pembayaran && strtolower($pembayaran->status) == 'pending')
                                                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full font-semibold">Pending</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded-full font-semibold">Belum Lunas</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                @if ($pembayaran && strtolower($pembayaran->status) == 'lunas')
                                                    <a href="{{ route('invoice.download', $pembayaran->id) }}" target="_blank"
                                                        class="px-3 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600 text-xs">
                                                        Download Invoice
                                                    </a>
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                @if ($pembayaran && strtolower($pembayaran->status) == 'lunas')
                                                    <span class="px-3 py-1 bg-gray-300 text-gray-600 rounded text-xs cursor-not-allowed">Lunas</span>
                                                @elseif ($pembayaran)
                                                    <a href="{{ route('pembayaran.pay_midtrans', $pembayaran->id) }}"
                                                        class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-xs">
                                                        Bayar Online
                                                    </a>
                                                @else
                                                    <a href="{{ route('pembayaran.create_and_pay', ['year' => $bulan->year, 'month' => $bulan->month]) }}"
                                                        class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs">
                                                        Buat & Bayar
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-3 px-4 text-center text-gray-500">Belum ada data tagihan.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 text-sm text-white opacity-80">* Mohon melakukan pembayaran sebelum tanggal 20.</div>
                        </div>

                         <!-- Rekap Absensi UI -->
                         <div class="bg-gradient-to-br from-blue-400 to-green-400 p-6 rounded-xl shadow-lg">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 018 0v2M12 7a4 4 0 100 8 4 4 0 000-8zm0 0V3m0 8v8m8-8a8 8 0 11-16 0 8 8 0 0116 0z" />
                                </svg>
                                <h3 class="text-2xl font-bold text-white">Rekap Absensi Bulanan</h3>
                            </div>
                            @php
                            $absensiData = \App\Models\Absensi::with('kelas')
                                ->selectRaw('kelas_id, COUNT(CASE WHEN user_id = ? AND status = "hadir" THEN 1 END) as hadir_count, COUNT(CASE WHEN user_id = ? AND status = "izin" THEN 1 END) as izin_count, COUNT(CASE WHEN user_id = ? AND status = "sakit" THEN 1 END) as sakit_count, COUNT(CASE WHEN user_id = ? AND status = "alpha" THEN 1 END) as alpha_count', [auth()->id(), auth()->id(), auth()->id(), auth()->id()])
                                ->groupBy('kelas_id')
                                ->get();
                            @endphp
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
                                    <thead class="bg-green-100">
                                        <tr>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Mata Pelajaran</th>
                                            <th class="py-2 px-4 text-center text-sm font-semibold text-gray-700">Hadir</th>
                                            <th class="py-2 px-4 text-center text-sm font-semibold text-gray-700">Izin</th>
                                            <th class="py-2 px-4 text-center text-sm font-semibold text-gray-700">Sakit</th>
                                            <th class="py-2 px-4 text-center text-sm font-semibold text-gray-700">Alpha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($absensiData as $data)
                                        <tr class="border-b">
                                            <td class="py-2 px-4">{{ optional($data->kelas)->mata_pelajaran ?? 'Tidak Diketahui' }}</td>
                                            <td class="py-2 px-4 text-center font-semibold text-green-700">{{ $data->hadir_count }}</td>
                                            <td class="py-2 px-4 text-center font-semibold text-yellow-700">{{ $data->izin_count }}</td>
                                            <td class="py-2 px-4 text-center font-semibold text-blue-700">{{ $data->sakit_count }}</td>
                                            <td class="py-2 px-4 text-center font-semibold text-red-700">{{ $data->alpha_count }}</td>
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