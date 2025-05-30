<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceTax extends Model
{
    use HasFactory;

    function invoice() : BelongsTo {

        return $this->belongsTo(Invoice::class);
        
    }

    function tax() : BelongsTo {

        return $this->belongsTo(Tax::class);
        
    }

    public static function amount($invoice_tax_id) {

        $invoiceTax = InvoiceTax::find($invoice_tax_id);

        $invoice_value = $invoiceTax->invoice->amount($invoiceTax->invoice_id);

        $tax_value = round(((($invoiceTax->percentage_tax/100) * $invoice_value) + $invoiceTax->amount),2);

        return $tax_value;
        
    }

    public static function taxes($invoice_id){

        $applied_taxes = InvoiceTax::where('invoice_id',$invoice_id)->get();

        $total_tax = 0;

        foreach ($applied_taxes as $applied_tax) {

            $total_tax = $total_tax + InvoiceTax::amount($applied_tax->id);
            
        }

        return $total_tax;
        
    }
}
