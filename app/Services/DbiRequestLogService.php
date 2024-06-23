<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DbiRequestLogService
{
    public function log($dbiRequestId, $message, $data = null)
    {
        $logFileName = 'dbi_request_' . $dbiRequestId . '_log.txt';
        $logPath = 'logfiles/' . $logFileName;
        $timestamp = Carbon::now()->toDateTimeString();

        $logMessage = "========================================================================================================================================\n";
        $logMessage .= "[{$timestamp}] {$message}\n";
        $logMessage .= "User Name: ".Auth::user()->user_firstname. " User Lastname:".Auth::user()->user_lastname. " User Id: ".Auth::user()->id. "User email: ".Auth::user()->email;
        if ($data !== null) {
            if ($data instanceof Model) {
                // If it's an Eloquent model, we'll log its attributes
                $logMessage .= "DBI Request Data:\n";
                $logMessage .= json_encode($data->toArray(), JSON_PRETTY_PRINT) . "\n";
            } elseif (is_array($data) || is_object($data)) {
                // If it's an array or object, we'll JSON encode it
                $logMessage .= "Additional Data:\n";
                $logMessage .= json_encode($data, JSON_PRETTY_PRINT) . "\n";
            } else {
                // For any other type, we'll just convert to string
                $logMessage .= "Additional Data: " . (string)$data . "\n";
            }
        }
        
        $logMessage .= "========================================================================================================================================\n\n";

        if (Storage::disk('public')->exists($logPath)) {
            Storage::disk('public')->append($logPath, $logMessage);
        } else {
            Storage::disk('public')->put($logPath, $logMessage);
        }
    }
}