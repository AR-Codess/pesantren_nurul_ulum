@extends('layouts.app')

@section('content')
<!-- Bootstrap 5 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="container my-5">
    <div class="card shadow">
        <div class="card-header text-center bg-white border-bottom-0">
            <div class="d-flex flex-column align-items-center">
                <img src="https://via.placeholder.com/100x100.png?text=Logo" alt="Pesantren Logo" class="mb-2" style="height: 80px;">
                <h2 class="fw-bold mb-1">INVOICE</h2>
                <div class="mb-0">
                    <div class="fw-semibold">Pesantren Nurul Ulum</div>
                    <div class="small">Jl. Contoh Alamat No. 123, Kota, Provinsi</div>
                    <div class="small">Telp: (021) 12345678</div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="fw-bold">Detail Invoice</h6>
                    <ul class="list-unstyled mb-0">
                        <li><span class="fw-semibold">No. Invoice:</span> {{ $tagihan->nomor_invoice }}</li>
                        <li><span class="fw-semibold">Tanggal Terbit:</span> {{ \Carbon\Carbon::parse($tagihan->tanggal_terbit)->format('d M Y') }}</li>
                        <li><span class="fw-semibold">Jatuh Tempo:</span> {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d M Y') }}</li>
                        <li>
                            <span class="fw-semibold">Status:</span>
                            @php
                            $badge = 'bg-secondary';
                            if ($tagihan->status === 'LUNAS') $badge = 'bg-success';
                            elseif ($tagihan->status === 'BELUM_LUNAS') $badge = 'bg-warning text-dark';
                            elseif ($tagihan->status === 'JATUH_TEMPO') $badge = 'bg-danger';
                            @endphp
                            <span class="badge {{ $badge }}">
                                @if($tagihan->status === 'LUNAS') Lunas
                                @elseif($tagihan->status === 'BELUM_LUNAS') Belum Lunas
                                @elseif($tagihan->status === 'JATUH_TEMPO') Jatuh Tempo
                                @else {{ $tagihan->status }}
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 text-md-end mt-4 mt-md-0">
                    <h6 class="fw-bold">Tagihan Untuk:</h6>
                    <ul class="list-unstyled mb-0">
                        <li><span class="fw-semibold">Nama:</span> {{ $tagihan->santri->nama_lengkap }}</li>
                        <li><span class="fw-semibold">NIS:</span> {{ $tagihan->santri->nis }}</li>
                        <li><span class="fw-semibold">Kamar/Kelas:</span> {{ $tagihan->santri->kamar->nama }}</li>
                    </ul>
                </div>
            </div>

            <h6 class="fw-bold mb-3">Rincian Tagihan</h6>
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Deskripsi</th>
                            <th>Periode</th>
                            <th class="text-end">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tagihan->items as $item)
                        <tr>
                            <td>{{ $item->deskripsi }}</td>
                            <td>{{ $item->periode }}</td>
                            <td class="text-end">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row justify-content-end mt-4">
                <div class="col-md-6 col-lg-5">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($tagihan->subtotal, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Diskon</span>
                            <span>Rp {{ number_format($tagihan->diskon, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center fw-bold fs-5">
                            <span>Total Tagihan</span>
                            <span>Rp {{ number_format($tagihan->total, 0, ',', '.') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="fw-semibold mb-1">Metode Pembayaran:</div>
                    <ul class="mb-0 ps-3">
                        <li>Transfer Bank Virtual Account</li>
                        <li>QRIS</li>
                    </ul>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="btn btn-primary me-2"><i class="bi bi-credit-card"></i> Bayar Sekarang</a>
                    <a href="#" class="btn btn-secondary"><i class="bi bi-printer"></i> Cetak / Unduh PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection