<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $berita->title }} - Pesantren Nurul Ulum</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ Str::limit(strip_tags($berita->description), 160) }}">
    <meta property="og:title" content="{{ $berita->title }} - Pesantren Nurul Ulum">
    <meta property="og:description" content="{{ Str::limit(strip_tags($berita->description), 160) }}">
    <meta property="og:image" content="{{ $berita->image_url }}">
    <meta property="og:url" content="{{ route('berita.show', ['hashed_id' => $berita->hashed_id, 'slug' => $berita->slug]) }}">
    <meta property="og:type" content="article">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="canonical" href="{{ route('berita.show', ['hashed_id' => $berita->hashed_id, 'slug' => $berita->slug]) }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #046c4e;
            --secondary-color: #facc15;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        
        .navbar {
            background-color: var(--primary-color);
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: white !important;
            font-size: 1.5rem;
        }
        
        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-weight: 500;
            padding-left: 20px;
            padding-right: 20px;
        }
        
        .navbar-nav .nav-link:hover {
            color: var(--secondary-color) !important;
        }
        
        .page-title {
            padding: 80px 0;
            background-color: #f9fafb;
            margin-bottom: 40px;
        }
        
        .berita-header {
            position: relative;
            padding: 40px 0;
            margin-bottom: 30px;
            background-color: #f9fafb;
        }
        
        .berita-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            margin-bottom: 30px;
        }
        
        .berita-content {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 40px;
        }
        
        .berita-meta {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #666;
        }
        
        .related-title {
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        
        .related-title:after {
            content: '';
            position: absolute;
            width: 50px;
            height: 3px;
            background-color: var(--primary-color);
            bottom: 0;
            left: 0;
        }
        
        .berita-item {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
        }
        
        .berita-item:hover {
            transform: translateY(-10px);
        }
        
        .berita-item .card-title {
            color: var(--primary-color);
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
        }
        
        .berita-image-wrapper {
            height: 150px;
            overflow: hidden;
        }
        
        .berita-img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            object-position: center;
            transition: transform 0.5s ease;
        }
        
        .berita-item:hover .berita-img {
            transform: scale(1.08);
        }
        
        .btn-outline-success {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-success:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .footer {
            background-color: var(--primary-color);
            color: #fff;
            padding: 30px 0;
            margin-top: 50px;
        }
        
        /* Share buttons */
        .share-buttons {
            margin-top: 40px;
            margin-bottom: 30px;
        }
        
        .share-buttons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .share-buttons a:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }
        
        .share-facebook {
            background-color: #3b5998;
        }
        
        .share-twitter {
            background-color: #1da1f2;
        }
        
        .share-whatsapp {
            background-color: #25D366;
        }
        
        .share-telegram {
            background-color: #0088cc;
        }
    </style>
