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

    public function categoryDetails() {
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function productImage() {
        return $this->hasmany('App\ProductImage', 'product_id');
    }
    public function productDefaultImage() {
        return $this->hasmany('App\ProductImage', 'product_id')->where('default_image', 'Y');
    }

    
}
