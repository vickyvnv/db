<?php

namespace App\Http\Controllers\DBI;

use Illuminate\Http\Request;
use App\Models\DbiRequest;
use App\Models\Category;
use App\Models\Priority;
use App\Models\DbiRequestStatus;
use App\Models\User;
use App\Models\Market;
use App\Models\DbiType;
use App\Models\DbiStatus;
use App\Models\TemporaryTable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\DatabaseInfo;
use App\Models\DbInstance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\File;


class DbiRequestController extends Controller
{
    /**
     * Display the DBI request list.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Fetch all DbiRequests
            if (Auth::user()->userRoles[0]->name === 'DAT') {
                $dbiRequests = DbiRequest::with(['requestor' => function($query) {
                    $query->select('id', 'user_firstname', 'user_lastname', 'email');
                }, 'operator' => function($query) {
                    $query->select('id', 'user_firstname', 'user_lastname', 'email');
                }])
                ->where('is_requestor_submit', 1)
                ->where('is_operator_approve', 1)
                ->orWhere('requestor_id', Auth::user()->id)
                ->get();
                //$dbiRequests = DbiRequest::with('requestor', 'operator')->get();
            } else if(Auth::user()->userRoles[0]->name === 'SDE') {
                $dbiRequests = DbiRequest::with(['requestor' => function($query) {
                    $query->select('id', 'user_firstname', 'user_lastname', 'email');
                }, 'operator' => function($query) {
                    $query->select('id', 'user_firstname', 'user_lastname', 'email');
                }])
                ->where('operator_id', Auth::user()->id)
                ->where('is_requestor_submit', 1)
                ->get();
                //$dbiRequests = DbiRequest::with('requestor', 'operator')->where('operator_id', Auth::user()->id)->get();
            } else {
                $dbiRequests = DbiRequest::with(['requestor' => function($query) {
                    $query->select('id', 'user_firstname', 'user_lastname', 'email');
                }, 'operator' => function($query) {
                    $query->select('id', 'user_firstname', 'user_lastname', 'email');
                }])
                ->where('requestor_id', Auth::user()->id)
                ->get();
            }
            //dd($dbiRequests);
            // Log successful retrieval of DbiRequests
            Log::channel('daily')->info('Fetched all Dbi Requests successfully DbiRequestController::index(). User id:' . Auth::user()->id . ' email: ' . Auth::user()->email);

            return view('dbi.index', compact('dbiRequests'));
        } catch (\Exception $e) {
            // Log error if fetching DbiRequests fails
            Log::channel('daily')->error('Error occurred while fetching Dbi Requests: ' . $e->getMessage() . 'DbiRequestController::index()    User id:' . Auth::user()->id . ' email: ' . Auth::user()->email);

            return redirect()->back()->with('error', 'Failed to fetch Dbi Requests.');
        }
    }

    /**
     * Display the DBI request create form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Retrieve all categories, priorities, markets, and dbiTypes
        $categories = Category::all();
        $priorities = Priority::all();
        
        $dbiTypes = DbiType::all();

        // Render the create form
        return view('dbi.create', compact('categories', 'priorities', 'dbiTypes'));
    }

    /**
     * Store a newly created DbiRequest.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Define validation rules
        $rules = [
            'category' => 'required',
            'priority_id' => 'required',
            //'sw_version' => 'required',
            'tt_id' => 'required',
            'serf_cr_id' => 'required',
            'reference_dbi' => 'required',
            'brief_desc' => 'required',
            'problem_desc' => 'required',
            'business_impact' => 'required',
        ];

        // Add dbi_type validation rule if category is not 'SP'
        if ($request->input('category') !== 'SP') {
            $rules['dbi_type'] = 'required';
        }

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        // Redirect back with errors if validation fails
        if ($validator->fails()) {
            Log::error('Error occurred while creating Dbi Request: ' . $validator->fails() . '  DbiRequestController::store() User id:' . Auth::user()->id . ' email: ' . Auth::user()->email);
            
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userAssigned = User::with('assignedUser')->where('id', Auth::user()->id)->first();
        //dd($userAssigned);
        if ($userAssigned) {
            $assignedUserIds = $userAssigned->assignedUser->pluck('pivot.assigned_user_id')->toArray();
        }
        
        try {
            // Create a new DbiRequest
            $dbiRequest = new DbiRequest();
            $dbiRequest->fill($request->all());
            $dbiRequest->requestor_id = Auth::user()->id;
            $dbiRequest->operator_id = $assignedUserIds == [] ? Auth::user()->id : $assignedUserIds[0];
            $dbiRequest->save();

            // Create a new DbiStatus for the current user
            $dbiStatus = new DbiStatus();
            $dbiStatus->dbi_id = $dbiRequest->id;
            $dbiStatus->user_id = Auth::user()->id;
            $dbiStatus->status_detail = "DBI Created";
            $dbiStatus->filled = 1;
            $dbiStatus->save();

            // Log successful creation of DbiRequest
            Log::channel('daily')->info('Dbi Request created successfully. DbiRequestController::store()' . 'User id:' . Auth::user()->id . ' email: ' . Auth::user()->email);

            return redirect()->route('dbi.selectdb', $dbiRequest->id)->with('success', 'Dbi Request created successfully.');
        } catch (\Exception $e) {
            // Log error if creating DbiRequest fails
            Log::error('Error occurred while creating Dbi Request: ' . $e->getMessage() . '  DbiRequestController::store() User id:' . Auth::user()->id . ' email: ' . Auth::user()->email);
            return redirect()->back()->with('error', 'Failed to create Dbi Request.');
        }
    }

    /**
     * Display the specified DbiRequest.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            // Find the specified DbiRequest
            $dbiRequest = DbiRequest::findOrFail($id);

            $userAssigned = User::with('userRoles')->whereIn('id', [$dbiRequest->requestor_id, $dbiRequest->operator_id])->get();
            $assigned = $userAssigned->toArray();
            //dd($assigned);
            // Log successful retrieval of DbiRequest
            Log::info('Fetched Dbi Request successfully. DbiRequestController::show()  ' . ' User id:' . Auth::user()->id . ' email: ' . Auth::user()->email);
            return view('dbi.show', compact('dbiRequest', 'assigned'));
        } catch (\Exception $e) {
            // Log error if fetching DbiRequest fails
            Log::error('Error occurred while fetching Dbi Request: DbiRequestController::show()' . $e->getMessage() . ' User id:' . Auth::user()->id . ' email: ' . Auth::user()->email);
            return redirect()->back()->with('error', 'Failed to fetch Dbi Request.');
        }
    }

    /**
     * Display the DbiRequest edit form.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $categories = Category::all();
            $priorities = Priority::all();
            
            $dbiTypes = DbiType::all();

            // Find the DbiRequest to edit
            $dbiRequest = DbiRequest::findOrFail($id);

            // Log access to edit DbiRequest page
            Log::info('Dbi Request edit. DbiRequestController::edit()' . ' User id:' . Auth::user()->id . ' email: ' . Auth::user()->email);
            return view('dbi.edit', compact('dbiRequest', 'categories', 'priorities', 'dbiTypes'));
        } catch (\Exception $e) {
            // Log error if fetching DbiRequest for editing fails
            Log::error('Error occurred while fetching Dbi Request for editing: DbiRequestController::edit()' . $e->getMessage() . 'User id:' . Auth::user()->id . ' email: ' . Auth::user()->email);
            return redirect()->back()->with('error', 'Failed to fetch Dbi Request for editing.');
        }
    }

    /**
     * Update the specified DbiRequest in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'category' => 'required',
            'priority_id' => 'required',
            //'sw_version' => 'required',
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
            Log::info('Dbi Request updated successfully. DbiRequestController::update() User id:' . Auth::user()->id . ' email: ' . Auth::user()->email . ' Data: ' . $dbiRequest);
            return redirect()->route('dbi.selectdb', $id)->with('success', 'Dbi Request updated successfully.');
            //return redirect()->route('dbi.index')->with('success', 'Dbi Request updated successfully.');
        } catch (\Exception $e) {
            // Log error if updating DbiRequest fails
            Log::error('Error occurred while updating Dbi Request: ' . $e->getMessage() . ' DbiRequestController::update() User id:' . Auth::user()->id . ' email: ' . Auth::user()->email);
            return redirect()->back()->with('error', 'Failed to update Dbi Request.');
        }
    }

    /**
     * Remove the specified DbiRequest from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            // Find the specified DbiRequest and delete it
            $dbiRequest = DbiRequest::findOrFail($id);
            $dbiRequest->delete();

            // Log successful deletion of DbiRequest
            Log::info('Dbi Request deleted successfully. DbiRequestController::update() Deleted dbi request:' . $id . ' User id:' . Auth::user()->id . ' email: ' . Auth::user()->email);
            return redirect()->route('dbi.index')->with('success', 'Dbi Request deleted successfully.');
        } catch (\Exception $e) {
            // Log error if deleting DbiRequest fails
            Log::error('Error occurred while deleting Dbi Request: ' . $e->getMessage() . 'User id:' . Auth::user()->id . ' email: ' . Auth::user()->email . 'DbiRequestController::update()');
            return redirect()->back()->with('error', 'Failed to delete Dbi Request.');
        }
    }

    /**
     * Display the database selection form.
     *
     * @param  \App\Models\DbiRequest  $dbiRequest
     * @return \Illuminate\View\View
     */
    public function selectdb(DbiRequest $dbiRequest)
    {
        $markets = Market::all();

        // $username = 'DB_DEBIT';
        // $tables = DB::connection('oracle')
        //     ->select("SELECT TABLE_NAME FROM ALL_TABLES WHERE OWNER = ?", [$username]);
        // dd($tables);

        // $usernames = ["MNDBARW", "DB_CREDIT", "DB_DEBIT", "TOAKDBI", "RFEGADE"];
        // $placeholders = implode(',', array_fill(0, count($usernames), '?'));

        // $users = DB::connection('oracle')
        //     ->select("SELECT * FROM ALL_USERS WHERE USERNAME IN ($placeholders)", $usernames);

       // $users = $connection->select("SELECT USERNAME, ACCOUNT_STATUS, LOCK_DATE, EXPIRY_DATE FROM DBA_USERS WHERE USERNAME = 'DB_NK'");
        //dd($users);
        // Retrieve the list of databases grouped by db_user_name
        // $dbList = DatabaseInfo::select('db_user_name', DB::raw('CAST(LISTAGG(DISTINCT db_name, \',\') WITHIN GROUP (ORDER BY db_name) AS VARCHAR2(4000)) AS db_names'))
        //     ->groupBy('db_user_name')
        //     ->get();

        return view('dbi.selectdb', compact('dbiRequest', 'markets'));
    }

