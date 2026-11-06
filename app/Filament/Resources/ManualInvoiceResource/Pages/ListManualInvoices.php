<?php

namespace App\Filament\Resources\ManualInvoiceResource\Pages;

use App\Filament\Resources\ManualInvoiceResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListManualInvoices extends ListRecords
{
    protected static string $resource = ManualInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Manual Invoice'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Invoices'),
            'pending' => Tab::make('Pending / Unpaid')
                ->badge(fn () => ManualInvoiceResource::getEloquentQuery()->get()->filter(fn ($inv) => $inv->balance > 0)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereRaw('
                    (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM quotation_details WHERE quotation_details.quotation_id = quotations.id)
                    >
                    (SELECT COALESCE(SUM(amount_paid), 0) FROM quotation_payments WHERE quotation_payments.quotation_id = quotations.id)
                ')),
            'paid' => Tab::make('Fully Paid')
                ->badge(fn () => ManualInvoiceResource::getEloquentQuery()->get()->filter(fn ($inv) => $inv->total_amount > 0 && $inv->balance <= 0)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereRaw('
                    (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM quotation_details WHERE quotation_details.quotation_id = quotations.id) > 0
                    AND
                    (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM quotation_details WHERE quotation_details.quotation_id = quotations.id)
                    <=
                    (SELECT COALESCE(SUM(amount_paid), 0) FROM quotation_payments WHERE quotation_payments.quotation_id = quotations.id)
                ')),
        ];
    }
}
