<?php
/*****************************************************/
# Role
# Page/Class name   : Role
# Author            :
# Created Date      : 22-05-2019
# Functionality     : Table declaration
# Purpose           : Table declaration
/*****************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

	/*****************************************************/
    # Role
    # Function name : permissions
    # Author        :
    # Created Date  : 27-05-2019
    # Purpose       : Getting role permissions
    # Params        : 
    /*****************************************************/
	public function permissions() {
		return $this->hasMany('App\RolePermission', 'role_id');
	}
}