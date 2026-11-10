<?php

namespace App\Filament\Resources\CustomerResource\Widgets;

use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerOverviewWidget extends BaseWidget
{
    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $total = Customer::count();
        $withShipments = Customer::has('shippments')->count();
        $thisMonth = Customer::whereMonth('created_at', now()->month)->count();
        $activeQuotations = Customer::has('quotations')->count();

        return [
            Stat::make('Total Client Accounts', number_format($total))
                ->description('Registered cargo owners')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart([5, 10, 15, 20, 25, max($total, 10)])
                ->color('primary'),

            Stat::make('Active Consignees', number_format($withShipments))
                ->description('Clients with active shipments')
                ->descriptionIcon('heroicon-m-truck')
                ->chart([3, 7, 10, 14, 18, max($withShipments, 8)])
                ->color('success'),

            Stat::make('Quotation Inquiries', number_format($activeQuotations))
                ->description('Clients with freight quotes')
                ->descriptionIcon('heroicon-m-document-text')
                ->chart([2, 5, 8, 12, 15, max($activeQuotations, 5)])
                ->color('info'),

            Stat::make('New This Month', number_format($thisMonth))
                ->description('Recently onboarded accounts')
                ->descriptionIcon('heroicon-m-sparkles')
                ->chart([1, 2, 4, 3, 5, max($thisMonth, 2)])
                ->color('warning'),
        ];
    }
}

