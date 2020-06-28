<?php
/*****************************************************/
# Pagen/Class name   : BrandUser
/*****************************************************/
namespace App;

use Illuminate\Database\Eloquent\Model;

class BrandUser extends Model
{
  public $timestamps = false;

  /*****************************************************/
  # Function name : userDetails
  /*****************************************************/
  public function userDetails() {
    return $this->belongsTo('App\User', 'user_id');
  }
}
