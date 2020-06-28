<?php
/*****************************************************/
# ProductController
# Page/Class name : ProductController
# Author :
# Created Date : 22-05-2019
# Functionality : requestSubmit
# Purpose : all product related api
/*****************************************************/
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use App\Http\Helpers\AdminHelper;
use App\Product;
use App\ProductImages;
use App\ProductRequest;
use App\ProductVendor;
use App\ProductVendorLocal;
use App\Category;
use App\ProductCategory;
use App\Unit;
use App\User;
use Illuminate\Http\Request;
use Image;
use Helper;
use Validator;
use Response;

class ProductController extends Controller
{
    /*****************************************************/
    # ProductController
    # Function name : requestSubmit
    # Author :
    # Created Date : 22-05-2019
    # Purpose :  to submit product request from vendor
    # Params :
    /*****************************************************/
    public function requestSubmit(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        $userData = ApiHelper::getUserFromHeader($request);
        $productName = $request->product_name;
        if ($productName) {
            $productRequest = new ProductRequest;
            $productRequest->product_name = $productName;
            $productRequest->requested_by = $userData->id;
            $productRequest->save();
            return \Response::json(ApiHelper::generateResponseBody('PC-RS-0001#product_request', trans('custom.product_request_submitted')));
        } else {
            return \Response::json(ApiHelper::generateResponseBody('PC-RS-0002#product_request', trans('custom.something_went_wrong'), false, 200));
        }
    }

    /*****************************************************/
    # ProductController
    # Function name : searchResult
    # Author :
    # Created Date : 22-05-2019
    # Purpose :  to search products
    # Params :
    /*****************************************************/
    public function searchResult(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);

