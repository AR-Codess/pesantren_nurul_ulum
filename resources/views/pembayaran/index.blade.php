<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pembayaran') }}
        </h2>
    </x-slot>

    @if(auth()->user() && auth()->user()->hasRole('admin'))
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Daftar Pembayaran</h2>
                        <a href="{{ route('pembayaran.create') }}"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                            + Tambah Pembayaran
                        </a>
                    </div>

                    <!-- Search Form -->
                    <div class="mb-4">
                        <form id="searchForm" action="{{ route('pembayaran.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                            <div class="flex-grow">
                                <input type="text" id="searchInput" name="search" value="{{ request('search') }}" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                                        placeholder="Cari nama santri atau nis...">
                            </div>
                            <div class="w-40">
                                <select name="bulan" id="bulanFilter" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="">-- Pilih Bulan --</option>
                                    <option value="Januari" {{ request('bulan') == 'Januari' ? 'selected' : '' }}>Januari</option>
                                    <option value="Februari" {{ request('bulan') == 'Februari' ? 'selected' : '' }}>Februari</option>
                                    <option value="Maret" {{ request('bulan') == 'Maret' ? 'selected' : '' }}>Maret</option>
                                    <option value="April" {{ request('bulan') == 'April' ? 'selected' : '' }}>April</option>
                                    <option value="Mei" {{ request('bulan') == 'Mei' ? 'selected' : '' }}>Mei</option>
                                    <option value="Juni" {{ request('bulan') == 'Juni' ? 'selected' : '' }}>Juni</option>
                                    <option value="Juli" {{ request('bulan') == 'Juli' ? 'selected' : '' }}>Juli</option>
                                    <option value="Agustus" {{ request('bulan') == 'Agustus' ? 'selected' : '' }}>Agustus</option>
                                    <option value="September" {{ request('bulan') == 'September' ? 'selected' : '' }}>September</option>
                                    <option value="Oktober" {{ request('bulan') == 'Oktober' ? 'selected' : '' }}>Oktober</option>
                                    <option value="November" {{ request('bulan') == 'November' ? 'selected' : '' }}>November</option>
                                    <option value="Desember" {{ request('bulan') == 'Desember' ? 'selected' : '' }}>Desember</option>
                                </select>
                            </div>
                            <div class="w-40">
                                <select name="status" id="statusFilter" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                    <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                    Cari
                                </button>
                            </div>
                        </form>
                    </div>

                    @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3">No</th>
                                    <th scope="col" class="px-6 py-3">NIS</th>
                                    <th scope="col" class="px-6 py-3">Santri</th>
                                    <th scope="col" class="px-6 py-3">SPP</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembayarans as $index => $pembayaran)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4">{{ $index + $pembayarans->firstItem() }}</td>
                                        <td class="px-6 py-4">
                                            @if($pembayaran->user)
                                                {{ $pembayaran->user->nis ?? '-' }}
                                            @else
                                                <span class="text-red-500">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($pembayaran->user)
                                                {{ $pembayaran->user->nama_santri }}
                                            @else
                                                <span class="text-red-500">Data santri tidak ditemukan</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                // Menghitung total yang sudah dibayar dari semua detail pembayaran
                                                $totalPaid = $pembayaran->detailPembayaran->sum('jumlah_dibayar');
                                                $totalTagihan = $pembayaran->total_tagihan;
                                        
                                                // Menentukan apakah sudah lunas atau belum
                                                $isLunas = $totalPaid >= $totalTagihan;
                                            @endphp
                                        
                                            {{-- Menampilkan format: Rp [sudah dibayar] / Rp [total tagihan] --}}
                                            <span class="{{ $isLunas ? 'text-green-600' : 'text-red-600' }} font-medium">
                                                Rp {{ number_format($totalPaid, 0, ',', '.') }} / Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($pembayaran->status == 'confirmed')
                                                <span class="px-2 py-1 text-xs text-white bg-green-500 rounded">Dikonfirmasi</span>
                                            @elseif($pembayaran->status == 'pending')
                                                <span class="px-2 py-1 text-xs text-white bg-yellow-500 rounded">Menunggu</span>
                                            @elseif($pembayaran->status == 'lunas')
                                                <span class="px-2 py-1 text-xs text-white bg-green-600 rounded">Lunas</span>
                                            @elseif($pembayaran->status == 'belum_lunas')
                                                <span class="px-2 py-1 text-xs text-white bg-yellow-600 rounded">Belum Lunas</span>
                                            @elseif($pembayaran->status == 'belum_bayar')
                                                <span class="px-2 py-1 text-xs text-white bg-red-600 rounded">Belum Bayar</span>
                                            @else
                                                <span class="px-2 py-1 text-xs text-white bg-red-500 rounded">Ditolak</span>
                                            @endif
                                            @if($pembayaran->is_cicilan)
                                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded ml-1">Cicilan</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 flex space-x-2">
                                            <a href="{{ route('pembayaran.show', $pembayaran->id) }}" class="font-medium text-blue-600 hover:underline">Lihat</a>
                                            {{-- <a href="{{ route('pembayaran.edit', $pembayaran->id) }}" class="font-medium text-green-600 hover:underline">Edit</a> --}}
                                            
                                            @php
                                                $showCicilanButton = false;
                                                // Tampilkan tombol cicilan jika pembayaran adalah cicilan dan belum lunas
                                                if ($pembayaran->is_cicilan && !$isLunas) {
                                                    $showCicilanButton = true;
                                                }
                                            @endphp

                                        @if($showCicilanButton)
                                        <a href="{{ route('pembayaran.installment.show', $pembayaran->id) }}" class="font-medium text-purple-600 hover:underline">Cicilan</a>
                                        @endif

                                        <form action="{{ route('pembayaran.destroy', $pembayaran->id) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr class="bg-white border-b">
                                    <td colspan="9" class="px-6 py-4 text-center">Tidak ada data pembayaran</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-6">
                        {{ $pembayarans->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live search JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search input handling
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                let timer;

                searchInput.addEventListener('input', function(e) {
                    clearTimeout(timer);
                    timer = setTimeout(function() {
                        document.getElementById('searchForm').submit();
                    }, 500);
                });
            }

            // Filter dropdown handling
            const bulanFilter = document.getElementById('bulanFilter');
            const statusFilter = document.getElementById('statusFilter');

            if (bulanFilter) {
                bulanFilter.addEventListener('change', function() {
                    document.getElementById('searchForm').submit();
                });
            }

            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    document.getElementById('searchForm').submit();
                });
            }
        });
    </script>
    @else
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-bold">Akses Ditolak</p>
                <p>Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Halaman ini hanya tersedia untuk administrator.</p>
            </div>
        </div>
    </div>
    @endif
</x-app-layout>