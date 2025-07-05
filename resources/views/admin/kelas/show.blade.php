<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <a href="{{ route('admin.kelas.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700 mr-2">
                                &laquo; Kembali
                            </a>
                            <a href="{{ route('admin.kelas.edit', $kela) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-700">
                                Edit
                            </a>
                        </div>
                    </div>

                    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                        <div class="px-4 py-5 sm:px-6 bg-gray-50">
                            <h3 class="text-lg font-medium text-gray-900">Informasi Kelas</h3>
                        </div>
                        <div class="border-t border-gray-200">
                            <dl>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Mata Pelajaran</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $kela->mata_pelajaran }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Jenjang Kelas</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $kela->classLevel->level ?? 'Tidak diatur' }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Tahun Ajaran</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $kela->tahun_ajaran }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Hari Kelas</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $kela->jadwal_hari ?? '-' }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Guru Pengajar</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $kela->guru->nama_pendidik ?? 'Tidak diatur' }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Jumlah Santri</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $kela->users->count() }} santri</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Santri</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">No</th>
                                        <th scope="col" class="px-6 py-3">NIS</th>
                                        <th scope="col" class="px-6 py-3">Nama Santri</th>
                                        <th scope="col" class="px-6 py-3">Jenis Kelamin</th>
                                        <th scope="col" class="px-6 py-3">Tanggal Lahir</th>
                                        <th scope="col" class="px-6 py-3">Alamat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($kela->users as $index => $user)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">{{ $user->nis }}</td>
                                        <td class="px-6 py-4">{{ $user->nama_santri }}</td>
                                        <td class="px-6 py-4">{{ $user->jenis_kelamin ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td class="px-6 py-4">{{ $user->tanggal_lahir ? $user->tanggal_lahir->format('d-m-Y') : '-' }}</td>
                                        <td class="px-6 py-4">{{ $user->alamat }}</td>
                                    </tr>
                                    @empty
                                    <tr class="bg-white border-b">
                                        <td colspan="6" class="px-6 py-4 text-center">Tidak ada santri yang terdaftar di kelas ini</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable if needed
            if (typeof($.fn.DataTable) !== 'undefined') {
                $('table').DataTable({
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