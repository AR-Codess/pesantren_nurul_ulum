@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Daftar Level Kelas</h2>
                    <button onclick="document.getElementById('modalTambahKelas').classList.remove('hidden')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                        + Tambah Kelas
                    </button>
                </div>
                @if(session('success'))
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 2500)"
                    x-show="show"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 flex items-center gap-2 font-semibold shadow rounded"
                    role="alert"
                    style="min-height:44px">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                @endif
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3">No</th>
                                <th scope="col" class="px-6 py-3">Tingkatan Kelas</th>
                                <th scope="col" class="px-6 py-3">Nominal SPP</th>
                                <th scope="col" class="px-6 py-3">Nominal SPP Beasiswa</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($levels as $index => $level)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">{{ $level->level }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($level->spp, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">{{ $level->spp_beasiswa ? 'Rp ' . number_format($level->spp_beasiswa, 0, ',', '.') : '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                                    <button onclick="showEditKelas({{ $level->id }}, {{ json_encode($level->level) }}, {{ $level->spp }}, {{ $level->spp_beasiswa !== null ? $level->spp_beasiswa : 'null' }})" class="font-medium text-yellow-600 hover:underline">Edit</button>
                                    <form action="{{ route('kelola-spp.destroy', $level->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus kelas ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr class="bg-white border-b">
                                <td colspan="5" class="px-6 py-4 text-center">Tidak ada data level kelas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kelas -->
    <div id="modalTambahKelas" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button onclick="document.getElementById('modalTambahKelas').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
            <h3 class="text-lg font-bold mb-4">Tambah Level Kelas</h3>
            <form method="POST" action="{{ route('kelola-spp.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="level" class="block text-sm font-semibold text-blue-700 mb-1">Level Kelas</label>
                    <input type="text" id="level" name="level" placeholder="Contoh: Kelas 7" class="border border-blue-300 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" required>
                </div>
                <div class="mb-4">
                    <label for="spp" class="block text-sm font-semibold text-green-700 mb-1">Nominal SPP</label>
                    <input type="number" id="spp" name="spp" placeholder="Masukkan nominal SPP" class="border border-green-300 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-green-200 focus:border-green-400 transition" required min="0">
                </div>
                <div class="mb-4">
                    <label for="spp_beasiswa" class="block text-sm font-semibold text-yellow-700 mb-1">SPP Beasiswa</label>
                    <input type="number" id="spp_beasiswa" name="spp_beasiswa" placeholder="Nominal SPP Beasiswa" class="border border-yellow-300 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-yellow-200 focus:border-yellow-400 transition" min="0">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Kelas (dynamic) -->
    <div id="modalEditKelas" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button onclick="document.getElementById('modalEditKelas').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
            <h3 class="text-lg font-bold mb-4">Edit Level Kelas</h3>
            <form id="formEditKelas" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PATCH">
                <div class="mb-4">
                    <label for="edit_level" class="block text-sm font-semibold text-blue-700 mb-1">Level Kelas</label>
                    <input type="text" id="edit_level" name="level" class="border border-blue-300 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" required>
                </div>
                <div class="mb-4">
                    <label for="edit_spp" class="block text-sm font-semibold text-green-700 mb-1">Nominal SPP</label>
                    <input type="number" id="edit_spp" name="spp" class="border border-green-300 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-green-200 focus:border-green-400 transition" required min="0">
                </div>
                <div class="mb-4">
                    <label for="edit_spp_beasiswa" class="block text-sm font-semibold text-yellow-700 mb-1">SPP Beasiswa</label>
                    <input type="number" id="edit_spp_beasiswa" name="spp_beasiswa" class="border border-yellow-300 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-yellow-200 focus:border-yellow-400 transition" min="0">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showEditKelas(id, level, spp, spp_beasiswa) {
        document.getElementById('modalEditKelas').classList.remove('hidden');
        document.getElementById('edit_level').value = level;
        document.getElementById('edit_spp').value = spp;
        document.getElementById('edit_spp_beasiswa').value = spp_beasiswa !== null && spp_beasiswa !== undefined ? spp_beasiswa : '';
        var form = document.getElementById('formEditKelas');
        form.action = '/kelola-spp/' + id;
    }
</script>
@endsection