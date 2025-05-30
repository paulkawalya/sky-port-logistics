<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if(!Auth::user()->canDo(['view_suppliers'])) return redirect('home');

        $suppliers = Supplier::get();

        $data = [
            'suppliers'=>$suppliers,
            'title'=>'Suppliers'
        ];

        return view('users.suppliers')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if(!Auth::user()->canDo(['create_suppliers'])) return redirect('home');

        $data = [
            'title'=>'Create Suppliers'
        ];

        return view('users.create_suppliers')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name'=>'required',
            'phone_number'=>'required',
        ];

        $this->validate($request,$rules);

        $saveSupplier = new Supplier();

        $saveSupplier->name = $request->name;

        $saveSupplier->phone_number = $request->phone_number;

        $saveSupplier->address = $request->address;

        $saveSupplier->save();

        return redirect()->route('suppliers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $data = [
            'supplier'=>$supplier,
            'title'=>'Edit supplier'
        ];

        return view('users.edit_suppliers')->with($data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {

        $saveSupplier = Supplier::find($supplier->id);

        $saveSupplier->name = $request->name;

        $saveSupplier->phone_number = $request->phone_number;

        $saveSupplier->address = $request->address;

        $saveSupplier->save();

        return redirect()->route('suppliers.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }
}
