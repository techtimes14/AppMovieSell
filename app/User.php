<?php
/*****************************************************/
# Page/Class name   : User
# Purpose           : Table declaration, Hash password
/*****************************************************/
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*****************************************************/
    # Function name : setPasswordAttribute
    # Params        : $pass
    /*****************************************************/
    public function setPasswordAttribute($pass)
    {
        $this->attributes['password'] = \Hash::make($pass);
    }

    /*****************************************************/
    # Function name : getFirstNameAttribute
    # Params        : $firstName
    /*****************************************************/
    public function getFirstNameAttribute($firstName)
    {
        return ucfirst($firstName);
    }

    /*****************************************************/
    # Function name :  boardList
    # Params        : 
    /*****************************************************/
    public function  userDetail() {
        return $this->hasOne('App\UserDetail', 'user_id');
    }

    /*****************************************************/
    # Function name : role
    # Params        : 
    /*****************************************************/
    public function role() {
        return $this->belongsTo('App\Role', 'role_id');
    }

    /*****************************************************/
    # Function name : checkRolePermission
    # Params        : 
    /*****************************************************/
    public function checkRolePermission() {
        return $this->belongsTo('App\Role', 'role_id')->where('is_admin','1');
    }

    /*****************************************************/
    # Function name : allRolePermissionForUser
    # Params        : 
    /*****************************************************/
    public function allRolePermissionForUser() {
        return $this->hasMany('App\RolePermission', 'role_id', 'role_id');
    }

}