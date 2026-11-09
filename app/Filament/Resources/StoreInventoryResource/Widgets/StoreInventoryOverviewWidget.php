<?php

namespace App\Filament\Resources\StoreInventoryResource\Widgets;

use App\Models\Shippment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StoreInventoryOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $inStore = Shippment::whereIn('cargo_status', ['Received in Store', 'Sorted & Inspected'])->count();
        $ready = Shippment::where('cargo_status', 'Ready for Pickup')->count();
        
        // Cargo with >14 days in store (demurrage alert)
        $demurrageCount = Shippment::whereIn('cargo_status', ['Received in Store', 'Sorted & Inspected', 'Ready for Pickup'])
            ->whereNotNull('received_in_store_at')
            ->where('received_in_store_at', '<=', now()->subDays(14))
            ->count();

        // Checked out today
        $releasedToday = Shippment::where('cargo_status', 'Delivered')
            ->whereDate('dispatched_at', today())
            ->count();

        return [
            Stat::make('Holding In Store', number_format($inStore))
                ->description('Active warehouse stock')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->chart([3, 5, 4, 7, 6, max($inStore, 4)])
                ->color('warning'),

            Stat::make('Ready for Collection', number_format($ready))
                ->description('Sorted, inspected & cleared')
                ->descriptionIcon('heroicon-m-check-circle')
                ->chart([2, 3, 5, 4, 6, max($ready, 3)])
                ->color('primary'),

            Stat::make('Demurrage Risk (>14d)', number_format($demurrageCount))
                ->description($demurrageCount > 0 ? 'Storage demurrage accruing' : 'Zero overdue cargo')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->chart([1, 2, 3, 2, 4, max($demurrageCount, 1)])
                ->color($demurrageCount > 0 ? 'danger' : 'success'),

            Stat::make('Released Today', number_format($releasedToday))
                ->description('Gate passes dispatched today')
                ->descriptionIcon('heroicon-m-arrow-up-tray')
                ->chart([1, 4, 3, 5, 4, max($releasedToday, 2)])
                ->color('info'),
        ];
    }
}

