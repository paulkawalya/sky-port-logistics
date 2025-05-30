<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Customer;
use App\Models\Transit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $data = ['title'=>'Home'];

        if(Auth::check() && method_exists(Auth::user(), 'canDo') && !Auth::user()->canDo(['view_dasbord']))

            return view('inside')->with($data);

        $on_port =  Transit::transitStatus("On Port");

        $pickedup = Transit::transitStatus("Pickedup");

        $in_transit = Transit::transitStatus("In Transit");

        $in_warehouse = Transit::transitStatus("In Warehouse");

        $transferred = Transit::transitStatus("Transferred");

        // Get all containers instead of just pending ones
        $all_containers = Transit::getAllTransits();


        $agents = Agent::count();

        $customers = Customer::count();

        $data = [
            'on_port'=>$on_port,
            'pickedup'=>$pickedup,
            'in_transit'=>$in_transit,
            'in_warehouse'=>$in_warehouse,
            'transits'=>$all_containers,
            'transferred'=>$transferred,
            'title'=>'Dashboard',
            'agents'=>$agents,
            'customers'=>$customers,
        ];

        return view('home')->with($data);

    }


}