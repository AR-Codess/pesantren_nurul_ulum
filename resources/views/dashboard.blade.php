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
                        <h1 class="text-3xl font-bold mb-6 text-green-600">User Dashboard</h1>
                    @else
                        <h1 class="text-3xl font-bold mb-6 text-gray-600">User Dashboard</h1>
                    @endif
                    
                    <p class="mb-4">{{ __("Selamat datang di dashboard Pondok Pesantren Nurul Ulum") }}</p>
                    
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Informasi Akun</h3>
                        <div class="flex items-center mb-2">
                            <span class="font-medium mr-2">Nama:</span> 
                            <span>{{ auth()->user()->name }}</span>
                        </div>
                        <div class="flex items-center mb-2">
                            <span class="font-medium mr-2">Email:</span> 
                            <span>{{ auth()->user()->email }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-medium mr-2">Role:</span> 
                            @if(auth()->user()->hasRole('admin'))
                                <span class="px-3 py-1 text-xs text-white bg-red-500 rounded-full">Admin</span>
                            @elseif(auth()->user()->hasRole('guru'))
                                <span class="px-3 py-1 text-xs text-white bg-blue-500 rounded-full">Guru</span>
                            @elseif(auth()->user()->hasRole('user'))
                                <span class="px-3 py-1 text-xs text-white bg-green-500 rounded-full">User</span>
                            @else
                                <span class="px-3 py-1 text-xs text-white bg-gray-500 rounded-full">User</span>
                            @endif
                        </div>
                    </div>

                    <!-- Role-specific dashboard content -->
                    @if(auth()->user()->hasRole('admin'))
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white p-4 border rounded-lg shadow-sm">
                                <h3 class="font-bold text-lg mb-2">Kelola Pengguna</h3>
                                <p class="text-gray-600 mb-4">Tambah, edit, dan hapus data pengguna.</p>
                                <a href="{{ route('users.index') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Data Pengguna</a>
                            </div>
                            
                            <div class="bg-white p-4 border rounded-lg shadow-sm">
                                <h3 class="font-bold text-lg mb-2">Kelola Guru</h3>
                                <p class="text-gray-600 mb-4">Tambah, edit, dan hapus data guru.</p>
                                <a href="{{ route('guru.index') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Data Guru</a>
                            </div>
                            
                            <div class="bg-white p-4 border rounded-lg shadow-sm">
                                <h3 class="font-bold text-lg mb-2">Konfirmasi Pembayaran</h3>
                                <p class="text-gray-600 mb-4">Lihat dan konfirmasi pembayaran pengguna.</p>
                                <a href="{{ route('pembayaran.index') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Data Pembayaran</a>
                            </div>
                        </div>
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
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white p-4 border rounded-lg shadow-sm">
                                <h3 class="font-bold text-lg mb-2">Pembayaran</h3>
                                <p class="text-gray-600 mb-4">Lakukan pembayaran bulanan.</p>
                                <a href="{{ route('pembayaran.user') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Riwayat Pembayaran</a>
                            </div>
                            
                            <div class="bg-white p-4 border rounded-lg shadow-sm">
                                <h3 class="font-bold text-lg mb-2">Absensi</h3>
                                <p class="text-gray-600 mb-4">Lihat riwayat kehadiran.</p>
                                <a href="{{ route('absensi.check') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Cek Absensi</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>
