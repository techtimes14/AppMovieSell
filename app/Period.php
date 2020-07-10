<?php
/*****************************************************/
# Page/Class name   : Period
# Purpose           : Table declaration
/*****************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackagePeriod extends Model
{
    use SoftDeletes;    
}