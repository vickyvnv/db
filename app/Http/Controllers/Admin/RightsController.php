<?php

namespace App\Http\Controllers\Admin;

use App\Models\Right;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class RightsController extends Controller
{
    /**
     * Display a listing of the rights.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $rights = Right::paginate(4);
        return view('admin.rights.index', compact('rights'));
    }

    /**
     * Show the form for creating a new right.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.rights.create');
    }

    /**
     * Store a newly created right in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:rights']
            ]);

            $right = Right::create($request->all());

            Log::channel('daily')->info('Right created successfully. Created by: ' . Auth::user()->email . '  New right is: ' . $right->name);

            return redirect()->route('rights.index')->with('success', 'Right created successfully!');
        } catch (\Exception $e) {
            Log::channel('daily')->error('Error during creation of right: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An error occurred. Please try again.']);
        }
    }

    /**
     * Show the form for editing the specified right.
     *
     * @param  \App\Models\Right  $right
     * @return \Illuminate\View\View
     */
    public function edit(Right $right)
    {
        return view('admin.rights.edit', compact('right'));
    }

    /**
     * Update the specified right in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Right  $right
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Right $right)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:rights,name,' . $right->id]
            ]);

            $right->update($request->all());

            Log::channel('daily')->info('Right updated successfully. Updated by: ' . Auth::user()->email . '  Updated right is: ' . $right->name);

            return redirect()->route('rights.index')->with('success', 'Right updated successfully!');
        } catch (\Exception $e) {
            Log::channel('daily')->error('Error during update of right: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An error occurred. Please try again.']);
        }
    }

    /**
     * Remove the specified right from storage.
     *
     * @param  \App\Models\Right  $right
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Right $right)
    {
        try {
            $rightName = $right->name;
            $right->delete();

            Log::channel('daily')->info('Right deleted successfully. Deleted by: ' . Auth::user()->email . '  Deleted right is: ' . $rightName);

            return redirect()->route('rights.index')->with('success', 'Right deleted successfully!');
        } catch (\Exception $e) {
            Log::channel('daily')->error('Error during deletion of right: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An error occurred. Please try again.']);
        }
    }
}
