{{-- Layout utama Filament hasil copy, tambahkan SweetAlert2 --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Filament Admin') }}</title>
    @filamentStyles
    @livewireStyles
    @stack('head')
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="filament-body">
    {{ $slot }}
    @filamentScripts
    @livewireScripts
    @stack('scripts')


<script>
    window.addEventListener('swal:success', event => {
        console.log('SweetAlert2 event received', event.detail);
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: event.detail.message,
            timer: 2000,
            showConfirmButton: false
        });
    });
</script>
</body>
</html>
