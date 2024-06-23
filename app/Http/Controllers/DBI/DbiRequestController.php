<?php

namespace App\Http\Controllers\DBI;

use App\Http\Controllers\Controller;
use App\Models\DbiRequest;
use App\Models\DbiRequestSQL;
use App\Models\Category;
use App\Models\Priority;
use App\Models\DbiType;
use App\Models\DbiRequestLog;
use App\Models\DatabaseInfo;
use App\Models\DbInstance;
use App\Models\RejectionReason;
use App\Services\DbiRequestService;
use App\Services\DbiRequestLogService;
use App\Services\LogService;
use App\Services\SqlService;
use App\Services\MarketService;
use App\Http\Requests\CreateDbiRequest;
use App\Http\Requests\UpdateDbiRequest;
use App\Http\Requests\SelectDbRequest;
use App\Http\Requests\ExecuteDbiQueryRequest;
use App\Http\Requests\StoreTemporaryTableRequest;
use App\Http\Requests\UpdateRequestStatusRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DbiRequestController extends Controller
{
    protected $dbiRequestService;
    protected $logService;
    protected $sqlService;
    protected $marketService;
    protected $dbiRequestLogService;

    /**
     * DbiRequestController constructor.
     *
     * @param DbiRequestService $dbiRequestService
     * @param LogService $logService
     * @param SqlService $sqlService
     */
    public function __construct(DbiRequestService $dbiRequestService, LogService $logService, SqlService $sqlService, MarketService $marketService, DbiRequestLogService $dbiRequestLogService)
    {
        $this->dbiRequestService = $dbiRequestService;
        $this->logService = $logService;
        $this->sqlService = $sqlService;
        $this->marketService = $marketService;
        $this->dbiRequestLogService = $dbiRequestLogService;
    }

    /**
     * Display a listing of the DBI requests.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('viewAny', DbiRequest::class);

        $dbiRequests = $this->dbiRequestService->getAllDbiRequests();
        
        return view('dbi.index', compact('dbiRequests'));
    }

    /**
     * Show the form for creating a new DBI request.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', DbiRequest::class);

        $categories = Category::all();
        $priorities = Priority::all();
        $dbiTypes = DbiType::all();

        return view('dbi.create', compact('categories', 'priorities', 'dbiTypes'));
    }

    /**
     * Store a newly created DBI request in storage.
     *
     * @param CreateDbiRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateDbiRequest $request)
    {
        $this->authorize('create', DbiRequest::class);
        
        try {
            $dbiRequest = $this->dbiRequestService->createDbiRequest($request->validated(), Auth::id());

            $this->dbiRequestLogService->log($dbiRequest->id, "DBI Request created successfully.", $request->validated());

            return redirect()->route('dbi.selectdb', $dbiRequest->id)->with('success', 'DBI Request created successfully.');
        } catch (\Exception $e) {
            
            Log::error('Failed to update DBI Request', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            $this->dbiRequestLogService->log(null, "Failed to create DBI Request", $e->getMessage());

            return redirect()->back()->with('error', 'Failed to create DBI Request: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified DBI request.
     *
     * @param DbiRequest $dbiRequest
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $dbiRequest = DbiRequest::findOrFail($id);
        //dd($dbiRequest);
        $this->authorize('view', $dbiRequest);
        
        $dbiRequest->load(['requestor', 'operator', 'dbiRequestStatus']);
        $rejectionReasons  = RejectionReason::get();

        return view('dbi.show', compact('dbiRequest', 'rejectionReasons'));
    }

    /**
     * Show the form for editing the specified DBI request.
     *
     * @param DbiRequest $dbiRequest
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $dbiRequest = DbiRequest::findOrFail($id);

        $this->authorize('update', $dbiRequest);

        $categories = Category::all();
        $priorities = Priority::all();
        $dbiTypes = DbiType::all();

        return view('dbi.edit', compact('dbiRequest', 'categories', 'priorities', 'dbiTypes'));
    }

    /**
     * Update the specified DBI request in storage.
     *
     * @param UpdateDbiRequest $request
     * @param DbiRequest $dbiRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateDbiRequest $request, $id)
    {
        $dbiRequest = DbiRequest::findOrFail($id);
        $this->authorize('update', $dbiRequest);
        
        Log::info('DBI Request update process started', [
            'user_id' => auth()->id(),
            'dbi_request_id' => $id,
            'updated_fields' => array_keys($request->validated())
        ]);

        try {
            //dd($request->validated());
            $updatedDbiRequest = $this->dbiRequestService->updateDbiRequest($dbiRequest, $request->validated());
            
            $this->dbiRequestLogService->log($dbiRequest->id, "DBI Request created successfully.", $request->validated());

            Log::info('DBI Request updated successfully', [
                'user_id' => auth()->id(),
                'dbi_request_id' => $id,
                'updated_fields' => array_keys($request->validated())
            ]);

            return redirect()->route('dbi.selectdb', $id)->with('success', 'DBI Request updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update DBI Request', [
                'user_id' => auth()->id(),
                'dbi_request_id' => $id,
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            $this->dbiRequestLogService->log($id, "Failed to update DBI Request", $e->getMessage());

            return redirect()->back()->with('error', 'Failed to update DBI Request: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified DBI request from storage.
     *
     * @param DbiRequest $dbiRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DbiRequest $dbiRequest)
    {
        $this->authorize('delete', $dbiRequest);

        try {
            $this->dbiRequestService->deleteDbiRequest($dbiRequest);

            $this->dbiRequestLogService->log($dbiRequest->id, "DBI Request deleted successfully.", $dbiRequest);

            return redirect()->route('dbi.index')->with('success', 'DBI Request deleted successfully.');
        } catch (\Exception $e) {
            $this->dbiRequestLogService->log($dbiRequest->id, "Failed to create DBI Request", $e->getMessage());

            return redirect()->back()->with('error', 'Failed to delete DBI Request: ' . $e->getMessage());
        }
    }

    /**
     * Show the database selection form.
     *
     * @param DbiRequest $dbiRequest
     * @return \Illuminate\View\View
     */
    public function selectdb(DbiRequest $dbiRequest)
    {
        $this->authorize('update', $dbiRequest);

        try {
            $markets = $this->marketService->getAllMarkets();
            
            $selectedMarket = $dbiRequest->sw_version ?? '';
            $selectedDbUser = $dbiRequest->db_user ?? '';
            $selectedProdInstance = $dbiRequest->prod_instance ?? '';
            $selectedTestInstance = $dbiRequest->test_instance ?? '';
            $sourceCode = $dbiRequest->source_code ?? '';

            Log::info('DBI Request select DB page accessed', [
                'user_id' => auth()->id(),
                'dbi_request_id' => $dbiRequest->id,
                'selected_market' => $selectedMarket,
                'selected_db_user' => $selectedDbUser
            ]);

            return view('dbi.selectdb', compact(
                'dbiRequest',
                'markets',
                'selectedMarket',
                'selectedDbUser',
                'selectedProdInstance',
                'selectedTestInstance',
                'sourceCode'
            ));
        } catch (\Exception $e) {
            Log::error('Error accessing DBI Request select DB page', [
                'user_id' => auth()->id(),
                'dbi_request_id' => $dbiRequest->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('dbi.index')->with('error', 'An error occurred while accessing the select DB page. Please try again.');
        }
    }

    /**
     * Get DB User
     *
     * @param SelectDbRequest $request
     * @param DbiRequest $dbiRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDbUser(Request $request)
    {
        try {
            $swVersion = $request->input('sw_version');

            Log::info('Fetching DB user for market', [
                'user_id' => auth()->id(),
                'sw_version' => $swVersion
            ]);

            $dbUser = DatabaseInfo::get();

            $marketDB = DbInstance::where('market_id', $swVersion)->get();

            $databaseUsersdata = [
                "dbuser" => $dbUser,
                "marketDB" => $marketDB
            ];

            Log::info('DB user fetched successfully', [
                'user_id' => auth()->id(),
                'sw_version' => $swVersion,
                'db_user_count' => count($dbUser),
                'market_db_count' => count($marketDB)
            ]);

            return response()->json($databaseUsersdata);

        } catch (\Exception $e) {
            Log::error('Error fetching DB user', [
                'user_id' => auth()->id(),
                'sw_version' => $swVersion ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'An error occurred while fetching the DB user. Please try again.'
            ], 500);
        }
    }

    /**
     * Update the selected database for a DBI request.
     *
     * @param SelectDbRequest $request
     * @param DbiRequest $dbiRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSelectDb(Request $request, DbiRequest $dbiRequest)
    {
        $this->authorize('update', $dbiRequest);

        $validationRules = [
            'source_code' => 'required|string',
        ];

        // Only validate these fields if they're not already set
        if (!$dbiRequest->sw_version) {
            $validationRules['sw_version'] = 'required';
        }
        if (!$dbiRequest->db_user) {
            $validationRules['db_user'] = 'required';
        }
        
        if (!$dbiRequest->prod_instance) {
            $validationRules['prod_instance'] = 'required';
        }
        if (!$dbiRequest->test_instance) {
            $validationRules['test_instance'] = 'required';
        }

        $validatedData = $request->validate($validationRules);
        //dd($validatedData);
        try {
            // Merge the existing data with the new data
            $updatedData = array_merge([
                'sw_version' => $dbiRequest->sw_version,
                'db_user' => $dbiRequest->db_user,
                'prod_instance' => $dbiRequest->prod_instance,
                'test_instance' => $dbiRequest->test_instance,
            ], $validatedData);
            
            $this->dbiRequestService->updateDbiRequest($dbiRequest, $updatedData);

            $this->dbiRequestLogService->log($dbiRequest->id, "DBI Request db selection updated successfully.", $updatedData);

            Log::info('DBI Request select DB updated', [
                'user_id' => auth()->id(),
                'dbi_request_id' => $dbiRequest->id,
            ]);

            return redirect()->route('dbi.createsqlfile', $dbiRequest->id)->with('success', 'DBI Request updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating DBI Request select DB', [
                'user_id' => auth()->id(),
                'dbi_request_id' => $dbiRequest->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->dbiRequestLogService->log($dbiRequest->id, "DBI Request db selection failed.", $e->getMessage());

            return redirect()->back()->with('error', 'Failed to update DBI Request: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the SQL file creation form.
     *
     * @param DbiRequest $dbiRequest
     * @return \Illuminate\View\View
     */
    public function createsqlfile(DbiRequest $dbiRequest)
    {
        $this->authorize('update', $dbiRequest);

        return view('dbi.createsqlfile', compact('dbiRequest'));
    }

    /**
     * Display the test DBI form for a DbiRequest.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $dbiRequestId
     * @return \Illuminate\View\View
     */
    public function testDBI(Request $request, $dbiRequestId)
    {
        // Find the DbiRequest by ID
        $dbiRequest = DbiRequest::findOrFail($dbiRequestId);
        $prodTest = "No";

        // Return the test DBI view with the DbiRequest data
        return view('dbi.testDBI', compact('dbiRequest', "prodTest"));
    }

    /**
     * Execute the DBI query.
     *
     * @param ExecuteDbiQueryRequest $request
     * @param DbiRequest $dbiRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function executeDbiQuery(ExecuteDbiQueryRequest $request, DbiRequest $dbiRequest)
    {
        $this->authorize('executeQuery', $dbiRequest);

        try {
            $result = $this->dbiRequestService->executeDbiQuery($dbiRequest, $request->prodTest === "Yes");
            $logmessage = $request->prodTest === "Yes" ? "SQL query executed successfully on Production." : "SQL query executed successfully on PreProduction.";

            $this->dbiRequestLogService->log($dbiRequest->id, $logmessage, $dbiRequest);

            $message = $result['status'] === 'success' ? 'SQL query executed successfully. Log file generated.' : 'SQL query execution failed. Please check the logs.';
            return redirect()->route('dbi.show', $dbiRequest->id)->with($result['status'], $message);
        } catch (\Exception $e) {
            $logmessage = $request->prodTest === "Yes" ? "SQL query executed failed on Production." : "SQL query executed failed PreProduction.";

            $this->dbiRequestLogService->log($dbiRequest->id, $logmessage, $e->getMessage());

            return redirect()->back()->with('error', 'An error occurred while executing the SQL query: ' . $e->getMessage());
        }
    }

    /**
     * Show the additional information form.
     *
     * @param DbiRequest $dbiRequest
     * @return \Illuminate\View\View
     */
    public function additionalinfo(DbiRequest $dbiRequest)
    {
        $this->authorize('update', $dbiRequest);

        $temporaryTables = $dbiRequest->temporaryTables;
        return view('dbi.additionalinfo', compact('dbiRequest', 'temporaryTables'));
    }

    /**
     * Store temporary tables for a DBI request.
     *
     * @param StoreTemporaryTableRequest $request
     * @param DbiRequest $dbiRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeTemporaryTable(StoreTemporaryTableRequest $request, DbiRequest $dbiRequest)
    {
        $this->authorize('manageTemporaryTables', $dbiRequest);

        try {
            $this->dbiRequestService->storeTemporaryTables($dbiRequest, $request->validated());
            return redirect()->route('dbi.additionalinfo', $dbiRequest->id)->with('success', 'Temporary tables stored successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to store temporary tables: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Submit the DBI request to SDE.
     *
     * @param DbiRequest $dbiRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitToSDE(DbiRequest $dbiRequest)
    {
        $this->authorize('submitToSDE', $dbiRequest);

        try {
            $this->dbiRequestService->submitToSDE($dbiRequest);

            $this->dbiRequestLogService->log($dbiRequest->id, "DBI Request submitted to SDE successfully.", $dbiRequest->dbiRequestStatus());

            return redirect()->route('dbi.show', $dbiRequest->id)->with('success', 'DBI Request submitted to SDE successfully.');
        } catch (\Exception $e) {
            $this->dbiRequestLogService->log($dbiRequest->id, "Failed to submit DBI Request to SDE:", $e->getMessage());

            return redirect()->back()->with('error', 'Failed to submit DBI Request to SDE: ' . $e->getMessage());
        }
    }

    /**
     * SDE approval or rejection of the DBI request.
     *
     * @param UpdateRequestStatusRequest $request
     * @param DbiRequest $dbiRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sdeApproveOrReject(Request $request, DbiRequest $dbiRequest)
    {
        $this->authorize('approveOrReject', $dbiRequest);

        try {
            Log::info('SDE approval/rejection process started', [
                'dbi_request_id' => $dbiRequest->id,
                'user_id' => auth()->id(),
                'action' => $request->input('approvalorreject')
            ]);

            $validatedData = $request->validate([
                'approvalorreject' => 'required|in:approve,reject',
                'operator_comment' => 'nullable|array',
                'operator_comment.*' => 'string',
            ]);

            $result = $this->dbiRequestService->sdeApproveOrReject($dbiRequest, $validatedData);

            
            $message = $result['status'] === 'approved' ? 'DBI Request approved by SDE successfully.' : 'DBI Request rejected by SDE.';

            $this->dbiRequestLogService->log($dbiRequest->id, $message, $validatedData);
            
            Log::info('SDE approval/rejection process completed', [
                'dbi_request_id' => $dbiRequest->id,
                'status' => $result['status'],
                'user_id' => auth()->id()
            ]);

            return redirect()->route('dbi.show', $dbiRequest->id)->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Failed to process SDE decision', [
                'dbi_request_id' => $dbiRequest->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            $this->dbiRequestLogService->log($dbiRequest->id, "Failed to process SDE decision", $e->getMessage());

            return redirect()->back()->with('error', 'Failed to process SDE decision: ' . $e->getMessage());
        }
    }

    /**
     * DAT approval or rejection of the DBI request.
     *
     * @param UpdateRequestStatusRequest $request
     * @param DbiRequest $dbiRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function datApprovedOrReject(Request $request, DbiRequest $dbiRequest)
    {
        $this->authorize('approveOrReject', $dbiRequest);

        try {
            Log::info('SDE approval/rejection process started', [
                'dbi_request_id' => $dbiRequest->id,
                'user_id' => auth()->id(),
                'action' => $request->input('approvalorreject')
            ]);

            $validatedData = $request->validate([
                'approvalorreject' => 'required|in:approve,reject',
                'operator_comment' => 'nullable|array',
                'operator_comment.*' => 'string',
            ]);

            $result = $this->dbiRequestService->datApproveOrReject($dbiRequest, $validatedData);

            $message = $result['status'] === 'approved' ? 'DBI Request approved by DAT successfully.' : 'DBI Request rejected by DAT.';

            $this->dbiRequestLogService->log($dbiRequest->id, $message, $validatedData);

            Log::info('DAT approval/rejection process completed', [
                'dbi_request_id' => $dbiRequest->id,
                'status' => $result['status'],
                'user_id' => auth()->id()
            ]);

            return redirect()->route('dbi.show', $dbiRequest->id)->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Failed to process SDE decision', [
                'dbi_request_id' => $dbiRequest->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            $this->dbiRequestLogService->log($dbiRequest->id, "Failed to process SDE decision", $e->getMessage());

            return redirect()->back()->with('error', 'Failed to process SDE decision: ' . $e->getMessage());
        }
    }

    /**
     * Display the DBI request logs.
     *
     * @param DbiRequest $dbiRequest
     * @return \Illuminate\View\View
     */
    public function showLogs($id)
    {
        $dbiRequestLog = DbiRequestLog::where('id', $id)->first();

        //$logFile = storage_path('dbilogs/' . $id . '_dbi_request.log');
        $logFile = storage_path('app/sql_logs/' . $dbiRequestLog->log_file);

        if (file_exists($logFile)) {
            return response()->file($logFile, [
                'Content-Type' => 'text/plain',
                'Content-Disposition' => 'inline; '
            ]);
        } else {
            abort(404, 'Log file not found.');
        }
    }

    /**
     * Display the content of a specific log file.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function viewLogFile($id)
    {
        try {
            $content = $this->logService->getLogContent($id);
            return response($content, 200)->header('Content-Type', 'text/plain');
        } catch (\Exception $e) {
            return response('Log file not found.', 404);
        }
    }

    /**
     * Display all SQL files for a DBI request.
     *
     * @param DbiRequest $dbiRequest
     * @return \Illuminate\View\View
     */
    public function allSqlFiles($id)
    {
        try {
            $dbiRequestsql = DbiRequestSQL::with(['dbiRequest.requestor'])
                ->where('dbi_request_id', $id)
                ->paginate(10); // Adjust the number per page as needed

            Log::info('Accessed all SQL files for DBI Request', [
                'user_id' => auth()->id(),
                'dbi_request_id' => $id
            ]);

            return view('dbi.allsqlfiles', compact('dbiRequestsql'));
        } catch (\Exception $e) {
            Log::error('Error accessing all SQL files for DBI Request', [
                'user_id' => auth()->id(),
                'dbi_request_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('dbi.index')->with('error', 'An error occurred while retrieving SQL files. Please try again.');
        }
    }

    /**
     * Display the content of a specific SQL file.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function showSQL($id)
    {
        try {
            $dbiRequestSQL = DbiRequestSQL::findOrFail($id);
            $filePath = storage_path('app/public/source_code_files/' . $dbiRequestSQL->sql_file);

            if (file_exists($filePath)) {
                return response()->file($filePath, [
                    'Content-Type' => 'text/plain',
                    'Content-Disposition' => 'inline; filename="' . $dbiRequestSQL->sql_file . '"'
                ]);
            } else {
                throw new \Exception('SQL file not found.');
            }
        } catch (\Exception $e) {
            Log::error('Error showing SQL file', [
                'user_id' => auth()->id(),
                'dbi_request_sql_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Unable to display SQL file. ' . $e->getMessage());
        }
    }

    /**
     * Display the content of a specific dbi request flow.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function requestProcess($id)
    {
        try {
            $filePath = storage_path('app/public/logfiles/dbi_request_' . $id . '_log.txt');

            if (file_exists($filePath)) {
                return response()->file($filePath, [
                    'Content-Type' => 'text/plain',
                    'Content-Disposition' => 'inline; filename="dbi_request_' . $id . '_log.txt"'
                ]);
            } else {
                throw new \Exception('SQL file not found.');
            }
        } catch (\Exception $e) {
            Log::error('Error showing SQL file', [
                'user_id' => auth()->id(),
                'dbi_request_sql_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Unable to display SQL file. ' . $e->getMessage());
        }
    }

    /**
     * Display the content of a specific SQL file.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function viewSqlFile($id)
    {
        try {
            $content = $this->sqlService->getSqlContent($id);
            return response($content, 200)->header('Content-Type', 'text/plain');
        } catch (\Exception $e) {
            return response('SQL file not found.', 404);
        }
    }

    /**
     * Display all logs for a specific DBI request.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function allLogs($id)
    {
        $dbiRequest = DbiRequest::findOrFail($id);
        $this->authorize('view', $dbiRequest);

        $logs = $this->logService->getAllLogs($dbiRequest->id);

        return view('dbi.allLogs', compact('dbiRequest', 'logs'));
    }
}