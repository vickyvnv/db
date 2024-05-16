<?php

namespace App\Http\Controllers\DBI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DbiType;

class DbiTypeController extends Controller
{
    public function index()
    {
        $dbiTypes = DbiType::all();
        return response()->json(['dbiTypes' => $dbiTypes]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:dbi_types|max:255',
        ]);

        $dbiType = DbiType::create($request->all());
        return response()->json(['dbiType' => $dbiType], 201);
    }

    public function show($id)
    {
        $dbiType = DbiType::findOrFail($id);
        return response()->json(['dbiType' => $dbiType]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:dbi_types,name,' . $id,
        ]);

        $dbiType = DbiType::findOrFail($id);
        $dbiType->update($request->all());
        return response()->json(['dbiType' => $dbiType]);
    }

    public function destroy($id)
    {
        $dbiType = DbiType::findOrFail($id);
        $dbiType->delete();
        return response()->json(['message' => 'DbiType deleted successfully']);
    }
}