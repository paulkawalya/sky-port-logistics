<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\RequisitionPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if(!Auth::user()->canDo(['can_view_expenses'])) return redirect('home');

        // $expenses = Expense::paginate(50);

        // $data = [
        //     'expenses'=>$expenses,
        //     'title'=>'Expenses'
        // ];

        // return view('pages.expenses')->with($data);

        $requsiition_payments = RequisitionPayment::paginate(50);
        
        $data = [
            'requsiition_payments'=>$requsiition_payments,
            'title'=>'Requisition payments (Expenses)'
        ];

        return view('requisitions.expenditures')->with($data);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
