<?php

namespace App\Http\Controllers;

use App\Models\PaymentAccount;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::paginate(50);

        $data = [
            'transactions'=>$transactions,
            'title'=>'Transactions',
        ];

        return view('transactions.list')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $transaction_types = ['debit','credit'];

        $payment_accounts = PaymentAccount::get();

        $data = [
            'title'=>'Create a transaction',
            'transaction_types'=>$transaction_types,
            'payment_accounts'=>$payment_accounts,
        ];

        return view('transactions.create')->with($data);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $rules = [
            'transaction_date'=>'required',
            'payment_account_id'=>'required',
            'amount'=>'required|numeric',
            'transaction_type'=>'required',
        ];

        $this->validate($request,$rules);

        $account = PaymentAccount::find($request->payment_account_id);

        if(!empty($request->source_payment_account_id))//destination account

           Transaction::saveTransaction($request->source_payment_account_id,$request->amount,$request->transaction_date,Auth::id(),$request->payment_account_id,'credit',$request->particular);       

        Transaction::saveTransaction($request->payment_account_id,$request->amount,$request->transaction_date,Auth::id(),$request->source_payment_account_id,$request->transaction_type,$request->particular);
  
        return redirect()->route('transactions.index');


    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {

        Transaction::destroy($transaction->id);

        return redirect()->route('transactions.index');

    }

    function relatedAccounts($account_id){

        $account = PaymentAccount::find($account_id); 

        return PaymentAccount::where('id','!=',$account->id)->get();
        // return PaymentAccount::where('id','!=',$account->id)->where('transaction_currency',$account->transaction_currency)->get();
        
    }

    function getAccounts($mode_of_payment,$currency) {

        return PaymentAccount::where('account_type',$mode_of_payment)->get();
        // return PaymentAccount::where('transaction_currency',$currency)->where('account_type',$mode_of_payment)->get();
        
    }
}
