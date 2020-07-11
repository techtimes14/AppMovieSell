<?php
/*****************************************************/
# Page/Class name   : MembershipPlan
# Purpose           : Table declaration and other relations
/*****************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipPlan extends Model
{
    use SoftDeletes;

    /*****************************************************/
    # Function name : planDetails
    # Params        : 
    /*****************************************************/
    public function planDetails() {
        return $this->belongsTo('App\Plan', 'plan_id');
    }

    /*****************************************************/
    # Function name : periodDetails
    # Params        : 
    /*****************************************************/
    public function periodDetails() {
        return $this->belongsTo('App\Period', 'period_id');
    }

}