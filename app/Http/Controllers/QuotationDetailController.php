<?php

namespace App\Http\Controllers;

use App\Models\QuotationDetail;
use Illuminate\Http\Request;
use App\Imports\QuotationDetailsImport;
use Maatwebsite\Excel\Facades\Excel;

class QuotationDetailController extends Controller
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
            'unit_price'=>'required|numeric'
        ];

        $this->validate($request,$rules);

        $saveDetail = new QuotationDetail();

        $saveDetail->quotation_id = $request->quotation_id;

        $saveDetail->unit_price = $request->unit_price;

        $saveDetail->quantity = $request->quantity;

        $saveDetail->particular = $request->particular;

        $saveDetail->save();

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(QuotationDetail $quotationDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuotationDetail $quotationDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuotationDetail $quotationDetail)
    {
        $saveDetail = QuotationDetail::find($quotationDetail->id);

        $saveDetail->unit_price = $request->unit_price;

        $saveDetail->quantity = $request->quantity;

        $saveDetail->particular = $request->particular;

        $saveDetail->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuotationDetail $quotationDetail)
    {
        QuotationDetail::destroy($quotationDetail->id);

        return back();
    }

    /**
     * Import quotation details from Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'quotation_id' => 'required|exists:quotations,id',
            'excel_file'   => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(
                new QuotationDetailsImport($request->quotation_id), 
                $request->file('excel_file')
            );

            return back()->with('success', 'Quotation items imported successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
