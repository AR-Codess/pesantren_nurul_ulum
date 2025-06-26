@extends('layouts.app')

@section('title', 'Dashboard Pesantren')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Selamat Datang di Dashboard Pesantren</h1>
        <div class="row">
            <!-- Card Info -->
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Jumlah Santri</div>
                    <div class="card-body">
                        <h5 class="card-title">120</h5>
                        <p class="card-text">Total santri terdaftar saat ini.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Jumlah Guru</div>
                    <div class="card-body">
                        <h5 class="card-title">15</h5>
                        <p class="card-text">Guru aktif mengajar di pesantren.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Jumlah Kelas</div>
                    <div class="card-body">
                        <h5 class="card-title">8</h5>
                        <p class="card-text">Kelas tersedia di tahun ajaran ini.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Pembayaran Lunas</div>
                    <div class="card-body">
                        <h5 class="card-title">100</h5>
                        <p class="card-text">Santri sudah lunas pembayaran.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Absensi Dummy -->
        <div class="card mt-4">
            <div class="card-header">
                <b>Data Absensi Hari Ini</b>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Santri</th>
                            <th>Kelas</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Ahmad Fauzi</td>
                            <td>Kelas 1A</td>
                            <td><span class="badge bg-success">Hadir</span></td>
                            <td>2025-06-26</td>
                        </tr>
                        <tr>
                            <td>Muhammad Rizki</td>
                            <td>Kelas 1A</td>
                            <td><span class="badge bg-danger">Tidak Hadir</span></td>
                            <td>2025-06-26</td>
                        </tr>
                        <tr>
                            <td>Siti Aminah</td>
                            <td>Kelas 2B</td>
                            <td><span class="badge bg-success">Hadir</span></td>
                            <td>2025-06-26</td>
                        </tr>
                        <tr>
                            <td>Nur Hidayah</td>
                            <td>Kelas 3C</td>
                            <td><span class="badge bg-success">Hadir</span></td>
                            <td>2025-06-26</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Dummy Pengumuman -->
        <div class="card mt-4">
            <div class="card-header">
                <b>Pengumuman Terbaru</b>
            </div>
            <div class="card-body">
                <ul>
                    <li>Libur pesantren pada tanggal 1 Juli 2025.</li>
                    <li>Pembayaran SPP bulan Juli dibuka mulai 28 Juni 2025.</li>
                    <li>Ujian kenaikan kelas dilaksanakan 15 Juli 2025.</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
