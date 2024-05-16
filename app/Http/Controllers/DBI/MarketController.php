<?php

namespace App\Http\Controllers\DBI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Market;

class MarketController extends Controller
{
    public function index()
    {
        $markets = Market::all();
        return response()->json(['markets' => $markets]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:markets|max:255',
        ]);

        $market = Market::create($request->all());
        return response()->json(['market' => $market], 201);
    }

    public function show($id)
    {
        $market = Market::findOrFail($id);
        return response()->json(['market' => $market]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:markets,name,' . $id,
        ]);

        $market = Market::findOrFail($id);
        $market->update($request->all());
        return response()->json(['market' => $market]);
    }

    public function destroy($id)
    {
        $market = Market::findOrFail($id);
        $market->delete();
        return response()->json(['message' => 'Market deleted successfully']);
    }
}
