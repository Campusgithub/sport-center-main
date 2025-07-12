<html>
<head>
    <title>Cetak Struk</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .struk {
            max-width: 350px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 24px 20px 16px 20px;
        }
        .logo {
            display: block;
            margin: 0 auto 8px auto;
            width: 60px;
        }
        .center { text-align: center; }
        .divider { border-top: 1px solid #bbb; margin: 12px 0; }
        .row { display: flex; justify-content: space-between; margin-bottom: 6px; }
        .label { font-weight: bold; }
        .qr { display: block; margin: 12px auto 0 auto; width: 100px; }
        .thanks { margin-top: 18px; text-align: center; font-weight: bold; }
        .note { text-align: center; font-size: 13px; margin-top: 4px; }
    </style>
</head>
<body onload="window.print()">
    <div class="struk">
        <img src="{{ asset('storage/image/Sport Center.png') }}" class="logo" alt="Logo Sport Center">
        <div class="center" style="font-size: 20px; font-weight: bold;">SPORT CENTER</div>
        <div class="center" style="font-size: 13px;">Jl. Inspeksi Kalimalang, Tegal Danas, Cikarang Pusat, Bekasi<br>Telp: 081234567890</div>
        <div class="divider"></div>
        <div class="center" style="font-size: 15px; font-weight: bold; margin-bottom: 10px;">STRUK PEMESANAN LAPANGAN</div><br>
        <div class="row"><span class="label">Kode Booking</span><span>{{ $transaction->ticket_code ?? '-' }}</span></div>
        <div class="row"><span class="label">Nama</span><span>{{ $transaction->customer->name }}</span></div>
        <div class="row"><span class="label">Telepon</span><span>{{ $transaction->customer->phone_number }}</span></div>
        <div class="row"><span class="label">Lapangan</span><span>{{ $transaction->venue->name }}</span></div>
        <div class="row"><span class="label">Tanggal</span><span>{{ \Carbon\Carbon::parse($transaction->start_time)->format('d F Y') }}</span></div>
        <div class="row"><span class="label">Jam</span><span>{{ \Carbon\Carbon::parse($transaction->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($transaction->end_time)->format('H:i') }} WIB</span></div>
        <div class="row"><span class="label">Metode Bayar</span><span>{{ $transaction->payment_method ?? 'Midtrans - QRIS' }}</span></div>
        <div class="row"><span class="label">Status</span><span>{{ ucfirst($transaction->status_transaksi ?? '-') }}</span></div>
        <div class="row"><span class="label">Total Bayar</span><span>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span></div>
        <div class="divider"></div>
        <div class="thanks">Terima kasih<br>atas pemesanannya!</div>
        <div class="center">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $transaction->ticket_code ?? '' }}" class="qr" alt="QR Code">
        </div>
        <div class="note">Tunjukkan struk ini saat datang</div>
    </div>
</body>
</html>