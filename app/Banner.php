<?php
/*****************************************************/
# Page/Class name   : Banner
# Purpose           : Table declaration and Other relations
/*****************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes;
}