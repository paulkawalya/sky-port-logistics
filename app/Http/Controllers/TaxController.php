<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if(!Auth::user()->canDo(['view_taxes'])) return redirect('home');

        $taxes = Tax::get();

        $data = [
            'taxes'=>$taxes,
            'title'=>'Taxes'
        ];

        return view('tax.taxes')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if(!Auth::user()->canDo(['create_taxes'])) return redirect('home');

        $data = [
            'title'=>'Create Taxes'
        ];

        return view('tax.create_tax')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'tax_name'=>'required',
        ];

        $this->validate($request,$rules);

        $saveUser = new Tax();

        $saveUser->tax_name = $request->tax_name;

        $saveUser->percentage_tax = $request->percentage_tax;

        $saveUser->amount = $request->amount;

        $saveUser->currency = $request->currency;

        $saveUser->save();

        return redirect()->route('taxes.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(Tax $tax)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tax $tax)
    {

        if(!Auth::user()->canDo(['edit_taxes'])) return redirect('home');

        $data = [
            'tax'=>$tax,
            'title'=>'Edit Tax'
        ];

        return view('tax.edit_tax')->with($data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tax $tax)
    {
        $saveUser = Tax::find($tax->id);

        $saveUser->tax_name = $request->tax_name;

        $saveUser->percentage_tax = $request->percentage_tax;

        $saveUser->amount = $request->amount;

        $saveUser->currency = $request->currency;

        $saveUser->save();

        return redirect()->route('taxes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tax $tax)
    {

        Tax::destroy($tax->id);

        return redirect()->route('taxes.index');

    }
}
