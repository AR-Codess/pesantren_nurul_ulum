<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold mb-6">Dashboard Admin Pesantren Nurul Ulum</h1>
                    
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        <div class="bg-blue-100 p-4 rounded-lg shadow">
                            <h3 class="font-bold text-lg text-blue-800">Total Santri</h3>
                            <p class="text-3xl font-bold">{{ $totalUsers }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg shadow">
                            <h3 class="font-bold text-lg text-green-800">Total Guru</h3>
                            <p class="text-3xl font-bold">{{ $totalGurus }}</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-lg shadow">
                            <h3 class="font-bold text-lg text-yellow-800">Pembayaran Pending</h3>
                            <p class="text-3xl font-bold">{{ $pendingPayments }}</p>
                        </div>
                        <div class="bg-purple-100 p-4 rounded-lg shadow">
                            <h3 class="font-bold text-lg text-purple-800">Pembayaran Dikonfirmasi</h3>
                            <p class="text-3xl font-bold">{{ $confirmedPayments }}</p>
                        </div>
                    </div>

                    <!-- Tabs for User and Guru management -->
                    <div class="mb-4 border-b border-gray-200">
                        <ul class="flex flex-wrap -mb-px" id="myTab" role="tablist">
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 active" 
                                        id="users-tab" 
                                        data-tabs-target="#users" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="users" 
                                        aria-selected="true">
                                    Manajemen Santri
                                </button>
                            </li>
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" 
                                        id="guru-tab" 
                                        data-tabs-target="#guru" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="guru" 
                                        aria-selected="false">
                                    Manajemen Guru
                                </button>
                            </li>
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" 
                                        id="finance-tab" 
                                        data-tabs-target="#finance" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="finance" 
                                        aria-selected="false">
                                    Manajemen Keuangan
                                </button>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Tab content -->
                    <div id="myTabContent">
                        <div class="p-4 rounded-lg bg-gray-50" 
                             id="users" 
                             role="tabpanel" 
                             aria-labelledby="users-tab">
                            
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-bold">Daftar Santri</h2>
                                <a href="{{ route('users.index') }}" 
                                   class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                    Kelola Santri
                                </a>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">NIS</th>
                                            <th scope="col" class="px-6 py-3">Nama</th>
                                            <th scope="col" class="px-6 py-3">Email</th>
                                            <th scope="col" class="px-6 py-3">No. HP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(App\Models\User::role('user')->take(5)->get() as $user)
                                            <tr class="bg-white border-b hover:bg-gray-50">
                                                <td class="px-6 py-4">{{ $user->nis }}</td>
                                                <td class="px-6 py-4">{{ $user->name }}</td>
                                                <td class="px-6 py-4">{{ $user->email }}</td>
                                                <td class="px-6 py-4">{{ $user->no_hp ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="hidden p-4 rounded-lg bg-gray-50" 
                             id="guru" 
                             role="tabpanel" 
                             aria-labelledby="guru-tab">
                            
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-bold">Daftar Guru</h2>
                                <a href="{{ route('guru.index') }}" 
                                   class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                    Kelola Guru
                                </a>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">NIP</th>
                                            <th scope="col" class="px-6 py-3">Nama</th>
                                            <th scope="col" class="px-6 py-3">Email</th>
                                            <th scope="col" class="px-6 py-3">Bidang</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(App\Models\User::role('guru')->take(5)->get() as $guru)
                                            <tr class="bg-white border-b hover:bg-gray-50">
                                                <td class="px-6 py-4">{{ $guru->nis }}</td> {{-- Using NIS field for NIP --}}
                                                <td class="px-6 py-4">{{ $guru->name }}</td>
                                                <td class="px-6 py-4">{{ $guru->email }}</td>
                                                <td class="px-6 py-4">
                                                    {{ App\Models\Guru::where('user_id', $guru->id)->first()->bidang ?? '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="hidden p-4 rounded-lg bg-gray-50" 
                             id="finance" 
                             role="tabpanel" 
                             aria-labelledby="finance-tab">
                            
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-bold">Manajemen Keuangan</h2>
                                <a href="{{ route('admin.financial-report') }}" 
                                   class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                    Laporan Keuangan
                                </a>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="bg-white p-4 border rounded-lg shadow-sm">
                                    <h3 class="font-bold text-lg mb-2">Pembayaran Terbaru</h3>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm text-left text-gray-500">
                                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3">Nama</th>
                                                    <th scope="col" class="px-6 py-3">Bulan</th>
                                                    <th scope="col" class="px-6 py-3">Jumlah</th>
                                                    <th scope="col" class="px-6 py-3">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach(App\Models\Pembayaran::with('user')->latest()->take(5)->get() as $pembayaran)
                                                    <tr class="bg-white border-b hover:bg-gray-50">
                                                        <td class="px-6 py-4">{{ $pembayaran->user->name }}</td>
                                                        <td class="px-6 py-4">{{ $pembayaran->bulan }}</td>
                                                        <td class="px-6 py-4">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                                        <td class="px-6 py-4">
                                                            @if($pembayaran->status == 'confirmed')
                                                                <span class="px-2 py-1 text-xs text-white bg-green-500 rounded-full">Dikonfirmasi</span>
                                                            @elseif($pembayaran->status == 'pending')
                                                                <span class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-full">Pending</span>
                                                            @else
                                                                <span class="px-2 py-1 text-xs text-white bg-red-500 rounded-full">Ditolak</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4 text-right">
                                        <a href="{{ route('pembayaran.index') }}" class="text-blue-600 hover:underline">Lihat Semua Pembayaran →</a>
                                    </div>
                                </div>
                                
                                <div class="bg-white p-4 border rounded-lg shadow-sm">
                                    <h3 class="font-bold text-lg mb-2">Konfirmasi Pembayaran</h3>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm text-left text-gray-500">
                                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3">Nama</th>
                                                    <th scope="col" class="px-6 py-3">Bulan</th>
                                                    <th scope="col" class="px-6 py-3">Jumlah</th>
                                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach(App\Models\Pembayaran::with('user')->where('status', 'pending')->latest()->take(5)->get() as $pembayaran)
                                                    <tr class="bg-white border-b hover:bg-gray-50">
                                                        <td class="px-6 py-4">{{ $pembayaran->user->name }}</td>
                                                        <td class="px-6 py-4">{{ $pembayaran->bulan }}</td>
                                                        <td class="px-6 py-4">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                                        <td class="px-6 py-4">
                                                            <form action="{{ route('admin.update-payment-status', $pembayaran->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="confirmed">
                                                                <button type="submit" class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-700 mr-1">Konfirmasi</button>
                                                            </form>
                                                            <form action="{{ route('admin.update-payment-status', $pembayaran->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="rejected">
                                                                <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-700">Tolak</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for tab functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all tab buttons and content
            const tabs = document.querySelectorAll('[role="tab"]');
            const tabContents = document.querySelectorAll('[role="tabpanel"]');

            // Add click event to each tab
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Remove active class from all tabs
                    tabs.forEach(t => {
                        t.classList.remove('text-blue-600', 'border-blue-600');
                        t.classList.add('border-transparent');
                        t.setAttribute('aria-selected', 'false');
                    });

                    // Add active class to clicked tab
                    tab.classList.add('text-blue-600', 'border-blue-600');
                    tab.classList.remove('border-transparent');
                    tab.setAttribute('aria-selected', 'true');

                    // Hide all tab content
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Show the corresponding tab content
                    const targetId = tab.getAttribute('data-tabs-target').substring(1);
                    document.getElementById(targetId).classList.remove('hidden');
                });
            });
        });
    </script>
</x-app-layout>