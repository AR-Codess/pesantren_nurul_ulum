<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Berita') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <a href="{{ route('admin.berita.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">
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

                    <form action="{{ route('admin.berita.update', $berita->hashed_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul Berita</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $berita->judul) }}" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">Ubah Foto (Opsional)</label>

                            @if ($berita->path_gambar)
                                <div class="mb-2">
                                    <img src="{{ filter_var($berita->path_gambar, FILTER_VALIDATE_URL) ? $berita->path_gambar : asset('storage/' . $berita->path_gambar) }}" 
                                         alt="Foto saat ini" class="w-40 rounded shadow">
                                </div>
                            @endif

                            <input type="file" name="image" id="image"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" class="ckeditor mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('description', $berita->deskripsi) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                Update Berita
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- CKEditor Scripts -->
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tambahkan meta CSRF token jika belum ada
            if (!document.querySelector('meta[name="csrf-token"]')) {
                var meta = document.createElement('meta');
                meta.name = "csrf-token";
                meta.content = "{{ csrf_token() }}";
                document.head.appendChild(meta);
            }
            
            CKEDITOR.replace('description', {
                customConfig: "{{ asset('ckeditor/config.js') }}",
                extraPlugins: 'uploadimage,image2',
                removePlugins: 'exportpdf',
                height: 300
            });
        });
    </script>
</x-app-layout>