<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use App\Models\RequisitionDetail;
use App\Models\RequisitionPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if(!Auth::user()->canDo(['manage_expense_categories'])) return redirect('home');

        $expenses_categories = ExpenseCategory::get();

        $data = [
            'expenses_categories'=>$expenses_categories,
            'title'=>'Expenses category',
        ];

        return view('requisitions.category')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if(!Auth::user()->canDo(['manage_expense_categories'])) return redirect('home');

        $data = [
            'title'=>'Create Expenses category',
        ];

        return view('requisitions.create_category')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name'=>'required',
        ];

        $this->validate($request,$rules);

        $saveExpenseCategory = new ExpenseCategory();

        $saveExpenseCategory->name = $request->name;

        $saveExpenseCategory->particular = $request->particular;

        $saveExpenseCategory->save();

        return redirect()->route('expense_categories.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(ExpenseCategory $expenseCategory)
    {

        $detailIds = RequisitionDetail::where('expense_category_id',$expenseCategory->id)->pluck('id')->toArray();

        $requsiition_payments = RequisitionPayment::whereIn('requisition_detail_id',$detailIds)->paginate(50);
        
        $data = [
            'requsiition_payments'=>$requsiition_payments,
            'title'=>'Requisition payments (Expenses) for '.$expenseCategory->name,
        ];

        return view('requisitions.expenditures')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpenseCategory $expenseCategory)
    {

        if(!Auth::user()->canDo(['manage_expense_categories'])) return redirect('home');

        $data = [
            'expenseCategory'=>$expenseCategory,
            'title'=>'Edit category'
        ];

        return view('requisitions.edit_category')->with($data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $saveExpenseCategory = ExpenseCategory::find($expenseCategory->id);

        $saveExpenseCategory->name = $request->name;

        $saveExpenseCategory->particular = $request->particular;

        $saveExpenseCategory->save();

        return redirect()->route('expense_categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        //
    }
}
