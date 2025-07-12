{{-- filepath: resources/views/components/transaction-actions.blade.php --}}
@php
    $record = $getRecord();
@endphp
<div class="flex items-center gap-1">
    <a href="{{ route('booking.send-wa', $record->id) }}" target="_blank" title="Kirim WhatsApp">
        {{-- Icon WhatsApp --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" viewBox="0 0 448 512" fill="#25D366">
            <path d="M380.9 97.1C339 55.2 283.5 32 224.2 32c-130.3 0-235.9 105.7-235.9 235.9 0 41.6 11.4 82.2 33 117.5L3.4 480l98.9-25.9c33.5 18.4 71.3 28.1 109.9 28.1h.1c130.3 0 235.9-105.7 235.9-235.9 0-59.3-23.2-114.8-65.3-157.1zM224.2 438.6c-33.4 0-66.2-9-94.7-26.1l-6.8-4-58.6 15.3 15.6-57-4.4-7C52.4 332.1 42.2 294.3 42.2 257.9c0-100.4 81.7-182.1 182.1-182.1 48.6 0 94.3 18.9 128.7 53.3s53.3 80.1 53.3 128.7c0 100.4-81.7 182.1-182.1 182.1zm101.7-138.1c-5.6-2.8-33.2-16.4-38.3-18.3-5.1-1.9-8.8-2.8-12.5 2.8s-14.3 18.3-17.6 22.1-6.5 4.2-12.1 1.4c-33.2-16.6-55-29.5-76.8-66.5-5.8-10 5.8-9.3 16.6-30.9 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2s-9.7 1.4-14.7 6.9c-5.1 5.6-19.3 18.8-19.3 45.9s19.8 53.2 22.5 56.9c2.8 3.7 39.1 59.7 94.7 83.7 35.2 15.2 49.1 16.5 66.7 13.9 10.7-1.6 33.2-13.6 37.9-26.7 4.7-13.1 4.7-24.3 3.2-26.7-1.3-2.5-5.1-3.9-10.7-6.6z"/>
        </svg>
    </a>

    <a href="{{ route('transaction.print', $record->id) }}" class="px-2 py-1 rounded text-xs" target="_blank" title="Print">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="#3B82F6" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V4h12v5M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2h-2m-6 0h4"/>
        </svg>
    </a>

    <button onclick="hapusTransaksi({{ $record->id }}, this)" class="px-2 py-1 rounded text-xs flex items-center" title="Hapus" type="button">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="#EF4444" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v3"/>
        </svg>
    </button>

    <a href="{{ route('filament.admin.resources.transactions.edit', ['record' => $record->id]) }}" class="px-2 py-1 rounded text-xs" title="Edit">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="#F59E42" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 1 1 3.182 3.182l-9.193 9.193a2.25 2.25 0 0 1-1.06.592l-3.372.843a.563.563 0 0 1-.686-.686l.843-3.372a2.25 2.25 0 0 1 .592-1.06l9.193-9.193z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5V19.125A1.125 1.125 0 0 1 18.375 20.25H4.875A1.125 1.125 0 0 1 3.75 19.125V5.625A1.125 1.125 0 0 1 4.875 4.5h13.5A1.125 1.125 0 0 1 20.25 5.625v7.875z"/>
        </svg>
    </a>
</div>

@once
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function hapusTransaksi(id, el) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Transaksi yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let component = el.closest('[wire\\:id]');
                    if (component) {
                        let livewireId = component.getAttribute('wire:id');
                        Livewire.find(livewireId).dispatch('deleteTransaction', [[id]]);
                    } else {
                        Livewire.dispatch('deleteTransaction', { id: [id] });
                    }
                }
            });
        }

        window.addEventListener('swal:success', function(e) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: e.detail.message || 'Transaksi berhasil dihapus',
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
@endonce
