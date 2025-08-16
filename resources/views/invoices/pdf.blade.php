<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $invoiceNumber }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            color: #333;
            background-color: #fff;
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
        .row {
            display: block;
            clear: both;
            margin-bottom: 15px;
        }
        .col {
            width: 48%;
            float: left;
            margin-right: 2%;
            margin-bottom: 20px;
        }
        .col:last-child {
            margin-right: 0;
        }
        h3 {
            background-color: #21503a;
            padding: 8px;
            margin: 0 0 10px 0;
            border-bottom: 1px solid #21503a;
            font-size: 14px;
            color: #fff;
            border-radius: 4px 4px 0 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        table th, table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #e0e0e0;
        }
        table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #2c3e50;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary {
            margin-top: 30px;
            border-top: 1px solid #e0e0e0;
            padding-top: 15px;
        }
        .summary-item {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            display: block;
            clear: both;
        }
        .summary-label {
            float: left;
            width: 70%;
            font-weight: normal;
        }
        .summary-value {
            float: right;
            width: 30%;
            text-align: right;
            font-weight: normal;
        }
        .total {
            font-weight: bold;
            font-size: 14px;
            background-color: #f8f9fa;
            padding: 10px 0;
            border-bottom: 2px solid #21503a;
            color: #e74c3c;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #7f8c8d;
            font-size: 11px;
            border-top: 1px solid #e0e0e0;
            padding-top: 15px;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 11px;
        }
        .status-lunas {
            background-color: #21503a;
            color: white;
        }
        .status-belum-lunas {
            background-color: #f39c12;
            color: white;
        }
        .status-belum-bayar {
            background-color: #e74c3c;
            color: white;
        }
        .clearfix {
            clear: both;
            display: block;
            content: "";
        }
        .detail-table td {
            border: none;
            padding: 5px;
            vertical-align: top;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 25%;
            transform: rotate(-45deg);
            transform-origin: 50% 50%;
            opacity: 0.15;
            font-size: 100px;
            color: #21503a;
            z-index: -1;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section with Logo -->
        <div class="header">
            <div class="logo-container">
                <img src="{{ public_path('images/logo.png') }}" alt="Logo Pesantren Nurul Ulum" class="logo-img">
                <div style="font-size: 24px; font-weight: bold; color: #21503a;">PESANTREN NURUL ULUM</div>
            </div>
            <p>Jl. Rs. Prawiro dirjo no.1a, Wirowongso-Ajung-Jember</p>
            <p>Telp: 082223333841 | Email: Nurululumofficial87@gmail.com</p>
        </div>
        
        @if($remainingBalance == 0)
        <div class="watermark">LUNAS</div>
        @endif
        
        <!-- Invoice Details Section -->
        <div class="row">
            <div class="col">
                <h3>Detail Tagihan SPP</h3>
                <table class="detail-table">
                    <tr>
                        <td style="width: 40%;"><strong>No. Tagihan</strong></td>
                        <td>: {{ $invoiceNumber }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Tagihan</strong></td>
                        <td>: {{ $invoiceDate }}</td>
                    </tr>
                    <tr>
                        <td><strong>Bulan</strong></td>
                        <td>: {{ $periodText }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>: 
                            @php
                                $statusClass = 'status-belum-bayar';
                                $statusText = 'Belum Bayar';
                                
                                if ($remainingBalance == 0) {
                                    $statusClass = 'status-lunas';
                                    $statusText = 'Lunas';
                                } elseif ($totalPaid > 0) {
                                    $statusClass = 'status-belum-lunas';
                                    $statusText = 'Belum Lunas';
                                }
                            @endphp
                            <span class="status {{ $statusClass }}">{{ $statusText }}</span>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="col">
                <h3>Ditagihkan Kepada</h3>
                <table class="detail-table">
                    <tr>
                        <td style="width: 40%;"><strong>Nama Santri</strong></td>
                        <td>: {{ $user->nama_santri }}</td>
                    </tr>
                    <tr>
                        <td><strong>NIS</strong></td>
                        <td>: {{ $user->nis }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kelas</strong></td>
                        <td>: {{ $user->classLevel->level }}</td>
                    </tr>
                    <tr>
                        <td><strong>Beasiswa</strong></td>
                        <td>: {{ $user->is_beasiswa ? 'Ya' : 'Tidak' }}</td>
                    </tr>
                </table>
            </div>
            <div class="clearfix"></div>
        </div>
        
        <!-- Main Bill Summary Section -->
        <h3>Tagihan Pembayaran</h3>
        <table>
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Bulan</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pembayaran SPP</td>
                    <td>{{ $periodText }}</td>
                    <td class="text-right">Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        
        <!-- Installment History Section -->
        <h3>Riwayat Pembayaran</h3>
        @if(count($detailPembayaran) > 0)
            <table>
                <thead>
                    <tr style="background-color: #21503a; color: white;">
                        <th style="width: 8%;">No.</th>
                        <th style="width: 25%;">Tanggal Bayar</th>
                        <th style="width: 25%;">Metode Pembayaran</th>
                        <th style="width: 20%;">Jumlah Dibayar</th>
                        <th style="width: 22%;">Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detailPembayaran as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($detail->tanggal_bayar)->format('d M Y') }}</td>
                        <td>{{ $detail->metode_pembayaran }}</td>
                        <td>Rp {{ number_format($detail->jumlah_dibayar, 0, ',', '.') }}</td>
                        <td>{{ $detail->adminPencatat->name ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center">Belum ada riwayat pembayaran</p>
        @endif
        
        <!-- Summary Section -->
        <div class="summary">
            <div class="summary-item">
                <span class="summary-label">Total Tagihan</span>
                <span class="summary-value">Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</span>
                <div class="clearfix"></div>
            </div>
            <div class="summary-item">
                <span class="summary-label">Total Dibayar</span>
                <span class="summary-value">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
                <div class="clearfix"></div>
            </div>
            <div class="summary-item total">
                <span class="summary-label">Sisa Tagihan</span>
                <span class="summary-value">Rp {{ number_format($remainingBalance, 0, ',', '.') }}</span>
                <div class="clearfix"></div>
            </div>
        </div>
        
        <!-- Footer Section -->
        <div class="footer">
            <p>Terima kasih atas pembayaran Anda. Dokumen ini dihasilkan secara elektronik.</p>
            <p>Untuk pertanyaan, silahkan hubungi bagian keuangan Pesantren Nurul Ulum.</p>
            <p>Dicetak pada: {{ now()->format('d M Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>