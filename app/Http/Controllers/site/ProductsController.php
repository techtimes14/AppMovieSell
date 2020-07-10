<?php
/*****************************************************/
# Page/Class name   : ProductsController
/*****************************************************/
namespace App\Http\Controllers\site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
Use Redirect;
use App;
use \Auth;
use \Response;
use \Validator;
use App\SiteSetting;
use App\Cms;
Use App\User;
use Helper;

class ProductsController extends Controller
{
    /*****************************************************/
    # Function name : index
    # Params        : 
    /*****************************************************/
    public function marketPlace()
    {
        $homeData       = Helper::getData('cms', '1');
        $siteSetting    = Helper::getSiteSettings();

        return view('site.home',[
            'title'                 => $homeData['title'], 
            'keyword'               => $homeData['keyword'], 
            'description'           => $homeData['description'],
            'siteSetting'           => $siteSetting,
            ]);
    }    

   
}