<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;


    public static function saveDetails($invoice_id,$particular,$quantity,$unit_price) {

        $makeDetail = new InvoiceDetail();

        $makeDetail->invoice_id = $invoice_id;

        $makeDetail->particular = $particular;

        $makeDetail->quantity = $quantity;

        $makeDetail->unit_price = $unit_price;

        $makeDetail->save();

        return $makeDetail;
        
    }
}
