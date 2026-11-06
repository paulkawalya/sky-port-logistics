<?php

namespace App\Filament\Resources\ManualInvoiceResource\Pages;

use App\Filament\Resources\ManualInvoiceResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateManualInvoice extends CreateRecord
{
    protected static string $resource = ManualInvoiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['transaction_type'] = 'invoice';
        $data['user_id'] = Auth::id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

