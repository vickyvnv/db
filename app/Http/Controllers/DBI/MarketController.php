<?php

namespace App\Http\Controllers\DBI;

use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class MarketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $markets = Market::latest()->paginate(10);

        return view('admin.markets.index', compact('markets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.markets.create');
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
            'name' => 'required|string|max:255',
            'subname' => 'required|string|max:255',
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Create a new market
            $market = Market::create($request->all());

            // Log the successful creation
            Log::info('New market created', ['market' => $market]);

            // Redirect with success message
            return redirect()->route('markets.index')->with('success', 'Market created successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating market', ['error' => $e->getMessage()]);

            // Redirect with error message
            return redirect()->back()->with('error', 'An error occurred while creating the market.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Market  $market
     * @return \Illuminate\Http\Response
     */
    public function show(Market $market)
    {
        return view('admin.markets.show', compact('market'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Market  $market
     * @return \Illuminate\Http\Response
     */
    public function edit(Market $market)
    {
        return view('admin.markets.edit', compact('market'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Market  $market
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Market $market)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'subname' => 'required|string|max:255',
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Update the market
            $market->update($request->all());

            // Log the successful update
            Log::info('Market updated', ['market' => $market]);

            // Redirect with success message
            return redirect()->route('markets.index')->with('success', 'Market updated successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error updating market', ['error' => $e->getMessage()]);

            // Redirect with error message
            return redirect()->back()->with('error', 'An error occurred while updating the market.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Market  $market
     * @return \Illuminate\Http\Response
     */
    public function destroy(Market $market)
    {
        try {
            // Delete the market
            $market->delete();

            // Log the successful deletion
            Log::info('Market deleted', ['market' => $market]);

            // Redirect with success message
            return redirect()->route('markets.index')->with('success', 'Market deleted successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error deleting market', ['error' => $e->getMessage()]);

            // Redirect with error message
            return redirect()->back()->with('error', 'An error occurred while deleting the market.');
        }
    }
}