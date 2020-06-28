<?php
/*****************************************************/
# Controller
# Page/Class name   : Controller
# Author            :
# Created Date      : 20-05-2019
# Functionality     : getSessionWiseCartItemDetails,
#                     mergeCartItemDetails
# Purpose           :
/*****************************************************/

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use \Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use \Auth;
use \Helper;
use \Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $currentLang;
    private $setLang;

    public function __construct(Request $request)
    {
    }

}
