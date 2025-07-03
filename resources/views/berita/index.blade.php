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
        
        .berita-item {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
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
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('welcome') }}">Pesantren Nurul Ulum</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('welcome') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('berita.index') }}">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('welcome') }}#tentang-kami">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('welcome') }}#kontak">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <section class="page-title bg-light">
        <div class="container">
            <h1 class="display-4 fw-bold text-center">Berita & Kegiatan</h1>
            <p class="lead text-center text-secondary">Informasi terbaru seputar kegiatan di Pesantren Nurul Ulum</p>
        </div>
    </section>
    
    <div class="container">
        <div class="row">
            @forelse($beritaItems as $item)
                <div class="col-md-4 mb-4">
                    <div class="card berita-item h-100 border-0 shadow-sm">
                        <div class="berita-image-wrapper">
                            <img src="{{ $item->image_url }}" class="img-fluid berita-img" alt="{{ $item->title }}">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold">{{ $item->title }}</h5>
                            @if(isset($item->description))
                                <p class="card-text text-muted mb-3">{{ Str::limit(strip_tags($item->description), 100) }}</p>
                            @endif
                            <div class="mt-auto text-end">
                                <a href="{{ route('berita.show', ['id' => $item->id, 'slug' => $item->slug]) }}" class="btn btn-sm btn-outline-success">Baca selengkapnya</a>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 text-muted">
                            <small><i class="bi bi-calendar-event me-2"></i>{{ $item->created_at ? $item->created_at->format('d M Y') : date('d M Y') }}</small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h3>Belum ada berita yang tersedia.</h3>
                    <p>Silakan kunjungi kembali halaman ini di lain waktu.</p>
                </div>
            @endforelse
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $beritaItems->links() }}
        </div>
    </div>
    
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Pesantren Nurul Ulum</h5>
                    <p>Mendidik generasi berakhlak mulia, berilmu, dan berdedikasi.</p>
                </div>
                <div class="col-md-3">
                    <h5>Navigasi</h5>
                    <ul class="list-unstyled">
                        <li><a class="text-white" href="{{ route('welcome') }}">Beranda</a></li>
                        <li><a class="text-white" href="{{ route('berita.index') }}">Berita</a></li>
                        <li><a class="text-white" href="{{ route('welcome') }}#tentang-kami">Tentang Kami</a></li>
                        <li><a class="text-white" href="{{ route('welcome') }}#kontak">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-geo-alt me-2"></i> Alamat Pesantren</li>
                        <li><i class="bi bi-telephone me-2"></i> (021) 1234-5678</li>
                        <li><i class="bi bi-envelope me-2"></i> info@nurululum.sch.id</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-light">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Pesantren Nurul Ulum. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>