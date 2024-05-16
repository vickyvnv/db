<?php

namespace App\Http\Controllers\DBI;

use Illuminate\Http\Request;
use App\Models\Priority;
use App\Http\Controllers\Controller;

class PriorityController extends Controller
{
    public function index()
    {
        $priorities = Priority::all();
        return response()->json(['priorities' => $priorities]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:priorities|max:255',
        ]);

        $priority = Priority::create($request->all());
        return response()->json(['priority' => $priority], 201);
    }

    public function show($id)
    {
        $priority = Priority::findOrFail($id);
        return response()->json(['priority' => $priority]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:priorities,name,' . $id,
        ]);

        $priority = Priority::findOrFail($id);
        $priority->update($request->all());
        return response()->json(['priority' => $priority]);
    }

    public function destroy($id)
    {
        $priority = Priority::findOrFail($id);
        $priority->delete();
        return response()->json(['message' => 'Priority deleted successfully']);
    }
}
