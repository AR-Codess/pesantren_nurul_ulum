<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Absensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Daftar Absensi</h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('absensi.create') }}" 
                               class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                + Tambah Absensi
                            </a>
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">
                                    Export Data
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg py-2 z-10">
                                    <a href="{{ route('absensi.export', 'excel') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                                        Export ke Excel
                                    </a>
                                    <a href="{{ route('absensi.export', 'pdf') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                                        Export ke PDF
                                    </a>
                                </div>
                            </div>
                        </div>
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
                                    <th scope="col" class="px-6 py-3">Tanggal</th>
                                    <th scope="col" class="px-6 py-3">Nama Santri</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($absensi as $a)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($a->tanggal)->format('d M Y') }}</td>
                                        <td class="px-6 py-4">{{ $a->user->name ?? $a->user->nama_santri ?? 'Santri tidak ditemukan' }}</td>
                                        <td class="px-6 py-4">
                                            @if($a->status == 'hadir')
                                                <span class="px-2 py-1 text-xs text-white bg-green-500 rounded-full">Hadir</span>
                                            @elseif($a->status == 'sakit')
                                                <span class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-full">Sakit</span>
                                            @elseif($a->status == 'izin')
                                                <span class="px-2 py-1 text-xs text-white bg-blue-500 rounded-full">Izin</span>
                                            @else
                                                <span class="px-2 py-1 text-xs text-white bg-red-500 rounded-full">Alpha</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 flex space-x-2">
                                            <a href="{{ route('absensi.show', $a->id) }}" class="font-medium text-blue-600 hover:underline">Lihat</a>
                                            <a href="{{ route('absensi.edit', $a->id) }}" class="font-medium text-green-600 hover:underline">Edit</a>
                                            <form action="{{ route('absensi.destroy', $a->id) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b">
                                        <td colspan="4" class="px-6 py-4 text-center">Tidak ada data absensi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Alpine.js for dropdown
        document.addEventListener('alpine:init', () => {
            // Alpine.js is already initialized
        });
    </script>
</x-app-layout>