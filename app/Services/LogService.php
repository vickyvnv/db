<?php

namespace App\Services;

use App\Models\DbiRequestLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LogService
{
    /**
     * Retrieve the content of a log file.
     *
     * @param int $id The ID of the DbiRequestLog
     * @return string The content of the log file
     * @throws \Exception if the log file is not found
     */
    public function getLogContent($id)
    {
        try {
            $dbiRequestLog = DbiRequestLog::findOrFail($id);
            $logPath = 'sql_logs/' . $dbiRequestLog->log_file;

            if (Storage::exists($logPath)) {
                return Storage::get($logPath);
            }

            throw new \Exception('Log file not found.');
        } catch (\Exception $e) {
            Log::error('Error retrieving log content: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all logs for a specific DBI request.
     *
     * @param int $dbiRequestId The ID of the DBI request
     * @param int $perPage Number of items per page for pagination
     * @return \Illuminate\Pagination\LengthAwarePaginator Paginated log entries
     */
    public function getAllLogs($dbiRequestId, $perPage = 10)
    {
        try {
            return DbiRequestLog::where('dbi_request_id', $dbiRequestId)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('Error retrieving all logs: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a new log entry.
     *
     * @param array $data The data for the new log entry
     * @return DbiRequestLog The created log entry
     */
    public function createLog(array $data)
    {
        try {
            return DbiRequestLog::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating log entry: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a log file and its corresponding database entry.
     *
     * @param int $id The ID of the DbiRequestLog
     * @return bool True if deletion was successful, false otherwise
     */
    public function deleteLog($id)
    {
        try {
            $dbiRequestLog = DbiRequestLog::findOrFail($id);
            $logPath = 'sql_logs/' . $dbiRequestLog->log_file;

            if (Storage::exists($logPath)) {
                Storage::delete($logPath);
            }

            return $dbiRequestLog->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting log: ' . $e->getMessage());
            throw $e;
        }
    }
}