<?php

namespace App\Services;

use App\Models\DbiRequestSQL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SqlService
{
    /**
     * Retrieve the content of an SQL file.
     *
     * @param int $id The ID of the DbiRequestSQL
     * @return string The content of the SQL file
     * @throws ModelNotFoundException if the DbiRequestSQL is not found
     * @throws \Exception if the SQL file is not found or cannot be read
     */
    public function getSqlContent($id)
    {
        try {
            // Find the DbiRequestSQL record or throw an exception if not found
            $dbiRequestSql = DbiRequestSQL::findOrFail($id);
            
            // Construct the path to the SQL file
            $sqlPath = 'public/source_code_files/' . $dbiRequestSql->sql_file;

            // Check if the file exists and return its content
            if (Storage::exists($sqlPath)) {
                return Storage::get($sqlPath);
            }

            // If the file doesn't exist, throw an exception
            throw new \Exception('SQL file not found.');
        } catch (ModelNotFoundException $e) {
            Log::error('DbiRequestSQL not found: ' . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error retrieving SQL content: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all SQL files for a specific DBI request.
     *
     * @param int $dbiRequestId The ID of the DBI request
     * @param int $perPage Number of items per page for pagination
     * @return \Illuminate\Pagination\LengthAwarePaginator Paginated SQL file entries
     */
    public function getAllSqlFiles($dbiRequestId, $perPage = 10)
    {
        try {
            // Retrieve paginated SQL files for the given DBI request
            return DbiRequestSQL::where('dbi_request_id', $dbiRequestId)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('Error retrieving all SQL files: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a new SQL file entry.
     *
     * @param int $dbiRequestId The ID of the DBI request
     * @param string $sqlContent The content of the SQL file
     * @return DbiRequestSQL The created SQL file entry
     * @throws \Exception if the SQL file cannot be created
     */
    public function createSqlFile($dbiRequestId, $sqlContent)
    {
        try {
            // Generate a unique filename
            $fileName = 'dbi_' . $dbiRequestId . '_' . time() . '.sql';
            $filePath = 'public/source_code_files/' . $fileName;

            // Store the SQL content in the file
            Storage::put($filePath, $sqlContent);

            // Create and return a new DbiRequestSQL record
            return DbiRequestSQL::create([
                'dbi_request_id' => $dbiRequestId,
                'sql_file' => $fileName,
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating SQL file: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an existing SQL file entry.
     *
     * @param int $id The ID of the DbiRequestSQL
     * @param string $sqlContent The new content of the SQL file
     * @return DbiRequestSQL The updated SQL file entry
     * @throws ModelNotFoundException if the DbiRequestSQL is not found
     * @throws \Exception if the SQL file cannot be updated
     */
    public function updateSqlFile($id, $sqlContent)
    {
        try {
            // Find the DbiRequestSQL record or throw an exception if not found
            $dbiRequestSql = DbiRequestSQL::findOrFail($id);
            $filePath = 'public/source_code_files/' . $dbiRequestSql->sql_file;

            // Update the SQL file content
            Storage::put($filePath, $sqlContent);

            // Update the 'updated_at' timestamp of the record
            $dbiRequestSql->touch();
            return $dbiRequestSql;
        } catch (ModelNotFoundException $e) {
            Log::error('DbiRequestSQL not found: ' . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error updating SQL file: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete an SQL file and its corresponding database entry.
     *
     * @param int $id The ID of the DbiRequestSQL
     * @return bool True if deletion was successful, false otherwise
     * @throws ModelNotFoundException if the DbiRequestSQL is not found
     * @throws \Exception if the SQL file cannot be deleted
     */
    public function deleteSqlFile($id)
    {
        try {
            // Find the DbiRequestSQL record or throw an exception if not found
            $dbiRequestSql = DbiRequestSQL::findOrFail($id);
            $filePath = 'public/source_code_files/' . $dbiRequestSql->sql_file;

            // Delete the SQL file if it exists
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            // Delete the database record
            return $dbiRequestSql->delete();
        } catch (ModelNotFoundException $e) {
            Log::error('DbiRequestSQL not found: ' . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error deleting SQL file: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Validate SQL content.
     *
     * @param string $sqlContent The SQL content to validate
     * @return array An array with 'isValid' boolean and 'errors' array if any
     */
    public function validateSql($sqlContent)
    {
        $errors = [];
        $isValid = true;

        // Check for potentially harmful SQL commands
        $dangerousCommands = ['DROP DATABASE', 'TRUNCATE DATABASE', 'DELETE FROM'];
        foreach ($dangerousCommands as $command) {
            if (Str::contains(strtoupper($sqlContent), $command)) {
                $errors[] = "Potentially harmful SQL command detected: $command";
                $isValid = false;
            }
        }

        // Check for proper SQL syntax (this is a basic check, might need to be more comprehensive)
        if (!Str::endsWith(trim($sqlContent), ';')) {
            $errors[] = "SQL statement must end with a semicolon (;)";
            $isValid = false;
        }

        // You could add more validation rules here

        return [
            'isValid' => $isValid,
            'errors' => $errors
        ];
    }

    /**
     * Analyze SQL content for potential performance issues.
     *
     * @param string $sqlContent The SQL content to analyze
     * @return array An array of warnings or suggestions
     */
    public function analyzeSqlPerformance($sqlContent)
    {
        $warnings = [];

        // Check for SELECT *
        if (Str::contains(strtoupper($sqlContent), 'SELECT *')) {
            $warnings[] = "Consider specifying columns instead of using SELECT *";
        }

        // Check for missing WHERE clause in UPDATE or DELETE
        if ((Str::contains(strtoupper($sqlContent), 'UPDATE') || Str::contains(strtoupper($sqlContent), 'DELETE')) 
            && !Str::contains(strtoupper($sqlContent), 'WHERE')) {
            $warnings[] = "UPDATE or DELETE statement without WHERE clause detected. This will affect all rows.";
        }

        // Check for potential full table scans
        if (Str::contains(strtoupper($sqlContent), 'LIKE "%')) {
            $warnings[] = "LIKE with leading wildcard detected. This may result in a full table scan.";
        }

        // You can add more performance checks here

        return $warnings;
    }
}