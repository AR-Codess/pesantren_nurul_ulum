<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Foto Galeri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <a href="{{ route('admin.gallery.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">
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

                    <form action="{{ route('admin.gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul Foto</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $gallery->title) }}" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Foto Saat Ini</label>
                            <div class="mt-2 mb-4">
                                <img src="{{ filter_var($gallery->image_path, FILTER_VALIDATE_URL) ? $gallery->image_path : asset('storage/' . $gallery->image_path) }}" 
                                     alt="{{ $gallery->alt_text }}" 
                                     class="w-60 h-auto object-cover border rounded">
                            </div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Ganti Foto (opsional)</label>
                            <input type="file" name="image" id="image"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengganti foto.</p>
                            {{-- <p class="text-xs text-red-600 font-semibold mt-1">Rekomendasi ukuran: 600×400 piksel (landscape) untuk tampilan yang seragam di galeri.</p> --}}
                        </div>
                        
                        <div class="mb-4">
                            <label for="alt_text" class="block text-sm font-medium text-gray-700">Teks Alternatif</label>
                            <input type="text" name="alt_text" id="alt_text" value="{{ old('alt_text', $gallery->alt_text) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" rows="3" 
                                     class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('description', $gallery->description) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="active" id="active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('active', $gallery->active) ? 'checked' : '' }}>
                                <label for="active" class="ml-2 block text-sm text-gray-700">Aktif</label>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Jika dicentang, foto akan ditampilkan di galeri.</p>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                Update Foto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>