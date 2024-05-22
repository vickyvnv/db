<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pwconnect extends Model
{
    use HasFactory;
    
    public $incrementing = true;

    protected $fillable = [
        'PWC_NAME',
        'PWC_USER',
        'PWC_PW',
        'PWC_WRITE',
        'UPDATE_DATE',
        'PWC_CAT',
        'PWC_TYP',
        'PWC_GROUP',
        'PWC_ACTIVE_IND',
        'PWC_CHANGE_TYP',
        'PWC_CHANGE_COND',
    ];

    public function users()
    {
        return $this->belongsToMany(Pwuser::class, 'pwconnect_user');
    }

    public function roles()
    {
        return $this->belongsToMany(Pwrole::class, 'pwconnect_role');
    }
}
