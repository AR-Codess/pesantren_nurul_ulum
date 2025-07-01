<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Guru') }}
        </h2>
    </x-slot>

    @if(auth()->user() && auth()->user()->hasRole('admin'))
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <a href="{{ route('guru.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">
                            &laquo; Kembali
                        </a>
                    </div>
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p class="font-bold">Terjadi kesalahan:</p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('guru.update', $guru->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="nama_pendidik" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-600">*</span></label>
                                <input type="text" name="nama_pendidik" id="nama_pendidik" value="{{ old('nama_pendidik', $guru->nama_pendidik) }}" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>

                            <div class="mb-4">
                                <label for="nik" class="block text-sm font-medium text-gray-700">NIK (Tidak dapat diubah)</label>
                                <input type="text" name="nik" id="nik" value="{{ old('nik', $guru->nik) }}" readonly
                                       class="mt-1 block w-full rounded-md bg-gray-100 border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-600">*</span></label>
                                <input type="email" name="email" id="email" value="{{ old('email', $guru->email) }}" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>

                            <div class="mb-4">
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <option value="1" {{ isset($guru->jenis_kelamin) && $guru->jenis_kelamin === true ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="0" {{ isset($guru->jenis_kelamin) && $guru->jenis_kelamin === false ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $guru->tempat_lahir) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>

                            <div class="mb-4">
                                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $guru->tanggal_lahir ? $guru->tanggal_lahir->format('Y-m-d') : '') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>

                            <div class="mb-4">
                                <label for="pendidikan_terakhir" class="block text-sm font-medium text-gray-700">Pendidikan Terakhir</label>
                                <input type="text" name="pendidikan_terakhir" id="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $guru->pendidikan_terakhir) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>

                            <div class="mb-4">
                                <label for="bidang" class="block text-sm font-medium text-gray-700">Bidang/Mata Pelajaran</label>
                                <input type="text" name="bidang" id="bidang" value="{{ old('bidang', $guru->bidang) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>

                            <div class="mb-4">
                                <label for="no_telepon" class="block text-sm font-medium text-gray-700">Nomor HP</label>
                                <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon', $guru->no_telepon) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>

                            <div class="mb-4">
                                <label for="provinsi" class="block text-sm font-medium text-gray-700">Provinsi</label>
                                <input type="text" name="provinsi" id="provinsi" value="{{ old('provinsi', $guru->provinsi) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>

                            <div class="mb-4">
                                <label for="kabupaten" class="block text-sm font-medium text-gray-700">Kabupaten</label>
                                <input type="text" name="kabupaten" id="kabupaten" value="{{ old('kabupaten', $guru->kabupaten) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="riwayat_pendidikan_keagamaan" class="block text-sm font-medium text-gray-700">Riwayat Pendidikan Keagamaan</label>
                            <textarea name="riwayat_pendidikan_keagamaan" id="riwayat_pendidikan_keagamaan" rows="3" 
                                     class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('riwayat_pendidikan_keagamaan', $guru->riwayat_pendidikan_keagamaan) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="alamat" id="alamat" rows="3" 
                                     class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('alamat', $guru->alamat) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password (kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" name="password" id="password" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                Update Data Guru
                            </button>
                        </div>
                    </form>
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