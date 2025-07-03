@extends('layouts.app')

@section('title', 'Dashboard Pesantren')

@section('content')
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <title>Profil Pondok Pesantren Nurul Ulum</title>

    <style>
        /* Variabel Warna & Font */
        :root {
            --primary-color: #0A6847; /* Hijau Tua */
            --secondary-color: #F6E9B2; /* Krem/Emas Muda */
            --accent-color: #7ABA78; /* Hijau Muda */
            --text-color: #333333;
            --light-bg: #f8f9fa;
            --font-family: 'Poppins', sans-serif;
        }

        body {
            font-family: var(--font-family);
            color: var(--text-color);
            background-color: #fff;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            color: var(--primary-color);
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            background: url('{{ asset('images/hero-bg.jpg') }}') no-repeat top center;
            background-size: cover;
            color: white;
            padding: 14rem ;
            text-align: center;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5); /* Overlay gelap untuk kontras */
        }
        .hero-section .container {
            position: relative;
            z-index: 1;
        }
        .hero-section h1 {
            color: #fff;
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        }

        /* Tombol Utama */
        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        /* Section Styling */
        .section-padding {
            padding: 80px 0;
        }
        .section-bg {
            background-color: var(--light-bg);
        }

        /* Kartu Program Unggulan */
        .program-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        .program-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }
        .program-card .icon-box {
            width: 70px;
            height: 70px;
            margin: 0 auto 20px auto;
            border-radius: 50%;
            background-color: var(--secondary-color);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .program-card .icon-box i {
            font-size: 2.5rem;
            color: var(--primary-color);
        }
        .program-card .card-title {
            margin-bottom: 15px;
        }

        /* Berita */
        .berita-img {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
            height: 200px; /* Fixed height for uniformity */
            object-fit: cover; /* Ensures images maintain aspect ratio */
            object-position: center; /* Centers the image */
        }
        .berita-img:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .berita-image-wrapper {
            width: 100%;
            height: 200px;
            overflow: hidden;
            border-radius: 10px;
        }
        .berita-item {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Footer */
        .footer {
            background-color: var(--primary-color);
            color: rgba(255, 255, 255, 0.8);
        }
        .footer h6 {
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .footer a:hover {
            color: #fff;
        }
        .footer hr {
            background-color: var(--accent-color);
            height: 2px;
            width: 60px;
            border: none;
        }
        .footer .copyright {
            background-color: rgba(0, 0, 0, 0.2);
        }

        /* Additional Berita Card styling */
        .berita-item {
            transition: all 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .berita-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
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

        /* Unit Pendidikan Section */
        .education-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .education-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        .img-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 15px 15px 0 0;
        }
        .img-wrapper img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            object-position: center;
            transition: transform 0.5s ease;
        }
        .img-wrapper:hover img {
            transform: scale(1.1);
        }
        .overlay-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            width: 100%;
        }
        .akreditasi-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            color: var(--primary-color);
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .program-badge {
            background-color: var(--primary-color);
            color: white;
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 10px;
        }

        /* Facilities Section Styling */
        .heading-line {
            width: 80px;
            height: 4px;
            background-color: var(--accent-color);
            margin-bottom: 20px;
        }
        
        .facilities-section {
            position: relative;
            background-color: var(--light-bg);
        }
        
        .facilities-container {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        }
        
        .facilities-image {
            height: 100%;
            min-height: 400px;
            object-fit: cover;
        }
        
        .facilities-content {
            background-color: white;
            padding: 40px;
            height: 100%;
        }
        
        .facilities-list {
            list-style: none;
            padding-left: 0;
            margin-top: 30px;
        }
        
        .facilities-list li {
            padding: 12px 0 12px 45px;
            position: relative;
            font-size: 1.1rem;
            border-bottom: 1px dashed #eee;
            transition: all 0.3s ease;
        }
        
        .facilities-list li:last-child {
            border-bottom: none;
        }
        
        .facilities-list li:hover {
            transform: translateX(10px);
            color: var(--primary-color);
        }
        
        .facilities-list li .facility-icon {
            position: absolute;
            left: 0;
            top: 10px;
            width: 35px;
            height: 35px;
            background-color: rgba(10, 104, 71, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
        }
        
        .facility-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 20px;
            height: 100%;
            transition: all 0.3s ease;
            border: none;
        }
        
        .facility-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }
        
        .facility-card .icon-container {
            width: 70px;
            height: 70px;
            background-color: rgba(122, 186, 120, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .facility-card .icon-container i {
            font-size: 30px;
            color: var(--primary-color);
        }
        
        .facility-card h5 {
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .facility-card p {
            color: #666;
        }
    </style>
</head>
<body>

    <div class="hero-section">
        <div class="container">
            <h1 class="display-4">Mencetak Generasi Qur'ani Berakhlak Mulia</h1>
            <p class="lead my-4">Selamat datang di Pesantren Nurul Ulum, tempat ilmu, iman, dan amal bersatu.</p>
            <a href="#" class="btn btn-primary btn-lg">Lihat Program Kami</a>
        </div>
    </div>

    <div class="container section-padding">
        <section class="text-center mb-5">
            <h2 class="mb-4">Profil Pesantren Nurul Ulum</h2>
            <p class="lead text-muted mx-auto" style="max-width: 800px;">Didirikan pada tahun 1990, Pesantren Nurul Ulum berkomitmen untuk memberikan pendidikan Islam yang komprehensif, menyeimbangkan antara ilmu agama, pengetahuan umum, dan pengembangan karakter untuk menghadapi tantangan zaman.</p>
        </section>
    </div>

    <section class="section-padding section-bg">
        <div class="container">
            <h2 class="text-center mb-5">Program Unggulan</h2>
            <div class="row text-center g-4">
                <div class="col-md-4">
                    <div class="card program-card p-4">
                        <div class="icon-box">
                            <i class="bi bi-book-half"></i>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Tahfidz Al-Qur'an</h5>
                            <p class="card-text">Program intensif menghafal Al-Qur'an dengan bimbingan ustadz/ustadzah yang berpengalaman.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card program-card p-4">
                        <div class="icon-box">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Kajian Kitab Kuning</h5>
                            <p class="card-text">Mendalami khazanah keilmuan Islam klasik melalui kajian kitab-kitab turats.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card program-card p-4">
                        <div class="icon-box">
                            <i class="bi bi-person-video3"></i>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Pendidikan Formal</h5>
                            <p class="card-text">Menyediakan jenjang pendidikan formal (SMP & SMK) dengan kurikulum terpadu.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding" id="unit-pendidikan">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <h2 class="mb-2">Unit Pendidikan Unggulan Kami</h2>
                    <div class="heading-line mx-auto mb-4"></div>
                    <p class="text-muted mb-5">Menyediakan beragam jenjang pendidikan formal dan keagamaan untuk membentuk generasi Qur'ani dan berakhlak mulia</p>
                </div>
            </div>
            
            <div class="row g-4">
                <!-- SMP Nurul Ulum -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="education-card h-100">
                        <div class="img-wrapper">
                            <img src="{{ asset('images/hero-bg.jpg') }}" class="img-fluid" alt="SMP Nurul Ulum">
                            <div class="overlay-content">
                                <a href="#" class="btn btn-light rounded-pill px-4">Selengkapnya</a>
                            </div>
                            <span class="akreditasi-badge">Akreditasi B</span>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title">SMP Nurul Ulum</h5>
                            <p class="card-text">Pendidikan menengah pertama dengan kurikulum nasional yang diperkaya dengan nilai-nilai keislaman.</p>
                            <div class="d-flex align-items-center mt-3">
                                <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                                <small class="text-muted">Jl. RS. Prawiro No. 1A</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- SMK Kesehatan -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="education-card h-100">
                        <div class="img-wrapper">
                            <img src="{{ asset('images/hero-bg.jpg') }}" class="img-fluid" alt="SMK Kesehatan">
                            <div class="overlay-content">
                                <a href="#" class="btn btn-light rounded-pill px-4">Selengkapnya</a>
                            </div>
                            <span class="akreditasi-badge">Akreditasi B</span>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title">SMK Kesehatan Bina Mitra Husada</h5>
                            <span class="program-badge d-inline-block mb-2">Program Studi: Keperawatan</span>
                            <p class="card-text">Mempersiapkan santri dengan keahlian profesional di bidang kesehatan.</p>
                            <div class="d-flex align-items-center mt-3">
                                <i class="bi bi-buildings-fill text-primary me-2"></i>
                                <small class="text-muted">Fasilitas Lab Modern</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tahfidz Qur'an -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="education-card h-100">
                        <div class="img-wrapper">
                            <img src="{{ asset('images/hero-bg.jpg') }}" class="img-fluid" alt="Tahfidz Qur'an">
                            <div class="overlay-content">
                                <a href="#" class="btn btn-light rounded-pill px-4">Selengkapnya</a>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title">Tahfidz Qur'an</h5>
                            <p class="card-text">Program intensif untuk menghafal Al-Qur'an dengan bimbingan ahli dan metode modern.</p>
                            <div class="d-flex align-items-center mt-3">
                                <i class="bi bi-book-half text-primary me-2"></i>
                                <small class="text-muted">Pembimbing Berpengalaman</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Madrasah Diniyah -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="education-card h-100">
                        <div class="img-wrapper">
                            <img src="{{ asset('images/hero-bg.jpg') }}" class="img-fluid" alt="Madrasah Diniyah">
                            <div class="overlay-content">
                                <a href="#" class="btn btn-light rounded-pill px-4">Selengkapnya</a>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title">Madrasah Diniyah</h5>
                            <p class="card-text">Pendidikan agama Islam komprehensif untuk membentuk karakter santri berakhlak mulia.</p>
                            <div class="d-flex align-items-center mt-3">
                                <i class="bi bi-journal-bookmark text-primary me-2"></i>
                                <small class="text-muted">Kajian Kitab Kuning</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding">
        <div class="container">
            <h2 class="text-center mb-5">Berita & Kegiatan Santri</h2>
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
                                    <a href="{{ route('berita.show', ['id' => $item->id, 'slug' => Str::slug($item->title)]) }}" class="btn btn-sm btn-outline-success">Baca selengkapnya</a>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 text-muted">
                                <small><i class="bi bi-calendar-event me-2"></i>{{ $item->created_at ? $item->created_at->format('d M Y') : date('d M Y') }}</small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>Belum ada berita atau kegiatan yang ditampilkan.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('berita.index') }}" class="btn btn-primary">Lihat Semua Berita</a>
            </div>
        </div>
    </section>

    <section class="facilities-section section-padding section-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <h2 class="mb-2">Fasilitas Lengkap untuk Mendukung Santri</h2>
                    <div class="heading-line mx-auto mb-4"></div>
                    <p class="text-muted mb-5">Pesantren Nurul Ulum menyediakan berbagai fasilitas modern untuk mendukung kegiatan belajar dan kehidupan santri</p>
                </div>
            </div>
            
            <div class="facilities-container">
                <div class="row g-0">
                    <div class="col-lg-6">
                        <img src="{{ asset('images/hero-bg.jpg') }}" alt="Fasilitas Pondok Pesantren" class="facilities-image w-100">
                    </div>
                    <div class="col-lg-6">
                        <div class="facilities-content">
                            <h3 class="mb-4">Fasilitas Unggulan</h3>
                            <p>Kami menyediakan beragam fasilitas untuk menunjang kenyamanan dan keberhasilan pendidikan santri di Pesantren Nurul Ulum Wirowongso.</p>
                            
                            <ul class="facilities-list">
                                <li>
                                    <div class="facility-icon">
                                        <i class="bi bi-house-door-fill"></i>
                                    </div>
                                    <strong>Asrama Pondok yang Nyaman</strong>
                                </li>
                                <li>
                                    <div class="facility-icon">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <strong>Mushola</strong>
                                </li>
                                <li>
                                    <div class="facility-icon">
                                        <i class="bi bi-flask"></i>
                                    </div>
                                    <strong>Laboratorium Keperawatan Modern</strong>
                                </li>
                                <li>
                                    <div class="facility-icon">
                                        <i class="bi bi-heart-pulse"></i>
                                    </div>
                                    <strong>Klinik Insan Medika</strong>
                                </li>
                                <li>
                                    <div class="facility-icon">
                                        <i class="bi bi-shield-plus"></i>
                                    </div>
                                    <strong>Jaminan Kesehatan Santri</strong>
                                </li>
                                <li>
                                    <div class="facility-icon">
                                        <i class="bi bi-laptop"></i>
                                    </div>
                                    <strong>Lab Komputer</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-5 g-4">
                <div class="col-md-4">
                    <div class="facility-card text-center">
                        <div class="icon-container mx-auto">
                            <i class="bi bi-book"></i>
                        </div>
                        <h5>Perpustakaan</h5>
                        <p>Koleksi buku ilmu pengetahuan dan kitab-kitab untuk memperdalam wawasan santri.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="facility-card text-center">
                        <div class="icon-container mx-auto">
                            <i class="bi bi-hospital"></i>
                        </div>
                        <h5>Klinik Kesehatan</h5>
                        <p>Layanan kesehatan dengan dokter berpengalaman untuk menjaga kesehatan santri.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="facility-card text-center">
                        <div class="icon-container mx-auto">
                            <i class="bi bi-dribbble"></i>
                        </div>
                        <h5>Sarana Olahraga</h5>
                        <p>Fasilitas olahraga lengkap untuk mendukung aktivitas fisik dan rekreasi santri.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer pt-5 pb-4">
        <div class="container text-center text-md-start">
            <div class="row">
                <div class="col-md-4 mx-auto mb-4">
                    <h6 class="fw-bold">Pesantren Nurul Ulum</h6>
                    <hr class="mb-4 mt-2 d-inline-block mx-auto mx-md-0">
                    <p>Membina insan cerdas, beriman, dan bertakwa, siap menjadi pemimpin masa depan.</p>
                </div>
                <div class="col-md-2 mx-auto mb-4">
                    <h6 class="fw-bold">Tautan</h6>
                    <hr class="mb-4 mt-2 d-inline-block mx-auto mx-md-0">
                    <p><a href="#!">Profil</a></p>
                    <p><a href="#!">Pendaftaran</a></p>
                    <p><a href="#!">Berita</a></p>
                </div>
                <div class="col-md-4 mx-auto mb-4">
                    <h6 class="fw-bold">Kontak</h6>
                    <hr class="mb-4 mt-2 d-inline-block mx-auto mx-md-0">
                    <p><i class="bi bi-geo-alt-fill me-3"></i>Ds. Wirowongso, Kec. Ajung, Jember, Jawa Timur</p>
                    <p><i class="bi bi-envelope-fill me-3"></i> info@nurululum.ac.id</p>
                    <p><i class="bi bi-telephone-fill me-3"></i> (031) 123-4567</p>
                </div>
            </div>
        </div>
        <div class="text-center p-3 copyright">
            © 2025 Copyright:
            <a href="#">Pesantren Nurul Ulum</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection