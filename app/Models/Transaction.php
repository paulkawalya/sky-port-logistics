<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    function account($payment_account_id){

        if(empty($payment_account_id)) 
        
            return;
        
        $payment_account = PaymentAccount::find($payment_account_id);

        return $payment_account;
        
    }

    function user() : BelongsTo {

        return $this->belongsTo(User::class,'user_id');
        
    }

    public static function saveTransaction($payment_account_id,$amount,$transaction_date,$user_id,$source_payment_account_id,$transaction_type,$particular){

        $account = PaymentAccount::find($payment_account_id);

        $saveTransaction = new Transaction();    
        
        if(!empty($request->source_payment_account_id)){//destination account

            $balance = $account->balance($account->id);

            if($amount > $balance) 
            
                return;

        }
        
        $saveTransaction->transaction_date = $transaction_date;

        $saveTransaction->user_id = $user_id;

        $saveTransaction->source_payment_account_id = $source_payment_account_id;

        $saveTransaction->payment_account_id = $payment_account_id;

        $saveTransaction->amount = $amount;

        $saveTransaction->particular = $particular;

        $saveTransaction->transaction_type = $transaction_type;

        $saveTransaction->transaction_currency = $account->transaction_currency;

        try {

            $saveTransaction->save();

        } catch (\Throwable $th) {}        

        return $saveTransaction;
        
    }
}
