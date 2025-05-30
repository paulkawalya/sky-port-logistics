<?php

namespace App\Http\Controllers;

use App\Models\InvoiceTax;
use App\Models\Tax;
use Illuminate\Http\Request;

class InvoiceTaxController extends Controller
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
      
        $this->validate($request,['taxes'=>'required']);

        foreach ($request->taxes as $tax_id) {

            $tax = Tax::find($tax_id);

            $saveInvoiceTax = new InvoiceTax();

            $saveInvoiceTax->tax_id = $tax->id;

            $saveInvoiceTax->invoice_id = $request->invoice_id;

            $saveInvoiceTax->percentage_tax = $tax->percentage_tax;

            $saveInvoiceTax->amount = $tax->amount;

            $saveInvoiceTax->save();
             
        }

        return back();
        
    }

    /**
     * Display the specified resource.
     */
    public function show(InvoiceTax $invoiceTax)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvoiceTax $invoiceTax)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvoiceTax $invoiceTax)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($invoiceTax_id)
    {
        InvoiceTax::destroy($invoiceTax_id);

        return back();
    }
}
