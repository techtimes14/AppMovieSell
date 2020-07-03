<?php
/*****************************************************/
# Pagen/Class name   : Product
/*****************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    public function features(){
        return $this->hasMany('App\ProductFeature', 'product_id');
    }

    
}
