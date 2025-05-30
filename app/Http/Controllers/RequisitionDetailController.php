<?php

namespace App\Http\Controllers;

use App\Models\RequisitionDetail;
use Illuminate\Http\Request;

class RequisitionDetailController extends Controller
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
            'particular'=>'required',
            'quantity'=>'required|numeric',
            'estimated_cost'=>'required|numeric',
            'expense_category_id'=>'required',
        ];

        $this->validate($request,$rules);

        $saveRequisitionDetail = new RequisitionDetail();

        $saveRequisitionDetail->requisition_id = $request->requisition_id;

        $saveRequisitionDetail->particular = $request->particular;

        $saveRequisitionDetail->reason = $request->reason;

        $saveRequisitionDetail->quantity = $request->quantity;

        $saveRequisitionDetail->estimated_cost = $request->estimated_cost;

        $saveRequisitionDetail->expense_category_id = $request->expense_category_id;

        $saveRequisitionDetail->save();


        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(RequisitionDetail $requisitionDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequisitionDetail $requisitionDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequisitionDetail $requisitionDetail)
    {
        
        $updateRequisition = RequisitionDetail::find($requisitionDetail->id);

        $updateRequisition->particular = $request->particular;

        $updateRequisition->reason = $request->reason;

        $updateRequisition->quantity = $request->quantity;

        $updateRequisition->estimated_cost = $request->estimated_cost;

        $updateRequisition->expense_category_id = $request->expense_category_id;

        $updateRequisition->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequisitionDetail $requisitionDetail)
    {
        //
    }

    function detailAmount($detail_id){
        
        $detail = RequisitionDetail::find($detail_id);

        return $detail->balance($detail->id);
    }
}
