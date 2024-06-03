<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\DatabaseInfo;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;


class DatabaseInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $databaseInfos = DatabaseInfo::all();
            return view('admin.dbinfo.index', compact('databaseInfos'));
        } catch (\Exception $e) {
            Log::error('Error fetching database info: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while fetching database info.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.dbinfo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'db_user_name' => 'required',
                'db_user_password' => 'required',
                //'db_name' => 'required',
            ]);

            $encryptedPassword = Crypt::encryptString($validatedData['db_user_password']);
            

            $validatedData['db_user_password'] = $encryptedPassword;

            DatabaseInfo::create($validatedData);

            return redirect()->route('database-info.index')->with('success', 'Database info created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating database info: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating database info.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DatabaseInfo $databaseInfo)
    {
        return view('admin.dbinfo.edit', compact('databaseInfo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DatabaseInfo $databaseInfo)
    {
        try {
            $validatedData = $request->validate([
                'db_user_name' => 'required',
                'db_user_password' => 'required',
                //'db_name' => 'required',
            ]);

            $encryptedPassword = Crypt::encryptString($validatedData['db_user_password']);
            

            $validatedData['db_user_password'] = $encryptedPassword;

            //$validatedData['db_user_password'] = Hash::make($validatedData['db_user_password']);

            $databaseInfo->update($validatedData);

            return redirect()->route('database-info.index')->with('success', 'Database info updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating database info: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating database info.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DatabaseInfo $databaseInfo)
    {
        try {
            $databaseInfo->delete();
            return redirect()->route('database-info.index')->with('success', 'Database info deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting database info: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting database info.');
        }
    }
}
