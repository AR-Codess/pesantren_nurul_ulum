<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Guru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <a href="{{ route('guru.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">
                            &laquo; Kembali
                        </a>
                    </div>
                    
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Guru</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Detail data personal dan akademik.</p>
                        </div>
                        <div class="border-t border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Field
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Value
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">ID</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $guru->id }}</td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">Nomor Induk Pengajar (NIK)</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $guru->nik ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">Nama Lengkap</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $guru->nama_pendidik ?: '-' }}</td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">Jenis Kelamin</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ isset($guru->jenis_kelamin) ? ($guru->jenis_kelamin ? 'Laki-laki' : 'Perempuan') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">Tempat Lahir</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $guru->tempat_lahir ?: '-' }}</td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">Tanggal Lahir</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $guru->tanggal_lahir ? $guru->tanggal_lahir->format('d F Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">Pendidikan Terakhir</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $guru->pendidikan_terakhir ?: '-' }}</td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">Riwayat Pendidikan Keagamaan</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $guru->riwayat_pendidikan_keagamaan ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">Email</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $guru->email ?: '-' }}</td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">Nomor HP</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $guru->no_telepon ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">Provinsi</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $guru->provinsi ?: '-' }}</td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">Kabupaten</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $guru->kabupaten ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">Alamat</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $guru->alamat ?: '-' }}</td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">Bidang/Mata Pelajaran</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $guru->bidang ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">Tanggal Bergabung</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $guru->created_at ? $guru->created_at->format('d F Y') : '-' }}</td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">Terakhir Diperbarui</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $guru->updated_at ? $guru->updated_at->format('d F Y') : '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('guru.edit', $guru->id) }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">
                            Edit Data
                        </a>
                        <form action="{{ route('guru.destroy', $guru->id) }}" method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700">
                                Hapus Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>