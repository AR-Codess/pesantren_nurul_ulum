{{-- resources/views/pembayaran/midtrans_payment.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran Anda</title>

    {{-- 1. Font dari Google --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- 2. Script Midtrans --}}
    <script type="text/javascript"
            src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
            
    <style>
        :root {
            --primary-color: #007BFF;
            --primary-hover: #0056b3;
            --background-gradient: linear-gradient(135deg, #e0f7fa 0%, #f0f4f8 100%);
            --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            --text-primary: #1a202c;
            --text-secondary: #5a677d;
        }
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: var(--background-gradient);
            color: var(--text-primary);
        }
        .payment-card {
            background-color: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            text-align: center;
            max-width: 400px;
            width: 90%;
            transition: transform 0.3s ease;
        }
        .institution-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-primary);
        }
        .card-header {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 24px;
        }
        .payment-details {
            text-align: left;
            margin-bottom: 24px;
            border-top: 1px solid #e2e8f0;
            padding-top: 24px;
        }
        .detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font-size: 0.95rem;
        }
        .detail-item svg {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            color: var(--primary-color);
            flex-shrink: 0;
        }
        .detail-item .label {
            color: var(--text-secondary);
        }
        .detail-item .value {
            font-weight: 600;
            margin-left: auto;
        }
        .total-amount {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 24px;
        }
        #pay-button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #pay-button:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.3);
        }
        #pay-button:disabled {
            background-color: #a0aec0;
            cursor: not-allowed;
            transform: translateY(0);
            box-shadow: none;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            font-size: 0.9rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s;
        }
        .back-link:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }
        .spinner {
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 2px solid #fff;
            width: 18px;
            height: 18px;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="payment-card">
        <div class="institution-name">Pondok Pesantren Nurul Ulum</div>
        <h1 class="card-header">Konfirmasi Pembayaran</h1>
        
        @php
            $bulanNames = [
                '1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April',
                '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus',
                '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];
            $periode = ($bulanNames[$pembayaran->periode_bulan] ?? '') . ' ' . $pembayaran->periode_tahun;
        @endphp

        <div class="payment-details">
            <div class="detail-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
                <span class="label">Tagihan:</span>
                <span class="value">Tagihan SPP Bulan {{ $periode }}</span>
            </div>
            <div class="detail-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                <span class="label">Periode:</span>
                <span class="value">{{ $periode }}</span>
            </div>
        </div>

        <p class="label">Total Pembayaran</p>
        <div class="total-amount">Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</div>
        
        <button id="pay-button">Bayar Sekarang</button>

        <a href="{{ route('dashboard') }}" class="back-link">Kembali ke Dashboard</a>
    </div>

    <script type="text/javascript">
        const payButton = document.getElementById('pay-button');
        const snapToken = '{{ $snapToken }}';
        const dashboardUrl = "{{ route('dashboard') }}";

        function triggerPayment() {
            payButton.disabled = true;
            payButton.innerHTML = `<div class="spinner"></div><span>Memproses...</span>`;
            
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    // Redirect ke dashboard dengan status sukses
                    window.location.href = dashboardUrl + "?payment_status=success";
                },
                onPending: function(result) {
                    // Redirect ke dashboard dengan status pending
                    window.location.href = dashboardUrl + "?payment_status=pending";
                },
                onError: function(result) {
                    // alert() dihapus, hanya mereset tombol
                    // console.error("Error Result:", result);
                    resetButton();
                },
                onClose: function() {
                    // alert() dihapus, hanya mereset tombol
                    // console.log('Popup ditutup tanpa menyelesaikan pembayaran.');
                    resetButton();
                }
            });
        }
        
        function resetButton() {
            payButton.disabled = false;
            payButton.innerHTML = 'Bayar Sekarang';
        }

        payButton.addEventListener('click', triggerPayment);

        window.onload = function() {
            triggerPayment();
        };
    </script>
</body>
</html>