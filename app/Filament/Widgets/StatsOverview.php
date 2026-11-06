<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\ManualInvoiceResource;
use App\Filament\Resources\RequisitionResource;
use App\Filament\Resources\ShippmentResource;
use App\Filament\Resources\TransitResource;
use App\Models\Customer;
use App\Models\Quotation;
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
        $inStoreShipments = Shippment::where('cargo_status', 'received_in_store')->count();
        $totalShipments = Shippment::count();
        $totalCustomers = Customer::count();
        $totalContainers = Transit::count();

        $unpaidInvoices = Quotation::where('transaction_type', 'invoice')
            ->get()
            ->filter(fn ($inv) => $inv->balance > 0)
            ->count();

        return [
            Stat::make('Cargo Shipments', number_format($totalShipments))
                ->description($inStoreShipments > 0 ? "{$inStoreShipments} in warehouse store" : "All dispatched / in transit")
                ->descriptionIcon('heroicon-m-truck')
                ->chart([3, 5, 8, 12, 10, 15, $totalShipments > 0 ? $totalShipments : 4])
                ->color('primary')
                ->url(ShippmentResource::getUrl('index')),

            Stat::make('Containers & Transits', number_format($totalContainers))
                ->description('Active ocean & air freight')
                ->descriptionIcon('heroicon-m-cube-transparent')
                ->chart([2, 4, 3, 5, 4, 6, $totalContainers > 0 ? $totalContainers : 3])
                ->color('info')
                ->url(TransitResource::getUrl('index')),

            Stat::make('Client Accounts', number_format($totalCustomers))
                ->description('Active cargo owners & CRM')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->url(CustomerResource::getUrl('index')),

            Stat::make('Pending Requisitions', number_format($pendingReqs))
                ->description($pendingReqs > 0 ? 'Awaiting management sign-off' : 'All budgets approved')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color($pendingReqs > 0 ? 'warning' : 'success')
                ->url(RequisitionResource::getUrl('index', ['activeTab' => 'pending'])),

            Stat::make('Unpaid Invoices', number_format($unpaidInvoices))
                ->description($unpaidInvoices > 0 ? 'Outstanding balance pending' : 'Zero overdue balance')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($unpaidInvoices > 0 ? 'danger' : 'success')
                ->url(ManualInvoiceResource::getUrl('index', ['activeTab' => 'pending'])),
        ];
    }
}
