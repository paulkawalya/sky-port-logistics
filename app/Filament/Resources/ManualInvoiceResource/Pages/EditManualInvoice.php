<?php

namespace App\Filament\Resources\ManualInvoiceResource\Pages;

use App\Filament\Resources\ManualInvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManualInvoice extends EditRecord
{
    protected static string $resource = ManualInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->url(fn () => route('quotations.print', $this->record->id))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
