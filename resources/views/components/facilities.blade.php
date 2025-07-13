<style>
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
        object-position: bottom centers;
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
        color: #198754;
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
        color: #198754;
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
        color: #198754;
    }
    .facility-card h5 {
        font-weight: 600;
        margin-bottom: 15px;
    }
    .facility-card p {
        color: #666;
    }

    /* Tambahan CSS untuk efek fade */
    .fade {
        transition: opacity 0.5s ease;
    }
    .fade-out {
        opacity: 0;
    }
    .fade-in {
        opacity: 1;
    }

    /* Tambahan CSS untuk efek slide */
    .slide {
        transition: transform 0.5s ease, opacity 0.5s ease;
        opacity: 1;
        transform: translateX(0);
    }
    .slide-out {
        opacity: 0;
        transform: translateX(-100%);
    }
    .slide-in {
        opacity: 1;
        transform: translateX(0);
    }
</style>

<section class="facilities-section section-padding section-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <h2 class="mb-2 text-success">Fasilitas Lengkap untuk Mendukung Santri</h2>
                <div class="heading-line mx-auto mb-4"></div>
                <p class="text-muted mb-5">Pesantren Nurul Ulum menyediakan berbagai fasilitas modern untuk mendukung kegiatan belajar dan kehidupan santri</p>
            </div>
        </div>
        
        <div class="facilities-container">
            <div class="row g-0">
                <div class="col-lg-6">
                    <img id="facility-image" src="{{ asset('images/2.jpg') }}" alt="Fasilitas Pondok Pesantren" class="facilities-image w-100 fade">
                </div>
                <div class="col-lg-6">
                    <div class="facilities-content">
                        <h3 class="mb-4 text-success">Fasilitas Unggulan</h3>
                        <p class="text-muted">Kami menyediakan beragam fasilitas untuk menunjang kenyamanan dan keberhasilan pendidikan santri di Pesantren Nurul Ulum Wirowongso.</p>
                        
                        <ul id="facility-list" class="facilities-list">
                            <li data-index="0">
                                <div class="facility-icon">
                                    <i class="bi bi-house-door-fill"></i>
                                </div>
                                <strong>Asrama Pondok</strong>
                            </li>
                            <li data-index="1">
                                <div class="facility-icon">
                                    <i class="bi bi-building"></i>
                                </div>
                                <strong>Mushola</strong>
                            </li>
                            <li data-index="2">
                                <div class="facility-icon">
                                    <i class="bi bi-pc-display"></i>
                                </div>
                                <strong>Laboratorium Keperawatan Modern</strong>
                            </li>
                            <li data-index="3">
                                <div class="facility-icon">
                                    <i class="bi bi-heart-pulse"></i>
                                </div>
                                <strong>Klinik Insan Medika</strong>
                            </li>
                            <li data-index="4">
                                <div class="facility-icon">
                                    <i class="bi bi-shield-plus"></i>
                                </div>
                                <strong>Jaminan Kesehatan Santri</strong>
                            </li>
                            <li data-index="5">
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
                    <p class="text-muted">Koleksi buku ilmu pengetahuan dan kitab-kitab untuk memperdalam wawasan santri.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="facility-card text-center">
                    <div class="icon-container mx-auto">
                        <i class="bi bi-hospital"></i>
                    </div>
                    <h5>Klinik Kesehatan</h5>
                    <p class="text-muted">Layanan kesehatan dengan dokter berpengalaman untuk menjaga kesehatan santri.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="facility-card text-center">
                    <div class="icon-container mx-auto">
                        <i class="bi bi-dribbble"></i>
                    </div>
                    <h5>Sarana Olahraga</h5>
                    <p class="text-muted">Fasilitas olahraga lengkap untuk mendukung aktivitas fisik dan rekreasi santri.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Data Fasilitas (sesuaikan path jika perlu)
    const facilities = [
      { title: 'Asrama Pondok', image: "{{ asset('images/asrama.jpg') }}" },
      { title: 'Mushola', image: "{{ asset('images/mushola.jpg') }}" },
      { title: 'Laboratorium Keperawatan Modern', image: "{{ asset('images/keperawatan.jpg') }}" },
      { title: 'Klinik Insan Medika', image: "{{ asset('images/klinik.jpg') }}" },
      { title: 'Jaminan Kesehatan Santri', image: "{{ asset('images/jaminankesehatan.jpg') }}" },
      { title: 'Lab Komputer', image: "{{ asset('images/2.jpg') }}" } // Ganti '2.jpg' dengan gambar yang sesuai
    ];
    
    // Seleksi Elemen DOM
    const facilityImage = document.getElementById('facility-image');
    const facilityListItems = document.querySelectorAll('#facility-list li');
    
    // Variabel untuk melacak indeks saat ini
    let currentIndex = 0;
    let slideshowInterval;
    
    // Perbaikan fungsi updateView untuk animasi yang lebih halus
function updateView(index) {
    // Siapkan gambar berikutnya sebelum transisi
    const nextImage = new Image();
    nextImage.src = facilities[index].image;
    nextImage.onload = () => {
        // Tambahkan animasi transisi yang lebih halus
        facilityImage.style.transition = 'transform 0.8s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.8s ease';
        facilityImage.style.opacity = '0';
        facilityImage.style.transform = 'translateX(-50%) scale(0.8)';

        setTimeout(() => {
            // Ganti gambar setelah transisi selesai
            facilityImage.src = nextImage.src;
            facilityImage.alt = facilities[index].title;

            // Reset gaya transisi
            facilityImage.style.opacity = '1';
            facilityImage.style.transform = 'translateX(0) scale(1)';

            // Perbarui 'active' state pada list
            facilityListItems.forEach(item => item.classList.remove('active'));
            facilityListItems[index].classList.add('active');

            currentIndex = index;
        }, 800); // Durasi transisi keluar
    };
}
    
    // Tambahkan event listener untuk klik manual
    facilityListItems.forEach(item => {
      item.addEventListener('click', () => {
        const index = parseInt(item.getAttribute('data-index'), 10);
        updateView(index);
    
        // Reset timer slideshow otomatis
        clearInterval(slideshowInterval);
        startSlideshow();
      });
    });
    
    // Fungsi untuk memulai slideshow otomatis
    function startSlideshow() {
      slideshowInterval = setInterval(() => {
        const nextIndex = (currentIndex + 1) % facilities.length;
        updateView(nextIndex);
      }, 5000);
    }
    
    // Inisialisasi tampilan awal
    updateView(0);
    startSlideshow();
</script>