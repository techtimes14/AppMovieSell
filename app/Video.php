<?php
/*****************************************************/
# Pagen/Class name   : Video
/*****************************************************/
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
  use SoftDeletes;

  /*****************************************************/
  # Function name : videoCategories
  /*****************************************************/
  public function videoCategories() {
    return $this->hasMany('App\VideoCategory', 'video_id');
  }

  /*****************************************************/
  # Function name : videoTags
  /*****************************************************/
  public function videoTags() {
    return $this->hasMany('App\VideoTag', 'video_id');
  }

  /*****************************************************/
  # Function name : videoBrands
  /*****************************************************/
  public function videoBrands() {
    return $this->hasMany('App\VideoBrand', 'video_id');
  }  

}
