<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoicePayment extends Model
{
    use HasFactory;

    function user() : BelongsTo {

        return $this->belongsTo(User::class);
        
    }

    function invoice() : BelongsTo {

        return $this->belongsTo(Invoice::class);
        
    }
}
