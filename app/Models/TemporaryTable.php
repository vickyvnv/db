<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'dbi_request_id',
        'user_id',
        'table_name',
        'type',
        'drop_date',
        'sql',
    ];

    public function dbiRequest()
    {
        return $this->belongsTo(DbiRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
