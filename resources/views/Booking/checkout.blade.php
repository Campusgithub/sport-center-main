@extends('layouts.app')

@section('content')
<div class="container py-10">
    <h2 class="text-2xl font-bold mb-4">Konfirmasi Pembayaran</h2>

    <p>Terima kasih sudah booking. Klik tombol di bawah untuk membayar.</p>

    <button id="pay-button"
        class="bg-blue-600 text-white px-6 py-3 rounded mt-4 hover:bg-blue-700">
        Bayar Sekarang
    </button>
</div>

{{-- Snap JS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
    document.getElementById('pay-button').addEventListener('click', function () {
        fetch('/get-snap-token', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                booking_code: '{{ $bookingCode }}'
            })
        })
        .then(res => res.json())
        .then(data => {
            window.snap.pay(data.snapToken, {
                onSuccess: function(result) {
                    alert('Pembayaran sukses!');
                },
                onPending: function(result) {
                    alert('Menunggu pembayaran...');
                },
                onError: function(result) {
                    alert('Pembayaran gagal!');
                },
                onClose: function() {
                    alert('Kamu menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        });
    });
</script>
@endsection
