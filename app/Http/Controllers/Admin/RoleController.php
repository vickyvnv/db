<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Right;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{

     /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::with('rights')->get();
        //dd($roles);
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rights = Right::all();

        return view('admin.roles.create', compact('rights'));
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation rules
        $request->validate([
            'name' => 'required|unique:roles',
        ]);

        try {
            // Encode as JSON
            $rightsAsString = json_encode($request->rights);
            //dd($rightsAsString);
            // Create role
            $role = Role::create([
                'name' => $request->name
            ]);

            // Attach selected rights to the role
            $rights = $request->input('rights'); // Assuming 'rights' is an array of selected right IDs
            $role->rights()->attach($rights);
            
            // Log daily message
            Log::info("Role '{$role->name}' created by user '{$request->user()->name}'.");

            return redirect()->route('roles.index')->with('success', 'Role created successfully!');
        } catch (\Exception $e) {
            // Log error
            Log::error("Error creating role: {$e->getMessage()}");

            return redirect()->back()->withErrors(['error' => 'An error occurred. Please try again.']);
        }
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::with('rights')->findOrFail($id);
        $rights = Right::all();

        return view('admin.roles.edit', compact('role', 'rights'));
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validation rules
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
        ]);

        try {
            // Find the role by ID
            $role = Role::findOrFail($id);

            // Update role name
            $role->name = $request->name;

            // Update associated rights
            $rights = $request->input('rights'); // Assuming 'rights' is an array of selected right IDs
            $role->rights()->sync($rights);

            // Save changes
            $role->save();

            // Log daily message
            Log::info("Role '{$role->name}' updated by user '{$request->user()->name}'.");

            return redirect()->route('roles.index')->with('success', 'Role created successfully!');
        } catch (\Exception $e) {
            // Log error
            Log::error("Error updating role: {$e->getMessage()}");

            return redirect()->back()->withErrors(['error' => 'An error occurred. Please try again.']);
        }
    }


    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        try {
            // Delete role
            $role->delete();

            // Log daily message
            Log::info("Role '{$role->name}' deleted.");

            return response()->json(['message' => 'Role deleted successfully']);
        } catch (\Exception $e) {
            // Log error
            Log::error("Error deleting role: {$e->getMessage()}");

            return redirect()->back()->withErrors(['error' => 'An error occurred. Please try again.']);
        }
    }
}
