<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hero Section - Fixed</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
        }

        /* Hero Section - Container Utama */
        .hero-section {
            position: relative;
            width: 100%;
            height: 90vh; /* Full viewport height */
            overflow: hidden;
            color: white;
            text-align: center;
            background-color: #333; /* Fallback background */
        }

        /* Slider Container */
        .hero-slider {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        /* Gambar Slider - PERBAIKAN UTAMA */
        .hero-slider .slick-slide {
            height: 90vh; /* Pastikan slide setinggi viewport */
        }

        .hero-slider img {
            width: 100%;
            height: 90vh; /* Tinggi penuh viewport */
            object-fit: cover; /* UBAH dari contain ke cover */
            object-position: center center; /* Posisi gambar di tengah */
            display: block;
        }

        /* Overlay Gelap */
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                rgba(0, 0, 0, 0.3), 
                rgba(0, 0, 0, 0.3)
            ); 
            z-index: 2;
        }

        /* Konten Teks */
        .hero-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            text-align: center;
            max-width: 800px;
            padding: 0 20px;
        }

        .hero-content h1 {
            font-size: 3.2rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.8);
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 1.4rem;
            font-weight: 400;
            color: #f5f5f5;
            margin-top: 1rem;
            text-shadow: 1px 2px 5px rgba(0, 0, 0, 0.8);
            line-height: 1.5;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .hero-content p {
                font-size: 1.2rem;
            }
            
            .hero-section {
                height: 70vh; /* Sedikit lebih pendek di mobile */
            }
            
            .hero-slider img {
                height: 70vh;
            }
        }

        @media (max-width: 480px) {
            .hero-content h1 {
                font-size: 2rem;
            }
            
            .hero-content p {
                font-size: 1rem;
            }
        }

        /* Memastikan tidak ada background kosong */
        .slick-track {
            height: 90vh;
        }

        .slick-slide > div {
            height: 90vh;
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <div class="hero-slider">
            <div>
                <img src="{{ asset('images/hero-1.jpg') }}" alt="Pondok Pesantren Nurul Ulum">
            </div>
            <div>
                <img src="{{ asset('images/hero-2.jpg') }}" alt="SMP Nurul Ulum">
            </div>
            <div>
                <img src="{{ asset('images/hero-8.jpg') }}" alt="SMK Kesehatan Bina Mitra Husada">
            </div>
        </div>
        <div class="hero-content">
            <h1>Mencetak Generasi Qur'ani Berakhlak Mulia</h1>
            <p>Selamat datang di Yayasan Pendidikan Pondok Pesantren Nuru Ulum Wirowongso.</p>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.hero-slider').slick({
                dots: true,
                infinite: true,
                speed: 800,
                fade: true,
                cssEase: 'ease-in-out',
                autoplay: true,
                autoplaySpeed: 4000,
                pauseOnHover: false,
                pauseOnFocus: false,
                arrows: false, // Hilangkan arrow untuk tampilan yang lebih bersih
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            autoplaySpeed: 3000,
                        }
                    }
                ]
            });
        });
    </script>
</body>
</html>