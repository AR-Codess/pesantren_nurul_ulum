<style>
    .berita-img {
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        width: 100%;
        height: 200px;
        object-fit: cover;
        object-position: center;
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
</style>

<section class="section-padding">
    <div class="container">
        <h2 class="text-center mb-5 text-success">Berita & Kegiatan Santri</h2>
        <div class="row g-4">
            @forelse($beritaItems as $item)
                <div class="col-md-4 mb-4">
                    <div class="card berita-item h-100 border-0 shadow-sm">
                        <div class="berita-image-wrapper">
                            <img src="{{ $item->image_url }}" class="img-fluid berita-img" alt="{{ $item->alt_text ?? $item->title }}">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold line-clamp-2">{{ $item->title }}</h5>
                            @if(isset($item->description))
                            <p class="card-text text-muted mb-3 line-clamp-3">
                                {{ Str::limit(html_entity_decode(strip_tags($item->description)), 120) }}
                            </p>
                            
                            @endif
                            <div class="mt-auto text-end">
                                <a href="{{ route('berita.show', ['hashed_id' => $item->hashed_id, 'slug' => Str::slug($item->title)]) }}" class="btn btn-sm btn-outline-success">Baca selengkapnya</a>
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
            <a href="{{ route('berita.index') }}" class="btn btn-success">Lihat Semua Berita</a>
        </div>
    </div>
</section>