<?php

namespace App\Imports;

use App\Models\QuotationDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class QuotationDetailsImport implements ToCollection, WithStartRow
{
    protected $quotationId;

    public function __construct($quotationId)
    {
        $this->quotationId = $quotationId;
    }

    /**
     * Start reading from row 3 (skip header row 1 and spacer row 2)
     */
    public function startRow(): int
    {
        return 3;
    }

    /**
     * Process the collection of rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Skip rows without an item description
            if (empty($row[1]) && empty($row[2])) {
                continue;
            }

            // Combine Manufacturer & Model (B) and Technical Spec (C)
            $modelName = trim($row[1] ?? '');
            $specDetails = trim($row[2] ?? '');
            $particular = $modelName;
            if (!empty($specDetails)) {
                $particular .= ' - ' . $specDetails;
            }

            // Parse Quantity (D)
            $quantity = $this->cleanNumeric($row[3] ?? 0);

            // Parse Unit Price (F)
            $unitPrice = $this->cleanNumeric($row[5] ?? 0);

            // Create detail item record
            QuotationDetail::create([
                'quotation_id' => $this->quotationId,
                'particular'   => $particular,
                'quantity'     => $quantity,
                'unit_price'   => $unitPrice,
            ]);
        }
    }

    /**
     * Clean numeric fields by stripping commas/spaces
     */
    private function cleanNumeric($value)
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        $clean = preg_replace('/[^\d.]/', '', $value);
        return (float) $clean;
    }
}
