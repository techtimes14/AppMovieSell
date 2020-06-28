<?php
/*****************************************************/
# Pagen/Class name   : VideoTag
/*****************************************************/
namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoTag extends Model
{
  public $timestamps = false;

  /*****************************************************/
  # Function name : tagDetails
  /*****************************************************/
  public function tagDetails() {
    return $this->belongsTo('App\Tag', 'tag_id');
  }

  /*****************************************************/
  # Function name : videoGetDetails
  /*****************************************************/
  public function videoGetDetails() {
    return $this->belongsTo('App\Video', 'video_id');
  }
  
}
