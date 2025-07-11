<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita & Kegiatan - Pesantren Nurul Ulum</title>
    <meta name="description" content="Berita terbaru dan kegiatan santri di Pesantren Nurul Ulum">

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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: white !important;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
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

        .berita-item {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
            margin-bottom: 30px;
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
            height: 200px;
            overflow: hidden;
        }

        .berita-img {
            width: 100%;
            height: 200px;
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

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .pagination .page-link {
            color: var(--primary-color);
        }

        .pagination .page-link:hover {
            color: #025a3e;
        }
    </style>
</head>

<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Berita & Kegiatan') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <!-- Breadcrumbs -->
                        <nav class="mb-3" aria-label="breadcrumb">
                            <ol class="flex space-x-2 text-gray-600 text-sm">
                                <li><a href="{{ route('welcome') }}" class="hover:text-blue-600">Beranda</a></li>
                                <li class="px-2">/</li>
                                <li class="text-blue-600 font-medium">Berita</li>
                            </ol>
                        </nav>

                        <h1 class="text-2xl font-bold mb-6">Berita & Kegiatan Pesantren</h1>
                        <p class="text-gray-600 mb-8">Informasi terbaru seputar kegiatan di Pesantren Nurul Ulum</p>

                        <div class="row g-4">
                            @forelse($beritaItems as $item)
                            <div class="col-md-4 mb-4">
                                <div class="card berita-item h-100 border-0 shadow-sm">
                                    <div class="berita-image-wrapper">
                                        <img src="{{ $item->image_url }}" class="img-fluid berita-img" alt="{{ $item->alt_text ?? $item->title }}">
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title fw-bold">{{ $item->title }}</h5>
                                        @if(isset($item->description))
                                        <p class="card-text text-muted mb-3">{{ Str::limit(strip_tags($item->description), 100) }}</p>
                                        @endif
                                        <div class="mt-auto text-end">
                                            <a href="{{ route('berita.show', ['hashed_id' => $item->hashed_id, 'slug' => Str::slug($item->title)]) }}" class="btn btn-sm btn-outline-success">Baca selengkapnya</a>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-0 text-muted">
                                        <small><i class="bi bi-calendar-event me-2"></i>{{ $item->created_at ? $item->created_at->format('d M Y') : date('d M Y') }}</small>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center py-5">
                                <h3 class="text-xl font-medium text-gray-500">Belum ada berita yang tersedia.</h3>
                                <p class="text-gray-400 mt-2">Silakan kunjungi kembali halaman ini di lain waktu.</p>
                            </div>
                            @endforelse
                        </div>


                        <div class="mt-8 flex justify-center">
                            @if(isset($beritaItems) && method_exists($beritaItems, 'links'))
                            {{ $beritaItems->links() }}
                            @endif
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