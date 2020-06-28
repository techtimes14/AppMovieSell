<?php
/*****************************************************/
# ContractController
# Page/Class name : ContractController
# Author :
# Created Date : 24-05-2019
# Functionality : addContract
# Purpose : contarct  related all functions
/*****************************************************/
namespace App\Http\Controllers\api;
use Illuminate\Http\Request;
use App;
use App\Http\Controllers\Controller;
use \Response;
use Helper;
use \App\Contract;
use \App\User;
use \Auth;
use Image;
use Validator;
Use Redirect;
use \App\Category;
use \App\ContractProduct;
use \App\ContractProductLocal;
use \App\Frequency;
use \App\ProductCategory;
use \App\Product;
use \App\ProductVendor;
use \App\ProductVendorLocal;
use \App\ProductImages;
use \App\UserContractor;
use \App\SiteSetting;
use \App\PushNotification;
use \App\ContractShipmentNote;
use \App\ContractShipmentProduct;
use App\Http\Helpers\ApiHelper;
use App\Http\Helpers\AdminHelper;
use App\Http\Helpers\NotificationHelper;

class ContractController extends Controller
{
    /*****************************************************/
    # ContractController
    # Function name : listContract
    # Author :
    # Created Date : 24-05-2019
    # Purpose :  to list of all contract
    # Params : $userType (contractor/customer)
    /*****************************************************/
    public function listContract(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);

        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('CC-LC-0003#contract_list',  trans('custom.not_authorized'), false, 400));
        }
        $offset = 0;
        $limit  = Helper::WHOLESALER_PRODUCT_LIST_LIMIT;
        $page   = isset($request->page)?$request->page:0;
        if ($page > 0) {
            $offset = ($limit * $page);
        }

        $getAllContractors  = User::where('is_contractor', '1')
                                    ->where('id','<>', $userData->id)
                                    ->where('status','=', 1);
        $totalContractors   = $getAllContractors->count();
        $getAllContractors  = $getAllContractors->skip($offset)->take($limit)->get();

        $baseUrl                = asset('');
        $contractorsUrl         = 'uploads/users/thumbs/';
        $contractorImageUrl     = $baseUrl.$contractorsUrl;
        $allContractorList      = array();
        if($getAllContractors->count()){
            $allContractors = [];
            foreach($getAllContractors as $key => $val) {
                $contracts['id']           = $val->id;

                $contracts['profile_image']                 = $val->profile_image ? $contractorImageUrl.$val->profile_image : '';
                $contracts['name']                          = $val->userContractor ? $val->userContractor->full_name : '';
                $contracts['mobile_number']                 = $val->userContractor ? $val->userContractor->mobile_number : '';
                $contracts['all_contract_count']            = $val->allContractCount->count();
                $contracts['all_contract_completed_count']  = $val->allComplitedContractCount->count();
                $allContractorList[]                        = $contracts;
                unset($contracts);
            }
            $listData['total_list']             = $totalContractors;
            $listData['contractor_list']        = $allContractorList;
            
            return \Response::json(ApiHelper::generateResponseBody('CC-LC-0001#contract_list', $listData));
        }else{
            return \Response::json(ApiHelper::generateResponseBody('CC-LC-0002#contract_list', ['contractor_list' => [],'total_list'=>0]));
        }
    }

    /*****************************************************/
    # ContractController
    # Function name : getCategoryFrequency
    # Author        :
    # Created Date  : 24-07-2019
    # Purpose       : To get category list and frequency list
    # Params        : Request $request
    /*****************************************************/
    public function getCategoryFrequency(Request $request)
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
        $data['category_list'] = $parentCaregoryList;

        $frequencyList = $frequency = Frequency::with([
                    'local' => function ($qry) use ($lang) {
                        $qry->where('lang_code', '=', $lang);
                    },
                ])->get();
        $data['frequency_list'] = $frequencyList;

        if ($data) {
            return Response::json(ApiHelper::generateResponseBody('CC-GCF-0001#contract_get_category_frequency', $data));
        } else {
            return Response::json(ApiHelper::generateResponseBody('CC-GCF-0002#contract_get_category_frequency', trans('custom.something_went_wrong'), false, 100)); 
        }
    }

    /*****************************************************/
    # ContractController
    # Function name : getSubCategoryProduct
    # Author        :
    # Created Date  : 24-07-2019
    # Purpose       : To get sub category lists
    # Params        : Request $request
    /*****************************************************/
    public function getSubCategoryProduct(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);

        $userData  = ApiHelper::getUserFromHeader($request);
        $category_id = $request->category_id;
        if ($request->has('category_id')) {
            $subCaregoryList = Category::select('id', 'name', 'parent_id')->with([
                'local' => function ($query) use ($lang) {
                    $query->where('lang_code', '=', $lang);
                },
            ])
                ->where('status', '1')->where('parent_id', $category_id)->get();
            $data['sub_category_list'] = $subCaregoryList->count() ? ApiHelper::replaceNulltoEmptyStringAndIntToString($subCaregoryList->toArray()) : [];
            $data['product_list'] = [];
            $productIds = [];
            if (!$subCaregoryList->count()) {

                $productList = ProductVendor::where(['status' => '1'])
                    ->where('category_id', $category_id)
                    ->whereNull('deleted_at')
                    ->with([
                        'local' => function ($query) use ($lang) {
                            $query->where('lang_code', '=', $lang);
                        },
                    ])
                    ->select('id', 'name')
                    ->get();
                $data['product_list'] = $productList;

                if ($productList->count() > 0) {
                    $data['product_list'] = ApiHelper::replaceNulltoEmptyStringAndIntToString($productList->toArray());
                    return \Response::json(ApiHelper::generateResponseBody('CC-GSCP-0003#contract_get_sub_category_product', $data));
                } else {
                    return \Response::json(ApiHelper::generateResponseBody('CC-GSCP-0004#contract_get_sub_category_product', $data));
                }

            } else {
                return \Response::json(ApiHelper::generateResponseBody('CC-GSCP-0001#contract_get_sub_category_product', $data));
            }
        } else {
            return \Response::json(ApiHelper::generateResponseBody('CC-GSCP-0002#contract_get_sub_category_product', trans('custom.something_went_wrong'), false, 200));
        }
    }

    /*****************************************************/
    # ContractController
    # Function name : getProduct
    # Author        :
    # Created Date  : 24-07-2019
    # Purpose       : To get product list from sub category id
    # Params        : Request $request
    /*****************************************************/
    public function getProduct(Request $request)
    {
        $currentLang = App::getLocale();
        $lang = strtoupper($currentLang);
        $sub_category_id = $request->category_id;
        if ($request->has('category_id')) {

            $data['product_list'] = [];

            $productList = ProductVendor::where(['status' => '1'])
                                        ->where('sub_category_id', $sub_category_id)
                                        ->whereNull('deleted_at')
                                        ->with([
                                            'local' => function ($query) use ($lang) {
                                                $query->where('lang_code', '=', $lang);
                                            },
                                        ])
                                        ->select('id', 'name')
                                        ->get();
            $data['product_list'] = $productList;

            if ($productList->count() > 0) {
                $data['product_list'] = ApiHelper::replaceNulltoEmptyStringAndIntToString($productList->toArray());
                return \Response::json(ApiHelper::generateResponseBody('CC-GP-0003#contract_get_product', $data));
            } else {
                return \Response::json(ApiHelper::generateResponseBody('CC-GP-0001#contract_get_product', $data));
            }
        } else {
            return \Response::json(ApiHelper::generateResponseBody('CC-GP-0002#contract_get_product', trans('custom.something_went_wrong'), false, 200));
        }
    }

    /*****************************************************/
    # ContractController
    # Function name : incompleteContractDetails
    # Author        :
    # Created Date  : 24-07-2019
    # Purpose       : To get incomplete contract details 
    # Params        : Request $request
    /*****************************************************/
    public function incompleteContractDetails(Request $request)
    {
        $getSetLang     = ApiHelper::getSetLocale($request);
        $lang           = strtoupper($getSetLang);
        $contractorId   = $request->contractor_id;
        $userData       = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('CC-IC-0001#contract_incomplete_contract_details',  trans('custom.not_authorized'), false, 400));
        }
        $contractCreatorId = $request->contract_creator_id;
        $contractDetails = Contract::where('contract_creator_id',$contractCreatorId)->where('contractor_id',$contractorId)->where('status','IC')->with([
            'contractProduct' =>function($query) use($lang)  {
                $query->with([
                    'contractProductLocal' => function ($query) use ($lang) {
                        $query->where('lang_code', '=', $lang);
                    },
                ]);
            },
        ])->first();
        
        $data['contract'] = array();
        if($contractDetails){
            $data['contract']['id'] = $contractDetails->id;
            $data['contract']['unique_contract_id'] = $contractDetails->unique_contract_id;
            $data['contract']['status'] = $contractDetails->status;
            $data['contract']['contract_product'] = array();
            if($contractDetails->contractProduct->count()){
                foreach($contractDetails->contractProduct as $key => $val){
                    $prod['contract_product_id'] = $val->id;
                    $prod['local_product_name'] = $val->contractProductLocal->count() ? $val->contractProductLocal[0]->local_product_name : '';
                    $prod['quantity'] = $val->quantity ? (string)$val->quantity : '';
                    $prod['pack_value'] = $val->pack_value ? (string)$val->pack_value : '';
                    $prod['local_unit_name'] = $val->contractProductLocal->count() ? $val->contractProductLocal[0]->local_unit_name : '';
                    if($val->frequency){
                        $prod['frequency'] = $val->frequency->frequencyLocals->count() ? $val->frequency->frequencyLocals[0]->local_name : '';
                    }else{
                        $prod['frequency'] = '';
                    }
                    
                    $data['contract']['contract_product'][] = $prod;
                    unset($prod);
                }
            }
            $data['contract']['date_time_address']['start_date']        = $contractDetails->start_date ? date('m/d/Y', $contractDetails->start_date) : '';
            $data['contract']['date_time_address']['end_date']          = $contractDetails->end_date ? date('m/d/Y', $contractDetails->end_date) : '';
            $data['contract']['date_time_address']['full_name']         = $contractDetails->full_name ? $contractDetails->full_name : '';
            $data['contract']['date_time_address']['mobile']            = $contractDetails->mobile ? $contractDetails->mobile : '';
            $data['contract']['date_time_address']['landmark']          = $contractDetails->landmark ? $contractDetails->landmark : '';
            $data['contract']['date_time_address']['delivery_address']  = $contractDetails->delivery_address ? $contractDetails->delivery_address : '';

            $data['contract']['payment_note']['payment_mode']   = (string)$contractDetails->payment_mode;
            $data['contract']['payment_note']['title']          = $contractDetails->title ? $contractDetails->title : '';
            $data['contract']['payment_note']['payment_note']   = $contractDetails->payment_note ? $contractDetails->payment_note : '';
            
            // $data['contract']['review_and_submit']['comment'] = $contractDetails->comment ? $contractDetails->comment : '';
            $data['contract']['review_and_submit']['terms'] = $contractDetails->terms ? $contractDetails->terms : '';
        }else{
            $data['contract'] = '';
        }
        
        return \Response::json(ApiHelper::generateResponseBody('CC-IC-0001#contract_incomplete_contract_details',  $data));
        
        if($contractorId){

        }else{
            return \Response::json(ApiHelper::generateResponseBody('CC-IC-0002#contract_incomplete_contract_details', trans('custom.something_went_wrong'), false, 200));
        }
    }

    /*****************************************************/
    # ContractController
    # Function name : createContract1
    # Author        :
    # Created Date  : 25-07-2019
    # Purpose       : To create contract part 1
    # Params        : Request $request
    /*****************************************************/
    public function createContract1(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        $contractorId = $request->contractor_id;
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('CC-CC1-0001#contract_create_contracts_1',  trans('custom.not_authorized'), false, 400));
        }
        $contractCreatorId = $request->contract_creator_id;
        if($contractorId && $contractCreatorId){
            $contractorDetails = User::select('id','is_contractor','status')->where('id',$contractorId)->where('is_contractor','1')->where('status','1')->first();
            $contractCreatorDetails = User::select('id','is_contractor','status')->where('id',$contractCreatorId)->where('status','1')->first();
            if($contractorDetails && $contractCreatorDetails){
                $validator = Validator::make($request->all(),
                [
                    'category_id'   => 'required',
                    'product_id'    => 'required',
                    'quantity'      => 'required|regex:/^[0-9]+$/',
                    'frequency'     => 'required',
                ],
                [
                    'category_id.required'  => trans('custom.category_required'),
                    'product_id.required'   => trans('custom.product_required'),
                    'quantity.required'     => trans('custom.quantity_required'),
                    'frequency.required'    => trans('custom.frequency_required'),
                    'quantity.regex'        => trans('custom.quantity_regex'),
                ]
                );
                $errors = $validator->errors()->all();
                if ($errors) {
                    return \Response::json(ApiHelper::generateResponseBody('CC-CC1-0002#contract_create_contracts_1', ["errors" => $errors], false, 100));
                } else {
                    
                    $contractDetails = Contract::where('contract_creator_id', $contractCreatorId)->where('contractor_id', $contractorId)->where('status','IC')->first();
                    if (!$contractDetails) {
                        $contractDetails = new Contract;
                        $contractDetails->unique_contract_id = Helper::generateUniquesOrderId();
                        $contractDetails->contract_creator_id = $contractCreatorId;
                        $contractDetails->contractor_id = $contractorId;
                        $contractDetails->save();
                    }
                    $categoryId = isset($request->category_id) ? $request->category_id : 0;
                    $productId = $request->product_id;
                    $quantity = isset($request->quantity) ? $request->quantity : 0;
                    $frequency = isset($request->frequency) ? $request->frequency : 0;
    
                    if ($contractDetails->contractProduct->count() >= 10) {
                        return \Response::json(ApiHelper::generateResponseBody('CC-CC1-0003#contract_create_contracts_1',  trans('custom.max_limit_crossed_for_contract_product'), false, 400));
                    } else {
    
                        $productVendor = ProductVendor::where('id', $productId)->first();
    
                        if ($productVendor) {
                            $contractProduct = new ContractProduct;
                            $contractProduct->contract_id = $contractDetails->id;
                            $contractProduct->category_id = $categoryId;
                            if ($request->sub_category_id != '') {
                                $contractProduct->subcategory_id = $request->sub_category_id;
                            } else {
                                $contractProduct->subcategory_id = null;
                            }
                            $contractProduct->product_vendor_id     = $productId;
                            $contractProduct->mrp                   = $productVendor->mrp;
                            $contractProduct->product_selling_price = $productVendor->selling_price;
                            $contractProduct->vat_percent           = $productVendor->vat_percent;
                            $contractProduct->vat_price             = $productVendor->vat_price;
                            $contractProduct->price                 = $productVendor->price;
                            $contractProduct->pack_value            = $productVendor->pack_value;
                            $contractProduct->vendor_id             = $productVendor->vendor_id;
                            $contractProduct->quantity              = $quantity;
                            $contractProduct->frequency_id          = $frequency;
                            if ($contractProduct->save()) {
                                if ($productVendor->productVendorsLocals->count()) {
                                    foreach ($productVendor->productVendorsLocals as $key => $val) {
    
                                        $newProductLocal = new ContractProductLocal;
                                        $newProductLocal->local_product_name        = $val->local_name;
                                        $newProductLocal->local_product_description = $val->description;
                                        $newProductLocal->local_unit_description    = $val->unit_description;
                                        $newProductLocal->lang_code                 = $val->lang_code;
                                        $newProductLocal->local_unit_name           = $val->local_unit_name;
                                        if ($productVendor->categoryVendorsLocals->count()) {
                                            foreach ($productVendor->categoryVendorsLocals as $categoryKey => $categoryVal) {
                                                if ($categoryVal->lang_code == $val->lang_code) {
                                                    $newProductLocal->local_category_name = $categoryVal->local_name;
                                                }
                                            }
                                        }
                                        if ($productVendor->subcategoryVendorsLocals->count()) {
                                            foreach ($productVendor->subcategoryVendorsLocals as $subCategoryKey => $subCategoryVal) {
                                                if ($subCategoryVal->lang_code == $val->lang_code) {
                                                    $newProductLocal->local_subcategory_name = $subCategoryVal->local_name;
                                                }
                                            }
                                        }
    
                                        $newProductLocal->contract_id = $contractDetails->id;
                                        $newProductLocal->contract_product_id = $contractProduct->id;
    
                                        $productLocalSaved = $newProductLocal->save();
                                    }
                                }
                                return \Response::json(ApiHelper::generateResponseBody('CC-CC1-0004#contract_create_contracts_1',  trans('custom.product_added_success')));
                            } else {
                                return \Response::json(ApiHelper::generateResponseBody('CC-CC1-0005#contract_create_contracts_1',  trans('custom.something_went_wrong'), false, 400));
                            }
                        } else {
                            return \Response::json(ApiHelper::generateResponseBody('CC-CC1-0006#contract_create_contracts_1',  trans('custom.something_went_wrong'), false, 400));
                        }
                    }
                }
            }else{
                return \Response::json(ApiHelper::generateResponseBody('CC-CC1-0008#contract_create_contracts_1',  trans('custom.not_authorized'), false, 400));
            }
        }else{
            return \Response::json(ApiHelper::generateResponseBody('CC-CC1-0007#contract_create_contracts_1',  trans('custom.something_went_wrong'), false, 400));
        } 
     }

    /*****************************************************/
    # ContractController
    # Function name : createContract2
    # Author        :
    # Created Date  : 25-07-2019
    # Purpose       : To create contract part 2
    # Params        : Request $request
    /*****************************************************/
    public function createContract2(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        $contractorId = $request->contractor_id;
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('CC-CC2-0001#contract_create_contracts_2',  trans('custom.not_authorized'), false, 400));
        }
        $contractCreatorId = $request->contract_creator_id;
        $contractDetails = Contract::where('contract_creator_id',$contractCreatorId)->where('contractor_id',$contractorId)->where('status','IC')->with([
            'contractProduct' =>function($query) use($lang)  {
                $query->with([
                    'contractProductLocal' => function ($query) use ($lang) {
                        $query->where('lang_code', '=', $lang);
                    },
                ]);
            },
        ])->first();
        if (!$contractDetails) {
            return \Response::json(ApiHelper::generateResponseBody('CC-CC2-0002#contract_create_contracts_2',  trans('custom.create_contract_1_not_submitted'), false, 400));
        }
        if (!$contractDetails->contractProduct->count()) {
            return \Response::json(ApiHelper::generateResponseBody('CC-CC2-0003#contract_create_contracts_2',  trans('custom.create_contract_1_product_not_added'), false, 400));
        }

        $validator = Validator::make($request->all(),
                            [
                                'start_date'        => 'required',
                                'end_date'          => 'required',
                                'name'              => 'required',
                                'mobile'            => 'required',
                                'landmark'          => 'required',
                                'delivery_address'  => 'required',
                            ],
                            [
                                'start_date.required'           => trans('custom.start_date_required'),
                                'end_date.required'             => trans('custom.end_date_required'),
                                'name.required'                 => trans('custom.full_name_required'),
                                'mobile.required'               => trans('custom.phone_no_required'),
                                'landmark.required'             => trans('custom.landmark_required'),
                                'delivery_address.required'     => trans('custom.address_required')
                            ]
                        );
        $errors = $validator->errors()->all();
        if ($errors) {
            return \Response::json(ApiHelper::generateResponseBody('CC-CC2-0004#contract_create_contracts_2', ["errors" => $errors], false, 100));
            
        } else {
            $start_date         = $request->start_date;
            $end_date           = $request->end_date;
            $name               = $request->name;
            $mobile             = $request->mobile;
            $landmark           = $request->landmark;
            $delivery_address   = $request->delivery_address;  


            $contractDetails->start_date        = Helper::formattedTimestamp($start_date);           
            $contractDetails->end_date          = Helper::formattedTimestamp($end_date);  
            $contractDetails->full_name         = $name;         
            $contractDetails->mobile            = $mobile;                    
            $contractDetails->landmark          = $landmark;
            $contractDetails->delivery_address  = $delivery_address;
            if($contractDetails->save()){
                return \Response::json(ApiHelper::generateResponseBody('CC-CC2-0005#contract_create_contracts_2',  trans('custom.successfully_updated')));
            }
        }
    }

    /*****************************************************/
    # ContractController
    # Function name : createContract3
    # Author        :
    # Created Date  : 25-07-2019
    # Purpose       : To create contract part 3
    # Params        : Request $request
    /*****************************************************/
    public function createContract3(Request $request)
    { 
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        $contractorId = $request->contractor_id;
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('CC-CC3-0001#contract_create_contracts_3',  trans('custom.not_authorized'), false, 400));
        }
        $contractCreatorId = $request->contract_creator_id;
        $contractDetails = Contract::where('contract_creator_id',$contractCreatorId)->where('contractor_id',$contractorId)->where('status','IC')->with([
            'contractProduct' =>function($query) use($lang)  {
                $query->with([
                    'contractProductLocal' =>function($query) use($lang)  {
                        $query->where('lang_code','=', $lang);
                    },
                ]);
            },
        ])->first();
        if (!$contractDetails) {
            return \Response::json(ApiHelper::generateResponseBody('CC-CC3-0002#contract_create_contracts_3',  trans('custom.create_contract_1_not_submitted'), false, 400));
        }
        if (!$contractDetails->contractProduct->count()) {
            return \Response::json(ApiHelper::generateResponseBody('CC-CC3-0003#contract_create_contracts_3',  trans('custom.create_contract_1_product_not_added'), false, 400));
        }
            
        $validator = Validator::make($request->all(),
                            [
                                'payment_mode'         => 'required',
                                'title'                => 'required',
                                'payment_note'         => 'required',
                            ],
                            [
                                'payment_mode.required' => trans('custom.payment_mode_required'),
                                'title.required'        => trans('custom.title_required'),
                                'payment_note.required' => trans('custom.payment_note_required'),
                            ]
                        );
        $errors = $validator->errors()->all();
        if ($errors) {
            return \Response::json(ApiHelper::generateResponseBody('CC-CC3-0004#contract_create_contracts_3', ["errors" => $errors], false, 100));
        } else {
            $payment_mode       = $request->payment_mode;
            $title              = $request->title;
            $payment_note       = $request->payment_note;

            $contractDetails->payment_mode  = $payment_mode;         
            $contractDetails->title         = $title;                    
            $contractDetails->payment_note  = $payment_note;
            
            if($contractDetails->save()){
                return \Response::json(ApiHelper::generateResponseBody('CC-CC3-0005#contract_create_contracts_3',  trans('custom.successfully_updated')));  
            }
        }   
    }

    /*****************************************************/
    # ContractController
    # Function name : createContract4
    # Author        :
    # Created Date  : 25-07-2019
    # Purpose       : To create contract part 4
    # Params        : Request $request
    /*****************************************************/
    public function createContract4(Request $request)
    { 
        $getSetLang     = ApiHelper::getSetLocale($request);
        $lang           = strtoupper($getSetLang);
        $contractorId   = $request->contractor_id;
        $userData       = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('CC-CC4-0001#contract_create_contracts_4',  trans('custom.not_authorized'), false, 400));
        }
        $contractCreatorId = $request->contract_creator_id;
        $contractDetails = Contract::where('contract_creator_id',$contractCreatorId)->where('contractor_id',$contractorId)->where('status','IC')->with([
            'contractProduct' =>function($query) use($lang)  {
                $query->with([
                    'contractProductLocal' =>function($query) use($lang)  {
                        $query->where('lang_code','=', $lang);
                    },
                ]);
            },
        ])->first();
        if (!$contractDetails) {
            return \Response::json(ApiHelper::generateResponseBody('CC-CC4-0002#contract_create_contracts_4',  trans('custom.create_contract_1_not_submitted'), false, 400));
        }
        if (!$contractDetails->contractProduct->count()) {
            return \Response::json(ApiHelper::generateResponseBody('CC-CC4-0003#contract_create_contracts_4',  trans('custom.create_contract_1_product_not_added'), false, 400));
        }

            
        $validator = Validator::make($request->all(),
                            [
                                // 'comment'               => 'required',
                                'terms'                => 'required',
                            ],
                            [
                                // 'comment.required'      => trans('custom.comment_required'),
                                'terms.required'       => trans('custom.terms_required'),
                            ]
                        );
        $errors = $validator->errors()->all();
        if ($errors) {
            return \Response::json(ApiHelper::generateResponseBody('CC-CC4-0004#contract_create_contracts_4', ["errors" => $errors], false, 100));
        } else {
            // $comment         = $request->comment;
            $terms    = $request->terms; 

            if ($userData->id == $contractorId) {
                $contractDetails->created_by    = 'C';
                $contractDetails->status        = 'A';
            }else{
                $contractDetails->status        = 'P';
            }

            // $contractDetails->comment          = $comment;         
            $contractDetails->terms  = $terms;                    
            
            if($contractDetails->save()){

                NotificationHelper::sendNotificationContractCreate($contractDetails);
                
                $userData = User::find($contractCreatorId);
                $contractorData = UserContractor::where('user_id',$contractorId)->first();

                $siteSetting = SiteSetting::first();

                    //get contract details for the contractor mail
                    $contractDetailsForMail = Contract::where([
                        'id' => $contractDetails->id,
                        'contractor_id' => $contractDetails->contractor->id,
                        //'status' => 'P',
                    ])
                    ->where('contract_creator_id', '=', $contractCreatorId)
                    ->where('status', '<>', 'IC')   //Incomplete checking
                    ->with([
                        'contractProduct',
                        'contractCreatorDetails',
                    ])
                    ->first();
                    //dd($contractDetailsForMail);

                    if ($contractDetailsForMail->contractor->email != null) {
                    //mail send to contractor with contract details
                    \Mail::send('email_templates.site.response_to_contract',
                                    [
                                        'userData'                 => $userData,
                                        'contractorData'           => $contractorData,
                                        'contractDetailsForMail'   => $contractDetailsForMail,
                                             
                                        'thanks_order' => [
                                            'appname'           => $siteSetting->website_title,
                                            'appLink'           => Helper::getBaseUrl(),
                                            'controllerName'    => '',
                                            'currentLang'       => $lang,
                                            
                                        ],
                                    ], function ($m) use ($contractDetailsForMail) {                                        
                                        $m->to($contractDetailsForMail->contractor->email, $contractDetailsForMail->contractor->full_name)->subject('Response to placed contract');
                                    });
                    }
                    //mail send to customer with contract details
                    if ($userData->email != null) {
                        \Mail::send('email_templates.site.thanks_for_contract',
                                    [
                                        'userData'                 => $userData,
                                        'contractorData'           => $contractorData,
                                        'contractDetailsForMail'   => $contractDetailsForMail,
                                        'thanks_order' => [
                                            'appname'           => $siteSetting->website_title,
                                            'appLink'           => Helper::getBaseUrl(),
                                            'controllerName'    => '',
                                            'currentLang'       => $lang,
                                        ],
                                    ], function ($m) use ($userData) {
                                        $m->to($userData->email, $userData->full_name)->subject('Thanks for placing contract');
                                    });
                    }

                return \Response::json(ApiHelper::generateResponseBody('CC-CC4-0005#contract_create_contracts_4',  trans('custom.successfully_updated')));
            }
        }
    }

    /*****************************************************/
    # ContractController
    # Function name : deleteContractProduct
    # Author        :
    # Created Date  : 29-07-2019
    # Purpose       : To delete contract product
    # Params        : Request $request
    /*****************************************************/
    public function deleteContractProduct(Request $request)
    {
        $contractProductId = $request->contract_product_id;
        if ($contractProductId) {
            $contractProduct = ContractProduct::find($contractProductId);
            if ($contractProduct) {
                if ($contractProduct->contract->contractProduct->count() <= 1) {
                    return \Response::json(ApiHelper::generateResponseBody('CC-DCP-0001#contract_delete_contract_product',  trans('custom.cannot_delete_all_products'), false, 400));
                    // $request->session()->flash('alert-danger', trans('custom.cannot_delete_all_products'));
                } else {
                    ContractProductLocal::where('contract_product_id', $contractProductId)->delete();

                    $contractProduct->delete();
                    // $request->session()->flash('alert-success', trans('custom.cart_remove_success'));
                    return \Response::json(ApiHelper::generateResponseBody('CC-DCP-0004#contract_delete_contract_product',  trans('custom.product_removed_successfully')));
                }
            } else {
                return \Response::json(ApiHelper::generateResponseBody('CC-DCP-0002#contract_delete_contract_product',  trans('custom.something_went_wrong'), false, 400));
                // $request->session()->flash('alert-danger', trans('custom.something_went_wrong'));
            }
        } else {
            return \Response::json(ApiHelper::generateResponseBody('CC-DCP-0003#contract_delete_contract_product',  trans('custom.something_went_wrong'), false, 400));
            // $request->session()->flash('alert-danger', trans('custom.something_went_wrong'));
        }
    }

    /*****************************************************/
    # ContractController
    # Function name : showMyContractList (for user)
    # Author        :
    # Created Date  : 1-08-2019
    # Purpose       : To get ths listing of all contract of a user
    # Params        : Request $request
    /*****************************************************/
    public function showMyContractList(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('CC-SMCL-0001#contract_show_my_contract_list',  trans('custom.not_authorized'), false, 400));
        }

        $offset = 0;
        $limit  = Helper::WHOLESALER_PRODUCT_LIST_LIMIT;
        $page   = isset($request->page)?$request->page:0;
        if ($page > 0) {
            $offset = ($limit * $page);
        }

        $getMyAllContract = Contract::where('contract_creator_id', $userData->id)->with([
            'userContractorDetails'
        ])
        ->orderBy('start_date', 'DESC');
        $totalContract = $getMyAllContract->count();
        $getMyAllContract = $getMyAllContract->skip($offset)
        ->take($limit)->get();


        if($getMyAllContract->count()){
            $allgetMyAllContract = [];
            foreach($getMyAllContract as $key => $val) {
                $contracts['id']                = $val->id;
                $contracts['title']             = $val->title ? $val->title : '';
                $contracts['contractor_name']   = $val->userContractorDetails ? $val->userContractorDetails->full_name : '';
                $contracts['created_date']      = $val->created_at ? Helper::formattedDate($val->created_at) : '';
                $contracts['start_date']        = $val->start_date ? date('dS M, Y', $val->start_date) : '';
                $contracts['end_date']          = $val->end_date ? date('dS M, Y', $val->end_date) : '';
                $contracts['status']            = $val->status;
                
                $allgetMyAllContract[] = $contracts;
                unset($contracts);
            }
            $listData['total_contracts']     = $totalContract;
            $listData['contract_list']       = $allgetMyAllContract;
            
            return \Response::json(ApiHelper::generateResponseBody('CC-SMCL-0002#contract_show_my_contract_list', $listData));
        }else{
            return \Response::json(ApiHelper::generateResponseBody('CC-SMCL-0003#contract_show_my_contract_list', ['contract_list' => [],'total_contracts'=>0]));
        }
    }

    /*****************************************************/
    # ContractController
    # Function name : userContractDetails (for user)
    # Author        :
    # Created Date  : 01-08-2019
    # Purpose       : To get ths details of a particular contract for user
    # Params        : Request $request
    /*****************************************************/
    public function userContractDetails(Request $request){
        
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('CC-UCD-0001#contract_user_contract_details',  trans('custom.not_authorized'), false, 400));
        }
        if($request->contract_id){
            $getMySelectedContract = Contract::where([
                'id'                  => $request->contract_id,  
                'contract_creator_id' => $userData->id,
                 ])
                 ->with('contractProduct','userContractorDetails','contractor')
                ->first();
            $baseUrl            = asset('');
            $userUrl        = 'uploads/users/thumbs/';
            $userImageUrl    = $baseUrl.$userUrl;
            
            if($getMySelectedContract){
                $data['contractor_name'] = $getMySelectedContract->userContractorDetails ? $getMySelectedContract->userContractorDetails->full_name : '';
                $data['contractor_mobile_number'] = $getMySelectedContract->userContractorDetails ? $getMySelectedContract->userContractorDetails->mobile_number : '';
                if($getMySelectedContract->contractor){
                    $data['contractor_profile_image'] = $getMySelectedContract->contractor->profile_image ? $userImageUrl.$getMySelectedContract->contractor->profile_image : '';
                }else{
                    $data['contractor_profile_image'] = '';
                }
                $data['contract'] = array();
                $data['contract']['id'] = $getMySelectedContract->id;
                $data['contract']['unique_contract_id'] = $getMySelectedContract->unique_contract_id;
                $data['contract']['status'] = $getMySelectedContract->status;
                $data['contract']['contract_product'] = array();
                if($getMySelectedContract->contractProduct->count()){
                    foreach($getMySelectedContract->contractProduct as $key => $val){
                        $prod['contract_product_id']    = $val->id;
                        $prod['local_product_name']     = $val->contractProductLocal->count() ? $val->contractProductLocal[0]->local_product_name : '';
                        $prod['quantity']               = $val->quantity ? (string)$val->quantity : '';
                        $prod['pack_value']             = $val->pack_value ? (string)$val->pack_value : '';
                        $prod['local_unit_name']        = $val->contractProductLocal->count() ? $val->contractProductLocal[0]->local_unit_name : '';
                        if($val->frequency){
                            $prod['frequency'] = $val->frequency->frequencyLocals->count() ? $val->frequency->frequencyLocals[0]->local_name : '';
                        }else{
                            $prod['frequency'] = '';
                        }
                        
                        $data['contract']['contract_product'][] = $prod;
                        unset($prod);
                    }
                }
                $data['contract']['date_time_address']['start_date'] = $getMySelectedContract->start_date ? date('dS M, Y', $getMySelectedContract->start_date) : '';
                $data['contract']['date_time_address']['end_date'] = $getMySelectedContract->end_date ? date('dS M, Y', $getMySelectedContract->end_date) : '';
                $data['contract']['date_time_address']['full_name'] = $getMySelectedContract->full_name ? $getMySelectedContract->full_name : '';
                $data['contract']['date_time_address']['mobile'] = $getMySelectedContract->mobile ? $getMySelectedContract->mobile : '';
                $data['contract']['date_time_address']['landmark'] = $getMySelectedContract->landmark ? $getMySelectedContract->landmark : '';
                $data['contract']['date_time_address']['delivery_address'] = $getMySelectedContract->delivery_address ? $getMySelectedContract->delivery_address : '';
                
                $data['contract']['payment_note']['payment_mode'] = (string)$getMySelectedContract->payment_mode;
                $data['contract']['payment_note']['title'] = $getMySelectedContract->title ? $getMySelectedContract->title : '';
                $data['contract']['payment_note']['payment_note'] = $getMySelectedContract->payment_note ? $getMySelectedContract->payment_note : '';
                
                
                // $data['contract']['review_and_submit']['comment'] = $getMySelectedContract->comment ? $getMySelectedContract->comment : '';
                $data['contract']['review_and_submit']['terms'] = $getMySelectedContract->terms ? $getMySelectedContract->terms : '';
            }else{
                $data['contract'] = '';
            }
            return \Response::json(ApiHelper::generateResponseBody('CC-UCD-0002#contract_user_contract_details',  $data));
        }else{
            return \Response::json(ApiHelper::generateResponseBody('CC-UCD-0003#contract_user_contract_details',  trans('custom.something_went_wrong'), false, 400));
        }
    }

    /*****************************************************/
    # ContractController
    # Function name : userList (for contractors)
    # Author        :
    # Created Date  : 1-08-2019
    # Purpose       : To get ths listing of all user
    # Params        : Request $request
    /*****************************************************/
    public function userList(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('CC-UL-0001#contract_user_list',  trans('custom.not_authorized'), false, 400));
        }
        if($userData->is_contractor != 1){
            return \Response::json(ApiHelper::generateResponseBody('CC-UL-0002#contract_user_list',  trans('custom.not_authorized'), false, 400)); 
        }

        $offset = 0;
        $limit  = Helper::WHOLESALER_PRODUCT_LIST_LIMIT;
        $page   = isset($request->page)?$request->page:0;
        if ($page > 0) {
            $offset = ($limit * $page);
        }

        $userList = User::select('id','full_name','profile_image','role_id','is_customer','is_wholesaler','is_contractor','status','phone_no')
        ->where('is_customer','1')->where('status','1')
        ->whereNull('role_id')->whereNull('is_contractor')->whereNull('is_wholesaler')
        ->orderBy('created_at', 'DESC');
        $totalUsers = $userList->count();
        $userList = $userList->skip($offset)
        ->take($limit)->get();
        
        $baseUrl            = asset('');
        $userUrl        = 'uploads/users/thumbs/';
        $userImageUrl    = $baseUrl.$userUrl;
        if($userList->count()){
            $allUserList = [];
            foreach($userList as $key => $val) {
                $users['id']                            = $val->id;
                $users['name']                          = $val->full_name ? $val->full_name : '';
                
                $users['mobile_number']                 = $val->phone_no ? $val->phone_no : '';
                $users['profile_image']                 = $val->profile_image ? $userImageUrl.$val->profile_image : '';
                $users['all_contract_count']            = '';
                $users['all_contract_completed_count']  = '';
                
                $allUserList[] = $users;
                unset($users);
            }
            $listData['total_users']     = $totalUsers;
            $listData['user_list']       = $allUserList;
            
            return \Response::json(ApiHelper::generateResponseBody('CC-UL-0003#contract_user_list', $listData));
        }else{
            
            return \Response::json(ApiHelper::generateResponseBody('CC-UL-0004#contract_user_list', ['user_list' => [],'total_users'=>0]));
        }
    }

    /*****************************************************/
    # ContractController
    # Function name : newContracts
    # Author        :
    # Created Date  : 22-07-2019
    # Purpose       : To get the listing of new contract request
    # Params        : Request $request
    /*****************************************************/
    public function newContracts(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('CC-NC-0001#contract_new_contracts',  trans('custom.not_authorized'), false, 400));
        }
        $updateContractStatus = Helper::updateContractStatus();
        $offset = 0;
        $limit  = Helper::WHOLESALER_PRODUCT_LIST_LIMIT;
        $page   = isset($request->page)?$request->page:0;
        if ($page > 0) {
            $offset = ($limit * $page);
        }

        $getNewContracts = Contract::where([
            'contractor_id' => $userData->id,
            'status' => 'P',
        ])
        ->with([
            'contractProduct',
        ])
        ->select('id', 'title', 'unique_contract_id', 'contract_creator_id', 'contractor_id', 'start_date', 'end_date', 'full_name', 'mobile', 'status', 'created_at');
        $totalContracts = $getNewContracts->count();
        $getNewContracts = $getNewContracts->skip($offset)
        ->take($limit)->get();
        //dd($getNewContracts);
        if($getNewContracts->count()){
            $allGetNewContracts = [];
            foreach($getNewContracts as $key => $val) {
                $contracts['id']                        = $val->id;
                $contracts['title']                     = $val->title ? $val->title : '';
                // $contracts['contractor_name']           = $val->userContractorDetails ? $val->userContractorDetails->full_name : '';
                $contracts['contractor_creator_name']   = $val->contractCreatorDetails ? $val->contractCreatorDetails->full_name : '';
                $contracts['full_name']                 = $val->full_name ? $val->full_name : '';
                $contracts['created_date']              = $val->created_at ? Helper::formattedDate($val->created_at) : '';
                $contracts['start_date']                = $val->start_date ? date('dS M, Y', $val->start_date) : '';
                $contracts['end_date']                  = $val->end_date ? date('dS M, Y', $val->end_date) : '';
                $contracts['status']                    = $val->status;
                
                $allGetNewContracts[] = $contracts;
                unset($contracts);
            }
            $listData['total_contracts']     = $totalContracts;
            $listData['contract_list']       = $allGetNewContracts;
            
            return \Response::json(ApiHelper::generateResponseBody('CC-NC-0002#contract_new_contracts', $listData));
        }else{
            return \Response::json(ApiHelper::generateResponseBody('CC-NC-0003#contract_new_contracts', ['contract_list' => [],'total_contracts'=>0]));
        }
    }

    /*****************************************************/
    # ContractController
    # Function name : details
    # Author        :
    # Created Date  : 1-08-2019
    # Purpose       : To get the details of new contract request
    # Params        : Request $request
    /*****************************************************/
    public function details(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('CC-D-0001#contract_details',  trans('custom.not_authorized'), false, 400));
        }
        $updateContractStatus = Helper::updateContractStatus();
        $contractId = $request->contract_id;
        
        if ($contractId != null) {

            $contractDetails = Contract::where([
                    'id' => $contractId,
                    'contractor_id' => $userData->id,
                    //'status' => 'P',
                ])
                ->where('contract_creator_id', '<>', $userData->id)
                ->where('status', '<>', 'IC')   //Incomplete checking
                ->with([
                    'contractProduct',
                    'contractCreatorDetails',
                ])
                ->first();
            //dd($contractDetails);
            $baseUrl            = asset('');
            $userUrl        = 'uploads/users/thumbs/';
            $userImageUrl    = $baseUrl.$userUrl;
            
            if($contractDetails){
                $data['contractor_name'] = $contractDetails->userContractorDetails ? $contractDetails->userContractorDetails->full_name : '';
                $data['contractor_mobile_number'] = $contractDetails->userContractorDetails ? $contractDetails->userContractorDetails->mobile_number : '';
                if($contractDetails->contractor){
                    $data['contractor_profile_image'] = $contractDetails->contractor->profile_image ? $userImageUrl.$contractDetails->contractor->profile_image : '';
                }else{
                    $data['contractor_profile_image'] = '';
                }
                $data['user_name'] = $contractDetails->contractCreatorDetails ? $contractDetails->contractCreatorDetails->full_name : '';
                
                if($contractDetails->contractCreatorDetails){
                    $data['user_mobile_number'] = $contractDetails->contractCreatorDetails->phone_no ? $contractDetails->contractCreatorDetails->phone_no : '';
                    $data['user_profile_image'] = $contractDetails->contractCreatorDetails->profile_image ? $userImageUrl.$contractDetails->contractCreatorDetails->profile_image : '';
                }else{
                    $data['user_profile_image'] = '';
                    $data['user_mobile_number'] = '';
                }
                $data['contract'] = array();
                $data['contract']['id']                 = $contractDetails->id;
                $data['contract']['unique_contract_id'] = $contractDetails->unique_contract_id;
                $data['contract']['status']             = $contractDetails->status;
                $data['contract']['contract_product']   = array();
                if($contractDetails->contractProduct->count()){
                    foreach($contractDetails->contractProduct as $key => $val){
                        $prod['contract_product_id']    = $val->id;
                        $prod['local_product_name']     = $val->contractProductLocal->count() ? $val->contractProductLocal[0]->local_product_name : '';
                        $prod['quantity']               = $val->quantity ? (string)$val->quantity : '';
                        $prod['pack_value']             = $val->pack_value ? (string)$val->pack_value : '';
                        $prod['local_unit_name']        = $val->contractProductLocal->count() ? $val->contractProductLocal[0]->local_unit_name : '';
                        if($val->frequency){
                            $prod['frequency'] = $val->frequency->frequencyLocals->count() ? $val->frequency->frequencyLocals[0]->local_name : '';
                        }else{
                            $prod['frequency'] = '';
                        }
                        
                        $data['contract']['contract_product'][] = $prod;
                        unset($prod);
                    }
                }
                $data['contract']['date_time_address']['start_date']       = $contractDetails->start_date ? date('m/d/Y', $contractDetails->start_date) : '';
                $data['contract']['date_time_address']['end_date']          = $contractDetails->end_date ? date('m/d/Y', $contractDetails->end_date) : '';
                $data['contract']['date_time_address']['full_name']         = $contractDetails->full_name ? $contractDetails->full_name : '';
                $data['contract']['date_time_address']['mobile']            = $contractDetails->mobile ? $contractDetails->mobile : '';
                $data['contract']['date_time_address']['landmark']          = $contractDetails->landmark ? $contractDetails->landmark : '';
                $data['contract']['date_time_address']['delivery_address']  = $contractDetails->delivery_address ? $contractDetails->delivery_address : '';
                
    
                $data['contract']['payment_note']['payment_mode']   = (string)$contractDetails->payment_mode;
                $data['contract']['payment_note']['title']          = $contractDetails->title ? $contractDetails->title : '';
                $data['contract']['payment_note']['payment_note']   = $contractDetails->payment_note ? $contractDetails->payment_note : '';
                
                
                // $data['contract']['review_and_submit']['comment'] = $contractDetails->comment ? $contractDetails->comment : '';
                $data['contract']['review_and_submit']['terms'] = $contractDetails->terms ? $contractDetails->terms : '';
            }else{
                $data['contract'] = '';
            }
            return \Response::json(ApiHelper::generateResponseBody('CC-D-0002#contract_details',  $data));
            
        } else {
            return \Response::json(ApiHelper::generateResponseBody('CC-D-0003#contract_details',  trans('custom.something_went_wrong'), false, 400));
        }
    }

    /*****************************************************/
    # ContractController
    # Function name : receivedContracts
    # Author        :
    # Created Date  : 1-08-2019
    # Purpose       : To get the listing of all approved contractor
    # Params        : Request $request
    /*****************************************************/
    public function receivedContracts(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('CC-RC-0001#contract_received_contracts',  trans('custom.not_authorized'), false, 400));
        }
        $updateContractStatus = Helper::updateContractStatus();
        $offset = 0;
        $limit  = Helper::WHOLESALER_PRODUCT_LIST_LIMIT;
        $page   = isset($request->page)?$request->page:0;
        if ($page > 0) {
            $offset = ($limit * $page);
        }

        $getReceivedContracts = Contract::where('contractor_id', $userData->id)
            ->whereIn('status', ['A','R','O','C','E'])
            ->with([
                'contractProduct',
            ]);
        $totalContracts = $getReceivedContracts->count();
        $getReceivedContracts = $getReceivedContracts->skip($offset)
        ->take($limit)->get();
            //dd($getReceivedContracts);
        if($getReceivedContracts->count()){
            $allGetReceivedContracts = [];
            foreach($getReceivedContracts as $key => $val) {
                $contracts['id']                        = $val->id;
                $contracts['title']                     = $val->title ? $val->title : '';
                $contracts['contractor_creator_name']   = $val->contractCreatorDetails ? $val->contractCreatorDetails->full_name : '';
                $contracts['full_name']                 = $val->full_name ? $val->full_name : '';
                $contracts['created_date']              = $val->created_at ? Helper::formattedDate($val->created_at) : '';
                $contracts['start_date']                = $val->start_date ? date('dS M, Y', $val->start_date) : '';
                $contracts['end_date']                  = $val->end_date ? date('dS M, Y', $val->end_date) : '';
                $contracts['status']                    = $val->status;
                
                $allGetReceivedContracts[] = $contracts;
                unset($contracts);
            }
            $listData['total_contracts']     = $totalContracts;
            $listData['contract_list']       = $allGetReceivedContracts;
            
            return \Response::json(ApiHelper::generateResponseBody('CC-RC-0002#contract_received_contracts', $listData));
        }else{
            return \Response::json(ApiHelper::generateResponseBody('CC-RC-0003#contract_received_contracts', ['contract_list' => [],'total_contracts'=>0]));
        }
    }

    /*****************************************************/
    # ContractController
    # Function name : contractAction
    # Author        :
    # Created Date  : 1-08-2019
    # Purpose       : To accept or reject contract
    # Params        : Request $request
    /*****************************************************/
    public function contractAction(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('CC-CA-0001#contract_action',  trans('custom.not_authorized'), false, 400));
        }
        if($userData->is_contractor != 1){
            return \Response::json(ApiHelper::generateResponseBody('CC-CA-0002#contract_action',  trans('custom.not_authorized'), false, 400)); 
        }

        $contractId = $request->contract_id;
        $status = $request->status;
        if($contractId && $status){
            $contract = Contract::where('id', $contractId)->where('contractor_id',$userData->id)->first();
            if($contract){
                if($status == 'A' || $status == 'R' ){
                    $contract->status = $status;
                    if($contract->save()){
                        NotificationHelper::sendNotificationContractAction($contract);
                        return \Response::json(ApiHelper::generateResponseBody('CC-CA-0006#contract_action',  trans('custom.successful')));        
                    }else{
                        return \Response::json(ApiHelper::generateResponseBody('CC-CA-0005#contract_action',  trans('custom.something_went_wrong'), false, 400));            
                    }
                }else{
                    return \Response::json(ApiHelper::generateResponseBody('CC-CA-0007#contract_action',  trans('custom.something_went_wrong'), false, 400));       
                }
            }else{
                return \Response::json(ApiHelper::generateResponseBody('CC-CA-0003#contract_action',  trans('custom.something_went_wrong'), false, 400));    
            }
        }else{
            return \Response::json(ApiHelper::generateResponseBody('CC-CA-0004#contract_action',  trans('custom.something_went_wrong'), false, 400));
        }
    }

    /*****************************************************/
    # ContractController
    # Function name : userRemoveContract
    # Author        :
    # Created Date  : 23-08-2019
    # Purpose       : user delete the pending contract
    # Params        : Request $request
    /*****************************************************/
    public function userRemoveContract(Request $request){

        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        
        $userData  = ApiHelper::getUserFromHeader($request);
  
        if($userData !== null){
            $request->contract_id = $request->contract_id;
            
            $getSelectedContract = Contract::where('id', $request->contract_id)->first();
            $getallProductids =    ContractProduct::where('contract_id', $request->contract_id)->pluck('id')->toArray();
            $getallProductLocalids =    ContractProductLocal::where('contract_id', $request->contract_id)->pluck('id')->toArray(); 
        
            NotificationHelper::sendNotificationContractDelete($getSelectedContract);

            if (is_array($getallProductLocalids)) {
                ContractProductLocal::destroy($getallProductLocalids);
            } else  {
                ContractProductLocal::findOrFail($getallProductLocalids)->delete();
            } 
            
            if (is_array($getallProductids)) {
                ContractProduct::destroy($getallProductids);
            } else  {
                ContractProduct::findOrFail($getallProductids)->delete();
            }
            
            $removeSucess = $getSelectedContract->delete();

            if($removeSucess) {
                return \Response::json(ApiHelper::generateResponseBody('CC-URC-0001#contract_user_remove_contract',  trans('custom.my_contract_details_remove')));
            } else {
                return \Response::json(ApiHelper::generateResponseBody('CC-URC-0002#contract_user_remove_contract',  trans('custom.my_contract_details_notremove'), false, 400));
            }
        } else {
            return \Response::json(ApiHelper::generateResponseBody('CC-URC-0003#contract_user_remove_contract',  trans('custom.please_provide_credential'), false, 400));
        }
    }


    /*****************************************************/
    # ContractController
    # Function name : viewShipmentDetails
    # Author        :
    # Created Date  : 27-08-2019
    # Purpose       : To get the details of shipment note
    # Params        : Request $request
    /*****************************************************/
    public function viewShipmentDetails(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        
        $userData  = ApiHelper::getUserFromHeader($request);
        $shipmentDetails = [];
        
        $shipmentDetails = ContractShipmentNote::where('contract_id', $request->contract_id)->with('contractShipmentProduct')->get();
        
        return \Response::json(ApiHelper::generateResponseBody('CC-VSD-0001#contract_view_shipment_details',   $shipmentDetails));
    }

    /*****************************************************/
    # ContractController
    # Function name : addShipmentNote
    # Author        :
    # Created Date  : 27-08-2019
    # Purpose       : To add shipment note
    # Params        : Request $request
    /*****************************************************/
    public function addShipmentNote(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        
        $userData  = ApiHelper::getUserFromHeader($request);

        $validator = Validator::make($request->all(),
                            [
                                'contract_id'           => 'required',
                                'delivery_date'         => 'required',
                                'note'                  => 'required',
                                'quantity'              => 'required',
                            ],
                            [
                                'contract_id.required'      => trans('custom.contract_id_required'),
                                'delivery_date.required'    => trans('custom.date_required'),
                                'note.required'             => trans('custom.comment_required'),
                                'quantity.required'         => trans('custom.quantity_required'),
                            ]
                        );
        $errors = $validator->errors()->all();
        if ($errors) {
            return \Response::json(ApiHelper::generateResponseBody('CC-ASN-0001#contract_add_shipment_details', ["errors" => $errors], false, 100));
        } else {
            $contractId = $request->contract_id;
            $contractShipmentNote = new ContractShipmentNote;
            $contractShipmentNote->contract_id = $contractId;
            $contractShipmentNote->delivery_date = isset($request->delivery_date) ? $request->delivery_date : null;
            $contractShipmentNote->note = isset($request->note) ? $request->note : null;

            if ($contractShipmentNote->save()) {
                NotificationHelper::sendNotificationContractShipmentNote($contractShipmentNote);
                foreach ($request->quantity as $key => $val) {
                    $ProductData = ContractProduct::where('id',$key)->first();

                    $contractShipmentProduct = new ContractShipmentProduct;
                    $contractShipmentProduct->contract_id = $contractId;
                    $contractShipmentProduct->shipment_note_id = $contractShipmentNote->id;
                    $contractShipmentProduct->contract_product_id = $key;

                    foreach ($ProductData->contractProductLocalData as $details) {
                        if ($details->lang_code == 'EN') {
                            $contractShipmentProduct->product_name_en = $details['local_product_name'];
                        } else {
                            $contractShipmentProduct->product_name_ar = $details['local_product_name'];
                        }
                    }
                    $contractShipmentProduct->quantity = $val;
                    $contractShipmentProduct->save();
                }                                
                return \Response::json(ApiHelper::generateResponseBody('CC-ASN-0002#contract_add_shipment_details',   trans('custom.shipment_success')));
             
            } else {
                return \Response::json(ApiHelper::generateResponseBody('CC-ASN-0003#contract_add_shipment_details',  trans('custom.something_went_wrong'), false, 400));
            }
        }
    }

    /*****************************************************/
    # ContractController
    # Function name : shipmentStatusUpdate
    # Author        :
    # Created Date  : 27-08-2019
    # Purpose       : Update shipment status
    # Params        : Request $request
    /*****************************************************/
    public function shipmentStatusUpdate(Request $request)
    {
        if ($request->isMethod('POST')) {
            $contractShipmentId = isset($request->contract_shipment_id) ? $request->contract_shipment_id : 0;
            $contractId = isset($request->contract_id) ? $request->contract_id : 0;
            $receivingStatus = isset($request->status) ? $request->status : 'P';

            if ($contractShipmentId > 0 && $contractId > 0) {
                $getShipmentDetail = ContractShipmentNote::where(['id' => $contractShipmentId, 'contract_id' => $contractId])->first();
                if ($getShipmentDetail != null) {
                    $getShipmentDetail->receiving_status = $receivingStatus;
                    $getShipmentDetail->save();
                    NotificationHelper::sendNotificationContractShipmentNoteAccepted($getShipmentDetail);
                    if ($receivingStatus == 'D') {
                        return \Response::json(ApiHelper::generateResponseBody('CC-SSU-0001#contract_shipment_status_update',   trans('custom.shipment_success')));
                    } else {
                        return \Response::json(ApiHelper::generateResponseBody('CC-SSU-0002#contract_shipment_status_update',   trans('custom.confirmed_successfully')));
                    }
                } else {
                    return \Response::json(ApiHelper::generateResponseBody('CC-SSU-0003#contract_shipment_status_update',  trans('custom.something_went_wrong'), false, 400));
                }
            } else {
                return \Response::json(ApiHelper::generateResponseBody('CC-SSU-0004#contract_shipment_status_update',  trans('custom.something_went_wrong'), false, 400));
            }
        }
    }
}
