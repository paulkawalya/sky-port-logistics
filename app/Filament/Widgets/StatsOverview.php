<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ManualInvoiceResource;
use App\Filament\Resources\ShippmentResource;
use App\Filament\Resources\StoreInventoryResource;
use App\Filament\Resources\TransitResource;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Shippment;
use App\Models\Transit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $totalShipments = Shippment::count();
        $inStoreShipments = Shippment::whereIn('cargo_status', ['Received in Store', 'Sorted & Inspected'])->count();
        $readyShipments = Shippment::where('cargo_status', 'Ready for Pickup')->count();
        $totalContainers = Transit::count();

        // Calculate total unpaid receivables (Manual Invoices + Freight Invoices)
        $manualInvoices = Quotation::where('transaction_type', 'invoice')->with('payments')->get();
        $unpaidManualInvoices = $manualInvoices->filter(fn ($inv) => $inv->balance > 0);
        $totalUnpaidBalance = $unpaidManualInvoices->sum('balance');

        $freightInvoices = Invoice::with(['details', 'payments'])->get();
        foreach ($freightInvoices as $fInv) {
            $billed = $fInv->details->sum(fn ($d) => $d->quantity * $d->unit_price);
            $paid = $fInv->payments->sum('amount_paid');
            $bal = max(0, $billed - $paid);
            if ($bal > 0) {
                $totalUnpaidBalance += $bal;
            }
        }

        $unpaidCount = $unpaidManualInvoices->count();

        return [
            Stat::make('Cargo Consignments', number_format($totalShipments))
                ->description($inStoreShipments > 0 ? "{$inStoreShipments} in warehouse store" : "All dispatched / in transit")
                ->descriptionIcon('heroicon-m-truck')
                ->chart([4, 6, 8, 12, 10, 15, max($totalShipments, 8)])
                ->color('primary')
                ->url(ShippmentResource::getUrl('index')),

            Stat::make('Store Inventory', number_format($inStoreShipments + $readyShipments))
                ->description($readyShipments > 0 ? "{$readyShipments} ready for client collection" : "Holding in warehouse")
                ->descriptionIcon('heroicon-m-building-storefront')
                ->chart([2, 5, 4, 7, 6, 8, max($inStoreShipments + $readyShipments, 4)])
                ->color('warning')
                ->url(StoreInventoryResource::getUrl('index')),

            Stat::make('Containers & Transits', number_format($totalContainers))
                ->description('Active ocean & air shipments')
                ->descriptionIcon('heroicon-m-cube-transparent')
                ->chart([3, 4, 3, 6, 5, 7, max($totalContainers, 5)])
                ->color('info')
                ->url(TransitResource::getUrl('index')),

            Stat::make('Outstanding Receivables', '$' . number_format($totalUnpaidBalance, 2))
                ->description($unpaidCount > 0 ? "{$unpaidCount} unpaid invoice" . ($unpaidCount > 1 ? 's' : '') : "All invoices settled")
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([15, 12, 18, 10, 8, 14, max((int)($totalUnpaidBalance / 1000), 5)])
                ->color($totalUnpaidBalance > 0 ? 'danger' : 'success')
                ->url(ManualInvoiceResource::getUrl('index', ['activeTab' => 'pending'])),
        ];
    }
}
