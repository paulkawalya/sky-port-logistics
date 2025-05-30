<?php

namespace App\Http\Controllers;

use App\Models\PaymentAccount;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if(!Auth::user()->canDo(['view_payment_accounts'])) return redirect('home');

        $payment_accounts = PaymentAccount::get();

        $data = [
            'payment_accounts'=>$payment_accounts,
            'title'=>'Payment accounts'
        ];

        return view('invoices.accounts')->with($data);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if(!Auth::user()->canDo(['create_payment_accounts'])) return redirect('home');


        $account_types = ['Cash','Bank','Mobile Money','Digital'];

        $transaction_currencies = ['USD','UGX'];

        $data = [
            'account_types'=>$account_types,
            'transaction_currencies'=>$transaction_currencies,
            'title'=>'Create a payment account'
        ];

        return view('invoices.create_accounts')->with($data);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $rules = [
            'name'=>'required',
            'account_type'=>'required',
            'transaction_currency'=>'required',
        ];

        $this->validate($request,$rules);

        $savePaymentAccount = new PaymentAccount();

        $savePaymentAccount->name = $request->name;

        $savePaymentAccount->account_number = $request->account_number;

        $savePaymentAccount->account_type = $request->account_type;

        $savePaymentAccount->transaction_currency = $request->transaction_currency;

        $savePaymentAccount->others = $request->others;

        $savePaymentAccount->save();

        return redirect()->route('payment_accounts.index');


    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentAccount $paymentAccount)
    {

        $transactions = Transaction::where('payment_account_id',$paymentAccount->id)->paginate(50);

        $data = [
            'transactions'=>$transactions,
            'title'=>'Transactions for '.$paymentAccount->name,
        ];

        return view('transactions.list')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentAccount $paymentAccount)
    {
        if(!Auth::user()->canDo(['edit_payment_accounts'])) return redirect('home');
        
        $account_types = ['cash','bank','mobile_money','digital'];

        $transaction_currencies = ['USD','UGX'];

        $data = [
            'paymentAccount'=>$paymentAccount,
            'title'=>'Edit Payment Account',
            'account_types'=>$account_types,
            'transaction_currencies'=>$transaction_currencies,
        ];

        return view('invoices.edit_accounts')->with($data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentAccount $paymentAccount)
    {

        $savePaymentAccount = PaymentAccount::find($paymentAccount->id);

        $savePaymentAccount->name = $request->name;

        $savePaymentAccount->account_number = $request->account_number;

        $savePaymentAccount->account_type = $request->account_type;

        $savePaymentAccount->transaction_currency = $request->transaction_currency;

        $savePaymentAccount->others = $request->others;

        $savePaymentAccount->save();

        return redirect()->route('payment_accounts.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentAccount $paymentAccount)
    {
        //
    }

    public function updateBalance(Request $request, PaymentAccount $paymentAccount)
{
    // Validate the form input
    $request->validate([
        'amount' => 'required|numeric',
        'reason' => 'required|string',
        'source' => 'required|string',
        'transaction_type' => 'required|in:credit,debit',
    ]);

    // Create a new transaction for this account
    $transaction = new Transaction();
    $transaction->payment_account_id = $paymentAccount->id;
    $transaction->amount = $request->amount;
    $transaction->transaction_type = $request->transaction_type;
     // Use current timestamp for transaction_date
     $transaction->transaction_date = now();

     // Set the user_id to the currently logged-in user
    $transaction->user_id = Auth::id(); // Get the ID of the currently logged-in user

    $transaction->particular = "Reason: " . $request->reason . ", Source: " . $request->source;
    $transaction->save();

    // Update the 'others' field to log the reason and source
    $newDetails = "Reason: " . $request->reason . ", Source: " . $request->source . ", Amount: " . $request->amount;

    $paymentAccount->others = $paymentAccount->others ? $paymentAccount->others . ' | ' . $newDetails : $newDetails;

    $paymentAccount->save();

    return redirect()->route('payment_accounts.index')->with('success', 'Balance updated and transaction recorded successfully');
}


}
