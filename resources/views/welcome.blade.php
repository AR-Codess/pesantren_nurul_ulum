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
    </style>
</head>
<body>

    @include('components.hero')

    <div class="container mt-32 mb-10">
        <div class="text-center">
            <h2 class="mb-4">Profil Pesantren Nurul Ulum</h2>
            <p class="lead text-muted mx-auto" style="max-width: 800px;">Didirikan pada tahun 1990, Pesantren Nurul Ulum berkomitmen untuk memberikan pendidikan Islam yang komprehensif, menyeimbangkan antara ilmu agama, pengetahuan umum, dan pengembangan karakter untuk menghadapi tantangan zaman.</p>
        </div>
    </div>

    @include('components.unit_pendidikan')

    @include('components.berita')

    @include('components.facilities')

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection