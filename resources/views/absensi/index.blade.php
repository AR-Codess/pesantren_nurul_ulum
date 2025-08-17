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
                        <div class="flex items-center space-x-4">
                            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 font-semibold transition">
                                &larr; Kembali
                            </a>
                            <h2 class="text-xl font-bold">Daftar Absensi</h2>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-2 md:space-y-0">
                            @php
                            $kelasId = request('kelas_id');
                            $jumlahMurid = null;
                            if ($kelasId) {
                            $kelas = \App\Models\Kelas::find($kelasId);
                            $jumlahMurid = $kelas ? $kelas->users()->count() : null;
                            }
                            @endphp
                            @if($jumlahMurid !== null)
                            <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded font-semibold">Jumlah Murid: {{ $jumlahMurid }}</span>
                            @endif
                        </div>
                    </div>

                    @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="overflow-x-auto">
                        @php
                        $kelasId = request('kelas_id');
                        $kelas = $kelasId ? \App\Models\Kelas::find($kelasId) : null;
                        $muridList = $kelas ? $kelas->users : collect();
                        @endphp
                        @if($muridList->count() > 0)
                        <form action="{{ route('absensi.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
                            <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">
                            <table class="w-full text-sm text-left text-gray-700">
                                <thead class="text-xs text-gray-700 uppercase bg-blue-50">
                                    <tr>
                                        <th class="px-4 py-3">No</th>
                                        <th class="px-4 py-3">NIS</th>
                                        <th class="px-4 py-3">Nama Santri</th>
                                        <th class="px-4 py-3">Jenjang Kelas</th>
                                        <th class="px-4 py-3 text-center">Hadir</th>
                                        <th class="px-4 py-3 text-center">Izin</th>
                                        <th class="px-4 py-3 text-center">Sakit</th>
                                        <th class="px-4 py-3 text-center">Alpha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($muridList as $i => $murid)
                                    <tr class="bg-white border-b hover:bg-blue-50 transition">
                                        <td class="px-4 py-3 font-semibold">{{ $i+1 }}</td>
                                        <td class="px-4 py-3">{{ $murid->nis ?? '-' }}</td>
                                        <td class="px-4 py-3 font-medium">{{ $murid->nama_santri ?? $murid->name }}</td>
                                        <td class="px-4 py-3">
                                            {{ $murid->classLevel->level ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <input type="radio" name="absensi[{{ $murid->id }}][status]" value="hadir" checked required>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <input type="radio" name="absensi[{{ $murid->id }}][status]" value="izin">
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <input type="radio" name="absensi[{{ $murid->id }}][status]" value="sakit">
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <input type="radio" name="absensi[{{ $murid->id }}][status]" value="alpha">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4 flex justify-end">
                                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-semibold">Simpan Absensi</button>
                            </div>
                        </form>
                        @else
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nama Santri</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b">
                                    <td class="px-6 py-4 text-center">Tidak ada murid pada kelas ini</td>
                                </tr>
                            </tbody>
                        </table>
                        @endif
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