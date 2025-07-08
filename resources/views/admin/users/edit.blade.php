<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">
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

                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="nama_santri" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_santri" id="nama_santri" value="{{ old('nama_santri', $user->nama_santri) }}" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="nis" class="block text-sm font-medium text-gray-700">Nomor Induk Santri (NIS) <span class="text-red-500">*</span></label>
                            <input type="text" id="nis" value="{{ $user->nis }}" readonly 
                                   class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <input type="hidden" name="nis" value="{{ $user->nis }}">
                            <p class="mt-1 text-xs text-gray-500">NIS tidak dapat diubah</p>
                        </div>

                        <div class="mb-4">
                            <label for="class_level_id" class="block text-sm font-medium text-gray-700">Kelas <span class="text-red-500">*</span></label>
                            <select name="class_level_id" id="class_level_id" required 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                    onchange="updateSpp(this.value)">
                                <option value="">Pilih Kelas</option>
                                @foreach($classLevels as $classLevel)
                                    <option value="{{ $classLevel->id }}" 
                                            data-spp="{{ $classLevel->spp }}" 
                                            data-spp-beasiswa="{{ $classLevel->spp_beasiswa ?? $classLevel->spp }}" 
                                            {{ old('class_level_id', $user->class_level_id) == $classLevel->id ? 'selected' : '' }}>
                                        {{ $classLevel->level }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="spp_bulanan" class="block text-sm font-medium text-gray-700">SPP Bulanan</label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="spp_bulanan" id="spp_bulanan" value="{{ old('spp_bulanan', $user->spp_bulanan) }}"
                                    class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-100 cursor-not-allowed"
                                    placeholder="0" readonly tabindex="-1">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="hidden" name="is_beasiswa" value="0">
                                <input type="checkbox" name="is_beasiswa" id="is_beasiswa" value="1" {{ old('is_beasiswa', $user->is_beasiswa) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <label for="is_beasiswa" class="ml-2 block text-sm text-gray-700">Penerima Beasiswa</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="jenis_kelamin" id="jenis_kelamin" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="1" {{ old('jenis_kelamin', $user->jenis_kelamin) == 1 ? 'selected' : '' }}>Laki-laki</option>
                                <option value="0" {{ old('jenis_kelamin', $user->jenis_kelamin) == 0 ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir  <span class="text-red-500">*</span></label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $user->tempat_lahir) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir  <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="provinsi" class="block text-sm font-medium text-gray-700">Provinsi  <span class="text-red-500">*</span></label>
                            <input type="text" name="provinsi" id="provinsi" value="{{ old('provinsi', $user->provinsi) }}" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="kabupaten" class="block text-sm font-medium text-gray-700">Kabupaten  <span class="text-red-500">*</span></label>
                            <input type="text" name="kabupaten" id="kabupaten" value="{{ old('kabupaten', $user->kabupaten) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat  <span class="text-red-500">*</span></label>
                            <textarea name="alamat" id="alamat" rows="3" required
                                     class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="no_hp" class="block text-sm font-medium text-gray-700">Nomor HP</label>
                            <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $user->no_hp) }}" pattern="[0-9]*" inputmode="numeric"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                   placeholder="Masukkan angka saja">
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
                                Perbarui Data Santri
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to update SPP field based on class level selection
        function updateSpp(classLevelId) {
            if (!classLevelId) return;
            
            // Get the selected option
            const selectedOption = document.querySelector(`#class_level_id option[value="${classLevelId}"]`);
            
            if (selectedOption) {
                const sppValue = selectedOption.getAttribute('data-spp');
                let sppBeasiswaValue = selectedOption.getAttribute('data-spp-beasiswa');
                const sppBulananField = document.getElementById('spp_bulanan');
                const isBeasiswaCheckbox = document.getElementById('is_beasiswa');

                // Pastikan sppBeasiswaValue tidak null/undefined/empty
                if (!sppBeasiswaValue || sppBeasiswaValue === 'null' || sppBeasiswaValue === '') {
                    sppBeasiswaValue = sppValue;
                }

                // Default: set SPP bulanan
                sppBulananField.value = sppValue;

                // If beasiswa checked, set SPP beasiswa
                if (isBeasiswaCheckbox && isBeasiswaCheckbox.checked) {
                    sppBulananField.value = sppBeasiswaValue;
                }
            }
        }

        // When beasiswa checkbox is toggled, update SPP bulanan
        document.addEventListener('DOMContentLoaded', function() {
            const classLevelField = document.getElementById('class_level_id');
            const isBeasiswaCheckbox = document.getElementById('is_beasiswa');
            if (classLevelField && classLevelField.value) {
                updateSpp(classLevelField.value);
            }
            if (isBeasiswaCheckbox) {
                isBeasiswaCheckbox.addEventListener('change', function() {
                    if (classLevelField && classLevelField.value) {
                        updateSpp(classLevelField.value);
                    }
                });
            }
        });

        // Initialize on page load if there's already a selected class level
        document.addEventListener('DOMContentLoaded', function() {
            const classLevelField = document.getElementById('class_level_id');
            if (classLevelField && classLevelField.value) {
                updateSpp(classLevelField.value);
            }
        });
    </script>
</x-app-layout>