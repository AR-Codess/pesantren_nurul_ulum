<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kelas Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <a href="{{ route('admin.kelas.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">
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

                    <form action="{{ route('admin.kelas.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="mata_pelajaran" class="block text-sm font-medium text-gray-700">Mata Pelajaran <span class="text-red-500">*</span></label>
                            <input type="text" name="mata_pelajaran" id="mata_pelajaran" value="{{ old('mata_pelajaran') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            @error('mata_pelajaran')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700">Tahun Ajaran <span class="text-red-500">*</span></label>
                            <input type="text" name="tahun_ajaran" id="tahun_ajaran" value="{{ old('tahun_ajaran') }}" placeholder="Contoh: 2025/2026" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            @error('tahun_ajaran')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="class_level_id" class="block text-sm font-medium text-gray-700">Jenjang Kelas <span class="text-red-500">*</span></label>
                            <select name="class_level_id" id="class_level_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">-- Pilih Jenjang Kelas --</option>
                                @foreach($classLevels as $classLevel)
                                    <option value="{{ $classLevel->id }}" {{ old('class_level_id') == $classLevel->id ? 'selected' : '' }}>{{ $classLevel->level }}</option>
                                @endforeach
                            </select>
                            @error('class_level_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="guru_id" class="block text-sm font-medium text-gray-700">Guru Pengajar <span class="text-red-500">*</span></label>
                            <select name="guru_id" id="guru_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">-- Pilih Guru --</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>{{ $guru->nama_pendidik }}</option>
                                @endforeach
                            </select>
                            @error('guru_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="users" class="block text-sm font-medium text-gray-700">Daftar Santri</label>
                            <select name="users[]" id="users" multiple
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}" {{ in_array($user->id, old('users', [])) ? 'selected' : '' }}>
                                        {{ $user->nis }} - {{ $user->nama_santri }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Anda dapat memilih beberapa santri sekaligus untuk dimasukkan ke dalam kelas ini.</p>
                            @error('users')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Pastikan DOM sudah termuat sepenuhnya
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Choices.js pada elemen select dengan id 'users'
            const usersElement = document.querySelector('#users');
            if(usersElement) {
                const choices = new Choices(usersElement, {
                    removeItemButton: true, // Tampilkan tombol hapus pada setiap item
                    placeholder: true,
                    placeholderValue: 'Ketik untuk mencari santri...',
                    searchPlaceholderValue: 'Ketik untuk mencari...',
                    // itemSelectText: 'Sudah terdaftar' // Mengubah teks "Press to select" menjadi "Sudah terdaftar"
                });
            }
        });
    </script>
    @endpush
</x-app-layout>