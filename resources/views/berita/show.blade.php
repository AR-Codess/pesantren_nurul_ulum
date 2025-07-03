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
    <meta property="og:url" content="{{ route('berita.show', ['id' => $berita->id, 'slug' => $berita->slug]) }}">
    <meta property="og:type" content="article">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="canonical" href="{{ route('berita.show', ['id' => $berita->id, 'slug' => $berita->slug]) }}">
    
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
    
    <section class="berita-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('berita.index') }}" class="text-decoration-none">Berita</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $berita->title }}</li>
                        </ol>
                    </nav>
                    <h1 class="display-5 fw-bold mt-4 mb-4">{{ $berita->title }}</h1>
                    <div class="berita-meta">
                        <span class="me-3"><i class="bi bi-calendar-event me-2"></i>{{ $berita->created_at ? $berita->created_at->format('d M Y') : date('d M Y') }}</span>
                        <span><i class="bi bi-person me-2"></i>Admin Pesantren</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <img src="{{ $berita->image_url }}" class="berita-image" alt="{{ $berita->title }}">
                
                <article class="berita-content">
                    {!! $berita->description !!}
                </article>
                
                <!-- Share Buttons -->
                <div class="share-buttons">
                    <h5 class="mb-3">Bagikan:</h5>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('berita.show', ['id' => $berita->id, 'slug' => $berita->slug])) }}" target="_blank" class="share-facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('berita.show', ['id' => $berita->id, 'slug' => $berita->slug])) }}&text={{ urlencode($berita->title) }}" target="_blank" class="share-twitter">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($berita->title . ' ' . route('berita.show', ['id' => $berita->id, 'slug' => $berita->slug])) }}" target="_blank" class="share-whatsapp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                    <a href="https://t.me/share/url?url={{ urlencode(route('berita.show', ['id' => $berita->id, 'slug' => $berita->slug])) }}&text={{ urlencode($berita->title) }}" target="_blank" class="share-telegram">
                        <i class="bi bi-telegram"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 30px;">
                    <h3 class="related-title">Berita Terkait</h3>
                    
                    @if($relatedBerita->count() > 0)
                        @foreach($relatedBerita as $item)
                            <div class="card berita-item mb-4 border-0 shadow-sm">
                                <div class="berita-image-wrapper">
                                    <img src="{{ $item->image_url }}" class="img-fluid berita-img" alt="{{ $item->title }}">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->title }}</h5>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted"><i class="bi bi-calendar-event me-2"></i>{{ $item->created_at ? $item->created_at->format('d M Y') : date('d M Y') }}</small>
                                        <a href="{{ route('berita.show', ['id' => $item->id, 'slug' => $item->slug]) }}" class="btn btn-sm btn-outline-success">Baca</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Tidak ada berita terkait.</p>
                    @endif
                    
                    <div class="mt-5">
                        <a href="{{ route('berita.index') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Berita
                        </a>
                    </div>
                </div>
            </div>
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