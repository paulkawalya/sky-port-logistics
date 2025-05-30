<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Shippment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if(!Auth::user()->canDo(['view_agent'])) return redirect('home');

        $agents = Agent::get();

        $data = [
            'agents'=>$agents,
            'title'=>'List of agents',
        ];

        return view('pages.agents')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if(!Auth::user()->canDo(['create_agent'])) return redirect('home');
        
        $data = [
            'title'=>'Create agent',
        ];

        return view('pages.create_agents')->with($data);
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

        Agent::saveAgent($request->name,$request->phone_number); 

        return redirect()->route('agents.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(Agent $agent)
    {

        if(!Auth::user()->canDo(['view_agent_shipments'])) return redirect('home');

        $shippments = Shippment::where('agent_id',$agent->id)->paginate(100);

        $data = [
            'shippments'=>$shippments,
            'title'=>'Shippmets from '.$agent->name
        ];

        return view('transit.shippments')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agent $agent)
    {

        if(!Auth::user()->canDo(['edit_agent'])) return redirect('home');

        $data = [
            'agent'=>$agent,
            'title'=>'List of agents'
        ];

        return view('pages.edit_agent')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agent $agent)
    {
        Agent::saveAgent($request->name,$request->phone_number); 

        return redirect()->route('agents.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agent $agent)
    {
        //
    }
}