</head>
<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Berita') }}
            </h2>
        </x-slot>

        <!-- SEO Meta Tags -->
        @section('meta_tags')
            <title>{{ $berita->title }} - Pesantren Nurul Ulum</title>
            <meta name="description" content="{{ Str::limit(strip_tags($berita->description), 160) }}">
            <meta property="og:title" content="{{ $berita->title }} - Pesantren Nurul Ulum">
            <meta property="og:description" content="{{ Str::limit(strip_tags($berita->description), 160) }}">
            <meta property="og:image" content="{{ $berita->image_url }}">
            <meta property="og:url" content="{{ route('berita.show', ['hashed_id' => $berita->hashed_id, 'slug' => $berita->slug]) }}">
            <meta property="og:type" content="article">
            <meta name="twitter:card" content="summary_large_image">
            <link rel="canonical" href="{{ route('berita.show', ['hashed_id' => $berita->hashed_id, 'slug' => $berita->slug]) }}">
        @endsection

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Breadcrumbs -->
                        <nav class="mb-3" aria-label="breadcrumb">
                            <ol class="flex space-x-2 text-gray-600 text-sm">
                                <li><a href="{{ route('welcome') }}" class="hover:text-blue-600">Beranda</a></li>
                                <li class="px-2">/</li>
                                <li><a href="{{ route('berita.index') }}" class="hover:text-blue-600">Berita</a></li>
                                <li class="px-2">/</li>
                                <li class="text-blue-600 font-medium">{{ $berita->title }}</li>
                            </ol>
                        </nav>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Main Content -->
                            <div class="lg:col-span-2">
                                <!-- Article Header -->
                                <div class="mb-8">
                                    <h1 class="text-3xl font-bold text-gray-900 mb-3 break-words">{{ $berita->title }}</h1>
                                    <div class="flex items-center text-gray-500 text-sm">
                                        <span class="mr-4 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $berita->created_at ? $berita->created_at->format('d M Y') : date('d M Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            Admin Pesantren
                                        </span>
                                    </div>
                                </div>

                                <!-- Featured Image -->
                                <div class="mb-6 rounded-lg overflow-hidden shadow-lg">
                                    <img src="{{ $berita->image_url }}" class="w-full h-auto object-cover" alt="{{ $berita->title }}">
                                </div>
                                
                                <!-- Article Content -->
                                <article class="prose prose-green max-w-none mb-10">
                                    {!! $berita->description !!}
                                </article>
                                
                                <div class="mt-8 pt-6 border-t border-gray-200">
                                    <h5 class="text-lg font-medium mb-4">Bagikan:</h5>
                                    <div class="flex space-x-3">
                                
                                        {{-- Link Facebook (hanya URL) --}}
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('berita.show', ['hashed_id' => $berita->hashed_id, 'slug' => $berita->slug])) }}" target="_blank" class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white hover:bg-blue-700 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                                            </svg>
                                        </a>
                                
                                        {{-- Link Twitter (hanya URL) --}}
                                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('berita.show', ['hashed_id' => $berita->hashed_id, 'slug' => $berita->slug])) }}" target="_blank" class="w-10 h-10 rounded-full bg-blue-400 flex items-center justify-center text-white hover:bg-blue-500 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
                                                <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>
                                            </svg>
                                        </a>
                                
                                        {{-- Link WhatsApp (HANYA URL SAJA) --}}
                                        {{-- Link WhatsApp (dengan pesan diketik dulu, tidak langsung terkirim) --}}
                                        <a href="https://api.whatsapp.com/send?text={{ urlencode(route('berita.show', ['hashed_id' => $berita->hashed_id, 'slug' => $berita->slug])) }}" target="_blank" class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white hover:bg-green-600 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                                <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                                            </svg>
                                        </a>

                                
                                        {{-- Link Telegram (hanya URL) --}}
                                        <a href="https://t.me/share/url?url={{ urlencode(route('berita.show', ['hashed_id' => $berita->hashed_id, 'slug' => $berita->slug])) }}" target="_blank" class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white hover:bg-blue-600 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-telegram" viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.23.05-.012.12-.026.166.016.047.041.042.12.037.141-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8.154 8.154 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629.093.06.183.125.27.187.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.426 1.426 0 0 0-.013-.315.337.337 0 0 0-.114-.217.526.526 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09z"/>
                                            </svg>
                                        </a>
                                
                                    </div>
                                </div>
                                
                            </div>
                            
                            {{-- Sidebar --}}
<div>
    <div class="sticky top-8">
        <div class="bg-gray-50 rounded-lg p-5 shadow-sm mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200">Berita Terkait</h3>
            
            @if($relatedBerita->count() > 0)
                <div class="space-y-4">
                    @foreach($relatedBerita as $item)
                        {{-- MEMBUNGKUS SELURUH CARD DENGAN <a> --}}
                        <a href="{{ route('berita.show', ['hashed_id' => $item->hashed_id, 'slug' => Str::slug($item->title)]) }}" class="block bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                            
                            {{-- Gambar --}}
                            <div class="h-32 overflow-hidden">
                                <img src="{{ $item->image_url }}" class="w-full h-full object-cover transition duration-300 hover:scale-105" alt="{{ $item->title }}">
                            </div>

                            {{-- Konten Teks & Tombol --}}
                            <div class="p-3">
                                <h5 class="font-medium text-gray-800 hover:text-green-700 line-clamp-2 mb-2">{{ $item->title }}</h5>
                                <div class="flex items-center text-xs text-gray-500 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $item->created_at ? $item->created_at->format('d M Y') : date('d M Y') }}
                                </div>
                                
                                {{-- TOMBOL "BACA SELENGKAPNYA" DIUBAH MENJADI DIV --}}
                                <div class="btn btn-sm btn-outline-success mt-2">
                                    Baca selengkapnya
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">Tidak ada berita terkait.</p>
            @endif
            
            <div class="mt-6">
                <a href="{{ route('berita.index') }}" class="inline-block w-full py-2 px-4 bg-green-600 text-white text-center rounded-md hover:bg-green-700 transition">
                    <div class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Lihat Semua Berita
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>