<?php

namespace App\Filament\Resources\RequisitionResource\Widgets;

use App\Models\Requisition;
use App\Models\RequisitionPayment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RequisitionStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalCount = Requisition::count();
        $pendingRequisitions = Requisition::where('status', 'pending')->with('details')->get();
        $pendingCount = $pendingRequisitions->count();
        $pendingAmount = $pendingRequisitions->sum(fn ($r) => $r->total_amount);

        $approvedRequisitions = Requisition::where('status', 'approved')->with(['details'])->get();
        $approvedCount = $approvedRequisitions->count();
        $approvedBalance = $approvedRequisitions->sum(fn ($r) => $r->balance);

        $totalDisbursed = RequisitionPayment::sum('amount');

        return [
            Stat::make('Total Requisitions', number_format($totalCount))
                ->description('All recorded budget requests')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->chart([3, 5, 8, 12, 10, max($totalCount, 4)])
                ->color('primary'),

            Stat::make('Pending Approval', number_format($pendingCount))
                ->description('Est. Value: ' . number_format($pendingAmount))
                ->descriptionIcon('heroicon-m-clock')
                ->chart([2, 4, 3, 5, 4, max($pendingCount, 2)])
                ->color($pendingCount > 0 ? 'warning' : 'success'),

            Stat::make('Approved & Pending Payout', number_format($approvedCount))
                ->description('Bal. Due: ' . number_format($approvedBalance))
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart([1, 3, 5, 4, 6, max($approvedCount, 3)])
                ->color('info'),

            Stat::make('Total Disbursed', number_format($totalDisbursed))
                ->description('All time completed payouts')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([5, 12, 18, 24, 30, max((int)($totalDisbursed / 1000000), 10)])
                ->color('success'),
        ];
    }
}

