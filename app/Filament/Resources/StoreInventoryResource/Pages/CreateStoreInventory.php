<?php

namespace App\Filament\Resources\StoreInventoryResource\Pages;

use App\Filament\Resources\StoreInventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStoreInventory extends CreateRecord
{
    protected static string $resource = StoreInventoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id() ?? 1;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
