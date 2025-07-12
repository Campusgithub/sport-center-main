<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Venue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Notifications\Notification;


class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        // Hanya tampilkan transaksi yang tidak dihapus (isDeleted = 0) dan eager load slots
        return parent::getEloquentQuery()->where('isDeleted', 0)->with('slots');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Select::make('customer_id')
                    ->label('Nama Customer')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),

                \Filament\Forms\Components\TextInput::make('phone_number')
                    ->label('Nomor Telepon')
                    ->default(fn ($record) => $record?->customer?->phone_number)
                    ->required()
                    ->columnSpanFull(),

                \Filament\Forms\Components\Select::make('venue_id')
                    ->label('Venue')
                    ->relationship('venue', 'name')
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),

                \Filament\Forms\Components\DateTimePicker::make('start_time')
                    ->label('Jam Mulai')
                    ->required()
                    ->columnSpanFull(),

                \Filament\Forms\Components\DateTimePicker::make('end_time')
                    ->label('Jam Selesai')
                    ->required()
                    ->columnSpanFull(),

                \Filament\Forms\Components\Select::make('status_transaksi')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->columnSpanFull(),

                \Filament\Forms\Components\TextInput::make('amount')
                    ->label('Total Bayar')
                    ->numeric()
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

public static function table(Table $table): Table
{
    return $table
        ->columns([
            \Filament\Tables\Columns\ViewColumn::make('aksi')
                ->label('Aksi')
                ->view('components.transaction-actions'),

            \Filament\Tables\Columns\TextColumn::make('customer.name')->label('Nama Customer'),
            \Filament\Tables\Columns\TextColumn::make('customer.phone_number')->label('Nomor Telepon'),
            \Filament\Tables\Columns\TextColumn::make('venue.name')->label('Venue'),
            \Filament\Tables\Columns\TextColumn::make('jam')
                ->label('Jam')
                ->getStateUsing(function ($record) {
                    if ($record->slots && $record->slots->count() > 0) {
                        $start = $record->slots->min('start_time');
                        $end = $record->slots->max('end_time');
                        return \Carbon\Carbon::parse($start)->format('H:i') . '—' . \Carbon\Carbon::parse($end)->format('H:i');
                    }
                    return $record->start_time && $record->end_time
                    ? \Carbon\Carbon::parse($record->start_time)->format('H:i') . '—' . \Carbon\Carbon::parse($record->end_time)->format('H:i')
                        : '-';
                }),
            \Filament\Tables\Columns\TextColumn::make('start_time')
                ->label('Tanggal Booking')
                ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('d F Y') : '-'),
            \Filament\Tables\Columns\TextColumn::make('amount')
                ->label('Total Bayar')
                ->formatStateUsing(fn ($state) => 'Rp' . number_format($state, 0, ',', '.')),
            \Filament\Tables\Columns\TextColumn::make('approval_status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger',
                })
                ->sortable(),
        ])
        ->filters([
            \Filament\Tables\Filters\SelectFilter::make('approval_status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                ]),
        ])
        ->actions([
            ActionGroup::make([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (Transaction $record) {
                        $record->update(['approval_status' => 'approved']);
                        Notification::make()
                            ->title('Transaksi Disetujui')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Transaction $record) => $record->approval_status === 'pending'),
                Action::make('print')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('transactions.print', $record->id))
                    ->openUrlInNewTab(),
                Action::make('wa')
                    ->label('Kirim WA')
                    ->icon('heroicon-o-chat-bubble-left')
                    ->url(fn ($record) => route('booking.send-wa', $record->id))
                    ->openUrlInNewTab(),
            ]),
        ]);
}


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
            // 'view' => Pages\ViewTransaction::route('/{record}'),
        ];
    }
}
