<?php

namespace App\Filament\Resources\ShippmentResource\Pages;

use App\Filament\Resources\ShippmentResource;
use App\Models\Shippment;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListShippments extends ListRecords
{
    protected static string $resource = ShippmentResource::class;

    public ?string $activeTab = 'all';

    public function getDefaultActiveTab(): string | int | null
    {
        return 'all';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Consignment')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Consignments'),
            'in_transit' => Tab::make('In Transit')
                ->badge(fn () => Shippment::where('cargo_status', 'In Transit')->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('cargo_status', 'In Transit')),
            'in_store' => Tab::make('In Store')
                ->badge(fn () => Shippment::where('cargo_status', 'Received in Store')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('cargo_status', 'Received in Store')),
            'ready' => Tab::make('Ready for Pickup')
                ->badge(fn () => Shippment::where('cargo_status', 'Ready for Pickup')->count())
                ->badgeColor('primary')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('cargo_status', 'Ready for Pickup')),
            'delivered' => Tab::make('Delivered')
                ->badge(fn () => Shippment::where('cargo_status', 'Delivered')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('cargo_status', 'Delivered')),
        ];
    }
}
