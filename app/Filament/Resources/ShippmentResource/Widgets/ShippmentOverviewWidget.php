<?php

namespace App\Filament\Resources\ShippmentResource\Widgets;

use App\Models\Shippment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ShippmentOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $total = Shippment::count();
        $inTransit = Shippment::where('cargo_status', 'In Transit')->count();
        $inStore = Shippment::whereIn('cargo_status', ['Received in Store', 'Sorted & Inspected'])->count();
        $delivered = Shippment::where('cargo_status', 'Delivered')->count();

        return [
            Stat::make('Total Consignments', number_format($total))
                ->description('All recorded cargo')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->chart([4, 6, 8, 12, 10, max($total, 5)])
                ->color('primary'),

            Stat::make('Cargo In Transit', number_format($inTransit))
                ->description('Moving to terminal')
                ->descriptionIcon('heroicon-m-truck')
                ->chart([3, 5, 4, 6, 5, max($inTransit, 3)])
                ->color('info'),

            Stat::make('In Warehouse Store', number_format($inStore))
                ->description('Holding in bonded store')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->chart([2, 4, 5, 7, 6, max($inStore, 4)])
                ->color('warning'),

            Stat::make('Released & Delivered', number_format($delivered))
                ->description('Gate pass signed off')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart([5, 8, 10, 12, 15, max($delivered, 6)])
                ->color('success'),
        ];
    }
}
