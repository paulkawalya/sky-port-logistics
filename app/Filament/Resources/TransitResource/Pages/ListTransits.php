<?php

namespace App\Filament\Resources\TransitResource\Pages;

use App\Filament\Resources\TransitResource;
use App\Filament\Resources\TransitResource\Widgets\TransitOverviewWidget;
use App\Models\Transit;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTransits extends ListRecords
{
    protected static string $resource = TransitResource::class;

    public ?string $activeTab = 'all';

    public function getDefaultActiveTab(): string | int | null
    {
        return 'all';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Container / Transit')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TransitOverviewWidget::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Containers'),
            'unpaid' => Tab::make('Pending Payment')
                ->badge(fn () => Transit::where('payment_status', 'not paid')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_status', 'not paid')),
            'partial' => Tab::make('Partially Paid')
                ->badge(fn () => Transit::where('payment_status', 'partialy paid')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_status', 'partialy paid')),
            'paid' => Tab::make('Fully Settled')
                ->badge(fn () => Transit::where('payment_status', 'paid')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_status', 'paid')),
        ];
    }
}
