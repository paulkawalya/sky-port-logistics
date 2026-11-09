<?php

namespace App\Filament\Resources\TransitResource\Widgets;

use App\Models\Transit;
use App\Models\TransitStatus;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransitOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $total = Transit::count();
        $totalPackages = Transit::withSum('shippments', 'number_of_packages')->get()->sum('shippments_sum_number_of_packages');
        
        // Active transits (not arrived / completed)
        $inTransit = Transit::whereHas('statuses', function ($q) {
            $q->where('status', 'like', '%transit%')
              ->orWhere('status', 'like', '%departed%')
              ->orWhere('status', 'like', '%on sea%');
        })->count();

        $cleared = Transit::whereHas('statuses', function ($q) {
            $q->where('status', 'like', '%warehouse%')
              ->orWhere('status', 'like', '%cleared%')
              ->orWhere('status', 'like', '%delivered%');
        })->count();

        return [
            Stat::make('Total Containers', number_format($total))
                ->description('Air & sea freight shipments')
                ->descriptionIcon('heroicon-m-cube-transparent')
                ->chart([3, 5, 4, 6, 7, max($total, 4)])
                ->color('primary'),

            Stat::make('Active In Transit', number_format($inTransit > 0 ? $inTransit : max(0, $total - $cleared)))
                ->description('En route to destination')
                ->descriptionIcon('heroicon-m-paper-airplane')
                ->chart([2, 4, 3, 5, 4, max($inTransit, 2)])
                ->color('info'),

            Stat::make('Arrived / Cleared', number_format($cleared))
                ->description('Terminal processing completed')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart([1, 3, 2, 4, 5, max($cleared, 3)])
                ->color('success'),

            Stat::make('Packages Loaded', number_format($totalPackages))
                ->description('Consolidated pieces count')
                ->descriptionIcon('heroicon-m-archive-box')
                ->chart([10, 25, 40, 60, 80, max((int)($totalPackages / 10), 20)])
                ->color('warning'),
        ];
    }
}

