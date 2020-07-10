<?php
/*****************************************************/
# PackageLocal
# Page/Class name   : PackageLocal
# Author            :
# Created Date      : 16-03-2020
# Functionality     : Table declaration
# Purpose           : Table declaration
/*****************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageLocal extends Model
{
	protected $table = 'le_package_locals';

    public  $timestamps = false;
}