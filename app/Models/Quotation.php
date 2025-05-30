<?php

namespace App\Models;

use App\Traits\HasHashedId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Quotation extends Model
{
    use HasFactory, HasHashedId;

    protected $fillable = [
        'user_id',
        'customer_id',
        'date_quoted',
        'transaction_type',
        'transaction_currency',
        'container_air_no',
        'entry_no',
        'weight',
        'volume',
        'packages',
        'description'
    ];

    function customer() : BelongsTo {
        return $this->belongsTo(Customer::class);
    }

    function details() : HasMany {
        return $this->hasMany(QuotationDetail::class);
    }
 
    function payments() : HasMany {
        return $this->hasMany(QuotationPayment::class);
    }

    function taxquotations() : HasMany {
        return $this->hasMany(QuotationTax::class);
    }

    function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    function amount($quotation_id) {
        return QuotationDetail::where('quotation_id',$quotation_id)->sum(DB::raw('quantity*unit_price'));
    }

    function totalAmount($quotation_id){
        return ($this->amount($quotation_id)+QuotationTax::taxes($quotation_id));
    }

    function paid($quotation_id) {
        return QuotationPayment::where('quotation_id',$quotation_id)->sum('amount_paid');
    }

    function balance($quotation_id){
        return $this->totalAmount($quotation_id) - $this->paid($quotation_id);
    }

    public function getTotalAmountAttribute()
    {
        return $this->details->sum(function($detail) {
            return $detail->quantity * $detail->unit_price;
        });
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments->where('status', 'completed')->sum('amount');
    }

    public function getBalanceAttribute()
    {
        return $this->total_amount - $this->total_paid;
    }

    public function getPaymentStatusAttribute()
    {
        if ($this->balance <= 0) {
            return 'Paid';
        } elseif ($this->total_paid > 0) {
            return 'Partial';
        }
        return 'Unpaid';
    }
}
