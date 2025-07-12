<style>
    .hero-section {
        position: relative;
        overflow: hidden;
        color: white;
        text-align: center;
        height: 100vh;
    }
    .hero-slider {
        position: relative;
        width: 100%;
        height: 100%;
    }
    .hero-slider img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .hero-slider .slick-dots {
        bottom: 20px;
    }
    .hero-slider .slick-dots li button:before {
        color: white;
    }
    .hero-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10;
        text-align: center;
    }
    .hero-content h1 {
    font-size: 3.5rem;
    font-weight: 700;
    color: #ffffff;
    letter-spacing: 1px;
    margin-bottom: 0.5rem;
    /* Bayangan dibuat semi-transparan (0.6) */
    text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.6); 
}

.hero-content p {
    font-size: 1.4rem;
    font-weight: 400;
    color: #f5f5f5;
    margin-top: 1rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    /* Bayangan juga dibuat semi-transparan (0.6) */
    text-shadow: 1px 2px 5px rgba(0, 0, 0, 0.6); 
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">

<div class="hero-section parallax">
    <div class="hero-slider">
        <div><img src="{{ asset('images/hero-1.jpg') }}" alt="Hero Image 1"></div>
        <div><img src="{{ asset('images/hero-2.jpg') }}" alt="Hero Image 2"></div>
    </div>
    <div class="hero-content">
        <h1 class="display-4">Mencetak Generasi Qur'ani Berakhlak Mulia</h1>
        <p class="lead my-4">Selamat datang di Yayasan Pendidikan Pondok Pesantren Nuru Ulum Wirowongso.</p>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>
    $(document).ready(function(){
        $('.hero-slider').slick({
            dots: true,
            infinite: true,
            speed: 500,
            fade: true,
            cssEase: 'linear',
            autoplay: true,
            autoplaySpeed: 3000,
        });
    });
</script>