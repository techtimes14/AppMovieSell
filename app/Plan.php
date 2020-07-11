<?php
/*****************************************************/
# Page/Class name   : Plan
# Purpose           : Table declaration
/*****************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;
    
    /*****************************************************/
    # Function name : planFeatures
    # Params        : 
    /*****************************************************/
    public function planFeatures() {
        return $this->hasMany('App\PlanFeature', 'plan_id');
    }
    
    /*****************************************************/
    # Function name : membershipPlanDetails
    # Params        : 
    /*****************************************************/
    public function membershipPlanDetails() {
        return $this->hasMany('App\MembershipPlan', 'plan_id');
    }
}