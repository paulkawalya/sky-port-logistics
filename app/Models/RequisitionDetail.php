<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class RequisitionDetail extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // Invalidate requisition cache when a detail is created
        static::created(function ($detail) {
            if ($detail->requisition) {
                $detail->requisition->invalidateCache();
            }
        });

        // Invalidate requisition cache when a detail is updated
        static::updated(function ($detail) {
            if ($detail->requisition) {
                $detail->requisition->invalidateCache();
            }
        });

        // Invalidate requisition cache when a detail is deleted
        static::deleted(function ($detail) {
            if ($detail->requisition) {
                $detail->requisition->invalidateCache();
            }
        });
    }

    function category() : BelongsTo {

        return $this->belongsTo(ExpenseCategory::class,'expense_category_id');

    }

    function requisition() : BelongsTo {

        return $this->belongsTo(Requisition::class,'requisition_id');

    }

    /**
     * Get the amount for a requisition detail
     *
     * @param int $requisition_detail_id
     * @return float
     */
    function amount($requisition_detail_id) {
        // Use a single query to get the amount
        $detail = RequisitionDetail::select(DB::raw('quantity * estimated_cost as total_amount'))
            ->where('id', $requisition_detail_id)
            ->first();

        return $detail ? $detail->total_amount : 0;
    }

    /**
     * Get the amount for the current requisition detail instance
     *
     * @return float
     */
    public function getAmountAttribute() {
        return $this->quantity * $this->estimated_cost;
    }

    /**
     * Get the total paid amount for a requisition detail
     *
     * @param int $requisition_detail_id
     * @return float
     */
    function paid($requisition_detail_id) {
        return RequisitionPayment::where('requisition_detail_id', $requisition_detail_id)
            ->sum('amount');
    }

    /**
     * Get the total paid amount for the current requisition detail instance
     *
     * @return float
     */
    public function getPaidAmountAttribute() {
        return RequisitionPayment::where('requisition_detail_id', $this->id)
            ->sum('amount');
    }

    /**
     * Get the balance for a requisition detail
     *
     * @param int $requisition_detail_id
     * @return float
     */
    function balance($requisition_detail_id) {
        return $this->amount($requisition_detail_id) - $this->paid($requisition_detail_id);
    }

    /**
     * Get the balance for the current requisition detail instance
     *
     * @return float
     */
    public function getBalanceAttribute() {
        return $this->amount - $this->paid_amount;
    }

    /**
     * Get the payments for this requisition detail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments() {
        return $this->hasMany(RequisitionPayment::class, 'requisition_detail_id');
    }
}
