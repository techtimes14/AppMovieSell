<?php
/*****************************************************/
# Pagen/Class name   : Category
/*****************************************************/
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use SoftDeletes;
	  
	public function activeProducts() {
        return $this->hasmany('App\Product', 'category_id')->where('status', '1')->whereNull('deleted_at');
    }
}
