<?php

namespace App\Http\Controllers;

use App\Models\QuotationPayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuotationPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'payment_date'=>'required',
            'amount_paid'=>'required|numeric',
            'mode_of_payment'=>'required',
            'payment_account_id'=>'required',
        ];

        $this->validate($request,$rules);

        $savePayment = new QuotationPayment();

        $savePayment->payment_date = $request->payment_date;

        $savePayment->amount_paid = $request->amount_paid;

        $savePayment->mode_of_payment = $request->mode_of_payment;

        $savePayment->narrative = $request->narrative;

        $savePayment->user_id = Auth::id();

        $savePayment->quotation_id = $request->quotation_id;

        $savePayment->save();

        try {

            $particular = 'Payment for Manual Invoice '.$savePayment->quotation_id.",  ".$savePayment->quotation->customer->name;

        } catch (\Throwable $th) {

            $particular =  $savePayment->narrative;

        }

        Transaction::saveTransaction($request->payment_account_id,$savePayment->amount_paid,$savePayment->payment_date,Auth::id(),null,'credit',$particular);

        return redirect()->route('quotations.show', $savePayment->quotation_id);

    }

    /**
     * Display the specified resource.
     */
    public function show(QuotationPayment $quotationPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuotationPayment $quotationPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuotationPayment $quotationPayment)
    {
        $rules = [
            'payment_date' => 'required|date',
            'amount_paid' => 'required|numeric',
            'mode_of_payment' => 'required|string',
            'narrative' => 'nullable|string',
            'quotation_id' => 'required|exists:quotations,id'
        ];
    
        $this->validate($request, $rules);
    
        $quotationPayment->payment_date = $request->payment_date;
        $quotationPayment->amount_paid = $request->amount_paid;
        $quotationPayment->mode_of_payment = $request->mode_of_payment;
        $quotationPayment->narrative = $request->narrative;
        $quotationPayment->user_id = Auth::id();
        $quotationPayment->quotation_id = $request->quotation_id;
    
        $quotationPayment->save();
    
        return redirect()->route('quotations.show', $quotationPayment->quotation_id)->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuotationPayment $quotationPayment)
    {
        //
    }
}
