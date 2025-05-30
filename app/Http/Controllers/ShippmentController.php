<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Customer;
use App\Models\ImportShipment;
use App\Models\Shippment;
use App\Models\Transit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ShippmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if(!Auth::user()->canDo(['view_shipment'])) return redirect('home');

        $shippments = Shippment::paginate(100);

        $data = [
            'shippments'=>$shippments,
            'title'=>'All time shipments'
        ];

        return view('transit.shippments')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(!Auth::user()->canDo(['create_shipment'])) return redirect('home');

        $tarnsits = Transit::get();

        $customers = Customer::get();

        $agents = Agent::get();

        $data = [
            'title'=>'Create a Shipment',
            'customers'=>$customers,
            'agents'=>$agents,
            'tarnsits'=>$tarnsits,
        ];

        return view('transit.create_shippment')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'transit_id' => 'required|exists:transits,id',
            'file_url' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        $transit = Transit::find($request->transit_id);

        try {
            // Get the initial count of shipments for this transit
            $initialCount = Shippment::where('transit_id', $transit->id)->count();

            // Import the shipments
            Excel::import(new ImportShipment($transit), $request->file('file_url'));

            // Get the new count of shipments
            $newCount = Shippment::where('transit_id', $transit->id)->count();

            // Calculate how many new shipments were added
            $addedCount = $newCount - $initialCount;

            if ($addedCount > 0) {
                return redirect()->route('containers.show', $transit->id)
                    ->with('success', $addedCount . ' shipment(s) successfully imported. Duplicate entries were skipped.');
            } else {
                return redirect()->route('containers.show', $transit->id)
                    ->with('error', 'No new shipments were imported. All entries were either empty or duplicates.');
            }
        } catch (\Exception $e) {
            return redirect()->route('containers.show', $transit->id)
                ->with('error', 'Error importing shipments: ' . $e->getMessage());
        }
    }

    public function saveManually(Request $request)  {

        $rules = [
            'transit_id'=>'required',
            'customer_id'=>'required',
            'agent_id'=>'required',
        ];

        $this->validate($request,$rules);

        $shipment =  Shippment::saveShipment($request->transit_id,$request->customer_id,$request->agent_id,$request->shipper,$request->mark,$request->bl_packages,$request->number_of_packages,$request->unit_of_measure,$request->weight,$request->volume,$request->type,$request->description);

        return redirect()->route('customers.show',$shipment->customer_id);

    }

    /**
     * Display the specified resource.
     */
    public function show(Shippment $shippment)
    {

        $data = [
            'shippment'=>$shippment,
            'title'=>$shippment->customer->name." - ".$shippment->description,
            'invoices'=>$shippment->invoices,
            'documents'=>$shippment->documents,
            'tranfers'=>$shippment->tranfers,
        ];

        return view('transit.shipment')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shippment $shippment)
    {

        if(!Auth::user()->canDo(['edit_shipment'])) return redirect('home');

        $tarnsits = Transit::get();

        $customers = Customer::get();

        $agents = Agent::get();

        $data = [
            'title'=>'Create a Shipment',
            'customers'=>$customers,
            'agents'=>$agents,
            'tarnsits'=>$tarnsits,
            'shippment'=>$shippment,
            'title'=>'Edit Shipment'
        ];

        return view('transit.edit_shippment')->with($data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shippment $shippment)
    {
        $shipment =  Shippment::saveShipment($request->transit_id,$request->customer_id,$request->agent_id,$request->shipper,$request->mark,$request->bl_packages,$request->number_of_packages,$request->unit_of_measure,$request->weight,$request->volume,$request->type,$request->description,$shippment->id);

        return redirect()->route('customers.show',$shipment->customer_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shippment $shippment)
    {
        //
    }
}