    public function getDbUser(Request $request)
    {
        $dbUser = Databaseinfo::get();

        try {
            // $marketDB = DB::connection('oracle')
            //     ->select("select * from $dbUser.DBI_INSTANCE");
            $marketDB = DbInstance::where('market_id', $request->sw_version)->get();

        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            
            if ($errorCode === '942') {
                // Handle the specific error when the table or view does not exist
                $marketDB = [];
                $errorMessage = "The table or view '$dbUser.DBI_INSTANCE' does not exist.";
            } else {
                // Handle other types of database errors
                $marketDB = [];
                $errorMessage = "An error occurred while fetching data from the database.";
            }
            
            // Log the error for debugging purposes
            //\Log::error($errorMessage);
        }
        
        $databaseUsersdata = [
            "dbuser" => $dbUser,
            "marketDB" => $marketDB,
            "error" => isset($errorMessage) ? $errorMessage : null
        ];
        //dd($databaseUsersdata);
        return response()->json($databaseUsersdata);
    }

    /**
     * Update the selected database for a DbiRequest.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DbiRequest  $dbiRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSelectDb(Request $request, DbiRequest $dbiRequest)
    {
        //dd($request);
        // Validate the request data
        $validatedData = $request->validate([
            'sw_version' => 'required',
            'db_user' => 'required',
            'source_code' => 'required',
            'prod_instance' => 'required',
            'test_instance' => 'required',
        ]);

        try {
            // Update the DbiRequest with the validated data
            $dbiRequest->update($validatedData);

            // Create a new DbiStatus for the current user
            // Check if the dbiStatus already exists for the given dbiRequest and user
            $dbiStatus = DbiStatus::where('id', $dbiRequest->id)
            ->where('user_id', Auth::user()->id)
            ->where('filled', 2)
            ->first();

            if ($dbiStatus) {
                // If the dbiStatus exists, update it
                $dbiStatus->status_detail = "DB Selected";
                $dbiStatus->filled = 2;
                $dbiStatus->save();
            } else {
                // If the dbiStatus doesn't exist, create a new one
                $dbiStatus = new DbiStatus();
                $dbiStatus->dbi_id = $dbiRequest->id;
                $dbiStatus->user_id = Auth::user()->id;
                $dbiStatus->status_detail = "DB Selected";
                $dbiStatus->filled = 2;
                $dbiStatus->save();
            }

            // Generate the SQL file name and save the source code content
            $sqlfile = 'dbi_' . $dbiRequest->id . '_' . time() . '.sql';
            $sourceCodeFilePath = storage_path('app/source_code_files/' . $sqlfile);
            file_put_contents($sourceCodeFilePath, $validatedData['source_code']);

            // Update the DbiRequest with the SQL file path and db_user
            $dbiRequest->sql_file_path = $sqlfile;
            $dbiRequest->db_user = $validatedData['db_user'];
            $dbiRequest->save();

            // Log successful update of DbiRequest
            Log::channel('daily')->info('Dbi Request updated successfully. DbiRequestController::updateSelectDb()' . 'User id:' . Auth::user()->id . ' email: ' . Auth::user()->email);

            return redirect()->route('dbi.createsqlfile', $dbiRequest->id)->with('success', 'Database selected successfully.');
        } catch (\Exception $e) {
            // Log error if updating DbiRequest fails
            Log::error('Error occurred while updating Dbi Request: ' . $e->getMessage() . ' DbiRequestController::updateSelectDb() User id:' . Auth::user()->id . ' email: ' . Auth::user()->email);
            return redirect()->back()->with('error', 'Failed to select database.');
        }
    }

    /**
     * Display the SQL file creation form.
     *
     * @param  \App\Models\DbiRequest  $dbiRequest
     * @return \Illuminate\View\View
     */
    public function createsqlfile(DbiRequest $dbiRequest)
    {
        return view('dbi.createsqlfile', compact('dbiRequest'));
    }

