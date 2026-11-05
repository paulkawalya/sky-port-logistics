<?php

namespace App\Http\Controllers;

use App\Models\ModelComponent;
use App\Models\User;
use App\Models\UserLog;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserPermission  $userPermission
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        if (!in_array(Auth::user()->user_type, ['Admin', 'MD', 'Manager']))

           return back()->with(['bad'=>'Permission denied']);

        $user = User::find($user_id);

        $readModelComponent = ModelComponent::get();

        $data = [

            'user' => $user,

            'readModelComponents' => $readModelComponent,

            'title' => 'Manage permissions for '.$user->name

        ];

        return view('users.manage_permissions')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserPermission  $userPermission
     * @return \Illuminate\Http\Response
     */
    public function edit(UserPermission $userPermission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserPermission  $userPermission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id)
    {
        
        $checkUserPermissions = UserPermission::where('user_id',$user_id)->where('permission_id',$request->permission_id)->get();

        if($checkUserPermissions->count() == 0){

            $userPermission = new UserPermission();

            $userPermission->user_id = $user_id;

            $userPermission->permission_id = $request->permission_id;

            $userPermission->save();

            return "Permission added";

        }else{

            $userPermission = $checkUserPermissions->last();

            UserPermission::where('user_id',$user_id)->where('permission_id',$request->permission_id)->delete();

            return "Permission revoked";

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserPermission  $userPermission
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserPermission $userPermission)
    {
        //
    }
}
