@once
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.addEventListener('swal:success', function(e) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: e.detail.message || 'Data berhasil disimpan',
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
@endonce
