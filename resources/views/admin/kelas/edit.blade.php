<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kelas') }}
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

                    <form action="{{ route('admin.kelas.update', $kela) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="mata_pelajaran" class="block text-sm font-medium text-gray-700">Mata Pelajaran <span class="text-red-500">*</span></label>
                            <input type="text" name="mata_pelajaran" id="mata_pelajaran" value="{{ old('mata_pelajaran', $kela->mata_pelajaran) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            @error('mata_pelajaran')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700">Tahun Ajaran <span class="text-red-500">*</span></label>
                            <input type="text" name="tahun_ajaran" id="tahun_ajaran" value="{{ old('tahun_ajaran', $kela->tahun_ajaran) }}" placeholder="Contoh: 2025/2026" required
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
                                <option value="{{ $classLevel->id }}" {{ (old('class_level_id', $kela->class_level_id) == $classLevel->id) ? 'selected' : '' }}>{{ $classLevel->level }}</option>
                                @endforeach
                            </select>
                            @error('class_level_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="mb-4">
                            <label for="jadwal_hari" class="block text-sm font-medium text-gray-700">Hari Kelas <span class="text-red-500">*</span></label>
                            <select name="jadwal_hari" id="jadwal_hari" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">-- Pilih Hari --</option>
                                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $hari)
                                <option value="{{ $hari }}" {{ (old('jadwal_hari', $kela->jadwal_hari) == $hari) ? 'selected' : '' }}>{{ $hari }}</option>
                                @endforeach
                            </select>
                            @error('jadwal_hari')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="guru_id" class="block text-sm font-medium text-gray-700">Guru Pengajar <span class="text-red-500">*</span></label>
                            <select name="guru_id" id="guru_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">-- Pilih Guru --</option>
                                @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" {{ (old('guru_id', $kela->guru_id) == $guru->id) ? 'selected' : '' }}>{{ $guru->nama_pendidik }}</option>
                                @endforeach
                            </select>
                            @error('guru_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="users" class="block text-sm font-medium text-gray-700">Daftar Santri</label>
                            
                            <select name="users[]" id="users" multiple>
                                {{-- Opsi yang sudah terpilih akan ditambahkan oleh JS --}}
                            </select>
                            
                            <p class="text-xs text-gray-500 mt-1">Anda dapat memilih beberapa santri sekaligus untuk dimasukkan ke dalam kelas ini.</p>
                            @error('users')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                Perbarui
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. SIMPAN SEMUA DATA DARI PHP KE JAVASCRIPT
        // Pastikan controller mengirim data user dengan relasi classLevel
        const allUsers = @json($users); 
        const selectedUserIds = @json($selectedUserIds ?? []);
    
        // 2. AMBIL ELEMENT PENTING
        const classLevelSelect = document.querySelector('#class_level_id');
        const usersElement = document.querySelector('#users');
    
        // 3. INISIALISASI CHOICES.JS
        const userChoices = new Choices(usersElement, {
            removeItemButton: true,
            placeholder: true,
            placeholderValue: 'Pilih jenjang kelas terlebih dahulu...',
            searchPlaceholderValue: 'Ketik untuk mencari...',
        });
    
        // 4. FUNGSI UNTUK MEMFILTER DAN MEMPERBARUI DAFTAR SANTRI
        function updateSantriList() {
            const selectedLevelId = classLevelSelect.value;
    
            // Kosongkan daftar santri jika tidak ada jenjang kelas yang dipilih
            if (!selectedLevelId) {
                userChoices.clearStore();
                userChoices.setChoices([{ value: '', label: 'Pilih jenjang kelas terlebih dahulu', disabled: true }], 'value', 'label', false);
                return;
            }
            
            // Filter santri berdasarkan jenjang kelas yang dipilih
            // Santri yang cocok ATAU yang belum punya kelas (class_level_id is null) akan ditampilkan
            const filteredUsers = allUsers.filter(user => {
                return user.class_level_id == selectedLevelId || user.class_level_id == null;
            });
    
            // Format data untuk Choices.js
            const choicesData = filteredUsers.map(user => {
                // Cek apakah user ini harusnya sudah terpilih
                const isSelected = selectedUserIds.includes(user.id);
                const levelName = user.class_level ? user.class_level.level : 'Belum Punya Kelas';
    
                return {
                    value: user.id,
                    label: `${user.nis} - ${user.nama_santri}`,
                    selected: isSelected
                };
            });
    
            // Perbarui pilihan di Choices.js
            userChoices.clearStore(); // Hapus pilihan lama
            userChoices.setChoices(choicesData, 'value', 'label', true); // Masukkan pilihan baru
        }
    
        // 5. TAMBAHKAN EVENT LISTENER DAN JALANKAN SAAT HALAMAN DIMUAT
        classLevelSelect.addEventListener('change', updateSantriList);
        
        // Jalankan fungsi saat pertama kali halaman dimuat untuk menampilkan santri sesuai jenjang kelas yang sudah ada
        updateSantriList(); 
    });
    </script>
    @endpush
</x-app-layout>