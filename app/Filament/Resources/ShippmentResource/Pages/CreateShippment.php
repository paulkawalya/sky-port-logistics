<?php

namespace App\Filament\Resources\ShippmentResource\Pages;

use App\Filament\Resources\ShippmentResource;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateShippment extends CreateRecord
{
    protected static string $resource = ShippmentResource::class;

    protected ?array $invoiceCreationData = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id() ?? 1;

        if (!empty($data['auto_generate_invoice'])) {
            $this->invoiceCreationData = [
                'freight' => (float)($data['invoice_freight'] ?? 0),
                'bl_fee' => (float)($data['invoice_bl_fee'] ?? 0),
                'others' => (float)($data['invoice_others'] ?? 0),
            ];
        }

        unset($data['auto_generate_invoice'], $data['invoice_freight'], $data['invoice_bl_fee'], $data['invoice_others']);

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->invoiceCreationData !== null) {
            $invoice = Invoice::saveInvoice($this->record->id);
            if ($invoice) {
                $freight = $this->invoiceCreationData['freight'];
                $bl = $this->invoiceCreationData['bl_fee'];
                $others = $this->invoiceCreationData['others'];

                InvoiceDetail::saveDetails($invoice->id, 'Freight', 1, $freight);
                InvoiceDetail::saveDetails($invoice->id, 'Bill of Lading', 1, $bl);
                InvoiceDetail::saveDetails($invoice->id, 'Others', 1, $others);

                Notification::make()
                    ->title('Freight Invoice Auto-Generated')
                    ->body("Invoice #{$invoice->id} created with total $" . number_format($freight + $bl + $others, 2))
                    ->success()
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('print')
                            ->label('Print Invoice')
                            ->button()
                            ->url(route('invoices.print', $invoice->id))
                            ->openUrlInNewTab(),
                    ])
                    ->send();
            }
        }
    }
}
