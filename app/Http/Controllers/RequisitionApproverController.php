<?php

namespace App\Http\Controllers;

use App\Mail\NotifyApprovers;
use App\Models\Permission;
use App\Models\Requisition;
use App\Models\RequisitionApprover;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RequisitionApproverController extends Controller
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

        $requisitions = RequisitionApprover::where('user_id',Auth::id())->where('requisition_id',$request->requisition_id)->get();

        if($requisitions->count() == 0){

            $saveRequisitionApprover = new RequisitionApprover();

            $saveRequisitionApprover->requisition_id = $request->requisition_id;

            $saveRequisitionApprover->user_id = Auth::id();

        } else{

            $saveRequisitionApprover = $requisitions->last();

        }

        $saveRequisitionApprover->status = $request->status;

        $saveRequisitionApprover->remarks = $request->remarks;

        $saveRequisitionApprover->save();

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(RequisitionApprover $requisitionApprover)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequisitionApprover $requisitionApprover)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $requisition_id)
    {

        $requisition = Requisition::find($requisition_id);

        $emails = $requisition->approvers($requisition_id);

        $secureUrl = route('requisitions.secure.show', $requisition->hashed_id);
        $requEmails = "Hello, <br> ".$requisition->user->name."  has placed a requisition No ".str_pad($requisition->id, 4, '0', STR_PAD_LEFT)." worth UGX ".number_format($requisition->amount($requisition->id))." that awaits your approval. <br><br> View requisition: <a href='".$secureUrl."'>".$secureUrl."</a> <br><br> Thank you!";

        Mail::to($requisition->user->email)->cc($emails)->send(new NotifyApprovers($requisition,$requEmails));

        return redirect()->back()->with(['status'=>'The message has been sent']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequisitionApprover $requisitionApprover)
    {
        //
    }
}
