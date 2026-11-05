<?php

namespace App\Filament\Resources\ShippmentResource\Pages;

use App\Filament\Resources\ShippmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShippments extends ListRecords
{
    protected static string $resource = ShippmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
