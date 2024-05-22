<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Authenticatable implements AuthenticatableContract{
    protected $table = 'users'; // Assuming 'users' is the name of your table in Oracle

    protected $fillable = [
        'email',
        'password',
        'user_firstname',
        'user_lastname',
        'tel',
        'user_function',
        'user_contractor',
        'team_id',
        'team_name',
        'team_group',
        'user_department',
        'tl',
        'gl',
        'al',
        'username',
        'mobile',
        'company',
        'position',
        'phone',
        'user_department'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

        public function userRoles()
    {
        return $this->belongsToMany(Role::class, 'user_role_right', 'user_id', 'role_id')->withTimestamps();
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function assignedUser()
    {
        return $this->belongsToMany(User::class, 'user_assigned_users', 'user_id', 'assigned_user_id');
    }

    public function pwgroups()
    {
        return $this->belongsToMany(Pwgroup::class, 'pwgroup_user');
    }
}
