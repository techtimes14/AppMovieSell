<?php
/*****************************************************/
# PackageDuration
# Page/Class name   : PackageDuration
# Author            :
# Created Date      : 18-03-2020
# Functionality     : Table declaration and other relations
# Purpose           : Table declaration and other relations
/*****************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageDuration extends Model
{
    protected $table = 'le_package_durations';
    
    use SoftDeletes;

    /*****************************************************/
    # Package
    # Function name : packageDetails
    # Author        :
    # Created Date  : 18-03-2020
    # Purpose       : Getting package details
    # Params        : 
    /*****************************************************/
    public function packageDetails() {
        return $this->belongsTo('App\Package', 'package_id');
    }

    /*****************************************************/
    # Package
    # Function name : packagePeriodDetails
    # Author        :
    # Created Date  : 18-03-2020
    # Purpose       : Getting package period details
    # Params        : 
    /*****************************************************/
    public function packagePeriodDetails() {
        return $this->belongsTo('App\PackagePeriod', 'package_period_id');
    }

}