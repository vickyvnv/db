<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DbiRequestSQL extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dbi_request_sql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dbi_request_id',
        'sql_file'
    ];

    /**
     * Get the DBI request associated with the status.
     */
    public function dbiRequest()
    {
        return $this->belongsTo(DbiRequest::class, 'dbi_request_id');
    }
}
