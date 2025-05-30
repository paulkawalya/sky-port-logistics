<?php

namespace App\Http\Controllers;

use App\Models\RequisitionDocument;
use App\Models\SifcoHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequisitionDocumentController extends Controller
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
        
        $this->validate($request,['file_url'=>'required']);

        $saveDoc = new RequisitionDocument();

        $saveDoc->requisition_id = $request->requisition_id;

        $saveDoc->user_id = Auth::id();

        $saveDoc->file_name = $request->file_name;

        $saveDoc->file_url = SifcoHelper::uploadImage($request->file('file_url'));

        $saveDoc->save();

        return back();

    }

    /**
     * Display the specified resource.
     */
    public function show(RequisitionDocument $requisitionDocument)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequisitionDocument $requisitionDocument)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequisitionDocument $requisitionDocument)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequisitionDocument $requisitionDocument)
    {
        //
    }
}
