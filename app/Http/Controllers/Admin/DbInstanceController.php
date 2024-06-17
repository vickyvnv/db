<?php

namespace App\Http\Controllers\Admin;

use App\Models\DbInstance;
use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class DbInstanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dbInstances = DbInstance::with('market')->latest()->paginate(10);

        return view('admin.dbinstance.index', compact('dbInstances'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $markets = Market::all();

        return view('admin.dbinstance.create', compact('markets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'prod' => 'required|string|max:255',
            'preprod' => 'required|string|max:255',
            'market_id' => 'required|exists:markets,id',
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Create a new db instance
            $dbInstance = DbInstance::create($request->all());

            // Log the successful creation
            Log::info('New db instance created', ['dbInstance' => $dbInstance]);

            // Redirect with success message
            return redirect()->route('db-instances.index')->with('success', 'DB instance created successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating db instance', ['error' => $e->getMessage()]);

            // Redirect with error message
            return redirect()->back()->with('error', 'An error occurred while creating the db instance.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DbInstance  $dbInstance
     * @return \Illuminate\Http\Response
     */
    public function show(DbInstance $dbInstance)
    {
        return view('admin.dbinstance.show', compact('dbInstance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DbInstance  $dbInstance
     * @return \Illuminate\Http\Response
     */
    public function edit(DbInstance $dbInstance)
    {
        $markets = Market::all();

        return view('admin.dbinstance.edit', compact('dbInstance', 'markets'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DbInstance  $dbInstance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DbInstance $dbInstance)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'prod' => 'required|string|max:255',
            'preprod' => 'required|string|max:255',
            'market_id' => 'required|exists:markets,id',
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Update the db instance
            $dbInstance->update($request->all());

            // Log the successful update
            Log::info('DB instance updated', ['dbInstance' => $dbInstance]);

            // Redirect with success message
            return redirect()->route('db-instances.index')->with('success', 'DB instance updated successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error updating db instance', ['error' => $e->getMessage()]);

            // Redirect with error message
            return redirect()->back()->with('error', 'An error occurred while updating the db instance.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DbInstance  $dbInstance
     * @return \Illuminate\Http\Response
     */
    public function destroy(DbInstance $dbInstance)
    {
        try {
            // Delete the db instance
            $dbInstance->delete();

            // Log the successful deletion
            Log::info('DB instance deleted', ['dbInstance' => $dbInstance]);

            // Redirect with success message
            return redirect()->route('db-instances.index')->with('success', 'DB instance deleted successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error deleting db instance', ['error' => $e->getMessage()]);

            // Redirect with error message
            return redirect()->back()->with('error', 'An error occurred while deleting the db instance.');
        }
    }
}