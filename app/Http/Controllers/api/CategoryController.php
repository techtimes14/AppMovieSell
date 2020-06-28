<?php
/*****************************************************/
# CategoryController
# Page/Class name   : CategoryController
# Author            :
# Created Date      : 22-05-2019
# Functionality     : getList
# Purpose           : category related all functions
/*****************************************************/
namespace App\Http\Controllers\api;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Category;
use \Response;
class CategoryController extends Controller
{
    /*****************************************************/
    # CategoryController
    # Function name : getList
    # Author        :
    # Created Date  : 22-05-2019
    # Purpose       : To get all categories list
    # Params        :
    /*****************************************************/
    public function getList(Request $request) {

        $newProduct = [];
        $showCategory = [];
   
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        
        $caregoryList = Category::with([
                                'local'=> function($query) use ($lang) {
                                    $query->where('lang_code','=', $lang);
                                }])
                                ->whereNull('deleted_at')
                                ->where('status', '=', '1')
                                ->select('id','name','parent_id','image')
                                ->get();
        if(count($caregoryList) > 0) {            
            foreach($caregoryList as $keyProduct => $valProduct) {
                $newProduct['id']           = $valProduct->id;
                $newProduct['name']         = $valProduct->name;
                $newProduct['parent_id']    = $valProduct->parent_id;
                $newProduct['image']        = asset('').'uploads/categories/thumbs/'.$valProduct->image;
                $newProduct['local_id']     = $valProduct->local[0]['id'];
                $newProduct['category_id']  = $valProduct->local[0]['category_id'];
                $newProduct['lang_code']    = $valProduct->local[0]['lang_code'];
                $newProduct['local_name']   = $valProduct->local[0]['local_name'];      
                                
                $showCategory['list'][] = $newProduct;
                unset($newProduct);
            }
        }    

        if ($caregoryList) {
            return Response::json(ApiHelper::generateResponseBody('CC-GL-0001#category_list', $showCategory)); 
        } else {
            return Response::json(ApiHelper::generateResponseBody('CC-GL-0002#category_list', trans('custom.something_went_wrong'), false, 200)); 
        }
    }
}
