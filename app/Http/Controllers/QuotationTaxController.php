<?php

namespace App\Http\Controllers;

use App\Models\QuotationTax;
use App\Models\Tax;
use Illuminate\Http\Request;

class QuotationTaxController extends Controller
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

            $saveQuotationTax = new QuotationTax();

            $saveQuotationTax->tax_id = $tax->id;

            $saveQuotationTax->quotation_id = $request->quotation_id;

            $saveQuotationTax->percentage_tax = $tax->percentage_tax;

            $saveQuotationTax->amount = $tax->amount;

            $saveQuotationTax->save();
             
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(QuotationTax $quotationTax)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuotationTax $quotationTax)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuotationTax $quotationTax)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($quotationTax_id)
    {
        QuotationTax::destroy($quotationTax_id);

        return back();
    }
}
