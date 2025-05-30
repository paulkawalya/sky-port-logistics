<?php

namespace App\Models;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ImportShipment implements ToCollection
{

    public $transit;

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

            // $agent = Agent::saveAgent($data[6], "Unknmown");

            // $saveShipment = Shippment::saveShipment($this->transit->id,$customer->id,$agent->id,$shipper=$data[2],$mark=$data[5],$bl_packages=$data[7],$number_of_packages=$data[8],$unit_of_measure=$data[9],$weight=$data[10],$volume=$data[11],$type=$data[12],$description=$data[14]);

            // if(!empty($saveShipment)){

                //make Invoice

                $invoice = Invoice::saveInvoice($saveShipment->id);

                $freight = isset($data[15]) ? $data[15] : 0;
                $bl_fee = isset($data[16]) ? $data[16] : 0;
                $others = isset($data[17]) ? $data[17] : 0;

                $invoice_details = [
                    'Freight' => $freight,
                    'Bill of Lading' => $bl_fee,
                    'Others' => $others
                ];

                foreach ($invoice_details as $key => $value) {

                    try {

                        InvoiceDetail::saveDetails($invoice->id,$key,1,$value);

                    } catch (\Throwable $th) {}

                }
            }

        }

    }
}
