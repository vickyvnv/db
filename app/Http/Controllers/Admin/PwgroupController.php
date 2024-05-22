<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pwgroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PwgroupController extends Controller
{
    /**
     * Display a listing of the pwgroups.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $pwgroups = Pwgroup::all();
            return view('admin.pwservice.groups.index', compact('pwgroups'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while fetching pwgroups.');
        }
    }

    /**
     * Show the form for creating a new pwgroup.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pwservice.groups.create');
    }

    /**
     * Store a newly created pwgroup in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'description' => 'nullable',
            ]);

            Pwgroup::create($validatedData);

            return redirect()->route('pwgroups.index')->with('success', 'Pwgroup created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the pwgroup.');
        }
    }

    /**
     * Display the specified pwgroup.
     *
     * @param  \App\Models\Pwgroup  $pwgroup
     * @return \Illuminate\Http\Response
     */
    public function show(Pwgroup $pwgroup)
    {
        return view('admin.pwservice.groups.show', compact('pwgroup'));
    }

    /**
     * Show the form for editing the specified pwgroup.
     *
     * @param  \App\Models\Pwgroup  $pwgroup
     * @return \Illuminate\Http\Response
     */
    public function edit(Pwgroup $pwgroup)
    {
        return view('admin.pwservice.groups.edit', compact('pwgroup'));
    }

    /**
     * Update the specified pwgroup in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pwgroup  $pwgroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pwgroup $pwgroup)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'description' => 'nullable',
            ]);

            $pwgroup->update($validatedData);

            return redirect()->route('pwgroups.index')->with('success', 'Pwgroup updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the pwgroup.');
        }
    }

    /**
     * Remove the specified pwgroup from storage.
     *
     * @param  \App\Models\Pwgroup  $pwgroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pwgroup $pwgroup)
    {
        try {
            $pwgroup->delete();
            return redirect()->route('pwgroups.index')->with('success', 'Pwgroup deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the pwgroup.');
        }
    }

    public function changeUsers(Pwgroup $pwgroup)
    {
        $assignedUsers = $pwgroup->users; 
        $availableUsers = User::whereNotIn('id', $assignedUsers->pluck('id'))
            ->where('team_id', '!=', 4)
            ->get();
        //dd($availableUsers);
        return view('admin.pwservice.groups.change_users', compact('pwgroup', 'assignedUsers', 'availableUsers'));
    }

    public function addUser(Pwgroup $pwgroup, User $user)
    {
        $pwgroup->users()->attach($user);
        return redirect()->back()->with('success', 'User added to the group.');
    }

    public function removeUser(Pwgroup $pwgroup, User $user)
    {
        $pwgroup->users()->detach($user);
        return redirect()->back()->with('success', 'User removed from the group.');
    }
}