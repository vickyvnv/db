<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DbiRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'request_id',
        'requestor_id', // requester user id
        'operator_id', // SDE user id
        'priority_id',
        'sw_version',
        'dbi_type',
        'tt_id',
        'serf_cr_id',
        'reference_dbi',
        'brief_desc',
        'problem_desc',
        'business_impact',
        'dbi_flag',
        'source_code',
        'db_instance',
        'sql_file_path',
        'sql_logs_info'
    ];

    /**
     * Get the requestor associated with the DBI request.
     */
    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor_id');
    }

    /**
     * Get the operator associated with the DBI request.
     */
    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}