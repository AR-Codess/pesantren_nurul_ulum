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
                            <svg class="w-10 h-10 text-white mr-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
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
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white p-4 border rounded-lg shadow-sm">
                            <h3 class="font-bold text-lg mb-2">Kelola Absensi</h3>
                            <p class="text-gray-600 mb-4">Catat kehadiran pengguna.</p>
                            <a href="{{ route('absensi.index') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Data Absensi</a>
                        </div>

                        <div class="bg-white p-4 border rounded-lg shadow-sm">
                            <h3 class="font-bold text-lg mb-2">Buat Absensi Baru</h3>
                            <p class="text-gray-600 mb-4">Tambah data absensi pengguna hari ini.</p>
                            <a href="{{ route('absensi.create') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Buat Absensi Baru</a>
                        </div>
                    </div>
                    @elseif(auth()->user()->hasRole('user'))
                    <div class="mt-6 grid grid-cols-1 gap-6">
                        <!-- Tagihan Bulanan UI -->
                        <div class="bg-gradient-to-br from-green-400 to-blue-500 p-6 rounded-xl shadow-lg">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 8v8m8-8a8 8 0 11-16 0 8 8 0 0116 0z"/></svg>
                                <h3 class="text-2xl font-bold text-white">Tagihan Bulanan</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
                                    <thead class="bg-blue-100">
                                        <tr>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Bulan</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Jumlah</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Status</th>
                                            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Contoh data statis, ganti dengan data dinamis jika sudah ada -->
                                        <tr class="border-b">
                                            <td class="py-2 px-4">Juli 2025</td>
                                            <td class="py-2 px-4">Rp 150.000</td>
                                            <td class="py-2 px-4"><span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded-full">Belum Lunas</span></td>
                                            <td class="py-2 px-4">
                                                <a href="#" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-xs">Bayar Sekarang</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4">Juni 2025</td>
                                            <td class="py-2 px-4">Rp 150.000</td>
                                            <td class="py-2 px-4"><span class="px-2 py-1 text-xs bg-green-100 text-green-600 rounded-full">Lunas</span></td>
                                            <td class="py-2 px-4">
                                                <span class="px-3 py-1 bg-gray-300 text-gray-600 rounded text-xs cursor-not-allowed">Sudah Dibayar</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 text-sm text-white opacity-80">* Silakan lakukan pembayaran sebelum tanggal 10 setiap bulan.</div>
                        </div>

                        <!-- Rekap Absensi UI -->
                        <div class="bg-gradient-to-br from-blue-400 to-green-400 p-6 rounded-xl shadow-lg">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 018 0v2M12 7a4 4 0 100 8 4 4 0 000-8zm0 0V3m0 8v8m8-8a8 8 0 11-16 0 8 8 0 0116 0z"/></svg>
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
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>