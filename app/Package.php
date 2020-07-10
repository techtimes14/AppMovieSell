<?php
/*****************************************************/
# Package
# Page/Class name   : Package
# Author            :
# Created Date      : 16-03-2020
# Functionality     : Table declaration, get local (english and arabic) details
# Purpose           : Table declaration, get local (english and arabic) details
/*****************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    protected $table = 'le_packages';
    use SoftDeletes;
    
    /*****************************************************/
    # Package
    # Function name : local
    # Author        :
    # Created Date  : 17-03-2020
    # Purpose       : Getting local (english and arabic) details
    # Params        : 
    /*****************************************************/
    public function local() {
        return $this->hasMany('App\PackageLocal', 'package_id');
    }

    /*****************************************************/
    # Package
    # Function name : packageDuration
    # Author        :
    # Created Date  : 09-04-2020
    # Purpose       : Getting package duration details
    # Params        : 
    /*****************************************************/
    public function packageDuration() {
        return $this->hasMany('App\PackageDuration', 'package_id');
    }
}