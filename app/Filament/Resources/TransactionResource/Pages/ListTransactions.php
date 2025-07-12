<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use App\Models\Transaction;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;

use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Columns\BadgeColumn;

use App\Filament\Resources\TransactionResource\Tables\Columns\TransactionColumns;


class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    #[On('deleteTransaction')]
    public function deleteTransaction($ids)
    {
        foreach ((array) $ids as $id) {
            $transaction = Transaction::find($id);
            if ($transaction) {
                $transaction->delete();
            }
        }

        $this->dispatch('swal:success', [
            'message' => 'Transaksi berhasil dihapus'
        ]);
        $this->dispatch('$refresh');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function table(Table $table): Table
{
    return $table
        ->columns([
            ViewColumn::make('aksi')
                ->label('Aksi')
                ->view('components.transaction-actions')
                ->getStateUsing(fn ($record) => $record),
            

        Tables\Columns\TextColumn::make('customer.name')->label('Nama Customer'),
        Tables\Columns\TextColumn::make('customer.phone_number')->label('Nomor Telepon'),
        Tables\Columns\TextColumn::make('venue.name')->label('Venue'),
        Tables\Columns\TextColumn::make('jam')
        ->label('Jam')
        ->getStateUsing(fn ($record) => $record->start_time && $record->end_time
            ? \Carbon\Carbon::parse($record->start_time)->format('H:i') . '—' . \Carbon\Carbon::parse($record->end_time)->format('H:i')
            : '-'),
    Tables\Columns\TextColumn::make('start_time')
        ->label('Tanggal Booking')
        ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('d F Y') : '-'),
    Tables\Columns\TextColumn::make('amount')
        ->label('Total Bayar')
        ->formatStateUsing(fn ($state) => 'Rp' . number_format($state, 0, ',', '.')),
    BadgeColumn::make('status_transaksi')
        ->label('Status Pembayaran')
        ->colors([
            'primary' => 'pending',
            'success' => 'paid',
            'danger' => 'cancelled',
        ])
        ->formatStateUsing(function ($state) {
            return match ($state) {
                'pending' => 'Pending',
                'paid' => 'Lunas',
                'cancelled' => 'Batal',
                default => ucfirst($state),
            };
        }),
])

        ->actions([
            Action::make('approve')
        ->label('Setujui')
        ->icon('heroicon-o-check-circle')
        ->color('success')
        ->visible(fn ($record) => $record->status_transaksi === 'pending')
        ->requiresConfirmation()
        ->action(function ($record) {
            $record->update(['status_transaksi' => 'paid']);

            Notification::make()
                ->title('Transaksi disetujui')
                ->success()
                ->send();

            $this->dispatch('$refresh');
        }),

    Action::make('reject')
        ->label('Tolak')
        ->icon('heroicon-o-x-circle')
        ->color('danger')
        ->visible(fn ($record) => $record->status_transaksi === 'pending')
        ->requiresConfirmation()
        ->modalHeading('Tolak Transaksi')
        ->modalSubheading('Apakah Anda yakin ingin menolak transaksi ini? Tindakan ini tidak dapat dibatalkan.')
        ->modalButton('Ya, Tolak')
        
        ->action(function ($record) {
            $record->update(['status_transaksi' => 'cancelled']);

            Notification::make()
                ->title('Transaksi ditolak')
                ->danger()
                ->send();

            $this->dispatch('$refresh');
        }),
        ]);
}


}
