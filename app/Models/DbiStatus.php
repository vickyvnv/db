<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DbiStatus extends Model
{
    use HasFactory;

    protected $fillable = ['dbi_id', 'user_id', 'filled', 'status_detail'];

    public function dbiRequest()
    {
        return $this->belongsTo(DbiRequest::class, 'dbi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
