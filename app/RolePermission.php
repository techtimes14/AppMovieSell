<?php
/*****************************************************/
# RolePermission
# Page/Class name   : RolePermission
# Author            :
# Created Date      : 27-05-2019
# Functionality     : Table declaration, get role page details
# Purpose           : Table declaration, get role page details
/*****************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
  public $timestamps = false;

  /*****************************************************/
  # RolePermission
  # Function name : page
  # Author        :
  # Created Date  : 27-05-2019
  # Purpose       : Getting role page details
  # Params        : 
  /*****************************************************/
  public function page() {
		return $this->belongsTo('App\RolePage', 'page_id');
	}
}