<?php

namespace App\Http\Controllers\DBI;

use Illuminate\Http\Request;
use App\Models\DbiRequest;
use App\Models\Category;
use App\Models\Priority;
use App\Models\User;
use App\Models\Market;
use App\Models\DbiType;
use App\Models\DbiStatus;
use App\Models\TemporaryTable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\DatabaseInfo;
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
                $dbiRequests = DbiRequest::all();
            } else if(Auth::user()->userRoles[0]->name === 'SDE') {
                $dbiRequests = DbiRequest::where('operator_id', Auth::user()->id)->get();
            } else {
                $dbiRequests = DbiRequest::where('requestor_id', Auth::user()->id)->get();
            }
            
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
        $markets = Market::all();
        $dbiTypes = DbiType::all();

        // Render the create form
        return view('dbi.create', compact('categories', 'priorities', 'markets', 'dbiTypes'));
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
            'sw_version' => 'required',
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
            // Find the DbiRequest to edit
            $dbiRequest = DbiRequest::findOrFail($id);

            // Log access to edit DbiRequest page
            Log::info('Dbi Request edit. DbiRequestController::edit()' . ' User id:' . Auth::user()->id . ' email: ' . Auth::user()->email);
            return view('dbi.edit', compact('dbiRequest'));
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
            Log::info('Dbi Request updated successfully. DbiRequestController::update() User id:' . Auth::user()->id . ' email: ' . Auth::user()->email . ' Data: ' . $dbiRequest);
            return redirect()->route('dbi.index')->with('success', 'Dbi Request updated successfully.');
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
        // Retrieve the list of databases grouped by db_user_name
        $dbList = DatabaseInfo::select('db_user_name', DB::raw('CAST(LISTAGG(DISTINCT db_name, \',\') WITHIN GROUP (ORDER BY db_name) AS VARCHAR2(4000)) AS db_names'))
            ->groupBy('db_user_name')
            ->get();

        return view('dbi.selectdb', compact('dbiRequest', 'dbList'));
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
        // Validate the request data
        $validatedData = $request->validate([
            'db_user' => 'required',
            'source_code' => 'required',
            'db_instance' => 'required',
        ]);

        try {
            // Update the DbiRequest with the validated data
            $dbiRequest->update($validatedData);

            // Create a new DbiStatus for the current user
            $dbiStatus = new DbiStatus();
            $dbiStatus->dbi_id = $dbiRequest->id;
            $dbiStatus->user_id = Auth::user()->id;
            $dbiStatus->status_detail = "DB Selected";
            $dbiStatus->filled = 2;
            $dbiStatus->save();

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
        // Validate the request data
        $validatedData = $request->validate([
            'db_user' => 'required',
            'db_instance' => 'required',
            'source_code' => 'required',
        ]);

        // Find the database information based on the validated data
        $databaseInfo = DatabaseInfo::where('db_user_name', $validatedData['db_user'])
            ->where('db_name', $validatedData['db_instance'])
            ->first();

        // If the database information is not found, log an error and redirect back with an error message
        if (!$databaseInfo) {
            Log::error('Error Database info ' . $dbiRequest->id . '. Error: ');
            return redirect()->back()->with('error', 'Database information not found.');
        }

        // Decrypt the password
        $decryptedPassword = Crypt::decryptString($databaseInfo->db_user_password);

        // Generate the SQL log file name
        $outputFileName = 'output' . $dbiRequest->id . '_' . time() . '.txt';

        // Get the necessary data for executing the SQL query
        $dbUser = $dbiRequest->db_user;
        $dbPassword = $decryptedPassword;
        $dbInstance = $dbiRequest->db_instance;
        $sourceCode = $dbiRequest->source_code;

        $tempFile = tempnam(sys_get_temp_dir(), 'sql_script');
        File::put($tempFile, $sourceCode);

        $command = "sqlplus  $dbUser/$dbPassword@$dbInstance @$tempFile 2>&1";
        $terminalLog = shell_exec($command);

        if ($terminalLog === false) {
            echo "Command execution failed.\n";
        } else {
            $logFile = storage_path('app/sql_logs/' . $outputFileName);
            File::put($logFile, $terminalLog);
            echo "SQL script executed successfully.\n";
        }

        // Remove the temporary file
        File::delete($tempFile);

        // Store the file output in the sql_log_info field
        $dbiRequest->sql_log_file = $outputFileName;
        $dbiRequest->sql_logs_info = $terminalLog;
        $dbiRequest->save();

        // Create a new DbiStatus for the current user
        $dbiStatus = new DbiStatus();
        $dbiStatus->dbi_id = $dbiRequest->id;
        $dbiStatus->user_id = Auth::user()->id;
        $dbiStatus->status_detail = "Test DBI";
        $dbiStatus->filled = 4;
        $dbiStatus->save();

        // Set the command for executing SQLplus
        // $command = "sqlplus -s /nolog";

        // // Set the log file path and open it in append mode
        // $logFile = storage_path('app/sql_logs/' . $outputFileName);
        // $logFileHandle = fopen($logFile, 'a');

        // // Set the descriptor specification for the process
        // $descriptorSpec = [
        //     0 => ["pipe", "r"], // stdin
        //     1 => ["pipe", "w"], // stdout
        //     2 => ["pipe", "w"]  // stderr
        // ];

        // // Open the process for executing the command
        // $process = proc_open($command, $descriptorSpec, $pipes);

        // // If the process is successfully opened
        // if (is_resource($process)) {
        //     // Write each line of the query to the log file
        //     fwrite($logFileHandle, "connect $dbUser/$dbPassword@$dbInstance\n");
        //     fwrite($logFileHandle, "$sourceCode\n");
        //     fwrite($logFileHandle, "exit;\n");

        //     // Write each line of the query to the stdin pipe
        //     fwrite($pipes[0], "connect $dbUser/$dbPassword@$dbInstance\n");
        //     fwrite($pipes[0], "$sourceCode\n");
        //     fwrite($pipes[0], "exit;\n");
        //     fclose($pipes[0]);

        //     // Initialize the terminal log variable
        //     $terminalLog = '';

        //     // Read the output from the stdout pipe and append it to the terminal log and log file
        //     while (!feof($pipes[1])) {
        //         $line = fgets($pipes[1]);
        //         $terminalLog .= $line;
        //         fwrite($logFileHandle, $line);
        //     }
        //     fclose($pipes[1]);

        //     // Read the error output from the stderr pipe and append it to the terminal log and log file
        //     while (!feof($pipes[2])) {
        //         $line = fgets($pipes[2]);
        //         $terminalLog .= $line;
        //         fwrite($logFileHandle, $line);
        //     }
        //     fclose($pipes[2]);

        //     // Close the process and get the return value
        //     $returnValue = proc_close($process);

        //     // Log the SQLplus command return value
        //     Log::info('SQLplus command return value: ' . $returnValue);
        // }

        // // Close the log file handle
        // fclose($logFileHandle);

        // // Store the file output in the sql_log_info field
        // $dbiRequest->sql_log_file = $outputFileName;
        // $dbiRequest->sql_logs_info = $terminalLog;
        // $dbiRequest->save();

        // Log the action
        Log::info('SQL query executed and log file generated for DBI request: ' . $dbiRequest->id);

        // Redirect back with a success message
        return redirect()->route('dbi.show', $dbiRequest->id);
        //return redirect()->back()->with('success', 'SQL query executed successfully. Log file generated.');
    } catch (\Exception $e) {
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

        // Redirect back with an error message
        return redirect()->back()->with('error', 'An error occurred while executing the SQL query. Error log generated.');
    }
}
}