<?php

namespace App\Filament\Resources\QuotationResource\Pages;

use App\Filament\Resources\QuotationResource;
use App\Filament\Resources\QuotationResource\Widgets\QuotationOverviewWidget;
use App\Models\Quotation;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListQuotations extends ListRecords
{
    protected static string $resource = QuotationResource::class;

    public ?string $activeTab = 'all';

    public function getDefaultActiveTab(): string | int | null
    {
        return 'all';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Quotation')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            QuotationOverviewWidget::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Quotations')
                ->badge(fn () => Quotation::where(function ($q) {
                    $q->whereNull('transaction_type')->orWhere('transaction_type', '!=', 'invoice');
                })->count()),
            'recent' => Tab::make('This Month')
                ->badge(fn () => Quotation::where(function ($q) {
                    $q->whereNull('transaction_type')->orWhere('transaction_type', '!=', 'invoice');
                })->whereMonth('created_at', now()->month)->count())
                ->badgeColor('primary')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereMonth('created_at', now()->month)),
            'invoiced' => Tab::make('Converted to Invoice')
                ->badge(fn () => Quotation::where('transaction_type', 'invoice')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('transaction_type', 'invoice')),
        ];
    }
}
