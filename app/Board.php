<?php
/*****************************************************/
# Page/Class name   : Board
# Purpose           : Table declaration and Other relations
/*****************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
    use SoftDeletes;

    // /*****************************************************/
  	// # Function name : BoardUsers
  	// /*****************************************************/
  	// public function BoardUsers() {
    // 	return $this->hasMany('App\BoardUser', 'Board_id');
  	// }
}