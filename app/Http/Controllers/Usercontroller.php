<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Usercontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if(Auth::user()->canDo(['view_users']) || Auth::user()->user_type == 'Admin'){

            $users = User::get();

            $data = [
                'users'=>$users,
                'title'=>'Users'
            ];

            return view('pages.users')->with($data);

        }

        return redirect('home');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if(!Auth::user()->canDo(['view_users'])) return redirect('home');

        $user_types = ['MD', 'Manager', 'Accountant', 'Admin', 'Agent', 'Operations', 'Field', 'freight'];

        $data = [
            'user_types'=>$user_types,
            'title'=>'Create Users'
        ];

        return view('pages.create_users')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name'=>'required',
            'email'=>'required|unique:users'
        ];

        $this->validate($request,$rules);

        $saveUser = new User();

        $saveUser->name = $request->name;

        $saveUser->email = $request->email;

        $saveUser->phone_number = $request->phone_number;

        $saveUser->user_type = $request->user_type;

        $saveUser->password = Hash::make('user123@');

        $saveUser->save();

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if(Auth::user()->canDo(['view_users']) || Auth::user()->user_type == 'Admin'){
            $user = User::findOrFail($id);

            $data = [
                'user' => $user,
                'title' => 'User Details: ' . $user->name
            ];

            return view('pages.user_details')->with($data);
        }

        return redirect('home')->with('error', 'Permission denied');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($user_id)
    {

        if(Auth::user()->canDo(['edit_users']) || Auth::user()->user_type == 'Admin'){

            $user = User::find($user_id);

            $user_types = ['MD', 'Manager', 'Accountant', 'Admin', 'Agent', 'Operations', 'Field', 'freight'];

            $data = [
                'user'=>$user,
                'title'=>'Edit User',
                'user_types'=>$user_types,
            ];

            return view('pages.edit_users')->with($data);

        }

        return "Permission denied";


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user_id)
    {

        $saveUser = User::find($user_id);

        $saveUser->name = $request->name;

        $saveUser->email = $request->email;

        $saveUser->phone_number = $request->phone_number;

        $saveUser->user_type = $request->user_type;

        $saveUser->save();

        return redirect()->route('users.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    /**
     * Show the user's profile.
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        // Validate the request with detailed error messages
        $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'string',
                'min:8', // Minimum length of 8 characters
                'confirmed',
                'regex:/[a-z]/', // Must contain at least one lowercase letter
                'regex:/[A-Z]/', // Must contain at least one uppercase letter
                'regex:/[0-9]/', // Must contain at least one number
                'regex:/[@$!%*?&]/', // Must contain at least one special character
            ],
        ], [
            'current_password.required' => 'Current password is required.',
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'New password must be at least 8 characters long.',
            'new_password.confirmed' => 'New password confirmation does not match.',
            'new_password.regex' => 'New password must include at least one lowercase letter, one uppercase letter, one number, and one special character.',
        ]);


        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('status', 'Password updated successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // Optionally, handle any cleanup tasks before deletion

        // Log out the user
        Auth::logout();

        // Delete the user account
        $user->delete(); // Ensure this is a valid model instance

        // Redirect to home or login page
        return redirect('/')->with('status', 'Your account has been deleted.');
    }

    // Update User Name
    public function updateName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return redirect()->route('profile')->with('status', 'Name updated successfully.');
    }

    /**
     * Reset user password (admin function)
     */
    public function resetPassword(Request $request, $id)
    {
        // Check if user is admin, MD, or Manager
        if (!in_array(Auth::user()->user_type, ['Admin', 'MD', 'Manager'])) {
            return redirect()->back()->with('error', 'Permission denied');
        }

        // Find the user
        $user = User::findOrFail($id);

        // Validate the request
        $request->validate([
            'new_password' => 'required|string|min:8',
        ]);

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('users.show', $user->id)
            ->with('success', 'Password has been reset successfully. Please share the new password with the user.');
    }

    /**
     * Display admin settings page
     */
    public function adminSettings()
    {
        if (!in_array(Auth::user()->user_type, ['Admin', 'MD', 'Manager'])) {
            return redirect('home')->with('error', 'Permission denied');
        }

        $data = [
            'title' => 'Admin Settings'
        ];

        return view('pages.admin_settings')->with($data);
    }

    /**
     * Backup the database
     */
    public function backupDatabase()
    {
        if (!in_array(Auth::user()->user_type, ['Admin', 'MD', 'Manager'])) {
            return redirect()->back()->with('error', 'Permission denied');
        }

        // Get database configuration
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        // Set filename with timestamp
        $filename = $database . '_' . date('Y-m-d_H-i-s') . '.sql';

        // Use mysqldump command to create backup
        $command = "mysqldump --user={$username} --password={$password} {$database} > {$filename}";

        try {
            // Execute the command
            exec($command, $output, $returnVar);

            if ($returnVar === 0) {
                // Command executed successfully
                // Set headers for download
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($filename));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filename));

                // Read the file and output it to the browser
                readfile($filename);

                // Delete the file after download
                unlink($filename);
                exit;
            } else {
                // Command failed
                return redirect()->back()->with('error', 'Database backup failed');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Database backup failed: ' . $e->getMessage());
        }
    }
}
