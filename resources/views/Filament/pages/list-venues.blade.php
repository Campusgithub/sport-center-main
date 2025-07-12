@extends('filament-panels::resources.pages.list-records')

@if (session('swal_success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('swal_success')),
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
@endif
