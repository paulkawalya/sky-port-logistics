<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationPayment extends Model
{
    use HasFactory;

    protected $casts = [
        'payment_date' => 'date',
    ];

    
    protected $fillable = [
        'quotation_id',
        'user_id',
        'payment_date',
        'amount_paid',
        'mode_of_payment',
        'narrative',
        'payment_account_id',
    ];

    function recorder() : BelongsTo {

        return $this->belongsTo(User::class, 'user_id');
        
    }

    function quotation() : BelongsTo {

        return $this->belongsTo(Quotation::class,'quotation_id');
        
    }
}
