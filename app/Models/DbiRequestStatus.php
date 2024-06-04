<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DbiRequestStatus extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dbi_request_status';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_id',
        'request_status',
        'operator_status',
        'dat_status',
        'operator_comment',
        'dat_comment',
    ];

    /**
     * Get the DBI request associated with the status.
     */
    public function dbiRequest()
    {
        return $this->belongsTo(DbiRequest::class, 'request_id');
    }
}
