<?php
/*****************************************************/
# Pagen/Class name   : FavouriteVideo
/*****************************************************/
namespace App;

use Illuminate\Database\Eloquent\Model;

class FavouriteVideo extends Model
{

  /*****************************************************/
  # Function name : videoDetails
  /*****************************************************/
  public function videoDetails() {
    return $this->belongsTo('App\Video', 'video_id');
  }
}
