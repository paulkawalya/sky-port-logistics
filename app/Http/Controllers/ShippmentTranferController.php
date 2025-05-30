<?php

namespace App\Http\Controllers;

use App\Models\ShippmentTranfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippmentTranferController extends Controller
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
            'date_transfered'=>'required',
            'product_name'=>'required',
        ];

        $this->validate($request,$rules);

        $saveShippmentTranfer = new ShippmentTranfer();

        $saveShippmentTranfer->shippment_id = $request->shippment_id;

        $saveShippmentTranfer->user_id = Auth::id();

        $saveShippmentTranfer->date_transfered = $request->date_transfered;

        $saveShippmentTranfer->product_name = $request->product_name;

        $saveShippmentTranfer->destination = $request->destination;

        $saveShippmentTranfer->narrative = $request->narrative;

        $saveShippmentTranfer->reference = $request->reference;

        $saveShippmentTranfer->quantity = $request->quantity;

        $saveShippmentTranfer->save();

        return back();


    }

    /**
     * Display the specified resource.
     */
    public function show(ShippmentTranfer $shippmentTranfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShippmentTranfer $shippmentTranfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShippmentTranfer $shippmentTranfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippmentTranfer $shippmentTranfer)
    {
        //
    }
}
