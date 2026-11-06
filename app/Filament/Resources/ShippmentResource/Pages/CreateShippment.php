<?php

namespace App\Filament\Resources\ShippmentResource\Pages;

use App\Filament\Resources\ShippmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateShippment extends CreateRecord
{
    protected static string $resource = ShippmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id() ?? 1;
        return $data;
    }
}
