<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Galeri') }}
        </h2>
    </x-slot>

    @if(auth()->user() && auth()->user()->hasRole('admin'))
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Daftar Foto Galeri</h2>
                        <a href="{{ route('admin.gallery.create') }}" 
                           class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                            + Tambah Foto Baru
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="mb-4 bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="flex items-center">
                            <div class="mr-3 text-blue-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-blue-700">
                                <span class="font-semibold">Drag & Drop untuk merubah urutan konten</span> 
                            </p>
                        </div>
                    </div>
                    
                    <div id="gallery-items" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                        @forelse($galleries as $gallery)
                            <div class="bg-white border rounded-lg overflow-hidden shadow-md cursor-move gallery-item" data-id="{{ $gallery->id }}">
                                <div class="relative pb-60 overflow-hidden">
                                    <img src="{{ filter_var($gallery->path_gambar, FILTER_VALIDATE_URL) ? $gallery->path_gambar : asset('storage/' . $gallery->path_gambar) }}" 
                                         alt="{{ $gallery->judul }}"
                                         class="absolute inset-0 h-full w-full object-cover object-center">
                                </div>
                                <div class="p-4">
                                    <h3 class="font-bold text-lg mb-2">{{ $gallery->judul }}</h3>
                                    @if($gallery->deskripsi)
                                        <p class="text-gray-700 text-sm mb-3">{{ Str::limit($gallery->deskripsi, 100) }}</p>
                                    @endif
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-gray-500 text-sm">ID: {{ $gallery->id }}</span>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.gallery.edit', $gallery->id) }}" class="font-medium text-green-600 hover:underline">Edit</a>
                                            <form action="{{ route('admin.gallery.destroy', $gallery->id) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-10">
                                <p class="text-gray-500">Belum ada foto di galeri. Tambahkan foto pertama anda.</p>
                            </div>
                        @endforelse
                    </div>

                    <div id="save-status" class="mt-6 hidden">
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>Urutan berhasil disimpan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Sortable.js library -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const galleryContainer = document.getElementById('gallery-items');
            const saveStatus = document.getElementById('save-status');
            
            if (galleryContainer) {
                // Initialize Sortable
                const sortable = Sortable.create(galleryContainer, {
                    animation: 150,
                    ghostClass: 'bg-gray-100',
                    onEnd: function() {
                        saveNewOrder();
                    }
                });
                
                // Function to save new order
                function saveNewOrder() {
                    const items = [];
                    
                    // Get all gallery items
                    document.querySelectorAll('.gallery-item').forEach((item, index) => {
                        items.push({
                            id: item.dataset.id,
                            order: index + 1
                        });
                        
                        // Update the displayed order
                        const orderLabel = item.querySelector('.order-label');
                        if (orderLabel) {
                            orderLabel.textContent = 'Urutan: ' + (index + 1);
                        }
                    });
                    
                    // Send the updated order to the server
                    fetch('{{ route('admin.gallery.update-order') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ items: items })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            saveStatus.classList.remove('hidden');
                            
                            // Hide message after 3 seconds
                            setTimeout(() => {
                                saveStatus.classList.add('hidden');
                            }, 3000);
                        }
                    })
                    .catch(error => {
                        console.error('Error saving order:', error);
                    });
                }
            }
        });
    </script>
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