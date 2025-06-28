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

    <title>Profil Pondok Pesantren Al-Hikmah</title>
    <style>
        .hero-section {
            background: url('https://via.placeholder.com/1200x400.png?text=Suasana+Pesantren') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 8rem 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Pesantren Al-Hikmah</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Program</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Galeri</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Kontak</a></li>
                    <li class="nav-item"><a class="btn btn-light ms-3" href="#">Pendaftaran</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Mencetak Generasi Qur'ani Berakhlak Mulia</h1>
            <p class="lead my-3">Selamat datang di Pesantren Al-Hikmah, tempat ilmu, iman, dan amal bersatu.</p>
            <a href="#" class="btn btn-primary btn-lg">Lihat Program Kami</a>
        </div>
    </div>

    <div class="container mt-5">
        <section class="text-center mb-5">
            <h2 class="mb-4">Profil Pesantren Al-Hikmah</h2>
            <p class="lead text-muted">Didirikan pada tahun 1990, Pesantren Al-Hikmah berkomitmen untuk memberikan pendidikan Islam yang komprehensif, menyeimbangkan antara ilmu agama, pengetahuan umum, dan pengembangan karakter untuk menghadapi tantangan zaman.</p>
        </section>

        <section class="mb-5">
            <h2 class="text-center mb-4">Program Unggulan</h2>
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-book-half fs-1 text-success"></i>
                            <h5 class="card-title mt-3">Tahfidz Al-Qur'an</h5>
                            <p class="card-text">Program intensif menghafal Al-Qur'an dengan bimbingan ustadz/ustadzah yang berpengalaman.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-journal-bookmark-fill fs-1 text-success"></i>
                            <h5 class="card-title mt-3">Kajian Kitab Kuning</h5>
                            <p class="card-text">Mendalami khazanah keilmuan Islam klasik melalui kajian kitab-kitab turats.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-person-video3 fs-1 text-success"></i>
                            <h5 class="card-title mt-3">Pendidikan Formal</h5>
                            <p class="card-text">Menyediakan jenjang pendidikan formal (SMP & SMA) dengan kurikulum terpadu.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-5">
            <h2 class="text-center mb-4">Galeri Kegiatan Santri</h2>
            <div class="row">
                <div class="col-md-3 mb-3"><img src="https://via.placeholder.com/300x200.png?text=Kegiatan+Belajar" class="img-fluid rounded shadow"></div>
                <div class="col-md-3 mb-3"><img src="https://via.placeholder.com/300x200.png?text=Olahraga+Pagi" class="img-fluid rounded shadow"></div>
                <div class="col-md-3 mb-3"><img src="https://via.placeholder.com/300x200.png?text=Kajian+Sore" class="img-fluid rounded shadow"></div>
                <div class="col-md-3 mb-3"><img src="https://via.placeholder.com/300x200.png?text=Ekstrakurikuler" class="img-fluid rounded shadow"></div>
            </div>
        </section>
    </div>

    <footer class="bg-dark text-white pt-5 pb-4">
        <div class="container text-center text-md-start">
            <div class="row">
                <div class="col-md-4 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold">Pesantren Al-Hikmah</h6>
                    <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: #7c4dff; height: 2px">
                    <p>Membina insan cerdas, beriman, dan bertakwa, siap menjadi pemimpin masa depan.</p>
                </div>
                <div class="col-md-4 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold">Tautan Cepat</h6>
                    <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: #7c4dff; height: 2px">
                    <p><a href="#!" class="text-white">Profil Lengkap</a></p>
                    <p><a href="#!" class="text-white">Info Pendaftaran</a></p>
                    <p><a href="#!" class="text-white">Berita Terbaru</a></p>
                </div>
                <div class="col-md-4 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold">Kontak</h6>
                    <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: #7c4dff; height: 2px">
                    <p><i class="bi bi-geo-alt-fill me-3"></i> Jl. Pesantren No. 123, Surabaya</p>
                    <p><i class="bi bi-envelope-fill me-3"></i> info@alhikmah.ac.id</p>
                    <p><i class="bi bi-telephone-fill me-3"></i> (031) 123-4567</p>
                </div>
            </div>
        </div>
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
            © 2025 Copyright:
            <a class="text-white" href="#">Pesantren Al-Hikmah</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection
