<?php

namespace App\Http\Controllers;

use App\Models\TransitStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransitStatusController extends Controller
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
        $saveTransit = new TransitStatus();

        $saveTransit->transit_id = $request->transit_id;

        $saveTransit->user_id = Auth::id();

        $saveTransit->status = $request->status;

        $saveTransit->narrative = $request->narrative;

        $saveTransit->save();

        return redirect()->route('containers.show',$saveTransit->transit_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(TransitStatus $transitStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransitStatus $transitStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransitStatus $transitStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransitStatus $transitStatus)
    {
        //
    }
}
