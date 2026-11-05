<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'requisition_id',
        'particular',
        'reason',
        'quantity',
        'estimated_cost',
    ];

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public static function saveExpense($particular,$reason,$quantity,$estimated_cost,$requisition_id) {

        $expenses = Expense::where('requisition_id',$requisition_id)->get();

        if($expenses->count() == 1) return;

        $saveExpense = new Expense();

        $saveExpense->user_id = Auth::id();

        $saveExpense->particular = $particular;

        $saveExpense->reason = $reason;

        $saveExpense->quantity = $quantity;

        $saveExpense->estimated_cost = $estimated_cost;

        $saveExpense->requisition_id = $requisition_id;

        $saveExpense->save();
        
    }
}
