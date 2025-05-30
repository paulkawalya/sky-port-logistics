<?php

namespace App\Http\Controllers;

use App\Models\InvoicePayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoicePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = InvoicePayment::paginate(50);

        $data = [
            'payments'=>$payments,
            'title'=>'Invoice Payment'
        ];

        return view('invoices.payments')->with($data);
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

        $savePayment = new InvoicePayment();

        $savePayment->payment_date = $request->payment_date;

        $savePayment->amount_paid = $request->amount_paid;

        $savePayment->mode_of_payment = $request->mode_of_payment;

        $savePayment->narrative = $request->narrative;

        $savePayment->user_id = Auth::id();

        $savePayment->invoice_id = $request->invoice_id;

        $savePayment->save();

        try {

            $particular = 'Payment for Shipment Invoice '.$savePayment->invoice_id.",  ".$savePayment->invoice->shippment->customer->name;

        } catch (\Throwable $th) {

            $particular =  $savePayment->narrative;

        }

        Transaction::saveTransaction($request->payment_account_id,$savePayment->amount_paid,$savePayment->payment_date,Auth::id(),null,'credit',$particular);

        return redirect()->route('invoices.show', $savePayment->invoice_id);

    }

    /**
     * Display the specified resource.
     */
    public function show(InvoicePayment $invoicePayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvoicePayment $invoicePayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvoicePayment $invoicePayment)
    {
        $rules = [
            'payment_date' => 'required|date',
            'amount_paid' => 'required|numeric',
            'mode_of_payment' => 'required|string',
            'narrative' => 'nullable|string',
            'invoice_id' => 'required|exists:invoices,id'
        ];
    
        $this->validate($request, $rules);
    
        $invoicePayment->payment_date = $request->payment_date;
        $invoicePayment->amount_paid = $request->amount_paid;
        $invoicePayment->mode_of_payment = $request->mode_of_payment;
        $invoicePayment->narrative = $request->narrative;
        $invoicePayment->user_id = Auth::id();
        $invoicePayment->invoice_id = $request->invoice_id;
    
        $invoicePayment->save();
    
        return redirect()->route('invoices.show', $invoicePayment->invoice_id)->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoicePayment $payment)
    {
        $invoiceId = $payment->invoice_id;
        
        $payment->delete();

        return redirect()->route('invoices.show', $invoiceId)->with('success', 'Payment deleted successfully.');
    }

}
