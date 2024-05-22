<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pwrole extends Model
{
    use HasFactory;

    protected $fillable = [
        'pwr_name',
        'pwr_description',
        'pwc_group',
        'pwr_type',
    ];

    public function pwconnects()
    {
        return $this->belongsToMany(Pwconnect::class, 'pwconnect_role');
    }
}
