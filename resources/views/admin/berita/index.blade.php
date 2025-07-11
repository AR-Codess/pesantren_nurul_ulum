<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Berita') }}
        </h2>
    </x-slot>

    @if(auth()->user() && auth()->user()->hasRole('admin'))
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Daftar Foto Berita</h2>
                        <a href="{{ route('admin.berita.create') }}" 
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
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-blue-700 font-semibold">
                                Drag & Drop untuk mengubah urutan konten.
                            </p>
                        </div>
                    </div>

                    <div id="berita-items" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                        @forelse($berita as $item)
                            <div class="bg-white border rounded-lg overflow-hidden shadow-md cursor-move berita-item" data-id="{{ $item->id }}">
                                <div class="relative pb-60 overflow-hidden">
                                    <img src="{{ filter_var($item->path_gambar, FILTER_VALIDATE_URL) ? $item->path_gambar : asset('storage/' . $item->path_gambar) }}"
                                         alt="{{ $item->judul }}"
                                         class="absolute inset-0 h-full w-full object-cover object-center">
                                </div>
                                <div class="p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="font-bold text-lg line-clamp-2">{{ $item->judul }}</h3>
                                        <span class="text-sm text-gray-500 order-label">Urutan: {{ $loop->iteration }}</span>
                                    </div>
                                    @if($item->deskripsi)
                                        <p class="text-gray-700 text-sm mb-3 line-clamp-3">{{ Str::limit(strip_tags($item->deskripsi)) }}</p>
                                    @endif
                                    <div class="flex justify-end items-center mt-4">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('berita.show', ['id' => $item->id, 'slug' => Str::slug($item->judul)]) }}" class="font-medium text-blue-600 hover:underline">Lihat</a>
                                            <a href="{{ route('admin.berita.edit', $item->id) }}" class="font-medium text-green-600 hover:underline">Edit</a>
                                            <form action="{{ route('admin.berita.destroy', $item->id) }}" method="POST" class="inline"
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
                                <p class="text-gray-500">Belum ada foto di berita. Tambahkan foto pertama Anda.</p>
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
            const beritaContainer = document.getElementById('berita-items');
            const saveStatus = document.getElementById('save-status');

            if (beritaContainer) {
                Sortable.create(beritaContainer, {
                    animation: 150,
                    ghostClass: 'bg-gray-100',
                    onEnd: function () {
                        saveNewOrder();
                    }
                });

                function saveNewOrder() {
                    const items = [];

                    document.querySelectorAll('.berita-item').forEach((item, index) => {
                        items.push({
                            id: item.dataset.id,
                            urut: index
                        });

                        const orderLabel = item.querySelector('.order-label');
                        if (orderLabel) {
                            orderLabel.textContent = 'Urutan: ' + (index + 1);
                        }
                    });

                    fetch('{{ route('admin.berita.update-order') }}', {
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
                            saveStatus.classList.remove('hidden');
                            setTimeout(() => {
                                saveStatus.classList.add('hidden');
                            }, 3000);
                        }
                    })
                    .catch(error => {
                        // console.error('Error saving order:', error);
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
