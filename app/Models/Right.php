<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Right extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function roles1()
    {
        return $this->belongsToMany(Role::class, 'right_role', 'right_id', 'role_id')->withTimestamps();
    }

    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'user_role_right', 'right_id', 'role_id')->withTimestamps();
    // }

    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'user_role_right', 'right_id', 'user_id')->distinct()->withTimestamps();
    // }

}
