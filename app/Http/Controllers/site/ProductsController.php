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
Use App\Product;
use Helper;

class ProductsController extends Controller
{
    /*****************************************************/
    # Function name : index
    # Params        : 
    /*****************************************************/
    public function marketPlace(Request $request)
    {
        $marketPlaceData    = Helper::getData('cms', '6');
        $siteSetting        = Helper::getSiteSettings();

        $products = Product::where(['status' => '1'])->whereNull('deleted_at');

        $page       = isset($request->page) ? $request->page : 1;
        $lookingFor = isset($request->lookingFor) ? $request->lookingFor : '';
        $price      = isset($request->price) ? $request->price : 'low-to-high';
        $perPage    = isset($request->perPage) ? $request->perPage : 12;

        if ($lookingFor != '') {
            $products->where(function ($query) use ($lookingFor) {
                $query->where('title', 'LIKE', '%' . $lookingFor . '%');
            });
        }
        $sortOrder = 'asc';
        if ($price == 'high-to-low') {
            $sortOrder = 'desc';
        }

        $products = $products->orderBy('price', $sortOrder)->paginate($perPage);
        
        return view('site.product',[
            'pageTitle'     => $marketPlaceData['title'],
            'title'         => $marketPlaceData['meta_title'],
            'keyword'       => $marketPlaceData['meta_keyword'], 
            'description'   => $marketPlaceData['meta_description'],
            'siteSetting'   => $siteSetting,
            'products'      => $products,
        ]);
    }

    /*****************************************************/
    # Function name : marketPlaceProducts
    # Params        : 
    /*****************************************************/    
    public function marketPlaceProducts(Request $request)
    {
        if ($request->ajax()) {
            $page       = isset($request->page) ? $request->page : 1;
            $lookingFor = isset($request->lookingFor) ? $request->lookingFor : '';
            $price      = isset($request->price) ? $request->price : 'low-to-high';
            $perPage    = isset($request->perPage) ? $request->perPage : 12;

            $products = Product::where(['status' => '1'])->whereNull('deleted_at');

            if ($lookingFor != '') {
                $products->where(function ($query) use ($lookingFor) {
                    $query->where('title', 'LIKE', '%' . $lookingFor . '%');
                });
            }
            $sortOrder = 'asc';
            if ($price == 'high-to-low') {
                $sortOrder = 'desc';
            }

            $products = $products->orderBy('price', $sortOrder)->paginate($perPage);
            // dd($products);

            return view('site.elements.products_with_pagination', compact('products'))->render();
        }
    }

   
}