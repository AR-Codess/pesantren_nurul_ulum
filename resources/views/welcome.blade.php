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

        /* Galeri */
        .gallery-img {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .gallery-img:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
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

    <section class="section-padding">
        <div class="container">
            <h2 class="text-center mb-5">Galeri Kegiatan Santri</h2>
            <div class="row g-4">
                <div class="col-md-3"><img src="https://drive.google.com/file/d/16NlgNczyKz23AuN6WCd7wgkstlegWIqv/view?usp=drive_link" class="img-fluid gallery-img" alt="Kegiatan Belajar"></div>
                <div class="col-md-3"><img src="https://images.unsplash.com/photo-1627917482322-a96b150c7635?q=80&w=300&auto=format&fit=crop" class="img-fluid gallery-img" alt="Olahraga Pagi"></div>
                <div class="col-md-3"><img src="https://images.unsplash.com/photo-1594411753713-b5a452136e9c?q=80&w=300&auto=format&fit=crop" class="img-fluid gallery-img" alt="Kajian Sore"></div>
                <div class="col-md-3"><img src="https://images.unsplash.com/photo-1619472322301-73f458693c13?q=80&w=300&auto=format&fit=crop" class="img-fluid gallery-img" alt="Ekstrakurikuler"></div>
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