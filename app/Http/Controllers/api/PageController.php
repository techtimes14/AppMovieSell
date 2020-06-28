<?php
/*****************************************************/
# PageController
# Page/Class name : PageController
# Author :
# Created Date : 21-05-2019
# Functionality : getPageContent
# Purpose : get cms page content
/*****************************************************/
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use \Response;
use \App\Cms;
class PageController extends Controller
{
    /*****************************************************/
    # PageController
    # Function name : getPageContent
    # Author :  
    # Created Date : 21-05-2019
    # Purpose :  to get page content
    # Params : pageSlug
    /*****************************************************/

    public function getPageContent($pageSlug, Request $request)
    {
        $lang = $request->header('x-lang');
        if (!$lang) {
            $lang =  \Lang::locale();
        }
        \App::setLocale(strtolower($lang));

        $lang = strtoupper($lang);

        $cmsData = Cms::where('slug','=', $pageSlug)->first();        
        if ($cmsData) {
            $data['details'] = $cmsData->toArray();
            $data['local'] = $cmsData->local()->where('lang_code', '=', $lang)->first() ? $cmsData->local()->where('lang_code', '=', $lang)->first()->toArray() : ''; 
            $data = ApiHelper::replaceNulltoEmptyStringAndIntToString($data);
            return Response::json(ApiHelper::generateResponseBody('PC-GPC-0001#page_content', $data));
        } else {
            return Response::json(ApiHelper::generateResponseBody('PC-GPC-0002#page_content', trans('custom.page_not_available'), false, 200));
        }
    }

}
