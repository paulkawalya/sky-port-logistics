<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Shippment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if(!Auth::user()->canDo(['view_customers'])) return redirect('home');

        $customers = Customer::get();

        $data = [
            'customers'=>$customers,
            'title'=>'Customers'
        ];

        return view('customers.list')->with($data);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if(!Auth::user()->canDo(['create_customers'])) return redirect('home');

        $data = [
            'title'=>'Create customers'
        ];

        return view('customers.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name'=>'required',
            'phone_number'=>'required'
        ];

        $this->validate($request,$rules);

        $customer = Customer::saveCustomer($request->name, $request->phone_number);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone_number' => $customer->phone_number
                ]
            ]);
        }

        return redirect()->route('customers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {

        if(!Auth::user()->canDo(['view_customers_shipments'])) return redirect('home');

        $shippments = Shippment::where('customer_id',$customer->id)->paginate(100);

        $data = [
            'shippments'=>$shippments,
            'title'=>'Shipmets to '.$customer->name
        ];

        return view('transit.shippments')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {

        if(!Auth::user()->canDo(['edit_customers'])) return redirect('home');

        $data = [
            'customer'=>$customer,
            'title'=>'Edit Customer'
        ];

        return view('customers.edit_customer')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        Customer::saveCustomer($request->name,$request->phone_number);

        return redirect()->route('customers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
