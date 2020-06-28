<?php
/*****************************************************/
# Pagen/Class name   : VideoCategory
/*****************************************************/
namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoCategory extends Model
{
  public $timestamps = false;

  /*****************************************************/
  # Function name : categoryDetails
  /*****************************************************/
  public function categoryDetails() {
    return $this->belongsTo('App\Category', 'category_id');
  }

  /*****************************************************/
  # Function name : videoGetDetails
  /*****************************************************/
  public function videoGetDetails() {
    return $this->belongsTo('App\Video', 'video_id');
  }
  
}
