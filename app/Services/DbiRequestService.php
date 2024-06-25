<?php

namespace App\Services;

use App\Models\DbiRequest;
use App\Models\OperatorComment;
use App\Models\DbiStatus;
use App\Models\DatabaseInfo;
use App\Models\DbiRequestLog;
use App\Models\DbiRequestSQL;
use App\Models\TemporaryTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use PDO;
use Carbon\Carbon;

class DbiRequestService
{
    /**
     * Get all DBI requests based on user role and permissions.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllDbiRequests()
    {
        $user = Auth::user();
        $query = DbiRequest::with(['requestor', 'operator', 'dbiRequestStatus']);

        if ($user->isDAT()) {
            $query->whereHas('dbiRequestStatus', function($q) {
                $q->whereIn('request_status', [1,3])
                  ->whereIn('operator_status', [1,3]);
            })->orWhere('requestor_id', $user->id);
        } elseif ($user->isSDE()) {
            $query->where('operator_id', $user->id)
                  ->whereHas('dbiRequestStatus', function($q) {
                      $q->whereIn('request_status', [1]);
                  });
        } else {
            $query->where('requestor_id', $user->id);
        }

        return $query->orderBy('updated_at', 'desc')->paginate(10);
    }

    /**
     * Create a new DBI request.
     *
     * @param array $data The validated data for creating the request
     * @param int $userId The ID of the user creating the request
     * @return DbiRequest The created DBI request
     * @throws \Exception if an error occurs during creation
     */
    public function createDbiRequest(array $data, $userId)
    {
        DB::beginTransaction();
        try {
            $opId = Auth::user()->assignedUser[0]->id ?? $userId;
            
            // Create the DBI request
            $dbiRequest = DbiRequest::create(array_merge($data, ['requestor_id' => $userId, 'operator_id' => $opId]));
            
            // Create an initial DBI status
            DbiStatus::create([
                'dbi_id' => $dbiRequest->id,
                'user_id' => $userId,
                'status_detail' => "DBI Created",
                'filled' => 1
            ]);

            // Create an initial request status
            $dbiRequest->dbiRequestStatus()->create([
                'request_status' => 0,
                'operator_status' => 0,
                'dat_status' => 0,
            ]);

            DB::commit();
            return $dbiRequest;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating DBI request: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an existing DBI request.
     *
     * @param DbiRequest $dbiRequest The DBI request to update
     * @param array $data The validated data for updating the request
     * @return DbiRequest The updated DBI request
     * @throws \Exception if an error occurs during update
     */
    public function updateDbiRequest(DbiRequest $dbiRequest, array $data)
    {
        DB::beginTransaction();
        try {
            $fieldsUpdated = [];

            if(isset($data['db_user'])) {
                $dbiRequest->db_user = $data['db_user'];
                $fieldsUpdated[] = 'db_user';
            }
            if(isset($data['sw_version'])) {
                $dbiRequest->sw_version = $data['sw_version'];
                $fieldsUpdated[] = 'sw_version';
            }
            if(isset($data['prod_instance'])) {
                $dbiRequest->prod_instance = $data['prod_instance'];
                $fieldsUpdated[] = 'prod_instance';
            }
            if(isset($data['test_instance'])) {
                $dbiRequest->test_instance = $data['test_instance'];
                $fieldsUpdated[] = 'test_instance';
            }
            if(isset($data['source_code'])) {
                $dbiRequest->source_code = $data['source_code'];
                $fieldsUpdated[] = 'source_code';

                // Generate and save SQL file
                $sqlfile = 'dbi_' . $dbiRequest->id . '_' . time() . '.sql';
                Storage::disk('public')->put('source_code_files/' . $sqlfile, $data['source_code']);
                $dbiRequest->sql_file_path = $sqlfile;
                // Create a record for the SQL file
                DbiRequestSQL::create([
                    'dbi_request_id' => $dbiRequest->id,
                    'sql_file' => $sqlfile,
                ]);
            }

            $dbiRequest->save();

            // Update or create a new DBI status
            DbiStatus::updateOrCreate(
                ['dbi_id' => $dbiRequest->id, 'user_id' => auth()->id()],
                ['status_detail' => "DBI Updated: " . implode(', ', $fieldsUpdated), 'filled' => 2]
            );

            DB::commit();
            return $dbiRequest;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating DBI request: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a DBI request.
     *
     * @param DbiRequest $dbiRequest
     * @return bool
     * @throws \Exception
     */
    public function deleteDbiRequest(DbiRequest $dbiRequest)
    {
        DB::beginTransaction();
        try {
            // Delete associated records
            $dbiRequest->dbiRequestStatus()->delete();
            $dbiRequest->dbiRequestLogs()->delete();
            $dbiRequest->dbiRequestSQLs()->delete();
            $dbiRequest->temporaryTables()->delete();

            // Delete the DBI request itself
            $dbiRequest->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting DBI request: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Execute a DBI query.
     *
     * @param DbiRequest $dbiRequest The DBI request to execute
     * @param bool $isProd Whether this is a production execution
     * @return array The result of the execution
     * @throws \Exception if an error occurs during execution
     */
    public function executeDbiQuery(DbiRequest $dbiRequest, $isProd)
    {
        DB::beginTransaction();
        try {
            $databaseInfo = DatabaseInfo::where('db_user_name', $dbiRequest->db_user)->firstOrFail();
            $decryptedPassword = Crypt::decryptString($databaseInfo->db_user_password);
            
            $outputFileName = $dbiRequest->id . '_output_' . ($isProd ? 'Prod' : 'PreProd') . '_' . time() . '.txt';

            $dbUser = $dbiRequest->db_user;
            $dbPassword = $decryptedPassword;
            $dbInstance = $isProd ? $dbiRequest->prod_instance : $dbiRequest->test_instance;
            
            // Assume $dbiRequest->source_code now contains the SQL or PL/SQL code
            $sourceCode = $dbiRequest->source_code;

            $executionLog = "SQL*Plus: Release 12.2.0.1.0\n";
            $executionLog .= "Copyright (c) 1982, 2022, Oracle.  All rights reserved.\n\n";
            $executionLog .= "Connected to:\n";
            $executionLog .= "Oracle Database 12c Enterprise Edition Release 12.2.0.1.0 - 64bit Production\n\n";

            $dsn = "oci:dbname=//localhost:1521/ocispice";
            $conn = new PDO($dsn, $dbUser, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $executionLog .= "SQL> BEGIN TRANSACTION;\n";
            $conn->beginTransaction();

            $executionLog .= "SQL> ALTER SESSION SET CURRENT_SCHEMA = $dbInstance;\n";
            $stmt = $conn->prepare("ALTER SESSION SET CURRENT_SCHEMA = $dbInstance");
            $stmt->execute();
            $executionLog .= "Session altered.\n\n";

            $executionStatus = 'Execution Successful';

            $sourceCode = trim($dbiRequest->source_code);
            $executionStatus = 'Execution Successful';

            // Function to determine the type of SQL input
            function determineInputType($code) {
                $upperCode = strtoupper($code);
                if (strpos($upperCode, 'CREATE OR REPLACE PACKAGE') !== false ||
                    strpos($upperCode, 'CREATE PACKAGE') !== false) {
                    return 'PACKAGE';
                } elseif (strpos($upperCode, 'BEGIN') !== false ||
                        strpos($upperCode, 'DECLARE') !== false ||
                        strpos($upperCode, 'CREATE OR REPLACE PROCEDURE') !== false ||
                        strpos($upperCode, 'CREATE OR REPLACE FUNCTION') !== false) {
                    return 'PLSQL';
                } else {
                    return 'SQL';
                }
            }

            $inputType = determineInputType($sourceCode);

            $executionLog .= "Detected input type: $inputType\n\n";

            switch ($inputType) {
                case 'PACKAGE':
                case 'PLSQL':
                    $executionLog .= "Executing as PL/SQL:\n$sourceCode\n";
                    try {
                        // For packages, we don't wrap in BEGIN/END
                        if ($inputType !== 'PACKAGE') {
                            // Ensure the PL/SQL block is properly formatted
                            if (strpos(strtoupper($sourceCode), 'BEGIN') === false) {
                                $sourceCode = "BEGIN\n" . $sourceCode . "\nEND;";
                            }
                        }
                        $sourceCode .= "\n";

                        $stmt = $conn->prepare($sourceCode);
                        $stmt->execute();
                        $executionLog .= "PL/SQL execution successful.\n\n";
                    } catch (\PDOException $e) {
                        $executionStatus = 'Execution Failed';
                        $errorInfo = $e->errorInfo;
                        $executionLog .= "Error: " . $errorInfo[2] . "\n\n";
                    }
                    break;

                case 'SQL':
                    $statements = explode(';', $sourceCode);
                    foreach ($statements as $statement) {
                        $statement = trim($statement);
                        if (empty($statement)) continue;

                        $executionLog .= "SQL> $statement;\n";
                        try {
                            $stmt = $conn->prepare($statement);
                            $stmt->execute();
                            $executionLog .= "Statement executed successfully.\n\n";
                        } catch (\PDOException $e) {
                            $executionStatus = 'Execution Failed';
                            $errorInfo = $e->errorInfo;
                            $executionLog .= "Error: " . $errorInfo[2] . "\n\n";
                            break;
                        }
                    }
                    break;
            }

            if ($executionStatus === 'Execution Successful') {
                if (!$isProd) {
                    $executionLog .= "SQL> ROLLBACK;\n";
                    $conn->rollBack();
                    $executionLog .= "Rollback complete.\n\n";
                } else {
                    $executionLog .= "SQL> COMMIT;\n";
                    $conn->commit();
                    $executionLog .= "Commit complete.\n\n";
                }
            } else {
                $executionLog .= "SQL> ROLLBACK;\n";
                $conn->rollBack();
                $executionLog .= "Rollback complete due to execution failure.\n\n";
            }

            $executionLog .= "SQL> QUIT\n";
            $executionLog .= $executionStatus === 'Execution Successful' ? "Procedure successfully completed.\n" : "Procedure failed.\n";

            $queries = DB::getQueryLog();
                $totalExecutionTime = 0;

                foreach ($queries as $query) {
                    $totalExecutionTime += $query['time'];
                }

                // Get the current date and time
                $currentDate = date('D M d H:i:s Y');

                // Prepare the additional information
                $additionalInfo = "Date: $currentDate" . PHP_EOL;
                $additionalInfo .= "DBI No.: $dbiRequest->id" . PHP_EOL;
                $additionalInfo .= "DB: " . ($isProd ? "Prod DB" : "PreProd DB") . PHP_EOL;
                $additionalInfo .= "DB-User: $dbUser" . PHP_EOL;
                $additionalInfo .= '======================================================'. PHP_EOL;
                $additionalInfo .= "Requestor: " . $dbiRequest->requestor->user_firstname . " " . $dbiRequest->requestor->user_lastname . " " . PHP_EOL;
                $additionalInfo .= "Operator: " . $dbiRequest->operator->user_firstname . " " . $dbiRequest->operator->user_lastname . "" . PHP_EOL;
                $additionalInfo .= "Team: " . Auth::user()->team->name . PHP_EOL;
                $additionalInfo .= '======================================================'. PHP_EOL;
                $additionalInfo .= "Database Instance: " . ($isProd == "Yes" ? $dbiRequest->prod_instance : $dbiRequest->test_instance) . PHP_EOL;
                Log::info('Executed Queries:');

                foreach ($queries as $index => $query) {
                    Log::info('Query ' . ($index + 1) . ': ' . $query['query']);
                    $additionalInfo .= 'Query ' . ($index + 1) . ': ' . $query['query']. PHP_EOL;
                    Log::info('Bindings: ' . implode(', ', $query['bindings']));
                    $additionalInfo .= 'Bindings: ' . implode(', ', $query['bindings']). PHP_EOL;
                    Log::info('Time: ' . $query['time'] . ' ms');
                    $additionalInfo .= 'Time: ' . $query['time'] . ' ms'. PHP_EOL;
                }
                

                $additionalInfo .= 'Total Execution Time: ' . $totalExecutionTime . ' ms'. PHP_EOL;
                $additionalInfo .= '======================================================'. PHP_EOL;

                Log::info('Total Execution Time: ' . $totalExecutionTime . ' ms');
                // Prepend the additional information to the $terminalLog
                $terminalLog = $additionalInfo . PHP_EOL . $executionLog;
                
            //Storage::delete($tempFilePath);

            $logFile = $this->generateLogFile($dbiRequest, $terminalLog, $isProd);

            $dbiLogCreate = DbiRequestLog::create([
                'dbi_request_id' => $dbiRequest->id,
                'execution_status' => $executionStatus,
                'log_file' => $logFile,
                'env' => $isProd ? "Prod" : "PreProd",
                'db_instance' => $dbInstance,
            ]);

            // Update DbiRequest with execution logs
            if ($isProd) {
                $dbiRequest->sql_log_file_prod = $outputFileName;
                $dbiRequest->sql_logs_info_prod = $executionLog;
                $dbiRequest->prod_execution = $executionStatus === 'Execution Successful' ? 1 : 2;
            } else {
                $dbiRequest->sql_log_file = $outputFileName;
                $dbiRequest->sql_logs_info = $executionLog;
                $dbiRequest->pre_execution = $executionStatus === 'Execution Successful' ? 1 : 2;
                $dbiRequest->sql_logs_info_prod = '';
            }
            $dbiRequest->save();

            $this->updateDbiRequestStatus($dbiRequest, $executionStatus, $isProd);

            DB::commit();
            return ['status' => strtolower($executionStatus), 'log' => $dbiLogCreate];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error executing DBI query: ' . $e->getMessage());

            $executionLog = "SQL*Plus: Release 12.2.0.1.0\n";
            $executionLog .= "Copyright (c) 1982, 2022, Oracle.  All rights reserved.\n\n";
            $executionLog .= "Error: " . $e->getMessage() . "\n";
            $executionLog .= "SQL> ROLLBACK;\n";
            $executionLog .= "Rollback complete due to unexpected error.\n\n";
            $executionLog .= "SQL> QUIT\nPL/SQL procedure failed.\n";

            $logFile = $this->generateLogFile($dbiRequest, $executionLog, $isProd);

            $dbiLogCreate = DbiRequestLog::create([
                'dbi_request_id' => $dbiRequest->id,
                'execution_status' => 'Execution Failed',
                'log_file' => $logFile,
                'env' => $isProd ? "Prod" : "PreProd",
                'db_instance' => $dbInstance ?? null,
            ]);

            // Update DbiRequest to indicate execution failure
            if ($isProd) {
                $dbiRequest->sql_log_file_prod = $outputFileName ?? null;
                $dbiRequest->sql_logs_info_prod = $executionLog;
                $dbiRequest->prod_execution = 2; // Execution failed
            } else {
                $dbiRequest->sql_log_file = $outputFileName ?? null;
                $dbiRequest->sql_logs_info = $executionLog;
                $dbiRequest->pre_execution = 2; // Execution failed
            }
            $dbiRequest->save();

            $this->updateDbiRequestStatus($dbiRequest, 'Execution Failed', $isProd);

            return ['status' => 'failed', 'log' => $dbiLogCreate];
        }
    }

    /**
     * Generate and save a log file for a DBI execution.
     *
     * @param DbiRequest $dbiRequest The DBI request
     * @param string $executionLog The log content
     * @param bool $isProd Whether this is a production execution
     * @return string The name of the generated log file
     */
    private function generateLogFile(DbiRequest $dbiRequest, $executionLog, $isProd)
    {
        //dd($executionLog);
        $outputFileName = $dbiRequest->id . '_output_' . ($isProd ? 'Prod' : 'PreProd') . '_' . time() . '.txt';
        Storage::put('sql_logs/' . $outputFileName, $executionLog);
        return $outputFileName;
    }

    /**
     * Update the status of a DBI request after execution.
     *
     * @param DbiRequest $dbiRequest The DBI request
     * @param string $executionStatus The status of the execution
     * @param bool $isProd Whether this was a production execution
     */
    private function updateDbiRequestStatus(DbiRequest $dbiRequest, $executionStatus, $isProd)
    {
        $statusCode = $executionStatus === 'Execution Successful' ? ($isProd ? 3 : 1) : 0;
        
        $dbiRequest->dbiRequestStatus()->updateOrCreate(
            ['request_id' => $dbiRequest->id],
            [
                'request_status' => $executionStatus === 'Execution Successful' ? ($isProd ? 3 : 0) : 0,
                'operator_status' => $executionStatus === 'Execution Successful' ? ($isProd ? 3 : 0) : 0,
                'dat_status' => $executionStatus === 'Execution Successful' ? ($isProd ? 3 : 0) : 0,
            ]
        );

        DbiStatus::updateOrCreate(
            ['dbi_id' => $dbiRequest->id, 'user_id' => auth()->id()],
            [
                'status_detail' => $isProd ? ($executionStatus === "Execution Failed" ? "Execution Failed" : "Prod Run") : ($executionStatus === "Execution Failed" ? "Execution Failed" : "Test DBI"),
                'filled' => $isProd ? 5 : 4,
            ]
        );
    }

    /**
     * Select a database for a DBI request.
     *
     * @param DbiRequest $dbiRequest The DBI request
     * @param array $data The validated data for selecting the database
     * @return DbiRequest The updated DBI request
     * @throws \Exception if an error occurs during the process
     */
    public function selectDb(DbiRequest $dbiRequest, array $data)
    {
        DB::beginTransaction();
        try {
            $dbiRequest->update($data);

            // Generate a unique filename for the SQL file
            $sqlfile = 'dbi_' . $dbiRequest->id . '_' . time() . '.sql';
            
            // Store the SQL file
            Storage::disk('public')->put('source_code_files/' . $sqlfile, $data['source_code']);

            // Create a record for the SQL file
            DbiRequestSQL::create([
                'dbi_request_id' => $dbiRequest->id,
                'sql_file' => $sqlfile,
            ]);

            // Update or create a new DBI status
            DbiStatus::updateOrCreate(
                ['dbi_id' => $dbiRequest->id, 'user_id' => auth()->id()],
                ['status_detail' => "DB Selected", 'filled' => 2]
            );

            DB::commit();
            return $dbiRequest;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error selecting DB for DBI request: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Store temporary tables for a DBI request.
     *
     * @param DbiRequest $dbiRequest The DBI request
     * @param array $data The data containing temporary table information
     * @throws \Exception if an error occurs during the process
     */
    public function storeTemporaryTables(DbiRequest $dbiRequest, array $data)
    {
        DB::beginTransaction();
        try {
            foreach ($data['tables'] as $table) {
                // Create a record for each temporary table
                TemporaryTable::create([
                    'dbi_request_id' => $dbiRequest->id,
                    'user_id' => auth()->id(),
                    'table_name' => $table['name'],
                    'type' => $table['type'],
                    'drop_date' => $table['drop_date'],
                    'sql' => $table['sql'],
                ]);

                // Actually create the temporary table in the database
                DB::statement('CREATE TABLE ' . $table['name'] . ' (' . $table['sql'] . ')');
            }

            // Update or create a new DBI status
            DbiStatus::updateOrCreate(
                ['dbi_id' => $dbiRequest->id, 'user_id' => auth()->id()],
                ['status_detail' => "Additional Info", 'filled' => 3]
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing temporary tables for DBI request: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Submit DBI request to SDE.
     *
     * @param DbiRequest $dbiRequest
     * @return DbiRequest
     * @throws \Exception
     */
    public function submitToSDE(DbiRequest $dbiRequest)
    {
        DB::beginTransaction();
        try {
            $dbiRequest->dbiRequestStatus()->updateOrCreate(
                ['request_id' => $dbiRequest->id],
                [
                    'request_status' => 1,
                    'operator_status' => 0,
                    'dat_status' => 0,
                ]
            );

            DbiStatus::create([
                'dbi_id' => $dbiRequest->id,
                'user_id' => auth()->id(),
                'status_detail' => "Submitted to SDE",
                'filled' => 3
            ]);

            DB::commit();
            return $dbiRequest;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting DBI request to SDE: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * SDE approve or reject DBI request.
     *
     * @param DbiRequest $dbiRequest
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function sdeApproveOrReject(DbiRequest $dbiRequest, array $data)
    {
        DB::beginTransaction();
        try {
            $status = $data['approvalorreject'] === 'approve' ? 1 : 2;
            
            $dbiRequest->dbiRequestStatus()->updateOrCreate(
                ['request_id' => $dbiRequest->id],
                [
                    'request_status' => $status,
                    'operator_status' => $status,
                ]
            );

            // Remove previous operator comments for this DBI request
            $dbiRequest->operatorComments()->delete();

            // Store new operator comments
            if (isset($data['operator_comment']) && is_array($data['operator_comment'])) {
                $now = Carbon::now();
                $comments = array_map(function($comment) use ($dbiRequest, $now) {
                    return [
                        'dbi_request_id' => $dbiRequest->id,
                        'comment' => $comment,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];
                }, $data['operator_comment']);
                
                OperatorComment::insert($comments);
            }

            DbiStatus::create([
                'dbi_id' => $dbiRequest->id,
                'user_id' => auth()->id(),
                'status_detail' => $status === 1 ? "Approved by SDE" : "Rejected by SDE",
                'filled' => 4
            ]);

            DB::commit();

            Log::info('SDE approve/reject process completed', [
                'dbi_request_id' => $dbiRequest->id,
                'status' => $status === 1 ? 'approved' : 'rejected',
                'user_id' => auth()->id()
            ]);

            return [
                'status' => $status === 1 ? 'approved' : 'rejected',
                'dbiRequest' => $dbiRequest
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in SDE approve/reject process', [
                'dbi_request_id' => $dbiRequest->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * DAT approve or reject DBI request.
     *
     * @param DbiRequest $dbiRequest
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function datApproveOrReject(DbiRequest $dbiRequest, array $data)
    {
        DB::beginTransaction();
        try {
            $status = $data['approvalorreject'] === 'approve' ? 1 : 2;
            
            $dbiRequest->dbiRequestStatus()->updateOrCreate(
                ['request_id' => $dbiRequest->id],
                [
                    'request_status' => $status,
                    'operator_status' => $status,
                    'dat_status' => $status,
                ]
            );

            // Remove previous operator comments for this DBI request
            $dbiRequest->operatorComments()->delete();

            // Store new operator comments
            if (isset($data['operator_comment']) && is_array($data['operator_comment'])) {
                $now = Carbon::now();
                $comments = array_map(function($comment) use ($dbiRequest, $now) {
                    return [
                        'dbi_request_id' => $dbiRequest->id,
                        'comment' => $comment,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];
                }, $data['operator_comment']);
                
                OperatorComment::insert($comments);
            }

            DbiStatus::create([
                'dbi_id' => $dbiRequest->id,
                'user_id' => auth()->id(),
                'status_detail' => $status === 1 ? "Approved by DAT" : "Rejected by DAT",
                'filled' => 4
            ]);

            DB::commit();

            Log::info('DAT approve/reject process completed', [
                'dbi_request_id' => $dbiRequest->id,
                'status' => $status === 1 ? 'approved' : 'rejected',
                'user_id' => auth()->id()
            ]);

            return [
                'status' => $status === 1 ? 'approved' : 'rejected',
                'dbiRequest' => $dbiRequest
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in DAT approve/reject process', [
                'dbi_request_id' => $dbiRequest->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}