    /**
     * Display the additional information form for a DbiRequest.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $dbiRequestId
     * @return \Illuminate\View\View
     */
    public function additionalinfo(Request $request, $dbiRequestId)
    {
        // Find the DbiRequest by ID
        $dbiRequest = DbiRequest::findOrFail($dbiRequestId);

        // Retrieve the temporary tables for the DbiRequest
        $temporaryTables = TemporaryTable::where('dbi_request_id', $dbiRequestId)->get();

        return view('dbi.additionalinfo', compact('dbiRequest', 'temporaryTables'));
    }

    /**
     * Store temporary tables for a DbiRequest.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $dbiRequestId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeTemporaryTable(Request $request, $dbiRequestId)
    {
        // Find the DbiRequest by ID
        $dbiRequest = DbiRequest::findOrFail($dbiRequestId);

        // Get the input data from the request
        $instances = $request->input('instance');
        $users = $request->input('user');
        $tables = $request->input('table');
        $types = $request->input('type');
        $dropDates = $request->input('drop_date');
        $sqls = $request->input('sql');

        // Loop through the instances and create temporary tables
        for ($i = 0; $i < count($instances); $i++) {
            $temporaryTable = new TemporaryTable();
            $temporaryTable->dbi_request_id = $dbiRequest->id;
            $temporaryTable->user_id = Auth::user()->id;
            $temporaryTable->table_name = $tables[$i];
            $temporaryTable->type = $types[$i];
            $temporaryTable->drop_date = $dropDates[$i];
            $temporaryTable->sql = $sqls[$i];
            $temporaryTable->save();

            // Create the temporary table in the database
            DB::statement('CREATE TABLE ' . $temporaryTable->table_name . ' (' . $temporaryTable->sql . ')');
        }

        // Create a new DbiStatus for the current user
        $dbiStatus = new DbiStatus();
        $dbiStatus->dbi_id = $dbiRequest->id;
        $dbiStatus->user_id = Auth::user()->id;
        $dbiStatus->status_detail = "Additional Info";
        $dbiStatus->filled = 3;
        $dbiStatus->save();

        // Redirect to the additional info route for the DbiRequest
        return redirect()->route('dbi.additionalinfo', $dbiRequest->id);
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

        // Return the test DBI view with the DbiRequest data
        return view('dbi.testDBI', compact('dbiRequest'));
    }

    /**
     * Execute the test DBI query for a DbiRequest.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DbiRequest  $dbiRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function testDbiQuery(Request $request, DbiRequest $dbiRequest)
    {
        try {
            DB::beginTransaction(); // Start a database transaction

            // // Find the database information based on the validated data
            $databaseInfo = DatabaseInfo::where('db_user_name', $dbiRequest->db_user)
                ->firstOrFail();

            // // Decrypt the password
            $decryptedPassword = Crypt::decryptString($databaseInfo->db_user_password);

            // Generate the SQL log file name
            $outputFileName = 'output' . $dbiRequest->id . '_' . time() . '.txt';

            $dbUser = $dbiRequest->db_user;  //$dbiRequest->db_user;   // mndbarw
            $dbPassword = $decryptedPassword;   //$decryptedPassword;  // Mndb_123
            $dbtestInstance = $dbiRequest->test_instance; //$dbiRequest->db_instance;  // U_PORCL1
            $sourceCode = $dbiRequest->source_code; //$dbiRequest->source_code;  // INSERT INTO test (id, name) VALUES (1, 'vicky');
            //dd($dbtestInstance);
            // Modify the source code to set the current schema and execute the insert query
            $modifiedSourceCode = "ALTER SESSION SET CURRENT_SCHEMA = $dbtestInstance;\n";
            $modifiedSourceCode .= $sourceCode . ";\n";
            $modifiedSourceCode .= "COMMIT;";
            
            $tempFile = tempnam(sys_get_temp_dir(), 'sql_script');
            File::put($tempFile, $modifiedSourceCode);

            $command = "sqlplus $dbUser/$dbPassword @$tempFile 2>&1";
            $terminalLog = shell_exec($command);
            //dd($terminalLog);
            // Get the current date and time
            $currentDate = date('D M d H:i:s Y');

            // Prepare the additional information
            $additionalInfo = "Date: $currentDate" . PHP_EOL;
            $additionalInfo .= "DBI No.: $dbiRequest->id" . PHP_EOL;
            $additionalInfo .= "DB: $dbtestInstance" . PHP_EOL;
            $additionalInfo .= "DB-User: $dbUser" . PHP_EOL;
            $additionalInfo .= "Requestor: " . $dbiRequest->requestor->user_firstname . " " . $dbiRequest->requestor->user_lastname . " " . PHP_EOL;
            $additionalInfo .= "Operator: " . $dbiRequest->operator->user_firstname . " " . $dbiRequest->operator->user_lastname . "" . PHP_EOL;
            $additionalInfo .= "Team: " . Auth::user()->team->name . PHP_EOL;

            // Prepend the additional information to the $terminalLog
            $terminalLog = $additionalInfo . PHP_EOL . $terminalLog;

            if ($terminalLog === false) {
                Log::error("Command execution failed for DBI request: " . $dbiRequest->id);
                throw new \Exception("Command execution failed.");
            } else {
                $logFile = storage_path('app/sql_logs/' . $outputFileName);
                File::put($logFile, $terminalLog);
                Log::info("SQL script executed successfully for DBI request: " . $dbiRequest->id);
            }

            // Remove the temporary file
            File::delete($tempFile);

            // Store the file output in the sql_log_info field
            $dbiRequest->sql_log_file = $outputFileName;
            $dbiRequest->sql_logs_info = $terminalLog;
            $dbiRequest->save();

            // Check if the dbiStatus already exists for the given dbiRequest and user
            $dbiStatus = DbiStatus::where('dbi_id', $dbiRequest->id)
            ->where('user_id', Auth::user()->id)
            ->first();
            
            // Create a new DbiStatus for the current user
            if ($dbiStatus) {
                // If the dbiStatus exists, update it
                $dbiStatus->status_detail = "Test DBI";
                $dbiStatus->filled = 4;
                $dbiStatus->save();
            } else {
                // If the dbiStatus doesn't exist, create a new one
                $dbiStatus = new DbiStatus();
                $dbiStatus->dbi_id = $dbiRequest->id;
                $dbiStatus->user_id = Auth::user()->id;
                $dbiStatus->status_detail = "Test DBI";
                $dbiStatus->filled = 4;
                $dbiStatus->save();
            }
            
            DB::commit(); // Commit the database transaction

            // Redirect with a success message
            return redirect()->route('dbi.show', $dbiRequest->id)->with('success', 'SQL query executed successfully. Log file generated.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the database transaction

            // Log the error
            Log::error('Error executing SQL query for DBI request: ' . $dbiRequest->id . '. Error: ' . $e->getMessage());

            // Generate the error log file name
            $errorLogFileName = 'error_log_' . $dbiRequest->id . '_' . time() . '.txt';
            $errorLogFilePath = storage_path('app/sql_logs/' . $errorLogFileName);

            // Save the error log file
            file_put_contents($errorLogFilePath, $e->getMessage());

            // Update the dbi_request record with the error log file path, error message, and shell output
            $dbiRequest->sql_log_file = $errorLogFileName;
            $dbiRequest->sql_logs_info = $e->getMessage();
            $dbiRequest->save();

            // Redirect with an error message
            return redirect()->back()->with('error', 'An error occurred while executing the SQL query. Error log generated.');
        }
    }

    /**
     * Execute the submit request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DbiRequest  $dbiRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitToSDE(Request $request, DbiRequest $dbiRequest)
    {
        $dbiRequest->dbiRequestStatus()->updateOrCreate(
            ['request_id' => $dbiRequest->id],
            [
                'request_status' => 1,
                'operator_status' => 0,
                'dat_status' => 0,
            ]
        );

        // Redirect with a success message
        return redirect()->route('dbi.show', $dbiRequest->id)->with('success', 'SQL query executed successfully. Log file generated.');
    }

    /**
     * Execute the submit request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DbiRequest  $dbiRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sdeApprovedOrReject(Request $request, DbiRequest $dbiRequest)
    {
        $dbiRequest->dbiRequestStatus()->updateOrCreate(
            ['request_id' => $dbiRequest->id],
            [
                'dat_status' => 0,
                'request_status' => 1,
                'operator_comment' => $request->operator_comment,
                'operator_status' => ($request->approvalorreject == 'approve') ? 1 : (($request->approvalorreject == 'reject') ? 2 : 0),
            ]
        );

        // Redirect with a success message
        return redirect()->route('dbi.show', $dbiRequest->id)->with('success', 'SQL query executed successfully. Log file generated.');
    }

    /**
     * Execute the submit request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DbiRequest  $dbiRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function datApprovedOrReject(Request $request, DbiRequest $dbiRequest)
    {
        $dbiRequest->dbiRequestStatus()->updateOrCreate(
            ['request_id' => $dbiRequest->id],
            [
                'operator_status' => 1,
                'request_status' => 1,
                'dat_comment' => $request->dat_comment,
                'dat_status' => ($request->approvalorreject == 'approve') ? 1 : (($request->approvalorreject == 'reject') ? 2 : 0),
            ]
        );

        // Redirect with a success message
        return redirect()->route('dbi.show', $dbiRequest->id)->with('success', 'SQL query executed successfully. Log file generated.');
    }
}