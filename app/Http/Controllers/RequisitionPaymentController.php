<?php

namespace App\Http\Controllers;

use App\Models\PaymentAccount;
use App\Models\RequisitionDetail;
use App\Models\RequisitionPayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\returnSelf;

class RequisitionPaymentController extends Controller
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
            'requisition_detail_id'=>'required',
            'payment_date'=>'required',
            'amount_paid'=>'required|numeric',
            'mode_of_payment'=>'required',
            'payment_account_id'=>'required',
            'supplier_id'=>'required',
        ];

        $this->validate($request,$rules);

        $requisitionDetail = RequisitionDetail::find($request->requisition_detail_id); 

        if($request->amount_paid > $requisitionDetail->balance($requisitionDetail->id))

            return back()->with(['bad'=>'Extra payments are not supported']);

        $paymentAccount = PaymentAccount::find($request->payment_account_id);

        if($request->amount_paid > $paymentAccount->balance($paymentAccount->id))

            return back()->with(['bad'=>'There is not enough money in the payout account']);        

        $saveRequisitionPayment = new RequisitionPayment();

        $saveRequisitionPayment->supplier_id = $request->supplier_id;

        $saveRequisitionPayment->user_id = Auth::id();

        $saveRequisitionPayment->requisition_detail_id = $request->requisition_detail_id;

        $saveRequisitionPayment->payment_date = $request->payment_date;

        $saveRequisitionPayment->amount = $request->amount_paid;

        $saveRequisitionPayment->mode_of_payment = $request->mode_of_payment;

        $saveRequisitionPayment->currency = $requisitionDetail->requisition->currency;

        $saveRequisitionPayment->save();

        try {
            $particular = "Payout to ".$saveRequisitionPayment->supplier->name." for ".$saveRequisitionPayment->detail->particular;
        } catch (\Throwable $th) {
            $particular = $saveRequisitionPayment->particular;
        }

        Transaction::saveTransaction($request->payment_account_id,$saveRequisitionPayment->amount,$saveRequisitionPayment->payment_date,$saveRequisitionPayment->user_id,null,$transaction_type="debit",$particular);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(RequisitionPayment $requisitionPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequisitionPayment $requisitionPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequisitionPayment $requisitionPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequisitionPayment $requisitionPayment)
    {
        //
    }
}
