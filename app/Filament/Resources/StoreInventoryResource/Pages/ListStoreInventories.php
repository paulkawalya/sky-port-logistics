<?php

namespace App\Filament\Resources\StoreInventoryResource\Pages;

use App\Filament\Resources\StoreInventoryResource;
use App\Models\Shippment;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListStoreInventories extends ListRecords
{
    protected static string $resource = StoreInventoryResource::class;

    public ?string $activeTab = 'in_store';

    public function getDefaultActiveTab(): string | int | null
    {
        return 'in_store';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Intake Inventory')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'in_store' => Tab::make('In Store Inventory')
                ->badge(fn () => Shippment::whereIn('cargo_status', ['Received in Store', 'Sorted & Inspected'])->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('cargo_status', ['Received in Store', 'Sorted & Inspected'])),
            'ready' => Tab::make('Ready for Pickup')
                ->badge(fn () => Shippment::where('cargo_status', 'Ready for Pickup')->count())
                ->badgeColor('primary')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('cargo_status', 'Ready for Pickup')),
            'checked_out' => Tab::make('Checked Out & Gate Pass Log')
                ->badge(fn () => Shippment::where('cargo_status', 'Delivered')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('cargo_status', 'Delivered')),
            'incoming' => Tab::make('Incoming / In Transit')
                ->badge(fn () => Shippment::where('cargo_status', 'In Transit')->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('cargo_status', 'In Transit')),
            'all' => Tab::make('All Consignments')
                ->badge(fn () => Shippment::count()),
        ];
    }
}
