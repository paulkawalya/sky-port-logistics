<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Requisition;
use App\Models\Shippment;
use App\Models\Transit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $pendingReqs = Requisition::where('status', 'pending')->count();

        return [
            Stat::make('Total Shipments', Shippment::count())
                ->description('All recorded consignments')
                ->descriptionIcon('heroicon-m-truck')
                ->color('primary'),

            Stat::make('Containers & Transits', Transit::count())
                ->description('Active & arrived shipments')
                ->descriptionIcon('heroicon-m-cube-transparent')
                ->color('info'),

            Stat::make('Total Customers', Customer::count())
                ->description('Registered client accounts')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Pending Requisitions', $pendingReqs)
                ->description($pendingReqs > 0 ? 'Requires management approval' : 'All approved')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color($pendingReqs > 0 ? 'warning' : 'success'),
        ];
    }
}
