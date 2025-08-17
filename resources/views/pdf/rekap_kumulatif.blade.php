<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekap Absensi Kumulatif</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            background-color: #fff;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #21503a;
            padding-bottom: 15px;
        }

        .logo-container {
            margin-bottom: 10px;
            text-align: center;
        }

        .logo-img {
            height: 80px;
            margin-bottom: 5px;
        }

        .header h1 {
            font-size: 24px;
            margin: 10px 0;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .header p {
            margin: 5px 0;
            color: #34495e;
            font-size: 11px;
        }
        
        h3 {
            background-color: #21503a;
            padding: 8px;
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #fff;
            border-radius: 4px 4px 0 0;
        }
        
        .section-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            padding: 24px;
            margin-bottom: 25px;
            border: 1px solid #e9ecef;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #21503a;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #21503a;
        }

        .info-table {
            width: 100%;
        }

        .info-table td {
            padding: 8px 0;
            font-size: 14px;
            vertical-align: top;
        }

        .info-table td:first-child {
            font-weight: 600;
            color: #495057;
            width: 35%;
        }

        .rekap-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-top: 0;
        }
        
        .rekap-table th, .rekap-table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #e0e0e0;
        }

        .rekap-table thead th {
            background: #21503a;
            color: #fff;
            font-weight: bold;
            padding: 12px 10px;
            font-size: 14px;
            text-align: left;
        }

        .rekap-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .rekap-table tbody tr:hover {
            background: #e9ecef;
            transition: background-color 0.2s ease;
        }
        
        .status-hadir { color: #28a745; font-weight: 600; }
        .status-izin { color: #ffc107; font-weight: 600; }
        .status-sakit { color: #17a2b8; font-weight: 600; }
        .status-alpha { color: #dc3545; font-weight: 600; }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #7f8c8d;
            font-size: 11px;
            border-top: 1px solid #e0e0e0;
            padding-top: 15px;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo-container">
                <img src="{{ public_path('images/logo.png') }}" alt="Logo Pesantren Nurul Ulum" class="logo-img">
                <div style="font-size: 24px; font-weight: bold; color: #21503a;">PESANTREN NURUL ULUM</div>
            </div>
            <p>Jl. Rs. Prawiro dirjo no.1a, Wirowongso-Ajung-Jember</p>
            <p>Telp: 082223333841 | Email: Nurululumofficial87@gmail.com</p>
        </div>

        <div class="section-card">
            <h3>Informasi Kelas</h3>
            <table class="info-table">
                <tr>
                    <td>Nama Kelas</td>
                    <td>: {{ $kelas->nama_kelas ?? $kelas->mata_pelajaran }}</td>
                </tr>
                <tr>
                    <td>Jenjang Kelas</td>
                    <td>: {{ $kelas->classLevels->pluck('level')->join(', ') }}</td>
                </tr>
                <tr>
                    <td>Hari Kelas</td>
                    <td>: {{ $kelas->jadwal_hari ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tahun Ajaran</td>
                    <td>: {{ $kelas->tahun_ajaran ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Nama Guru</td>
                    <td>: {{ optional($kelas->guru)->nama_pendidik ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Total Santri</td>
                    <td>: {{ $kelas->users->count() }}</td>
                </tr>
            </table>
        </div>

        <div class="section-card">
            <h3>Rekapitulasi Absensi</h3>
            <table class="rekap-table">
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Jenjang Kelas</th>
                        <th style="text-align: center;">Hadir</th>
                        <th style="text-align: center;">Izin</th>
                        <th style="text-align: center;">Sakit</th>
                        <th style="text-align: center;">Alpha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekap as $row)
                    <tr>
                        <td>{{ $row['nama'] }}</td>
                        <td>{{ $row['jenjang'] }}</td>
                        <td style="text-align: center;" class="status-hadir">{{ $row['hadir'] }}</td>
                        <td style="text-align: center;" class="status-izin">{{ $row['izin'] }}</td>
                        <td style="text-align: center;" class="status-sakit">{{ $row['sakit'] }}</td>
                        <td style="text-align: center;" class="status-alpha">{{ $row['alpha'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="footer">
            <p>Dokumen ini dihasilkan secara elektronik.</p>
            <p>Untuk pertanyaan, silahkan hubungi bagian Tata Usaha Pesantren Nurul Ulum.</p>
            <p>Dicetak pada: {{ now()->format('d M Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>