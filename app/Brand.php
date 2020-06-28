<?php
/*****************************************************/
# Page/Class name   : Brand
# Purpose           : Table declaration and Other relations
/*****************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    /*****************************************************/
  	# Function name : brandUsers
  	/*****************************************************/
  	public function brandUsers() {
    	return $this->hasMany('App\BrandUser', 'brand_id');
  	}
}