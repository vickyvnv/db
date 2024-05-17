<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role_right', 'role_id', 'user_id')->withTimestamps();
    }

    

    public function rights()
    {
        return $this->belongsToMany(Right::class);
    }

}
