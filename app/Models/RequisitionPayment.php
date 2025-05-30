<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequisitionPayment extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // Helper function to get the requisition from a payment
        $getRequisition = function ($payment) {
            if ($payment->detail && $payment->detail->requisition) {
                return $payment->detail->requisition;
            }
            return null;
        };

        // Invalidate requisition cache when a payment is created
        static::created(function ($payment) use ($getRequisition) {
            $requisition = $getRequisition($payment);
            if ($requisition) {
                $requisition->invalidateCache();
            }
        });

        // Invalidate requisition cache when a payment is updated
        static::updated(function ($payment) use ($getRequisition) {
            $requisition = $getRequisition($payment);
            if ($requisition) {
                $requisition->invalidateCache();
            }
        });

        // Invalidate requisition cache when a payment is deleted
        static::deleted(function ($payment) use ($getRequisition) {
            $requisition = $getRequisition($payment);
            if ($requisition) {
                $requisition->invalidateCache();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supplier_id',
        'user_id',
        'requisition_detail_id',
        'payment_date',
        'amount',
        'mode_of_payment',
        'currency',
        'particular'
    ];

    /**
     * Get the supplier for this payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function supplier() : BelongsTo {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Get the user who created this payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function user() : BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the requisition detail for this payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function detail() : BelongsTo {
        return $this->belongsTo(RequisitionDetail::class, 'requisition_detail_id');
    }

    /**
     * Get the requisition for this payment through the detail relationship
     *
     * @return Requisition|null
     */
    public function getRequisitionAttribute() {
        if ($this->detail && $this->detail->requisition) {
            return $this->detail->requisition;
        }
        return null;
    }

    /**
     * Scope a query to only include payments for a specific requisition
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $requisitionId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForRequisition($query, $requisitionId) {
        return $query->whereHas('detail', function($q) use ($requisitionId) {
            $q->where('requisition_id', $requisitionId);
        });
    }

    /**
     * Get total payments for a specific requisition
     *
     * @param int $requisitionId
     * @return float
     */
    public static function totalForRequisition($requisitionId) {
        return self::join('requisition_details', 'requisition_payments.requisition_detail_id', '=', 'requisition_details.id')
            ->where('requisition_details.requisition_id', $requisitionId)
            ->sum('requisition_payments.amount');
    }
}
