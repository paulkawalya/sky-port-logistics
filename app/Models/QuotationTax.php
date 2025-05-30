<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationTax extends Model
{
    use HasFactory;

    function tax() : BelongsTo {

        return $this->belongsTo(Tax::class);
        
    }

    function quotation() : BelongsTo {

        return $this->belongsTo(Quotation::class);
        
    }

    public static function amount($quotation_tax_id) {

        $quotationTax = QuotationTax::find($quotation_tax_id);

        $invoice_value = $quotationTax->quotation->amount($quotationTax->quotation_id);

        $tax_value = round(((($quotationTax->percentage_tax/100) * $invoice_value) + $quotationTax->amount),2);

        return $tax_value;
        
    }

    public static function taxes($quotation_id){

        $applied_taxes = QuotationTax::where('quotation_id',$quotation_id)->get();

        $total_tax = 0;

        foreach ($applied_taxes as $applied_tax) {

            $total_tax = $total_tax + QuotationTax::amount($applied_tax->id);
            
        }

        return $total_tax;
        
    }
}
