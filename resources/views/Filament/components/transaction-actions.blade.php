@props(['record'])

<div class="flex items-center gap-1">
    {{-- Kirim WhatsApp --}}
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $record->customer->phone_number) }}">
    Kirim WA
    </a>

    {{-- Tombol Print --}}
    <a href="{{ route('transaction.print', $record->id) }}"
        class="px-2 py-1 rounded text-xs"
        target="_blank"
        title="Print">
        <!-- SVG -->
    </a>

    {{-- Tombol Hapus --}}
    <button onclick="hapusTransaksi({{ $record->id }}, this)"
        class="px-2 py-1 rounded text-xs"
        title="Hapus"
        type="button">
        <!-- SVG -->
    </button>

    {{-- Tombol Edit --}}
    <a href="{{ route('filament.admin.resources.transactions.edit', ['record' => $record->id]) }}"
        class="px-2 py-1 rounded text-xs"
        title="Edit">
        <!-- SVG -->
    </a>
</div>
