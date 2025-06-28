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
                            + Tambah Pembayaran Baru
                        </a>
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
                                    <th scope="col" class="px-6 py-3">Santri</th>
                                    <th scope="col" class="px-6 py-3">Bulan</th>
                                    <th scope="col" class="px-6 py-3">Jumlah</th>
                                    <th scope="col" class="px-6 py-3">Metode</th>
                                    <th scope="col" class="px-6 py-3">Tanggal</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembayarans as $pembayaran)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4">{{ $pembayaran->user->name }}</td>
                                        <td class="px-6 py-4">{{ $pembayaran->bulan }}</td>
                                        <td class="px-6 py-4">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">{{ $pembayaran->metode_pembayaran }}</td>
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d M Y') }}</td>
                                        <td class="px-6 py-4">
                                            @if($pembayaran->status == 'confirmed')
                                                <span class="px-2 py-1 text-xs text-white bg-green-500 rounded">Dikonfirmasi</span>
                                            @elseif($pembayaran->status == 'pending')
                                                <span class="px-2 py-1 text-xs text-white bg-yellow-500 rounded">Menunggu</span>
                                            @else
                                                <span class="px-2 py-1 text-xs text-white bg-red-500 rounded">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 flex space-x-2">
                                            <a href="{{ route('pembayaran.show', $pembayaran->id) }}" class="font-medium text-blue-600 hover:underline">Lihat</a>
                                            <a href="{{ route('pembayaran.edit', $pembayaran->id) }}" class="font-medium text-green-600 hover:underline">Edit</a>
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
                                        <td colspan="7" class="px-6 py-4 text-center">Tidak ada data pembayaran</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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