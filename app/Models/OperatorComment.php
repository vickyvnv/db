<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatorComment extends Model
{
    use HasFactory;

    protected $fillable = ['dbi_request_id', 'comment'];

    public function dbiRequest()
    {
        return $this->belongsTo(DbiRequest::class);
    }
}