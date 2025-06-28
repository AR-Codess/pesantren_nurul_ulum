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
                            + Tambah Guru Baru
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
                                    <th scope="col" class="px-6 py-3">NIP</th>
                                    <th scope="col" class="px-6 py-3">Nama</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">Bidang</th>
                                    <th scope="col" class="px-6 py-3">No. HP</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($gurus as $guru)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ $guru->nis }}</td>
                                        <td class="px-6 py-4">{{ $guru->name }}</td>
                                        <td class="px-6 py-4">{{ $guru->email }}</td>
                                        <td class="px-6 py-4">
                                            {{ App\Models\Guru::where('user_id', $guru->id)->first()->bidang ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">{{ $guru->no_hp ?? '-' }}</td>
                                        <td class="px-6 py-4 flex space-x-2">
                                            <a href="{{ route('guru.show', $guru->id) }}" class="font-medium text-blue-600 hover:underline">Lihat</a>
                                            <a href="{{ route('guru.edit', $guru->id) }}" class="font-medium text-green-600 hover:underline">Edit</a>
                                            <form action="{{ route('guru.destroy', $guru->id) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b">
                                        <td colspan="6" class="px-6 py-4 text-center">Tidak ada data guru</td>
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