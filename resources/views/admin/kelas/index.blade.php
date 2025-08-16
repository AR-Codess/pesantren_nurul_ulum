<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Daftar Kelas</h2>
                        <a href="{{ route('admin.kelas.create') }}"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                            + Tambah Kelas Baru
                        </a>
                    </div>


                    @if(session('success'))
                    <div id="success-alert" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 transition-opacity duration-500" role="alert">
                        {{ session('success') }}
                    </div>
                    <script>
                        setTimeout(function() {
                            var alert = document.getElementById('success-alert');
                            if (alert) {
                                alert.style.opacity = 0;
                                setTimeout(function() {
                                    alert.style.display = 'none';
                                }, 500);
                            }
                        }, 2000);
                    </script>
                    @endif

                    <!-- Filter Form -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <form id="searchForm" action="{{ route('admin.kelas.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                            <div class="flex-1">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                                <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                    placeholder="Cari mata pelajaran atau tahun ajaran..."
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>

                            <div class="w-full md:w-1/5">
                                <label for="class_level_id" class="block text-sm font-medium text-gray-700 mb-1">Jenjang Kelas</label>
                                <select name="class_level_id" id="class_level_id" class="filter-select w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <option value="">-- Kelas --</option>
                                    @foreach($classLevels as $classLevel)
                                    <option value="{{ $classLevel->id }}" {{ request('class_level_id') == $classLevel->id ? 'selected' : '' }}>
                                        {{ $classLevel->level }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-full md:w-1/5">
                                <label for="guru_id" class="block text-sm font-medium text-gray-700 mb-1">Guru Pengajar</label>
                                <select name="guru_id" id="guru_id" class="filter-select w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <option value="">-- Guru --</option>
                                    @foreach($gurus as $guru)
                                    <option value="{{ $guru->id }}" {{ request('guru_id') == $guru->id ? 'selected' : '' }}>
                                        {{ $guru->nama_pendidik }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white rounded-md">
                                    Filter
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3">No</th>
                                    <th scope="col" class="px-6 py-3">Mata Pelajaran</th>
                                    <th scope="col" class="px-6 py-3">Jenjang Kelas</th>
                                    <th scope="col" class="px-6 py-3">Tahun Ajaran</th>
                                    <th scope="col" class="px-6 py-3">Hari</th>
                                    <th scope="col" class="px-6 py-3">Guru Pengajar</th>
                                    <th scope="col" class="px-6 py-3">Jumlah Santri</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kelas as $index => $item)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $loop->iteration + ($kelas->currentPage() - 1) * $kelas->perPage() }}</td>
                                    <td class="px-6 py-4">{{ $item->mata_pelajaran }}</td>
                                    <td class="px-6 py-4">
                                        @if($item->classLevels && $item->classLevels->count())
                                        {{ $item->classLevels->pluck('level')->join(', ') }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $item->tahun_ajaran }}</td>
                                    <td class="px-6 py-4">{{ $item->jadwal_hari ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $item->guru->nama_pendidik ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $item->users_count }}</td>
                                    <td class="px-6 py-4 space-x-2">
                                        <a href="{{ route('admin.kelas.show', $item) }}" class="text-blue-500 hover:underline">Lihat</a>
                                        <a href="{{ route('admin.kelas.edit', $item) }}" class="text-yellow-500 hover:underline">Edit</a>
                                        <form action="{{ route('admin.kelas.destroy', $item) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr class="bg-white border-b">
                                    <td colspan="7" class="px-6 py-4 text-center">Tidak ada data kelas</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-10">
                        {{ $kelas->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Live search functionality
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

            // Filter select change auto-submit
            const filterSelects = document.querySelectorAll('.filter-select');
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    document.getElementById('searchForm').submit();
                });
            });

            // Initialize DataTable if needed
            if (typeof($.fn.DataTable) !== 'undefined') {
                $('.table').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                });
            }
        });
    </script>
</x-app-layout>