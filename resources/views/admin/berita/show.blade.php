<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Berita') }}
        </h2>
    </x-slot>

    <!-- SEO Metadata -->
    @section('meta_tags')
        <title>{{ $berita->judul }} - Pesantren Nurul Ulum</title>
        <meta name="description" content="{{ Str::limit(strip_tags($berita->deskripsi), 160) }}">
        <meta name="keywords" content="pesantren, nurul ulum, {{ $berita->judul }}">
        
        <!-- Open Graph Tags for Social Media -->
        <meta property="og:title" content="{{ $berita->judul }}">
        <meta property="og:description" content="{{ Str::limit(strip_tags($berita->deskripsi), 160) }}">
        <meta property="og:image" content="{{ filter_var($berita->path_gambar, FILTER_VALIDATE_URL) ? $berita->path_gambar : asset('storage/' . $berita->path_gambar) }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:type" content="article">
    @endsection

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <a href="{{ route('admin.berita.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">
                            &laquo; Kembali
                        </a>
                    </div>

                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $berita->judul }}</h1>
                        
                        <div class="flex items-center text-gray-500 text-sm mb-6">
                            <span>Dipublikasikan: {{ $berita->created_at->format('d F Y') }}</span>
                            @if($berita->admin)
                            <span class="mx-2">|</span>
                            <span>Oleh: {{ $berita->admin->name ?? 'Admin' }}</span>
                            @endif
                        </div>
                        
                        <div class="mb-6 rounded-lg overflow-hidden">
                            <img 
                                src="{{ filter_var($berita->path_gambar, FILTER_VALIDATE_URL) ? $berita->path_gambar : asset('storage/' . $berita->path_gambar) }}" 
                                alt="{{ $berita->judul }}" 
                                class="w-full object-cover h-auto"
                            >
                        </div>
                        
                        <div class="prose max-w-none">
                            {!! $berita->deskripsi !!}
                        </div>
                    </div>
                    
                    <!-- Admin Actions -->
                    @if(auth()->user() && auth()->user()->hasRole('admin'))
                    <div class="mt-8 flex space-x-4">
                        <a href="{{ route('admin.berita.edit', $berita->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                            Edit Berita
                        </a>
                        <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700">
                                Hapus Berita
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JSON-LD Schema for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NewsArticle",
        "headline": "{{ $berita->judul }}",
        "image": [
            "{{ filter_var($berita->path_gambar, FILTER_VALIDATE_URL) ? $berita->path_gambar : asset('storage/' . $berita->path_gambar) }}"
        ],
        "datePublished": "{{ $berita->created_at->toIso8601String() }}",
        "dateModified": "{{ $berita->updated_at->toIso8601String() }}",
        "author": {
            "@type": "Person",
            "name": "{{ $berita->admin ? $berita->admin->name : 'Admin Pesantren Nurul Ulum' }}"
        },
        "publisher": {
            "@type": "Organization",
            "name": "Pesantren Nurul Ulum",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('images/logo.png') }}"
            }
        },
        "description": "{{ Str::limit(strip_tags($berita->deskripsi), 160) }}"
    }
    </script>
</x-app-layout>