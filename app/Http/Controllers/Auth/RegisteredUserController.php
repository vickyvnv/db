<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Right;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Render the registration view
        return view('admin.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
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
            return redirect()->route('admin.index')->with('success', 'User registered successfully!');
        } catch (\Exception $e) {
            //dd($e->getMessage());
            // Log the error if registration fails
            Log::channel('daily')->error('Error during user registration: ' . $e->getMessage());
        
            // Redirect back to the registration page with an error message
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred during registration. Please try again.']);
        }         
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $rights = Right::all();

        return view('admin.edit-user', compact('user', 'roles', 'rights'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate and process form submission
        // Update user's roles and rights based on form input

        return redirect()->route('edit', $user->id)->with('success', 'Roles and rights updated successfully.');
    }
}
