<?php

namespace App\Http\Controllers;

use App\Models\InvoiceDetail;
use Illuminate\Http\Request;

class InvoiceDetailController extends Controller
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
            'unit_price'=>'required|numeric',
        ];

        $this->validate($request,$rules);

        InvoiceDetail::saveDetails($request->invoice_id,$request->particular,$request->quantity,$request->unit_price);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(InvoiceDetail $invoiceDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvoiceDetail $invoiceDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvoiceDetail $invoiceDetail)
    {
        $updateItem = InvoiceDetail::find($invoiceDetail->id);

        $updateItem->particular = $request->particular;

        $updateItem->quantity = $request->quantity;

        $updateItem->unit_price = $request->unit_price;

        $updateItem->save();

        return redirect()->route('invoices.show',$updateItem->invoice_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoiceDetail $invoiceDetail)
    {
        $invoiceId = $invoiceDetail->invoice_id;
        
        $invoiceDetail->delete();

        return redirect()->route('invoices.show', $invoiceId)->with('success', 'Invoice detail deleted successfully.');
    }
}
