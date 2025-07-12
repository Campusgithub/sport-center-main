<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
{
    // Update nomor telepon customer jika berubah
    if (isset($data['customer_id']) && isset($data['phone_number'])) {
        $customer = \App\Models\Customer::find($data['customer_id']);
        if ($customer) {
            $customer->phone_number = $data['phone_number'];
            $customer->save();
        }
    }
    // Hapus phone_number dari data transaksi agar tidak error
    unset($data['phone_number']);
    return $data;
}
}
