<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DbiRequestLog extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dbi_request_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dbi_request_id',
        'execution_status',
        'log_file',
        'db_instance',
        'env',
        'created_date',
        'updated_date'
    ];

    /**
     * Get the DBI request associated with the status.
     */
    public function dbiRequest()
    {
        return $this->belongsTo(DbiRequest::class, 'dbi_request_id');
    }
}
