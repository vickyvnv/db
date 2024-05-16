<?php


namespace App\Http\Controllers\DBI;

use Illuminate\Http\Request;
use App\Models\Dbi;

class DbiController extends Controller
{
    public function index()
    {
        $dbis = Dbi::all();
        return view('dbi.index', compact('dbis'));
    }

    public function create()
    {
        return view('dbi.create');
    }

    public function store(Request $request)
    {
        // Validate and store data
    }

    public function show($id)
    {
        $dbi = Dbi::findOrFail($id);
        return view('dbi.show', compact('dbi'));
    }

    public function edit($id)
    {
        $dbi = Dbi::findOrFail($id);
        return view('dbi.edit', compact('dbi'));
    }

    public function update(Request $request, $id)
    {
        // Validate and update data
    }

    public function destroy($id)
    {
        // Delete data
    }
}
