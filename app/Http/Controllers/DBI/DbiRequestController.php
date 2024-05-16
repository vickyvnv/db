<?php

namespace App\Http\Controllers\DBI;

use Illuminate\Http\Request;
use App\Models\DbiRequest;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Market;
use App\Models\DbiType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DbiRequestController extends Controller
{
    /**
     * Display the DBI request list.
     */
    public function index()
    {
        try {
            // Fetch all DbiRequests
            $dbiRequests = DbiRequest::all();

            // Log successful retrieval of DbiRequests
            Log::channel('daily')->info('Fetched all Dbi Requests successfully DbiRequestController::index(). User id:'.Auth::user()->id.' email: '.Auth::user()->email);

            return view('dbi.index', compact('dbiRequests'));
        } catch (\Exception $e) {
            // Log error if fetching DbiRequests fails
            Log::channel('daily')->error('Error occurred while fetching Dbi Requests: ' . $e->getMessage() .'DbiRequestController::index()    User id:'.Auth::user()->id.' email: '.Auth::user()->email);

            return redirect()->back()->with('error', 'Failed to fetch Dbi Requests.');
        }
    }

    /**
     * Display the DBI request create form.
     */
    public function create(Request $request)
    {

        $categories = Category::all();

        $priorities = Priority::all();

        $markets = Market::all();

        $dbiTypes = DbiType::all();

        // Render the create form
        return view('dbi.create', compact('categories', 'priorities', 'markets', 'dbiTypes'));
    }

    /**
     * Store a newly created DbiRequest.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'category' => 'required',
            'priority_id' => 'required',
            'sw_version' => 'required',
            'dbi_type' => 'required',
            'tt_id' => 'required',
            'serf_cr_id' => 'required',
            'reference_dbi' => 'required',
            'brief_desc' => 'required',
            'problem_desc' => 'required',
            'business_impact' => 'required',
        ]);

        // Redirect back with errors if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {        
            // Create a new DbiRequest
            $dbiRequest = DbiRequest::create($request->all());

            // Log successful creation of DbiRequest
            Log::channel('daily')->info('Dbi Request created successfully. DbiRequestController::store()'. 'User id:'.Auth::user()->id.' email: '.Auth::user()->email);
            return redirect()->route('dbi.index')->with('success', 'Dbi Request created successfully.');
        } catch (\Exception $e) {
            // Log error if creating DbiRequest fails
            Log::error('Error occurred while creating Dbi Request: ' . $e->getMessage().'  DbiRequestController::store() User id:'.Auth::user()->id.' email: '.Auth::user()->email);
            return redirect()->back()->with('error', 'Failed to create Dbi Request.');
        }
    }

    /**
     * Display the specified DbiRequest.
     */
    public function show($id)
    {
        try {
            // Find the specified DbiRequest
            $dbiRequest = DbiRequest::findOrFail($id);

            // Log successful retrieval of DbiRequest
            Log::info('Fetched Dbi Request successfully. DbiRequestController::show()  '.' User id:'.Auth::user()->id.' email: '.Auth::user()->email);
            return view('dbi.show', compact('dbiRequest'));
        } catch (\Exception $e) {
            // Log error if fetching DbiRequest fails
            Log::error('Error occurred while fetching Dbi Request: DbiRequestController::show()' . $e->getMessage(). ' User id:'.Auth::user()->id.' email: '.Auth::user()->email);
            return redirect()->back()->with('error', 'Failed to fetch Dbi Request.');
        }
    }

    /**
     * Display the DbiRequest edit form.
     */
    public function edit($id)
    {
        try {
            // Find the DbiRequest to edit
            $dbiRequest = DbiRequest::findOrFail($id);

            // Log access to edit DbiRequest page
            Log::info('Dbi Request edit. DbiRequestController::edit()'.' User id:'.Auth::user()->id.' email: '.Auth::user()->email);
            return view('dbi.edit', compact('dbiRequest'));
        } catch (\Exception $e) {
            // Log error if fetching DbiRequest for editing fails
            Log::error('Error occurred while fetching Dbi Request for editing: DbiRequestController::edit()' . $e->getMessage(). 'User id:'.Auth::user()->id.' email: '.Auth::user()->email);
            return redirect()->back()->with('error', 'Failed to fetch Dbi Request for editing.');
        }
    }

    /**
     * Update the specified DbiRequest in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'category' => 'required',
            'priority_id' => 'required',
            'sw_version' => 'required',
            'dbi_type' => 'required',
            'tt_id' => 'required',
            'serf_cr_id' => 'required',
            'reference_dbi' => 'required',
            'brief_desc' => 'required',
            'problem_desc' => 'required',
            'business_impact' => 'required',
        ]);

        // Redirect back with errors if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Find the specified DbiRequest
            $dbiRequest = DbiRequest::findOrFail($id);

            // Update each attribute individually
            $dbiRequest->category = $request->category;
            $dbiRequest->priority_id = $request->priority_id;
            $dbiRequest->sw_version = $request->sw_version;
            $dbiRequest->dbi_type = $request->dbi_type;
            $dbiRequest->tt_id = $request->tt_id;
            $dbiRequest->serf_cr_id = $request->serf_cr_id;
            $dbiRequest->reference_dbi = $request->reference_dbi;
            $dbiRequest->brief_desc = $request->brief_desc;
            $dbiRequest->problem_desc = $request->problem_desc;
            $dbiRequest->business_impact = $request->business_impact;

            $dbiRequest->save();
            
            // Log successful update of DbiRequest
            Log::info('Dbi Request updated successfully. DbiRequestController::update() User id:'.Auth::user()->id.' email: '.Auth::user()->email .' Data: '. $dbiRequest);
            return redirect()->route('dbi.index')->with('success', 'Dbi Request updated successfully.');
        } catch (\Exception $e) {
            // Log error if updating DbiRequest fails
            Log::error('Error occurred while updating Dbi Request: ' . $e->getMessage(). ' DbiRequestController::update() User id:'.Auth::user()->id.' email: '.Auth::user()->email);
            return redirect()->back()->with('error', 'Failed to update Dbi Request.');
        }
    }

    /**
     * Remove the specified DbiRequest from storage.
     */
    public function destroy($id)
    {
        try {
            // Find the specified DbiRequest and delete it
            $dbiRequest = DbiRequest::findOrFail($id);
            $dbiRequest->delete();

            // Log successful deletion of DbiRequest
            Log::info('Dbi Request deleted successfully. DbiRequestController::update() Deleted dbi request:'.$id. ' User id:'.Auth::user()->id.' email: '.Auth::user()->email);
            return redirect()->route('dbi.index')->with('success', 'Dbi Request deleted successfully.');
        } catch (\Exception $e) {
            // Log error if deleting DbiRequest fails
            Log::error('Error occurred while deleting Dbi Request: ' . $e->getMessage().'User id:'.Auth::user()->id.' email: '.Auth::user()->email.'DbiRequestController::update()');
            return redirect()->back()->with('error', 'Failed to delete Dbi Request.');
        }
    }
}
