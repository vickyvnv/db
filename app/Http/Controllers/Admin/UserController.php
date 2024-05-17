<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Right;
use App\Models\User;
use App\Models\Team;
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
                'username' => ['required', 'string', 'max:255','unique:users,username'],
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
    public function getRightsForRole($roleId)
    {
        $role = Role::findOrFail($roleId);
        $rights = $role->rights;

        return response()->json($rights);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles = Role::get();
        $teams = Team::all();
        $user = User::with('userRoles', 'assignedUser')->findOrFail($id);
        //dd($user);
        // Get the current user's team ID and role ID
        $currentTeamId = $user->team_id;
        $currentRoleId = $user->userRoles->pluck('id')->first();

        // Retrieve assigned users based on the user's team_id and role conditions
        $assignedUsers = User::whereHas('userRoles', function ($query) use ($currentRoleId) {
            $query->where('role_id', $currentRoleId);
        })
        ->where('team_id', $currentTeamId)
        ->whereHas('userRoles', function ($query) {
            $query->where('role_id', 5); // Requester role ID
        })
        ->where('id', '!=', $user->id)
        ->with('userRoles')
        ->get();

        return view('admin.users.edit', compact('user', 'roles', 'teams', 'assignedUsers', 'currentRoleId'));
    }

    public function getAssignedUsers(Request $request)
    {
        $teamId = $request->input('team_id');
        $roleId = $request->input('role_id');
        $userId = $request->input('user_id');

        $assignedUsers = User::whereHas('userRoles', function ($query) use ($roleId) {
            $query->where('role_id', $roleId);
        })
        ->where('team_id', $teamId)
        ->where('id', '!=', $userId)
        ->with('userRoles')
        ->get();

        return response()->json($assignedUsers);
    }

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
                'team_id' => ['required', 'max:100'],
                'role_id' => ['required', 'exists:roles,id'],
                'assigned_user_id' => ['required_if:role_id,5', 'nullable', 'exists:users,id'],
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
                'team_id' => $request->team_id,
                'assigned_user_id' => $request->assigned_user_id,
            ]);

            // Update user roles
            $user->userRoles()->sync([$request->role_id]);
            
            
            if(isset($request->assigned_user_id) && $request->assigned_user_id != null) {
                // Update assigned user relation
                if ($request->has('assigned_user_id')) {
                    $assignedUser = User::findOrFail($request->assigned_user_id);
                    $user->assignedUser()->sync([$assignedUser->id => ['team_id' => $request->team_id]]);
                } else {
                    $user->assignedUser()->detach();
                }
            }
            
            // Log successful user update
            Log::channel('daily')->info('User details updated successfully by: ' . auth()->user()->email . '. Updated user is: ' . $user->email);

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
