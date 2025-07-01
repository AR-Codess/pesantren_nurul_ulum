<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Guru') }}
        </h2>
    </x-slot>

    @if(auth()->user() && auth()->user()->hasRole('admin'))
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Daftar Guru</h2>
                        <a href="{{ route('guru.create') }}" 
                           class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                            + Tambah Guru
                        </a>
                    </div>
                    
                    <!-- Search Form -->
                    <div class="mb-4">
                        <form id="searchForm" action="{{ route('guru.index') }}" method="GET" class="flex items-center space-x-2">
                            <input type="text" id="searchInput" name="search" value="{{ request('search') }}" 
                                   class="border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                                   placeholder="Cari nama atau nik...">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                Cari
                            </button>
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
                                    <th scope="col" class="px-6 py-3">NIK</th>
                                    <th scope="col" class="px-6 py-3">Nama Pendidik</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($guru as $index => $g)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">{{ $g->nik ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $g->nama_pendidik }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                                            <a href="{{ route('guru.show', $g->id) }}" class="font-medium text-blue-600 hover:underline">Lihat</a>
                                            <a href="{{ route('guru.edit', $g->id) }}" class="font-medium text-yellow-600 hover:underline">Edit</a>
                                            <form action="{{ route('guru.destroy', $g->id) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b">
                                        <td colspan="4" class="px-6 py-4 text-center">Tidak ada data guru</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination Links -->
                    <div class="mt-10">
                        {{ $guru->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Live search JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                let timer;
                
                searchInput.addEventListener('input', function(e) {
                    clearTimeout(timer);
                    timer = setTimeout(function() {
                        console.log('Submitting search form after 800ms delay');
                        document.getElementById('searchForm').submit();
                    }, 500);
                });
            } else {
                console.error('Search input element not found');
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