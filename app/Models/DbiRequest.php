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
        'requestor_id',
        'operator_id',
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
    ];
}