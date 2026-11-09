<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\CustomerResource\Widgets\CustomerOverviewWidget;
use App\Models\Customer;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    public ?string $activeTab = 'all';

    public function getDefaultActiveTab(): string | int | null
    {
        return 'all';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Client')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CustomerOverviewWidget::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Clients')
                ->badge(fn () => Customer::count()),
            'with_shipments' => Tab::make('With Cargo')
                ->badge(fn () => Customer::has('shippments')->count())
                ->badgeColor('primary')
                ->modifyQueryUsing(fn (Builder $query) => $query->has('shippments')),
            'with_invoices' => Tab::make('With Invoices')
                ->badge(fn () => Customer::has('invoices')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->has('invoices')),
        ];
    }
}
