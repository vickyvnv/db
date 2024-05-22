<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pwrole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PwroleController extends Controller
{
    public function index()
    {
        $pwroles = Pwrole::all();
        return view('admin.pwservice.roles.index', compact('pwroles'));
    }

    public function create()
    {
        return view('admin.pwservice.roles.create');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'pwr_name' => 'required',
                'pwr_description' => 'required'
            ]);

            Pwrole::create($validatedData);

            // Log successful user registration
            Log::channel('daily')->info('PW Role created successfully. Created by : ' . Auth::user()->email);
        
            return redirect()->route('pwroles.index')->with('success', 'Pwrole created successfully.');
        } catch (\Exception $e) {
            // Log the error if registration fails
            Log::channel('daily')->error('Error during user registration: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the pwrole.');
        }
    }

    public function show(Pwrole $pwrole)
    {
        return view('admin.pwservice.roles.show', compact('pwrole'));
    }

    public function edit(Pwrole $pwrole)
    {
        return view('admin.pwservice.roles.edit', compact('pwrole'));
    }

    public function update(Request $request, Pwrole $pwrole)
    {
        try {
            $validatedData = $request->validate([
                'pwr_name' => 'required',
                'pwr_description' => 'required',
            ]);

            $pwrole->update($validatedData);

            return redirect()->route('pwroles.index')->with('success', 'Pwrole updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the pwrole.');
        }
    }

    public function destroy(Pwrole $pwrole)
    {
        try {
            $pwrole->delete();
            return redirect()->route('pwroles.index')->with('success', 'Pwrole deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the pwrole.');
        }
    }
}