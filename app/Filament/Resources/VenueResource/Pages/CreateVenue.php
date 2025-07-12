<?php

namespace App\Filament\Resources\VenueResource\Pages;

use App\Filament\Resources\VenueResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;


use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;

class CreateVenue extends CreateRecord
{
    protected static string $resource = VenueResource::class;

    // Custom pesan notifikasi Filament, muncul di tengah
    protected function getCreatedNotification(): ?\Filament\Notifications\Notification
    {
        return \Filament\Notifications\Notification::make()
            ->title('Sukses!')
            ->body('Venue berhasil dibuat!')
            ->success()
            ->duration(3000); // posisi default kanan atas
    }
}

