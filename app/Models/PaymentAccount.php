<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAccount extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['balance'];

    /**
     * Get the balance of the account
     *
     * @param int|null $account_id
     * @return float
     */
    function balance($account_id = null) {
        // If no account_id is provided, use the current instance's id
        $account_id = $account_id ?? $this->id;

        $credit = Transaction::where('payment_account_id', $account_id)->where('transaction_type', 'credit')->sum('amount');
        $debit = Transaction::where('payment_account_id', $account_id)->where('transaction_type', 'debit')->sum('amount');

        return ($credit - $debit);
    }

    /**
     * Get the balance attribute
     *
     * @return float
     */
    public function getBalanceAttribute()
    {
        return $this->balance();
    }
}
