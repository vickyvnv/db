<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pwconnect;
use App\Models\Pwgroup;
use App\Models\Pwrole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PwconnectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            
            $pwconnects = Pwconnect::all();
            
            return view('admin.pwservice.connects.index', compact('pwconnects'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while fetching pwconnects.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pwGroups = Pwgroup::all();
        return view('admin.pwservice.connects.create', compact('pwGroups'));
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
            $validatedData = $request->validate([
                'PWC_NAME' => 'required',
                'PWC_USER' => 'required',
                'PWC_PW' => 'required',
                'PWC_CAT' => 'required',
                'PWC_GROUP' => '',
                'PWC_CHANGE_TYP' => 'required',
                'PWC_CHANGE_COND' => 'required',
            ]);

            Pwconnect::create($validatedData);

            return redirect()->route('pwconnects.index')->with('success', 'Pwconnect created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::channel('daily')->error('Error during user registration: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the pwconnect.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pwconnect  $pwconnect
     * @return \Illuminate\Http\Response
     */
    public function show(Pwconnect $pwconnect)
    {
        return view('admin.pwservice.connects.show', compact('pwconnect'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pwconnect  $pwconnect
     * @return \Illuminate\Http\Response
     */
    public function edit(Pwconnect $pwconnect)
    {
        $pwGroups = Pwgroup::all();
        return view('admin.pwservice.connects.edit', compact('pwconnect', 'pwGroups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pwconnect  $pwconnect
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pwconnect $pwconnect)
    {
        try {
            $validatedData = $request->validate([
                'PWC_NAME' => 'required',
                'PWC_USER' => 'required',
                'PWC_PW' => 'required',
                'PWC_CAT' => 'required',
                'PWC_GROUP' => 'required',
                'PWC_CHANGE_TYP' => 'required',
                'PWC_CHANGE_COND' => 'required',
            ]);

            $pwconnect->update($validatedData);

            return redirect()->route('pwconnects.index')->with('success', 'Pwconnect updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::channel('daily')->error('Error during user registration: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the pwconnect.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pwconnect  $pwconnect
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pwconnect $pwconnect)
    {
        try {
            $pwconnect->delete();
            return redirect()->route('pwconnects.index')->with('success', 'Pwconnect deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the pwconnect.');
        }
    }

    public function users(Pwconnect $pwconnect)
    {
        $assignedUsers = $pwconnect->users;
        $availableUsers = User::whereNotIn('id', $assignedUsers->pluck('id'))
            ->where('team_id', '!=', 4)
            ->get();

        return view('admin.pwservice.connects.users', compact('pwconnect', 'assignedUsers', 'availableUsers'));
    }

    public function assignUser(Pwconnect $pwconnect, User $user)
    {
        $pwconnect->users()->attach($user);
        return redirect()->back()->with('success', 'User assigned successfully.');
    }

    public function removeUser(Pwconnect $pwconnect, User $user)
    {
        $pwconnect->users()->detach($user);
        return redirect()->back()->with('success', 'User removed successfully.');
    }

    public function roles(Pwconnect $pwconnect)
    {
        $assignedRoles = $pwconnect->roles;
        $availableRoles = Pwrole::whereNotIn('id', $assignedRoles->pluck('id'))->get();

        return view('admin.pwservice.connects.roles', compact('pwconnect', 'assignedRoles', 'availableRoles'));
    }

    public function assignRole(Pwconnect $pwconnect, Pwrole $role)
    {
        $pwconnect->roles()->attach($role);
        return redirect()->back()->with('success', 'Role assigned successfully.');
    }

    public function removeRole(Pwconnect $pwconnect, Pwrole $role)
    {
        $pwconnect->roles()->detach($role);
        return redirect()->back()->with('success', 'Role removed successfully.');
    }
}