        $categoryId = $request->category_id;
        $productName = $request->product_name;
        $page = $request->page;
        if (!$page) {
            $page = 0;
        }
        $limit = 10;
        $skip = $page * $limit;
        $query = Product::with([
            'pictures' => function ($q) {
                $q->select('id', 'product_id', 'product_vendor_id', 'image_name');
            },
            'local' => function ($q) use ($lang) {
                $q->where('lang_code', '=', $lang);
            },
        ])
            ->where('status', '=', '1');
        if ($categoryId) {
            $query = $query->whereIn('category_id', $categoryId);
        }
        if ($productName) {
            $query = $query->where('name', 'LIKE', '%' . $productName . '%');
        }
        $records = $query->skip($skip)->limit($limit)->get();
        return \Response::json(ApiHelper::generateResponseBody('PC-SR-0001#product_search', ['list' => $records]));
    }

    /*****************************************************/
    # ProductController
    # Function name : productDetails
    # Author        :
    # Created Date  : 22-05-2019
    # Purpose       : To get product details
    # Params        :
    /*****************************************************/
    public function productDetails(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);

        $userData = ApiHelper::getUserFromHeader($request);
        $listData           = [];
        $productData = [];
        $productId = $request->product_id;
        if($productId){
            $details = ProductVendor::with([
                'productUnitLocals'=> function($query) use ($lang) {
                    $query->where('lang_code','=', $lang);
                },
                'productVendorsLocals'=> function($query) use ($lang) {
                    $query->where('lang_code','=', $lang);
                },
                'categoryVendorsLocals'=> function($query) use ($lang) {
                    $query->where('lang_code','=', $lang);
                },
                'productImages',
                'details'
            ])
            ->select('id','slug','pack_value','mrp','selling_price','vat_percent','vat_price',
            'unit_id','category_id','vendor_id','product_id')
            ->where('id', '=', $productId)
            ->whereNull('deleted_at')
            ->where('status', '=', '1')
            ->first();
                
            $baseUrl            = asset('');
            $productsUrl        = 'uploads/products/thumbs/';
            $productImageUrl    = $baseUrl.$productsUrl;

            if ($details != null) {
                $productData['id']           = $details->id;
                if ($details->productVendorsLocals != null) {
                    $productData['name']                 = Helper::cleanString($details->productVendorsLocals[0]['local_name']);
                    $productData['description']          = Helper::cleanString($details->productVendorsLocals[0]['description']);
                    $productData['unit_description']     = $details->productVendorsLocals[0]['unit_description'];
                    $productData['unit_name']= $details->productVendorsLocals[0]['local_unit_name'] ? $details->productVendorsLocals[0]['local_unit_name'] : '';
                } else {
                    $productData['name']                 = '';
                    $productData['description']          = '';
                    $productData['unit_description']     = '';
                    $productData['unit_name']= '';
                }
                if ($details->categoryVendorsLocals != null) {
                    $productData['category_name']                 = $details->categoryVendorsLocals[0]['local_name'];
                } else {
                    $productData['category_name']                 = '';
                }
                $productData['slug']         = $details->slug;
                $productData['pack_value']   = (string)$details->pack_value;
                $productData['mrp']          = (string)$details->mrp;
                $productData['selling_price']= (string)$details->selling_price;
                $productData['vat_percent']  = $details->vat_percent? (string)$details->vat_percent : '';
                $productData['vat_price']    = $details->vat_price? (string)$details->vat_price : '';
                
                if ($details->details != null) {
                    $productData['wholesaler_name']        = $details->details->full_name;
                }else{
                    $productData['wholesaler_name']         = '';
                }
    
                $productData['product_images'] = [];
                if($details->productImages && $details->productImages->count()){
                    foreach($details->productImages as $key => $val){
                        $productImg['id'] = $val->id;
                        $productImg['image'] = $productImageUrl.$val->image_name;
                        $productImg['default'] = $val->default_image;
                        $productImg['status'] = $val->status;
                        $productData['product_images'][] = $productImg;
                    }
                }
                
                $other_salers = ProductVendor::whereNull('deleted_at')
                ->where('id', '<>', $productId)
                ->where('vendor_id', '<>', $details->vendor_id)
                ->where([
                        'product_id'=> $details->product_id,
                        'status'    => '1'
                ])->with([
                'details'=> function($query) use ($lang) {
                    $query->select('id', 'full_name');
                }
                ])->select('id','product_id','vendor_id','pack_value','mrp','selling_price','vat_percent','vat_price','price','status')
                ->get()->groupBy('vendor_id');
                $productData['other_salers'] = [];
                
                foreach($other_salers as $key => $val){
                    $otherSaler = [];
                    $otherSaler['id'] = $val[0]->id;
                    $otherSaler['product_id'] = $val[0]->product_id ? (string)$val[0]->product_id : '';
                    $otherSaler['vendor_id'] = $val[0]->vendor_id ? (string)$val[0]->vendor_id : '';
                    $otherSaler['selling_price'] = $val[0]->selling_price ? (string)$val[0]->selling_price : '';
                    $otherSaler['mrp'] = $val[0]->mrp ? (string)$val[0]->mrp : '';
                    $otherSaler['vat_price'] = $val[0]->vat_price ? (string)$val[0]->vat_price : '';
                    $otherSaler['price'] = $val[0]->price ? (string)$val[0]->price : '';
                    if($val[0]->details){
                        $otherSaler['full_name'] = $val[0]->details->full_name;
                    }else{
                        $otherSaler['full_name'] = '';
                    }
                    
                    $productData['other_salers'][] = $otherSaler;
                }
                
                return Response::json(ApiHelper::generateResponseBody('PC-PD-0001#product_details', $productData));
            } else {
                return Response::json(ApiHelper::generateResponseBody('PC-PD-0002#product_details', trans('custom.no_records_found'), false, 200));
            }
        }else {
            return \Response::json(ApiHelper::generateResponseBody('PC-DVP-0002#product_details', trans('custom.product_id_is_not_provided'), false, 200));
        }  
    }

    
    /*****************************************************/
    # ProductController
    # Function name : addVendorProductCategoriesUnits
    # Author        :
    # Created Date  : 26-06-2019
    # Purpose       : After clicking on the Add vendor product
    # Params        : Request $request
    /*****************************************************/
    public function addVendorProductCategoriesUnits(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);

        $data = [];

        $parentCaregoryList = Category::where(['parent_id' => NULL, 'status' => '1'])
                                        ->whereNull('deleted_at')
                                        ->with([
                                            'local'=> function($query) use ($lang) {
                                                $query->where('lang_code','=', $lang);
                                            }])
                                        ->select('id','name','parent_id')->get();
        $data['category_list'] = $parentCaregoryList->count() ? ApiHelper::replaceNulltoEmptyStringAndIntToString($parentCaregoryList->toArray()) : [];

        $unitList = Unit::where(['status' => '1'])
                        ->whereNull('deleted_at')
                        ->with([
                            'local'=> function($query) use ($lang) {
                                $query->where('lang_code','=', $lang);
                            }])
                        ->select('id','title')->get();
        $data['unit_list'] = $unitList->count() ? ApiHelper::replaceNulltoEmptyStringAndIntToString($unitList->toArray()) : [];

        if ($data) {
            return Response::json(ApiHelper::generateResponseBody('PC-AVPP-0001#product_vendor_add_product_categories_units', $data));
        } else {
            return Response::json(ApiHelper::generateResponseBody('PC-AVPP-0002#product_vendor_add_product_categories_units', trans('custom.something_went_wrong'), false, 100)); 
        }
    }

    /*****************************************************/
    # ProductController
    # Function name : getSubCategories
    # Author        :
    # Created Date  : 26-06-2019
    # Purpose       : Getting sub-categories based on parent
    #                 category
    # Params        : Request $request
    /*****************************************************/
    public function getSubCategories(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        $productIds = [];

        $validator = Validator::make($request->all(),
                        [
                            'category_id' => 'required',
                        ],
                        [
                            'category_id.required' => trans('custom.category_required'),
                        ]
                        );
        $errors = $validator->errors()->all();
        if ($errors) {
            return Response::json(ApiHelper::generateResponseBody('PC-GSC-0004#product_vendor_get_subcategories', ["errors" => $errors], false, 400));
        } else {
            $categoryId = isset($request->category_id)?$request->category_id:null;
            if ($categoryId != null) {
                $subCaregoryList = Category::where(['parent_id' => $categoryId, 'status' => '1'])
                                            ->whereNull('deleted_at')
                                            ->with([
                                                'local'=> function($query) use ($lang) {
                                                    $query->where('lang_code','=', $lang);
                                                }])
                                            ->select('id','name','parent_id')
                                            ->get();
                if($subCaregoryList->count() == 0) {
                    return Response::json(ApiHelper::generateResponseBody('PC-GSC-0002#product_vendor_get_subcategories', trans('custom.no_records_found'), false, 200));
                } else {
                    $data['sub_category_list'] = $subCaregoryList;
                    return Response::json(ApiHelper::generateResponseBody('PC-GSC-0001#product_vendor_get_subcategories', $subCaregoryList));
                }
            } else {
                return Response::json(ApiHelper::generateResponseBody('PC-GSC-0003#product_vendor_get_subcategories', trans('custom.something_went_wrong'), false, 300));
            }
        }
    }
    
    /*****************************************************/
    # ProductController
    # Function name : getProducts
    # Author        :
    # Created Date  : 17-06-2019
    # Purpose       : Getting products based on category id
    # Params        : Request $request
    /*****************************************************/
    public function getProducts(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        $productIds = [];

        $validator = Validator::make($request->all(),
                        [
                            'category_id' => 'required',
                        ],
                        [
                            'category_id.required' => trans('custom.category_required'),
                        ]
                        );
        $errors = $validator->errors()->all();
        if ($errors) {
            return Response::json(ApiHelper::generateResponseBody('PC-GP-0004#product_vendor_get_products', ["errors" => $errors], false, 400));
        } else {
            $categoryId = isset($request->category_id)?$request->category_id:null;
            if ($categoryId != null) {
                $productCategoriesList = ProductCategory::where(['category_id' => $categoryId])
                                                            ->get();
                if ($productCategoriesList->count() > 0) {
                    foreach($productCategoriesList as $keyProductId => $valProductId) {
                        $productIds[] = $valProductId->product_id;
                    }
                }
                if(count($productIds) > 0) {
                    $productList = Product::where(['status' => '1'])
                                            ->whereIn('id', $productIds)
                                            ->whereNull('deleted_at')
                                            ->with([
                                                'local'=> function($query) use ($lang) {
                                                    $query->where('lang_code','=', $lang);
                                                }])
                                            ->select('id','name','vat_amount')
                                            ->get();
                    if($productList->count() > 0) {
                        $productList = ApiHelper::replaceNulltoEmptyStringAndIntToString($productList->toArray());
                        return Response::json(ApiHelper::generateResponseBody('PC-GP-0001#product_vendor_get_products', $productList));
                    } else {
                        return Response::json(ApiHelper::generateResponseBody('PC-GP-0002#product_vendor_get_products', trans('custom.no_records_found'), false, 200));
                    }
                } else {
                    return Response::json(ApiHelper::generateResponseBody('PC-GP-0002#product_vendor_get_products', trans('custom.no_records_found'), false, 200));
                }
            } else {
                return Response::json(ApiHelper::generateResponseBody('PC-GP-0003#product_vendor_get_products', trans('custom.something_went_wrong'), false, 300));
            }
        }
    }

    /*****************************************************/
    # ProductController
    # Function name : getProductDetails
    # Author        :
    # Created Date  : 27-06-2019
    # Purpose       : Getting product details based on product id
    # Params        : Request $request
    /*****************************************************/
    public function getProductDetails(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        
        $validator = Validator::make($request->all(),
                        [
                            'product_id' => 'required',
                        ],
                        [
                            'product_id.required' => trans('custom.product_required'),
                        ]
                        );
        $errors = $validator->errors()->all();
        if ($errors) {
            return Response::json(ApiHelper::generateResponseBody('PC-GPD-0004#product_vendor_get_product_details', ["errors" => $errors], false, 400));
        } else {
            $productId = isset($request->product_id)?$request->product_id:null;
            if ($productId != null) {
                $productDetails = Product::where(['id' => $productId, 'status' => '1'])
                                            ->whereNull('deleted_at')
                                            ->with([
                                                'local'/*=> function($query) use ($lang) {
                                                    $query->where('lang_code','=', $lang);
                                                }*/])
                                            ->select('id','name','slug','vat_amount')
                                            ->get();
                if($productDetails->count()){
                    $productDetails = ApiHelper::replaceNulltoEmptyStringAndIntToString($productDetails->toArray());
                } else {
                    return Response::json(ApiHelper::generateResponseBody('PC-GPD-0003#product_vendor_get_product_details', trans('custom.no_records_found'), false, 200));
                }
                
                return Response::json(ApiHelper::generateResponseBody('PC-GPD-0001#product_vendor_get_product_details', $productDetails));
            } else {
                return Response::json(ApiHelper::generateResponseBody('PC-GPD-002#product_vendor_get_product_details', trans('custom.something_went_wrong'), false, 200));
            }
        }
    }

    /*****************************************************/
    # ProductController
    # Function name : addVendorProduct
    # Author        :
    # Created Date  : 22-05-2019
    # Purpose       : To add vendor product
    # Params        : Request $request
    /*****************************************************/
    public function addVendorProduct(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);

        $validator = Validator::make($request->all(),
                                [
                                    'category_id'               => 'required',
                                    'product_id'                => 'required',
                                    'unit_id'                   => 'required',
                                    'unit_description_en'       => 'required',
                                    'unit_description_ar'       => 'required',
                                    'pack_value'                => 'required|regex:/^[0-9]+$/|max:10',
                                    'description_en'            => 'required',
                                    'description_ar'            => 'required',
                                    'mrp'                       => 'required|regex:/^[1-9]\d*(\.\d+)?$/|max:10',
                                    'selling_price'             => 'required|regex:/^[1-9]\d*(\.\d+)?$/|max:10',
                                    //'vat_percent'               => 'required|regex:/^[1-9]\d*(\.\d+)?$/',
                                    'images'                    => 'required',
                                    'images.*'                  => 'mimes:jpeg,jpg,png,svg|max:'.Helper::MULTIPLE_PRODUCT_IMAGE_MAX_UPLOAD_SIZE,
                                    'status'                    => 'required'
                                ],
                                [
                                    'category_id.required'      => trans('custom.category_required'),
                                    'product_id.required'       => trans('custom.product_required'),
                                    'unit_id.required'          => trans('custom.unit_required'),
                                    'unit_description_en.required' => trans('custom.unit_description_en_required'),
                                    'unit_description_ar.required' => trans('custom.unit_description_ar_required'),
                                    'pack_value.required'       => trans('custom.pack_value_required'),
                                    'pack_value.regex'          => trans('custom.pack_value_regex'),
                                    'pack_value.size'          => trans('custom.max_length_pack_value'),
                                    'description_en.required'   => trans('custom.product_descripton_english_required'),
                                    'description_ar.required'   => trans('custom.product_descripton_arabic_required'),
                                    'mrp.required'              => trans('custom.mrp_required'),
                                    'mrp.size'              => trans('custom.max_length_mrp'),
                                    'mrp.regex'                 => trans('custom.mrp_regex'),
                                    'selling_price.required'    => trans('custom.selling_price_required'),
                                    'selling_price.regex'       => trans('custom.selling_price_regex'),
                                    'selling_price.size'       => trans('custom.max_length_selling_price'),
                                    //'vat_percent.required'      => trans('custom.vat_percent_required'),
                                    //'vat_percent.regex'         => trans('custom.vat_percent_regex'),
                                    'images.required'           => trans('custom.image_required'),
                                    'status.required'           => trans('custom.status_required'),
                                ]
                            );
        $errors = $validator->errors()->all();
        if ($errors) {
            return Response::json(ApiHelper::generateResponseBody('PC-AVP-0005#product_vendor_add', ["errors" => $errors], false, 500));
        } else {
            if ($request->vat_percent != '') {
                $validatorVat = Validator::make($request->all(),
                                [
                                    'vat_percent'               => 'required|regex:/^[1-9]\d*(\.\d+)?$/'
                                ],
                                [
                                    'vat_percent.required'      => trans('custom.vat_percent_required'),
                                    'vat_percent.regex'         => trans('custom.vat_percent_regex')
                                ]
                            );
                $errorsVat  = $validatorVat->errors()->all();
                if ($errorsVat) {
                    return Response::json(ApiHelper::generateResponseBody('PC-AVP-0005#product_vendor_add', ["errors" => $errorsVat], false, 500));
                }
            }

            $userData           = ApiHelper::getUserFromHeader($request);
            $categoryId         = isset($request->category_id)?$request->category_id:0;
            $productId          = $request->product_id;
            $unitId             = isset($request->unit_id)?$request->unit_id:0;
            $packValue          = isset($request->pack_value)?$request->pack_value:0;
            $name               = $request->name;
            $mrp                = $request->mrp;
            $sellingPrice       = $request->selling_price;
            $vatPercent         = isset($request->vat_percent)?$request->vat_percent:null;
            $images             = $request->images;
            $status             = isset($request->status)?$request->status:0;
            $vatPrice           = null;
            $price              = 0;

            if ($productId) {
                if ($sellingPrice) {

                    if ($vatPercent != null) {
                        $vatPrice   = ($sellingPrice * $vatPercent) / 100;
                        $price      = $sellingPrice - $vatPrice;
                    } else {
                        $price      = $sellingPrice;
                    }
                    
                    $newVendorProduct                   = new ProductVendor;
                    $newVendorProduct->category_id      = $categoryId;
                    if ($request->sub_category_id != '') {
                        $newVendorProduct->sub_category_id  = $request->sub_category_id;
                    } else {
                        $newVendorProduct->sub_category_id  = null;
                    }
                    $newVendorProduct->product_id       = $productId;                    
                    $newVendorProduct->vendor_id        = $userData->id;
                    $newVendorProduct->unit_id          = $unitId;
                    $newVendorProduct->pack_value       = $packValue;
                    $newVendorProduct->mrp              = $mrp;
                    $newVendorProduct->selling_price    = $sellingPrice;
                    $newVendorProduct->vat_percent      = $vatPercent;
                    $newVendorProduct->vat_price        = $vatPrice;
                    $newVendorProduct->price            = $price;
                    $newVendorProduct->status            = $status;

                    $productSaved = $newVendorProduct->save();

                    if ($productSaved) {
                        if (count($name) > 1) {
                            foreach ($name as $key => $val) {
                                $newVendorProductLocal                          = new ProductVendorLocal;
                                if(strtoupper($key) == 'EN') {
                                    $newVendorProductLocal->description         = $request->description_en;
                                    $newVendorProductLocal->unit_description    = $request->unit_description_en;
                                    $updateProductData = array(
                                        'name'      => trim($val, ' '),
                                        'slug'      => Helper::generateUniqueSlug(new ProductVendor, trim($val, ' '))
                                    );
                                    ProductVendor::where('id', $newVendorProduct->id)->update($updateProductData);
                                } else {
                                    $newVendorProductLocal->description = $request->description_ar;
                                    $newVendorProductLocal->unit_description    = $request->unit_description_ar;
                                }
                                foreach($newVendorProduct->productUnitLocals as $unitKey => $unitVal){
                                    if($unitVal->lang_code == $key){
                                        $newVendorProductLocal->local_unit_name = $unitVal->local_title;
                                    }
                                    
                                }
                                $newVendorProductLocal->product_vendor_id   = $newVendorProduct->id;
                                $newVendorProductLocal->lang_code           = strtoupper($key);
                                $newVendorProductLocal->local_name          = $val;

                                $productLocalSaved = $newVendorProductLocal->save();
                            }
                        }

                        if ($images) {
                            $images = $request->file('images');
                            $i = 1;
                            foreach ($images as $img) {
                                $rawFilename    = $img->getClientOriginalName();
                                $extension      = pathinfo($rawFilename, PATHINFO_EXTENSION);
                                $filename       = $newVendorProduct->id.'_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
                                
                                if(in_array($extension,Helper::UPLOADED_IMAGE_FILE_TYPES)) {
                                    $image_resize = Image::make($img->getRealPath());
                                    $image_resize->save(public_path('uploads/products/'.$filename));
            
                                    $image_resize->resize(AdminHelper::ADMIN_PRODUCT_THUMB_IMAGE_WIDTH, AdminHelper::ADMIN_PRODUCT_THUMB_IMAGE_HEIGHT, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                    $image_resize->save(public_path('uploads/products/thumbs/'.$filename));
            
                                    $newProductImages                    = new ProductImages();
                                    $newProductImages->product_id        = $productId;
                                    $newProductImages->product_vendor_id = $newVendorProduct->id;
                                    $newProductImages->image_name        = $filename;
                                    if ($i == 1) {
                                        $newProductImages->default_image = '1';
                                    }
                                    $newProductImages->save();
                                    $i++;
                                }
                            }
                        }
                        return \Response::json(ApiHelper::generateResponseBody('PC-AVP-0001#product_vendor_add', trans('custom.product_added_success')));
                    } else {
                        return \Response::json(ApiHelper::generateResponseBody('PC-AVP-0002#product_vendor_add', trans('custom.something_went_wrong'), false, 200));
                    }
                } else {
                    return \Response::json(ApiHelper::generateResponseBody('PC-AVP-0003#product_vendor_add', trans('custom.product_selling_price_is_not_provided'), false, 300));
                }
            } else {
                return \Response::json(ApiHelper::generateResponseBody('PC-AVP-0004#product_vendor_add', trans('custom.product_id_is_not_provided'), false, 400));
            }
        }
    }

    /*****************************************************/
    # ProductController
    # Function name : editVendorProduct
    # Author :
    # Created Date : 22-05-2019
    # Purpose :  to edit vendor product
    # Params :
    /*****************************************************/
    public function editVendorProduct(Request $request)
    {
        $userData   = ApiHelper::getUserFromHeader($request);
        $productId  = $request->product_id;
        $price      = $request->price;
        $images     = $request->images;
        if ($productId) {
            if ($price) {

                $newVendorProduct = ProductVendor::where('product_id', '=', $productId)->where('vendor_id', '=', $userData->id)->first();
                $newVendorProduct->price = $price;
                $newVendorProduct->save();

                if ($images) {
                    $images = $request->file('images');
                    $i=1;
                    foreach ($images as $img) {
                        $rawFilename    = $img->getClientOriginalName();
                        $extension      = pathinfo($rawFilename, PATHINFO_EXTENSION);
                        $filename       = $newVendorProduct->id.'_'.strtotime(date('Y-m-d H:i:s')).$i.'.'.$extension;
                        $image_resize   = Image::make($img->getRealPath());
                        $image_resize->save(public_path('uploads/products/' . $filename));

                        $image_resize->resize(100, 100, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $image_resize->save(public_path('uploads/products/thumbs/' . $filename));

                        $newProductImages = new ProductImages();
                        $newProductImages->product_id = $productId;
                        $newProductImages->product_vendor_id = $newVendorProduct->id;
                        $newProductImages->image_name = $filename;
                        $newProductImages->save();
                        $i++;
                    }
                }
                return \Response::json(ApiHelper::generateResponseBody('PC-EVP-0001#product_vendor_edit', trans('custom.product_updated_success')));
            } else {
                return \Response::json(ApiHelper::generateResponseBody('PC-EVP-0003#product_vendor_edit', trans('custom.product_selling_price_is_not_provided'), false, 300));
            }

        } else {
            return \Response::json(ApiHelper::generateResponseBody('PC-EVP-0002#product_vendor_edit', trans('custom.product_id_is_not_provided'), false, 200));
        }
    }

    /*****************************************************/
    # ProductController
    # Function name : deleteVendorProduct
    # Author :
    # Created Date : 22-05-2019
    # Purpose :  to delete vendor product
    # Params :
    /*****************************************************/
    public function deleteVendorProduct(Request $request)
    {

        $userData = ApiHelper::getUserFromHeader($request);
        $productId = $request->product_id;
        $price = $request->price;
        $images = $request->images;
        if ($productId) {
            $vendorProduct = ProductVendor::where('product_id', '=', $productId)->where('vendor_id', '=', $userData->id)->first();
            $vendorProductImages = $vendorProduct->product->pictures()->where('product_vendor_id', '=', $vendorProduct->id)->get();
            if (count($vendorProductImages) > 0) {
                foreach ($vendorProductImages as $img) {
                    @unlink(public_path('uploads/products/' . $img->image_name));
                    @unlink(public_path('uploads/products/thumbs/' . $img->image_name));
                    $img->delete();
                }
            }
            $vendorProduct->delete();

            return \Response::json(ApiHelper::generateResponseBody('PC-DVP-0001#product_vendor_delete', trans('custom.deleted_successfully')));

        } else {
            return \Response::json(ApiHelper::generateResponseBody('PC-DVP-0002#product_vendor_delete', trans('custom.product_id_is_not_provided'), false, 200));
        }
    }

    /*****************************************************/
    # ProductController
    # Function name : deleteVendorProductImages
    # Author :
    # Created Date : 22-05-2019
    # Purpose :  to delete vendor product image
    # Params :
    /*****************************************************/
    public function deleteVendorProductImages(Request $request)
    {

        $imageId = $request->image_id;
        if ($imageId) {
            $vendorProdyctImage = ProductImages::find($imageId);

            @unlink(public_path('uploads/products/' . $vendorProdyctImage->image_name));
            @unlink(public_path('uploads/products/thumbs/' . $vendorProdyctImage->image_name));
            $vendorProdyctImage->delete();

            return \Response::json(ApiHelper::generateResponseBody('PC-DVPI-0001#product_vendor_images_delete', trans('custom.deleted_successfully')));

        } else {
            return \Response::json(ApiHelper::generateResponseBody('PC-DVPI-0002#product_vendor_images_delete', trans('custom.product_id_is_not_provided'), false, 200));
        }
    }

    /*****************************************************/
    # ProductController
    # Function name : getVendorProductList
    # Author        :
    # Created Date  : 22-05-2019
    # Purpose       : Get vendor product list pagination wise
    # Params        :
    /*****************************************************/
    public function getVendorProductList(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);

        $userData = ApiHelper::getUserFromHeader($request);

        $offset = 0;
        $limit  = Helper::WHOLESALER_PRODUCT_LIST_LIMIT;
        $page   = isset($request->page)?$request->page:0;
        if ($page > 0) {
            $offset = ($limit * $page);
        }

        $listData           = [];
        $allProductListData = [];
        $totalProducts      = 0;
        
        $totalProducts = $userData->products()->with(['product'])->whereNull('deleted_at')->count();
        
        $productsList = $userData->products()->with([
                                                'product',
                                                'productUnitLocals'=> function($query) use ($lang) {
                                                    $query->where('lang_code','=', $lang);
                                                },
                                                'productVendorsLocals'=> function($query) use ($lang) {
                                                    $query->where('lang_code','=', $lang);
                                                },
                                                'categoryVendorsLocals'=> function($query) use ($lang) {
                                                    $query->where('lang_code','=', $lang);
                                                },
                                                'productDefaultImage'=>function($q) {
                                                    $q->select('id', 'product_vendor_id', 'image_name');
                                                },
                                            ])
                                            ->whereNull('deleted_at')
                                            // ->where('status', '=', '1')
                                            ->orderBy('created_at', 'desc')
                                            ->skip($offset)
                                            ->take($limit)
                                            ->get();
        
        if (count($productsList) > 0) {
            $baseUrl            = asset('');
            $productsUrl        = 'uploads/products/thumbs/';
            $productImageUrl    = $baseUrl.$productsUrl;
            $listData['total_products']  = $totalProducts;

            foreach($productsList as $keyProduct => $valProduct) {
                $newProduct['id']           = $valProduct->id;
                if ($valProduct->productVendorsLocals != null) {
                    $newProduct['name']                 = $valProduct->productVendorsLocals[0]['local_name'];
                    $newProduct['description']          = $valProduct->productVendorsLocals[0]['description'];
                    $newProduct['unit_description']     = $valProduct->productVendorsLocals[0]['unit_description'];
                    $newProduct['unit_name']= $valProduct->productVendorsLocals[0]['local_unit_name'] ? $valProduct->productVendorsLocals[0]['local_unit_name'] : '';
                } else {
                    $newProduct['name']                 = '';
                    $newProduct['description']          = '';
                    $newProduct['unit_description']     = '';
                    $newProduct['unit_name']            = '';
                }
                if ($valProduct->categoryVendorsLocals != null) {
                    $newProduct['category_name']                 = $valProduct->categoryVendorsLocals[0]['local_name'];
                } else {
                    $newProduct['category_name']                 = '';
                }
                $newProduct['slug']         = $valProduct->slug;
                $newProduct['pack_value']   = (string)$valProduct->pack_value;
                $newProduct['mrp']          = (string)$valProduct->mrp;
                $newProduct['selling_price']= (string)$valProduct->selling_price;
                $newProduct['vat_percent']  = $valProduct->vat_percent? (string)$valProduct->vat_percent : '';
                $newProduct['vat_price']    = $valProduct->vat_price? (string)$valProduct->vat_price : '';
                //$newProduct['local']        = $valProduct->productVendorsLocals;                
                //$newProduct['unit_local']   = $valProduct->productUnitLocals;
                
                if ( count($valProduct->productDefaultImage) > 0) {
                    $newProduct['image']    = $productImageUrl.$valProduct->productDefaultImage[0]['image_name'];
                } else {
                    $newProduct['image']    = '';
                }
                $newProduct['status']    = $valProduct->status;
                $allProductListData[] = $newProduct;
                unset($newProduct);
            }
            $listData['product_list']       = $allProductListData;

            return \Response::json(ApiHelper::generateResponseBody('PC-GVPL-0001#product_vendor_list', $listData));
        } else {
            $listData['total_products']  = 0;
            $listData['product_list']       = [];
            return \Response::json(ApiHelper::generateResponseBody('PC-GVPL-0002#product_vendor_list',$listData));
        }
    }

    /*****************************************************/
    # ProductController
    # Function name : productList
    # Author        :
    # Created Date  : 03-07-2019
    # Purpose       : Get product list pagination wise and filtration
    # Params        :
    /*****************************************************/
    public function productList(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);

        $offset = 0;
        $limit  = Helper::WHOLESALER_PRODUCT_LIST_LIMIT;
        $page   = isset($request->page)?$request->page:0;
        if ($page > 0) {
            $offset = ($limit * $page);
        }

        $filter_category = false;
        if($request->has('category_id') && $request->category_id){
            $category_id = $request->category_id;
            $filter_category = true;
        }

        $filter_price_low = false;
        if($request->has('low_price')  && $request->low_price){
            $low_price = $request->low_price;
            $filter_price_low = true;
        }

        $filter_price_high = false;
        if($request->has('high_price')  && $request->high_price){
            $high_price = $request->high_price;
            $filter_price_high = true;
        }

        $filter_popularity = false;
        if($request->has('popularity')  && $request->popularity){
            $popularity = $request->popularity;
            $filter_popularity = true;
        }

        $filter_search = false;
        if($request->has('search')  && $request->search){
            $search = $request->search;
            $filter_search = true;
        }
        

        $listData           = [];
        $allProductListData = [];
        $totalProducts      = 0;
        $filter_sub_category = false;
        if($filter_category){
            $sub_category_ids = Category::select('id')->where('parent_id',$category_id)->get();
            
            if($sub_category_ids->count()){
                $filter_sub_category = true;
                $sub_category_ids = $sub_category_ids->pluck('id')->toArray();
            }
        }

        $listData           = [];
        $allProductListData = [];
        $totalProducts      = 0;
        
        
        
        $productsList = ProductVendor::whereNull('deleted_at')
                                        ->where('status', '=', '1')->with([
                                            'product',
                                            'productUnitLocals'=> function($query) use ($lang) {
                                                $query->where('lang_code','=', $lang);
                                            },
                                            'productVendorsLocals'=> function($query) use ($lang) {
                                                $query->where('lang_code','=', $lang);
                                            },
                                            'productDefaultImage'=>function($q) {
                                                $q->select('id', 'product_vendor_id', 'image_name');
                                            },
                                            'details'
                                        ]);
        if($filter_category){
            if($filter_sub_category){
                $productsList->whereIn('sub_category_id', $sub_category_ids);
            }else{
                $productsList->where('category_id', $category_id);
            }
        }
        
        if($filter_price_low){
            $productsList->where('selling_price', '>=',  $low_price);
        }

        if($filter_price_high){
            $productsList->where('selling_price', '<=',  $high_price);
        }
        //popularity
        //low to high price -> low_to_high_price
        //high to low price -> high_to_low_price
        //old to new product -> old_to_new_product
        //new to old product -> new_to_old_product
        if($filter_popularity){
            if($popularity == 'low_to_high_price'){
                $productsList->orderBy('selling_price', 'asc');
            }else if($popularity == 'high_to_low_price'){
                $productsList->orderBy('selling_price', 'desc');
            }else if($popularity == 'old_to_new_product'){
                $productsList->orderBy('created_at', 'asc');
            }else if($popularity == 'new_to_old_product'){
                $productsList->orderBy('created_at', 'desc');
            }
        }

        if($filter_search){
            $productsList->whereHas('productVendorsLocals', function($q) use ($lang,$search){
                $q->where('lang_code','=', $lang);
                $q->where('local_name','like', '%'.$search.'%');
            });
        }

        if(!$filter_popularity){
            $productsList->orderBy('created_at', 'desc');
        }
        $totalProducts = $productsList->count();
        $productsList = $productsList->skip($offset)
        ->take($limit)
        ->get();
        // dd($productsList);
        if (count($productsList) > 0) {
            $baseUrl            = asset('');
            $productsUrl        = 'uploads/products/thumbs/';
            $productImageUrl    = $baseUrl.$productsUrl;
            
            $listData['total_products']  = $totalProducts;

            foreach($productsList as $keyProduct => $valProduct) {
                $newProduct['id']           = $valProduct->id;
                if ($valProduct->productVendorsLocals != null) {
                    $newProduct['name']                 = $valProduct->productVendorsLocals[0]['local_name'];
                    $newProduct['description']          = Helper::cleanString($valProduct->productVendorsLocals[0]['description']);
                    $newProduct['unit_description']     = Helper::cleanString($valProduct->productVendorsLocals[0]['unit_description']);
                    $newProduct['unit_name']= $valProduct->productVendorsLocals[0]['local_unit_name'] ? $valProduct->productVendorsLocals[0]['local_unit_name'] : '';
                } else {
                    $newProduct['name']                 = '';
                    $newProduct['description']          = '';
                    $newProduct['unit_description']     = '';
                    $newProduct['unit_name']= '';
                }
                if ($valProduct->details != null) {
                    $newProduct['wholesaler_name']        = $valProduct->details->full_name;
                }else{
                    $newProduct['wholesaler_name']         = '';
                }
                $newProduct['slug']         = $valProduct->slug;
                $newProduct['category_id']         = (string)$valProduct->category_id;
                $newProduct['sub_category_id']         = $valProduct->sub_category_id? (string)$valProduct->sub_category_id : '';;
                $newProduct['pack_value']   = (string)$valProduct->pack_value;
                $newProduct['mrp']          = (string)$valProduct->mrp;
                $newProduct['selling_price']= (string)$valProduct->selling_price;
                $newProduct['vat_percent']  = $valProduct->vat_percent? (string)$valProduct->vat_percent : '';
                $newProduct['vat_price']    = $valProduct->vat_price? (string)$valProduct->vat_price : '';
                
                if ( count($valProduct->productDefaultImage) > 0) {
                    $newProduct['image']    = $productImageUrl.$valProduct->productDefaultImage[0]['image_name'];
                } else {
                    $newProduct['image']    = '';
                }
                $allProductListData[] = $newProduct;
                unset($newProduct);
            }
            $listData['product_list']       = $allProductListData;

            return \Response::json(ApiHelper::generateResponseBody('PC-PL-0001#product_list', $listData));
        } else {
            $listData['product_list'] = [];
            return \Response::json(ApiHelper::generateResponseBody('PC-PL-0002#product_list', $listData));
        }
    }
}