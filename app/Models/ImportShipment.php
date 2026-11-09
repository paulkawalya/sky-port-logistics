<?php

namespace App\Models;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ImportShipment implements ToCollection
{
    public $transit;
    public int $importedCount = 0;
    public int $invoicesCount = 0;

    public function __construct(Transit $transit) {
        $this->transit = $transit;
    }


    public function collection(Collection $shippments)
    {
        foreach ($shippments->slice(2) as $data)
        {
            // Skip empty records - check for multiple required fields to ensure row has meaningful data
            if(empty($data[0]) || empty($data[2]) || empty($data[3]) || empty($data[5])) {
                continue;
            }

            $customer_name = !empty($data[3]) ? $data[3] : "Unknown";
            $customer_address = !empty($data[4]) ? $data[4] : "Unknown";
            $shipper = !empty($data[2]) ? $data[2] : "Unknown";
            $mark = !empty($data[5]) ? $data[5] : "Unknown";
            $bl_packages = !empty($data[7]) ? $data[7] : "Unknown";
            $number_of_packages = !empty($data[8]) ? $data[8] : 0;
            $unit_of_measure = !empty($data[9]) ? $data[9] : "Unknown";
            $weight = !empty($data[10]) ? $data[10] : 0;
            $volume = !empty($data[11]) ? $data[11] : 0;
            $type = !empty($data[12]) ? $data[12] : "Unknown";
            $description = !empty($data[14]) ? $data[14] : "Unknown";

            // Check for duplicate shipment in the same container
            $existingShipment = Shippment::where('transit_id', $this->transit->id)
                ->where('mark', $mark)
                ->where('shipper', $shipper)
                ->where('description', $description)
                ->first();

            // Skip if duplicate found
            if ($existingShipment) {
                continue;
            }

            $customer = Customer::saveCustomer($customer_name, $customer_address);
            $agent = Agent::saveAgent(!empty($data[6]) ? $data[6] : "Unknown", "Unknown");

            $saveShipment = Shippment::saveShipment(
                $this->transit->id,
                $customer->id,
                $agent->id,
                $shipper,
                $mark,
                $bl_packages,
                $number_of_packages,
                $unit_of_measure,
                $weight,
                $volume,
                $type,
                $description
            );

            if (!empty($saveShipment)) {
                $this->importedCount++;

                // Make Invoice
                $invoice = Invoice::saveInvoice($saveShipment->id);
                if ($invoice) {
                    $this->invoicesCount++;

                    $cleanAmount = function ($val) {
                        if (is_numeric($val)) return (float) $val;
                        if (is_string($val)) {
                            $cleaned = preg_replace('/[^0-9.]/', '', $val);
                            return is_numeric($cleaned) ? (float) $cleaned : 0;
                        }
                        return 0;
                    };

                    $freight = $cleanAmount($data[15] ?? 0);
                    $bl_fee = $cleanAmount($data[16] ?? 0);
                    $others = $cleanAmount($data[17] ?? 0);

                    $invoice_details = [
                        'Freight' => $freight,
                        'Bill of Lading' => $bl_fee,
                        'Others' => $others,
                    ];

                    foreach ($invoice_details as $key => $value) {
                        try {
                            InvoiceDetail::saveDetails($invoice->id, $key, 1, $value);
                        } catch (\Throwable $th) {}
                    }
                }
            }

        }

    }
}
