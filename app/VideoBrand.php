<?php
/*****************************************************/
# Pagen/Class name   : VideoBrand
/*****************************************************/
namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoBrand extends Model
{
  public $timestamps = false;

  /*****************************************************/
  # Function name : brandDetails
  /*****************************************************/
  public function brandDetails() {
    return $this->belongsTo('App\Brand', 'brand_id');
  }

  /*****************************************************/
  # Function name : videoDetails
  /*****************************************************/
  public function videoDetails() {
    return $this->belongsTo('App\Video', 'video_id');
  }

  /*****************************************************/
  # Function name : videoGetDetails
  /*****************************************************/
  public function videoGetDetails() {
    return $this->belongsTo('App\Video', 'video_id');
  }

}
