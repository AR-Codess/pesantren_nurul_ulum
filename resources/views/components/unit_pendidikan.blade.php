<style>
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
</style>

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
                        <img src="{{ asset('images/hero-2.jpg') }}" class="img-fluid" alt="SMP Nurul Ulum">
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
                        <img src="{{ asset('images/4.jpg') }}" class="img-fluid" alt="SMK Kesehatan">
                        <div class="overlay-content">
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
                        <img src="{{ asset('images/6.jpg') }}" class="img-fluid" alt="Tahfidz Qur'an">
                        <div class="overlay-content">
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
                        <img src="{{ asset('images/6.jpg') }}" class="img-fluid" alt="Madrasah Diniyah">
                        <div class="overlay-content">
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