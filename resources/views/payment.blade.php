<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Midtrans</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: linear-gradient(135deg, #38bdf8 0%, #22d3ee 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .card h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #0ea5e9;
            margin-bottom: 1.5rem;
        }
        .info {
            font-size: 1.1rem;
            color: #334155;
            margin-bottom: 1.2rem;
        }
        .amount {
            font-size: 2.2rem;
            font-weight: 700;
            color: #22d3ee;
            margin-bottom: 0.5rem;
        }
        .desc {
            font-size: 1rem;
            color: #64748b;
            margin-bottom: 2rem;
        }
        #pay-button {
            background: linear-gradient(90deg, #22d3ee 0%, #38bdf8 100%);
            color: #fff;
            border: none;
            border-radius: 0.75rem;
            padding: 0.9rem 2.2rem;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 2px 8px rgba(34, 211, 238, 0.12);
        }
        #pay-button:hover {
            background: linear-gradient(90deg, #38bdf8 0%, #22d3ee 100%);
        }
        @media (max-width: 500px) {
            .card { padding: 1.2rem 0.5rem; }
            .card h1 { font-size: 1.3rem; }
            .amount { font-size: 1.3rem; }
        }
    </style>
    <script type="text/javascript"
      src="https://app.sandbox.midtrans.com/snap/snap.js"
      data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body>
    <div class="card">
        <h1>Detail Pembayaran</h1>
        <div class="info">Total Tagihan</div>
        <div class="amount">Rp {{ number_format($pembayaran->total_tagihan ?? 0,0,',','.') }}</div>
        <div class="desc">Deskripsi: {{ $pembayaran->deskripsi ?? '-' }}</div>
        <button id="pay-button">Bayar dengan Midtrans</button>
    </div>
    <script type="text/javascript">
      document.getElementById('pay-button').onclick = function() {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                alert('Pembayaran berhasil!');
                window.location.href = '/dashboard';
            },
            onPending: function(result) {
                alert('Pembayaran Anda sedang diproses.');
            },
            onError: function(result) {
                alert('Pembayaran gagal!');
            },
            onClose: function() {
                alert('Anda menutup popup tanpa menyelesaikan pembayaran');
            }
        });
      };
    </script>
</body>
</html>
