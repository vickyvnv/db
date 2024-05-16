<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Right;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Retrieve all users
        $users = User::query();

        // Apply search filters based on input parameters
        if ($request->filled('username')) {
            $users->where('username', 'like', '%' . $request->input('username') . '%');
        }

        if ($request->filled('phone')) {
            $users->where('phone', 'like', '%' . $request->input('phone') . '%');
        }

        if ($request->filled('id')) {
            $users->where('id', 'like', '%' . $request->input('id') . '%');
        }

        if ($request->filled('email')) {
            $users->where('email', 'like', '%' . $request->input('email') . '%');
        }

        if ($request->filled('company')) {
            $users->where('company', 'like', '%' . $request->input('company') . '%');
        }

        if ($request->filled('department')) {
            $users->where('user_department', 'like', '%' . $request->input('department') . '%');
        }

        if ($request->filled('position')) {
            $users->where('position', 'like', '%' . $request->input('position') . '%');
        }

        // Get the current authenticated user
        $currentUser = auth()->user();

        // Move the current user to the top of the user list
        $users = $users->orderByRaw("CASE WHEN id = {$currentUser->id} THEN 0 ELSE 1 END")
                    ->paginate(10); // Adjust pagination as needed

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Return the create user form
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'max:120', 'unique:users,email'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'user_firstname' => ['required', 'string', 'max:100'],
                'user_lastname' => ['required', 'string', 'max:100'],
                // 'tel' => ['required', 'string', 'max:100'],
                // 'user_function' => ['required', 'string', 'max:100'],
                // 'user_contractor' => ['required', 'string', 'max:100'],
                // 'team_id' => ['required', 'string', 'max:64'],
                'username' => ['required', 'string', 'max:255'],
                'mobile' => ['required', 'string', 'max:10'],
                'user_department' => ['required', 'string', 'max:31'],
                'company' => ['required', 'string', 'max:100'],
                'position' => ['required', 'string', 'max:100'],
                'phone' => ['required', 'string', 'max:100'],
            ]);
            
            // Redirect back with errors if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            // Create a new user
            //$user = User::create($request->all());
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_firstname' => $request->user_firstname,
                'user_lastname' => $request->user_lastname,
                'username' => $request->username,
                'mobile' => $request->mobile,
                'company' => $request->company,
                'position' => $request->position,
                'phone' => $request->phone,
                'user_department' => $request->user_department,
            ]);

            // Log successful user registration
            Log::channel('daily')->info('User registered successfully. Created by : ' . Auth::user()->email. '  New user is :'. $user->email);
        
            // Redirect to home page after successful registration
            return redirect()->route('users.index')->with('success', 'User registered successfully!');
        } catch (\Exception $e) {
            //dd($e->getMessage());
            // Log the error if registration fails
            Log::channel('daily')->error('Error during user registration: ' . $e->getMessage());
        
            // Redirect back to the registration page with an error message
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred during registration. Please try again.']);
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Retrieve the user by ID
        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //dd($user);
        
        
        //dd($user);
        //$user = User::findOrFail($id);
        $roles = Role::with('rights')->get();;
        

        // Retrieve the user by ID
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user',  'roles'));
    }

    public function getRightsForRole($roleId)
    {
        $role = Role::findOrFail($roleId);
        $rights = $role->rights;

        return response()->json($rights);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'max:120', 'unique:users,email,' . $user->id],
                //'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
                'user_firstname' => ['required', 'string', 'max:100'],
                'user_lastname' => ['required', 'string', 'max:100'],
                'username' => ['required', 'string', 'max:255'],
                'mobile' => ['required', 'string', 'max:10'],
                'user_department' => ['required', 'string', 'max:31'],
                'company' => ['required', 'string', 'max:100'],
                'position' => ['required', 'string', 'max:100'],
                'phone' => ['required', 'string', 'max:100'],
            ]);
            
            // Redirect back with errors if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Update user data
            $user->update([
                'email' => $request->email,
                //'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
                'user_firstname' => $request->user_firstname,
                'user_lastname' => $request->user_lastname,
                'username' => $request->username,
                'mobile' => $request->mobile,
                'company' => $request->company,
                'position' => $request->position,
                'phone' => $request->phone,
                'user_department' => $request->user_department,
            ]);
            
            $roleId = $request->role_id;
            $userId = $user->id;
            $rightIds = $request->right_id;

            // Detach existing records associated with the specific user
            $user->userRoles()->wherePivot('user_id', $userId)->detach();

            // Attach the new records
            foreach ($rightIds as $rightId) {
                $user->userRoles()->attach($roleId, ['right_id' => $rightId]);
            }


            // Log successful user update
            Log::channel('daily')->info('User details updated successfully by: ' . Auth::user()->email . '. Updated user is: ' . $user->email);
        
            // Redirect to user index page after successful update
            return redirect()->route('users.index')->with('success', 'User details updated successfully!');
        } catch (\Exception $e) {
            // Log the error if update fails
            Log::channel('daily')->error('Error during user update: ' . $e->getMessage());
        
            // Redirect back to the edit page with an error message
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred during user update. Please try again.']);
        }   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Retrieve the user by ID
            $user = User::findOrFail($id);
            
            // Delete user
            $user->delete();

            // Log user deletion
            Log::channel('daily')->info("User '{$user->name}' deleted by user '" . Auth::user()->name . "'.");
        
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            // Log error if user deletion fails
            Log::channel('daily')->error('Error deleting user: ' . $e->getMessage());
        
            return redirect()->back()->withErrors(['error' => 'An error occurred during user deletion. Please try again.']);
        }         
    }

    /**
     * Show the form to reset the user's password.
     *
     * @param  User  $user
     * @return \Illuminate\View\View
     */
    public function showResetPasswordForm(User $user)
    {
        return view('admin.users.resetpassword', compact('user'));
    }

    /**
     * Reset the user's password.
     *
     * @param  Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request, User $user)
    {
        // Validate the request data
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            // Update the user's password
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            // Log the password reset action
            Log::info('User password reset by: ' . auth()->user()->name . ' for user: ' . $user->name);

            // Redirect with success message
            return redirect()->route('users.index')->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            // Log any error that occurs during password reset
            Log::error('Error resetting password: ' . $e->getMessage());

            // Redirect back with error message
            return back()->withInput()->with('error', 'Failed to update password. Please try again.');
        }
    }
}
