<?php

namespace App\Http\Controllers;

use App\Models\ShippmentDocument;
use App\Models\SifcoHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippmentDocumentController extends Controller
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

            'file_name'=>'required',
            'file_url'=>'required'
        ];

        $this->validate($request,$rules);

        $saveDocument = new ShippmentDocument();

        $saveDocument->shippment_id = $request->shippment_id;

        $saveDocument->user_id = Auth::id();

        $saveDocument->file_name = $request->file_name;

        $saveDocument->file_url = SifcoHelper:: uploadImage($request->file('file_url'));

        $saveDocument->save();

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippmentDocument $shippmentDocument)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShippmentDocument $shippmentDocument)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShippmentDocument $shippmentDocument)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippmentDocument $shippmentDocument)
    {
        //
    }
}
