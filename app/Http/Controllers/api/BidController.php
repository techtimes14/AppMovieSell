<?php
/*****************************************************/
# BidController
# Page/Class name : BidController
# Author :
# Created Date : 23-05-2019
# Functionality : addBid, editBid, deleteBid, submitBid, listBid, detailsBid
# Purpose : bid related all functions
/*****************************************************/
namespace App\Http\Controllers\api;

use App;
use Image;
use \App\Bid;
use \App\BidLocal;
use \App\BidImages;
use \App\BidDetails;
use App\Http\Helpers\AdminHelper;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use App\Http\Helpers\Helper;
use App\Http\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use \Validator;

class BidController extends Controller
{
    /*****************************************************/
    # BidController
    # Function name : addBid
    # Author :
    # Created Date : 23-05-2019
    # Purpose :  to add new bid
    # Params :
    /*****************************************************/
    public function addBid(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);

        $userData  = ApiHelper::getUserFromHeader($request);
        if($userData->is_wholesaler == 0){
            return \Response::json(ApiHelper::generateResponseBody('BC-AB-0006#bids_create', trans('custom.not_authorized'), false, 100));
        }
        $validator = Validator::make($request->all(),
                                [
                                    'category_id'               => 'required',
                                    'product_id'                => 'required',
                                    'unit_id'                   => 'required',
                                    'pack_value'                => 'required|regex:/^[0-9]+$/',
                                    'quantity'                  => 'required|regex:/^[0-9]+$/',
                                    'bid_title_en'              => 'required',
                                    'bid_title_ar'              => 'required',
                                    'bid_rules_en'              => 'required',
                                    'bid_rules_ar'              => 'required',
                                    'minimum_amount'            => 'required|regex:/^[0-9]+$/',
                                    'start_date'                => 'required',
                                    'end_date'                  => 'required',
                                    'images'                    => 'required',
                                    'images.*'                  => 'mimes:jpeg,jpg,png,svg|max:'.Helper::MULTIPLE_PRODUCT_IMAGE_MAX_UPLOAD_SIZE,
                                    'status'                    => 'required'
                                ],
                                [
                                    'category_id.required'      => trans('custom.category_required'),
                                    'product_id.required'       => trans('custom.product_required'),
                                    'unit_id.required'          => trans('custom.unit_required'),
                                    'pack_value.required'       => trans('custom.pack_value_required'),
                                    'pack_value.regex'          => trans('custom.pack_value_regex'),
                                    'quantity.required'         => trans('custom.pack_value_required'),
                                    'quantity.regex'            => trans('custom.pack_value_regex'),
                                    'bid_title_en.required'     => trans('custom.bid_title_english_required'),
                                    'bid_title_ar.required'     => trans('custom.bid_title_arabic_required'),    
                                    'bid_rules_en.required'     => trans('custom.bid_rules_english_required'),
                                    'bid_rules_ar.required'     => trans('custom.bid_rules_arabic_required'),
                                    'minimum_amount.required'   => trans('custom.min_amount_required'),
                                    'minimum_amount.regex'      => trans('custom.mrp_regex'),
                                    'start_date.required'       => trans('custom.start_date_required'),
                                    'end_date.required'         => trans('custom.end_date_required'),
                                    'images.required'           => trans('custom.image_required'),
                                    'status.required'           => trans('custom.status_required'),
                                ]
                            );
        $errors = $validator->errors()->all();
        if ($errors) {
            return \Response::json(ApiHelper::generateResponseBody('BC-AB-0002#bids_create', ["errors" => $errors], false, 100));
        } else {
            
            // set all request data         
            $categoryId         = isset($request->category_id)?$request->category_id:0;
            $sub_category_id    = isset($request->sub_category_id)?$request->sub_category_id:0;
            $productId          = $request->product_id;
            $unitId             = isset($request->unit_id)?$request->unit_id:0;
            $packValue          = isset($request->pack_value)?$request->pack_value:0;
            $quantity           = isset($request->quantity)?$request->quantity:0;
            // $name               = $request->name;
            $minimum_amount     = $request->minimum_amount;
            $start_date         = $request->start_date;
            $end_date           = $request->end_date;      
            $images             = $request->images;
            $status             = $request->status;
            
           // if product id has been selected 
            if ($productId) {
               
                // if minimum bid amount and start date and end date has been selected
                if ($minimum_amount !== NULL  && $start_date !== NULL && $end_date !== NULL) {

                                        
                    // create new bid for Bid table
                    $newWholesalerBid = new Bid;
                                       
                    $newWholesalerBid->product_id       = $productId;    
                    $newWholesalerBid->category_id      = $categoryId;  
                    $newWholesalerBid->sub_category_id  = $sub_category_id;                    
                    $newWholesalerBid->vendor_id        = $userData->id;
                    $newWholesalerBid->minimum_amount   = $minimum_amount;
                    $newWholesalerBid->quantity         = $packValue;
                    $newWholesalerBid->start_time       = Helper::formattedTimestamp($start_date);  
                    $newWholesalerBid->end_time         = Helper::formattedTimestamp($end_date);  
                    $newWholesalerBid->status           = $status;
                    
                    $bidSaved = $newWholesalerBid->save();
                    
                    // save bid title and bid rules in BidLocal table
                    if ($bidSaved) {
                       
                            
                        $newBidLocal = new BidLocal;
                    
                            
                        $newBidLocal->bid_id     = $newWholesalerBid->id;
                        $newBidLocal->lang_code  = 'EN';
                        $newBidLocal->bid_title  = $request->bid_title_en;
                        $newBidLocal->bid_rules  = $request->bid_rules_en;
                        $newBidLocal->save();                                     
                    
                        $newBidLocal = new BidLocal;  
                        $newBidLocal->bid_id     = $newWholesalerBid->id;
                        $newBidLocal->lang_code  = 'AR';
                        $newBidLocal->bid_title  = $request->bid_title_ar;
                        $newBidLocal->bid_rules  = $request->bid_rules_ar;
                        
                        $newBidLocal->save();
                            
                        
                        
                        // save bid images into bid folder and bid thumbs folder
                        if ($images) {
                            $images = $request->file('images');
                            $i = 1;
                            foreach ($images as $img) {

                            	$rawFilename = $img->getClientOriginalName();
                                
                                $extension = pathinfo($rawFilename, PATHINFO_EXTENSION);

                                $filename = $newWholesalerBid->id.'_'.strtotime(date('Y-m-d H:i:s')).$i.'.'.$extension;

                                if(in_array($extension,Helper::UPLOADED_IMAGE_FILE_TYPES)) {
                                    
                                    $image_resize = Image::make($img->getRealPath());
                                    
                                    $image_resize->save(public_path('uploads/bids/'.$filename));
            
                                    $image_resize->resize(AdminHelper::ADMIN_PRODUCT_THUMB_IMAGE_WIDTH, 
                                    
                                    AdminHelper::ADMIN_PRODUCT_THUMB_IMAGE_HEIGHT, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                    
                                    $image_resize->save(public_path('uploads/bids/thumbs/'.$filename));
            
                                    $newBidImages               = new BidImages();
                                    $newBidImages->product_id   = $productId;
                                    $newBidImages->bid_id       = $newWholesalerBid->id;
                                    $newBidImages->image_name   = $filename;
                                    
                                    if ($i == 1) {
                                        $newBidImages->default_image = '1';
                                    }
                                    $newBidImages->save();
                                    $i++;
                                }
                            }
                        }
                        return \Response::json(ApiHelper::generateResponseBody('BC-AB-0001#bids_create', trans('custom.bid_created_successfully')));
                    } else {
                        return \Response::json(ApiHelper::generateResponseBody('BC-AB-0003#bids_create', trans('custom.something_went_wrong'), false, 100));
                    }
                } else {
                    return \Response::json(ApiHelper::generateResponseBody('BC-AB-0004#bids_create', trans('custom.product_selling_price_is_not_provided'), false, 100));
                }
            } else {
                return \Response::json(ApiHelper::generateResponseBody('BC-AB-0005#bids_create', trans('custom.product_id_is_not_provided'), false, 100));
            }
        }
    }

    /*****************************************************/
    # BidController
    # Function name : editBid
    # Author :
    # Created Date : 23-05-2019
    # Purpose :  to edit a bid
    # Params :
    /*****************************************************/
    public function editBid(Request $request)
    {
        // $validator = Validator::make($request->all(),
        //     [
        //         'bid_id' => 'required',
        //         'product_id' => 'required',
        //         'minimum_amount' => 'required',
        //         'start_time' => 'required',
        //         'end_time' => 'required',
        //     ]
        // );
        // $errors = $validator->errors()->all();
        // if ($errors) {
        //     return \Response::json(ApiHelper::generateResponseBody('BC-EB-0002#bids_edit', ["errors" => $errors], false, 200));
        // } else {
        //     $bidDetails = Bid::find($request->bid_id);
        //     if ($bidDetails->details()->count() == 0) {
        //         $bidDetails->product_id = $request->product_id;
        //         $bidDetails->minimum_amount = $request->minimum_amount;
        //         $bidDetails->start_time = $request->start_time;
        //         $bidDetails->end_time = $request->end_time;
        //         $bidDetails->save();
        //         return \Response::json(ApiHelper::generateResponseBody('BC-EB-0001#bids_edit', 'Bid is updated successfully'));
        //     } else {
        //         return \Response::json(ApiHelper::generateResponseBody('BC-EB-0003#bids_edit', 'Bid already starts, can not be updated', false, 300));
        //     }
        // }
        echo "suspended for now";

    }

    /*****************************************************/
    # BidController
    # Function name : deleteBid
    # Author :
    # Created Date : 23-05-2019
    # Purpose :  to delete a bid
    # Params :
    /*****************************************************/
    public function deleteBid(Request $request)
    {
        // $validator = Validator::make($request->all(),
        //     [
        //         'bid_id' => 'required',
        //     ]
        // );
        // $errors = $validator->errors()->all();
        // if ($errors) {
        //     return \Response::json(ApiHelper::generateResponseBody('BC-DB-0002#bids_delete', ["errors" => $errors], false, 200));
        // } else {
        //     $bidDetails = Bid::find($request->bid_id);
        //     if ($bidDetails->details()->count() == 0) {
        //         $bidDetails->delete();
        //         return \Response::json(ApiHelper::generateResponseBody('BC-DB-0001#bids_delete', 'Bid is deleted successfully'));
        //     } else {
        //         return \Response::json(ApiHelper::generateResponseBody('BC-DB-0003#bids_delete', 'Bid already starts, can not be deleted', false, 300));
        //     }
        // }
        echo "suspended for now";
    }

    /*****************************************************/
    # BidController
    # Function name : submitBid
    # Author :
    # Created Date : 23-05-2019
    # Purpose :  to submit bid by customer
    # Params :
    /*****************************************************/
    public function submitBid(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);

        $userData  = ApiHelper::getUserFromHeader($request);
        $validator = Validator::make($request->all(),
            [
                'bid_id' => 'required',
                'bid_amount' => 'required',
            ],
            [
                'bid_id.required'      => trans('custom.wholesaler_no_bids_found'),
                'bid_amount.required'       => trans('custom.wholesaler_enter_bid'),
            ]
        );
        $errors = $validator->errors()->all();
        if ($errors) {
            return \Response::json(ApiHelper::generateResponseBody('BC-SB-0002#bids_submit', ["errors" => $errors], false, 200));
        } else {
            $bidData = Bid::find($request->bid_id);
            $latestBidMaxAmount = $bidData->minimum_amount;
            if ($bidData->details()->count() > 0) {
                $latestBidMaxAmount = $bidData->maxBidDetails[0]->bid_amount;
            }
            if ($request->bid_amount > $latestBidMaxAmount) {
                
                if ($userData->id != $bidData->vendor_id) {
                    $newBidDetails = BidDetails::where([
                        'bid_id' => $request->bid_id,
                        'bid_by' => $userData->id
                            ])->first();
                    $newBid = false;
                    if(!$newBidDetails){
                        $newBid = true;
                        $newBidDetails = new BidDetails();
                    }

                    $newBidDetails->bid_id = $request->bid_id;
                    $newBidDetails->bid_by = $userData->id;
                    $newBidDetails->bid_amount = $request->bid_amount;
                    $newBidDetails->bid_post_time = now()->timestamp;
                    $newBidDetails->save();
                    NotificationHelper::sendNotificationBid($newBidDetails);
                    if($newBid){
                        $msg = trans('custom.bid_created_successfully');
                    }else{
                        $msg = trans('custom.bid_updated_successfully');
                    }
                    return \Response::json(ApiHelper::generateResponseBody('BC-SB-0001#bids_submit', $msg));
                } else {
                    return \Response::json(ApiHelper::generateResponseBody('BC-SB-0003#bids_submit', trans('custom.something_went_wrong'), false, 300));
                }
            } else {
                return \Response::json(ApiHelper::generateResponseBody('BC-SB-0004#bids_submit', trans('custom.wholesaler_bid_less_than_highest_bid'), false, 400));
            }
        }

    }
    /*****************************************************/
    # BidController
    # Function name : listBid
    # Author :
    # Created Date : 23-05-2019
    # Purpose :  to get all list of bids of a vendor
    # Params : Request $request
    /*****************************************************/
    public function listBid(Request $request)
    {
        
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);

        $userData  = ApiHelper::getUserFromHeader($request);
        
        $validator = Validator::make($request->all(),
            [
                // 'user_id' => 'required',
                'type' => 'required',
            ],
            [
                'type.required'      => trans('custom.no_bid_type_found'),
            ]
        );
        $errors = $validator->errors()->all();
        if ($errors) {
            return \Response::json(ApiHelper::generateResponseBody('BC-LB-0001#bids_list', ["errors" => $errors], false, 200));
        } else {
            $offset = 0;
            $limit  = Helper::WHOLESALER_PRODUCT_LIST_LIMIT;
            $page   = isset($request->page)?$request->page:0;
            if ($page > 0) {
                $offset = ($limit * $page);
            }
            
            $userId = $request->user_id ? $request->user_id : 0;
            $type = $request->type;
            $data['order_by']        = 'created_at';
            $data['order']           = 'desc';
            if($type == "created_bids" || $type == "open_bids" ){
                $bidList = Bid::
                with([
                    'bidLocal' => function ($q) use ($lang) {
                        $q->select('id','bid_id','bid_title');
                        $q->where('lang_code', '=', $lang);
                    },
                    'vendor' => function ($q) {
                        $q->select('id','full_name');
                    } ,
                    'productVendorLocal'=> function($q) use ($lang) {
                        $q->where('lang_code','=', $lang);
                    },
                    'pictures' => function($q)  {
                        $q->select('id','product_id','bid_id','image_name');
                        $q->where('default_image','=', '1');
                    },
                    'getCategory' => function($q) use ($lang) {
                        $q->where('lang_code','=', $lang);
                    },
                    'bidCountRelation',
                    'myBidDetails'=> function($q) use ($userId)  {
                        $q->where('bid_by','=', $userId);
                        $q->select('id','bid_id','bid_by','bid_amount');
                    },
                    'myBidDetails.user' => function ($q) {
                        $q->whereNull('deleted_at');
                        $q->where('status', '=', '1');
                        $q->select('id','full_name');
                    },
                    'maxBid'=> function($q) use ($userId)  {
                        $q->select('id','bid_id','bid_by','bid_amount');
                    },
                    'maxBid.user' => function ($q) {
                        $q->whereNull('deleted_at');
                        $q->where('status', '=', '1');
                        $q->select('id','full_name');
                    },
                    'unit'=> function($q)  {
                        $q->select('id','title');
                    },
                    'unit.local'=> function($q) use ($lang)  {
                        $q->select('id','unit_id','local_title');
                        $q->where('lang_code', '=', $lang);
                    },
                ])
                ->orderBy($data['order_by'], $data['order']);
                if($userId && $type == "created_bids"){
                    $bidList = $bidList->where('vendor_id', $userId);    
                }else if($type == "open_bids"){
                    $bidList = $bidList->where('status', '=', '1')
                                    ->where('end_time', '>=', now()->timestamp)
                                        ->whereNull('winner_id')    
                                        ->whereNull('deleted_at'); 
                }
                
                $bidList = $bidList->whereNull('deleted_at')
                ->select('id', 'vendor_id', 'product_id', 'minimum_amount', 'start_time', 'end_time','quantity','winner_id','pack','unit_id','category_id');
                $totalBids = $bidList->count();
                $bidList = $bidList->skip($offset)
                ->take($limit)->get();
                  
                $baseUrl            = asset('');
                $productsUrl        = 'uploads/bids/thumbs/';
                $productImageUrl    = $baseUrl.$productsUrl;
                if($bidList->count()){
                    $allBidList = [];
                    foreach($bidList as $key => $val) {
                        $bids['id']           = $val->id;
                        
                        if ($val->productVendorLocal != null && $val->productVendorLocal->count()) {
                            if($val->productVendorLocal->count()){
                                $bids['product_title']           = $val->productVendorLocal[0]->local_name;
                            }else{
                                $bids['product_title']           = '';
                            }
                        } else {
                            $bids['product_title']           = '';
                        }

                        if ($val->unit != null && $val->unit->local) {
                            if($val->unit->local->count()){
                                $bids['unit']           = $val->unit->local[0]->local_title;
                            }else{
                                $bids['unit']           = '';
                            }
                        } else {
                            $bids['unit']           = '';
                        }

                        if ( $val->pictures->count()) {
                            $bids['product_img']           = $productImageUrl.$val->pictures[0]->image_name;
                        } else {
                            $bids['product_img']           = '';
                        }

                        if ( $val->getCategory->count()) {
                            $bids['category_name']           = $val->getCategory[0]->local_name;
                        } else {
                            $bids['category_name']           = '';
                        }

                        if ( $val->bidLocal->count()) {
                            $bids['bid_title']           = $val->bidLocal[0]->bid_title;
                        } else {
                            $bids['bid_title']           = '';
                        }

                        if ( $val->vendor != null) {
                            $bids['wholesaler_name']         = $val->vendor->full_name;
                            $bids['wholesaler_id']           = $val->vendor->id;
                        } else {
                            $bids['wholesaler_name']         = '';
                            $bids['wholesaler_id']           = '';
                        }
                        $bids['pack'] = $val->pack ? (string)$val->pack : '';
                        $bids['quantity'] = $val->quantity ? (string)$val->quantity : '';
                        $bids['end_time'] = $val->end_time ? Helper::formattedDatefromTimestamp($val->end_time) : '';
                        $bids['min_bid'] = $val->minimum_amount ? (string)$val->minimum_amount : '';
                        if ( $val->bidCountRelation->count()) {
                            $bids['total_bid']           = (string)$val->bidCountRelation[0]->count;
                        } else {
                            $bids['total_bid']           = '0';
                        }

                        if($val->winner_id != NULL){
                            $status =  'awarded';
                        }else if($val->winner_id == null && $val->end_time >= now()->timestamp){
                            $status = 'active';
                        }else{
                            $status = 'expired';
                        }  

                        if ( $val->myBidDetails->count()) {
                            $bids['my_max_bid']           = (string)$val->myBidDetails[0]->bid_amount;
                        } else {
                            $bids['my_max_bid']           = '';
                        }

                        if ( $val->maxBid != null && $val->maxBid->user) {
                            $bids['max_bid']           = (string)$val->maxBid->bid_amount;
                            if($val->maxBid->user != null){
                                $bids['max_bid_user_id']    = (string)$val->maxBid->user->id;
                            }else{
                                $bids['max_bid_user_id']    = '';
                            }
                        } else {
                            $bids['max_bid']           		= '';
                            $bids['max_bid_user_id']        = '';
                        }

                        $bids['bid_placed_status'] = $status;
                        $allBidList[] = $bids;
                        unset($bids);
                    }
                    $listData['total_list']     = $totalBids;
                    $listData['bid_list']       = $allBidList;
                    
                    return \Response::json(ApiHelper::generateResponseBody('BC-LB-0002#bids_list', $listData));
                }else{
                    $totalBids = 0;
                    return \Response::json(ApiHelper::generateResponseBody('BC-LB-0003#bids_list', ['bid_list' => [],'total_list'=>$totalBids]));
                }
            }else{
                return \Response::json(ApiHelper::generateResponseBody('BC-LB-0004#bids_list',  trans('custom.no_bid_type_found'), false, 400));
            }
        }
    }

    /*****************************************************/
    # BidController
    # Function name : myPlacedBidList
    # Author :
    # Created Date : 23-05-2019
    # Purpose :  to get all list of bids placed
    # Params :
    /*****************************************************/
    public function myPlacedBidList(Request $request)
    {
        
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);

        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('BC-LB-0003#bids_myPlacedList',  trans('custom.not_authorized'), false, 400));
        }
        $offset = 0;
        $limit  = Helper::WHOLESALER_PRODUCT_LIST_LIMIT;
        $page   = isset($request->page)?$request->page:0;
        if ($page > 0) {
            $offset = ($limit * $page);
        }
        
        $data['order_by']        = 'created_at';
        $data['order']           = 'desc';
        $userId = $userData->id;

        $bidList = BidDetails::where('bid_by', $userData->id)
        ->whereNull('deleted_at')->with([
            'bid' => function ($q) use ($data,$lang,$userId) {
                $q->orderBy($data['order_by'], $data['order']);
                $q->whereNull('deleted_at');
                $q->with([ 
                    'bidLocal'=> function($query) use ($lang) {
                        $query->where('lang_code','=', $lang);
                    },
                    'vendor' => function ($q) {
                        $q->select('id','full_name');
                    } ,
                    'productVendorLocal'=> function($q) use ($lang) {
                        $q->where('lang_code','=', $lang);
                    },
                    'getCategory' => function($query) use ($lang) {
                        $query->where('lang_code','=', $lang);
                    },
                    'getSubCategory' => function($query) use ($lang) {
                        $query->where('lang_code','=', $lang);
                    },
                    'pictures' => function($query) use ($lang) {
                        $query->where('default_image','=', '1');
                    },
                    'bidCountRelation',
                    'myBidDetails'=> function($q) use ($userId)  {
                        $q->where('bid_by','=', $userId);
                        $q->select('id','bid_id','bid_by','bid_amount');
                    },
                    'myBidDetails.user' => function ($q) {
                        $q->whereNull('deleted_at');
                        $q->where('status', '=', '1');
                        $q->select('id','full_name');
                    },
                    'maxBid'=> function($q) use ($userId)  {
                        $q->select('id','bid_id','bid_by','bid_amount');
                    },
                    'maxBid.user' => function ($q) {
                        $q->whereNull('deleted_at');
                        $q->where('status', '=', '1');
                        $q->select('id','full_name');
                    },
                    'unit'=> function($q)  {
                        $q->select('id','title');
                    },
                    'unit.local'=> function($q) use ($lang)  {
                        $q->select('id','unit_id','local_title');
                        $q->where('lang_code', '=', $lang);
                    },

                ]); 
            }
        ]);
        
        $totalBids = $bidList->count();
        $bidList = $bidList->skip($offset)
        ->take($limit)->get();
                
        $baseUrl            = asset('');
        $productsUrl        = 'uploads/bids/thumbs/';
        $productImageUrl    = $baseUrl.$productsUrl;
        if($bidList->count()){
            $allBidList = [];
            foreach($bidList as $key => $val) {
                $bids['id']           = $val->bid_id;

                if ($val->bid->productVendorLocal != null && $val->bid->productVendorLocal->count()) {
                    if($val->bid->productVendorLocal->count()){
                        $bids['product_title']           = $val->bid->productVendorLocal[0]->local_name;
                    }else{
                        $bids['product_title']           = '';
                    }
                } else {
                    $bids['product_title']           = '';
                }

                if ($val->bid->unit != null && $val->bid->unit->local) {
                    if($val->bid->unit->local->count()){
                        $bids['unit']           = $val->bid->unit->local[0]->local_title;
                    }else{
                        $bids['unit']           = '';
                    }
                } else {
                    $bids['unit']           = '';
                }

                if ( $val->bid->pictures->count()) {
                    $bids['product_img']           = $productImageUrl .$val->bid->pictures[0]->image_name;
                } else {
                    $bids['product_img']           = '';
                }

                if ( $val->bid->getCategory->count()) {
                    $bids['category_name']           = $val->bid->getCategory[0]->local_name;
                } else {
                    $bids['category_name']           = '';
                }

                if ( $val->bid->bidLocal->count()) {
                    $bids['bid_title']           = $val->bid->bidLocal[0]->bid_title;
                } else {
                    $bids['bid_title']           = '';
                }

                if ( $val->bid->vendor != null) {
                    $bids['wholesaler_name']           = $val->bid->vendor->full_name;
                    $bids['wholesaler_id']           = $val->bid->vendor->id;
                } else {
                    $bids['wholesaler_name']           = '';
                    $bids['wholesaler_id']           = '';
                }
                $bids['pack'] = $val->bid->pack ? (string)$val->bid->pack : '';
                $bids['quantity'] = $val->bid->quantity ? (string)$val->bid->quantity : '';
                $bids['end_time'] = $val->bid->end_time ? Helper::formattedDatefromTimestamp($val->bid->end_time) : '';
                $bids['min_bid'] = $val->bid->minimum_amount ? (string)$val->bid->minimum_amount : '';
                if ( $val->bid->bidCountRelation->count()) {
                    $bids['total_bid']           = (string)$val->bid->bidCountRelation[0]->count;
                } else {
                    $bids['total_bid']           = '0';
                }

                if($val->bid->winner_id != NULL){
                    $status = 'awarded';
                }else if($val->bid->winner_id == null && $val->bid->end_time >= now()->timestamp){
                    $status = 'active';
                }else{
                    $status = 'expired';
                }  

                if ( $val->bid->myBidDetails->count()) {
                    $bids['my_max_bid']           = (string)$val->bid->myBidDetails[0]->bid_amount;
                } else {
                    $bids['my_max_bid']           = '';
                }

                if ( $val->bid->maxBid != null && $val->bid->maxBid->user) {
                    $bids['max_bid']           = (string)$val->bid->maxBid->bid_amount;
                    if($val->bid->maxBid->user != null){
                        $bids['max_bid_user_id']           = (string)$val->bid->maxBid->user->id;
                    }else{
                        $bids['max_bid_user_id']           = '';
                    }
                } else {
                    $bids['max_bid']           			= '';
                    $bids['max_bid_user_id']           	= '';
                }
                $bids['bid_placed_status'] = $status;
                $allBidList[] = $bids;
                unset($bids);
            }
            $listData['total_list']     = $totalBids;
            $listData['bid_list']       = $allBidList;
            
            return \Response::json(ApiHelper::generateResponseBody('BC-LB-0001#bids_myPlacedList', $listData));
        }else{
            $totalBids = 0;
            $bidList = [];
            return \Response::json(ApiHelper::generateResponseBody('BC-LB-0001#bids_myPlacedList', ['bid_list' => [],'total_list'=>$totalBids]));
        }        
    }

    /*****************************************************/
    # BidController
    # Function name : detailsBid
    # Author :
    # Created Date : 23-05-2019
    # Purpose :  to get details of a bid of a vendor
    # Params : bidId
    /*****************************************************/
    public function detailsBid(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);

        $userData  = ApiHelper::getUserFromHeader($request);
        $bidId              = $request->bid_id;
        if($userData){
            $userId             = $userData->id; 
            
        }else{
            $userId             = 0; 
        }
        if(!$bidId){
            return \Response::json(ApiHelper::generateResponseBody('BC-DB-0001#bids_details', trans('custom.wholesaler_no_bids_found'), false, 200));
        }
        
        $baseUrl            = asset('');
        $productsUrl        = 'uploads/bids/thumbs/';
        $productImageUrl    = $baseUrl.$productsUrl;
        $usersUrl        	= 'uploads/users/thumbs/';
        $userImageUrl    	= $baseUrl.$usersUrl;
        $bidDetails = Bid::with([
            'bidLocal' => function ($q) use ($lang) {
                $q->where('lang_code', '=', $lang);
            },
            'vendor' => function ($q) {
                $q->select('id','full_name');
            } ,
            'pictures' => function($q)  {
                // $q->where('default_image','=', '1');
            },
            'productVendorLocal' => function ($q) use ($lang) {
                $q->where('lang_code', '=', $lang);
            },
            'getCategory' => function($q) use ($lang) {
                $q->where('lang_code','=', $lang);
            },
            'myBidDetails'=> function($q) use ($userId)  {
                $q->where('bid_by','=', $userId);
                $q->select('id','bid_id','bid_by','bid_amount');
            },
            'myBidDetails.user' => function ($q) {
                $q->whereNull('deleted_at');
                $q->where('status', '=', '1');
                $q->select('id','full_name');
            },
            'maxBid'=> function($q) use ($userId)  {
                $q->select('id','bid_id','bid_by','bid_amount');
            },
            'maxBid.user' => function ($q) {
                $q->whereNull('deleted_at');
                $q->where('status', '=', '1');
                $q->select('id','full_name');
            },
            'details' => function ($q) {
                $q->orderBy('bid_amount', 'DESC');
            },
            'details.user' => function ($q) {
                $q->whereNull('deleted_at');
                $q->where('status', '=', '1');
                $q->select('id', 'full_name', 'email', 'phone_no' ,'profile_image');
            },
            'unit'=> function($q)  {
                $q->select('id','title');
            },
            'unit.local'=> function($q) use ($lang)  {
                $q->select('id','unit_id','local_title');
                $q->where('lang_code', '=', $lang);
            },
        ])
            ->select('id', 'vendor_id', 'product_id', 'minimum_amount', 'start_time', 'end_time','quantity','winner_id','pack','unit_id','category_id')
            ->where('id', '=', $bidId)->first();
        if($bidDetails){
            $val = $bidDetails;
            $bids['id']           = $val->id;

            if ( $val->pictures->count()) {
                foreach($val->pictures as $imgKey => $imgVal){
                    $img['img'] = $productImageUrl .$imgVal->image_name;
                    $img['default_image'] = $imgVal->default_image;

                    $bids['product_img'][] = $img;
                    unset($img);
                }
                
            } else {
                $bids['product_img']           = [];
            }

            $bids['pack'] = $val->pack ? (string)$val->pack : '';
            $bids['quantity'] = $val->quantity ? (string)$val->quantity : '';
            $bids['end_time'] = $val->end_time ? Helper::formattedDatefromTimestamp($val->end_time) : '';
            $bids['start_time'] = $val->start_time ? Helper::formattedDatefromTimestamp($val->start_time) : '';
            $bids['min_bid'] = $val->minimum_amount ? (string)$val->minimum_amount : '';
            $bids['total_bid'] = $val->details->count() ? (string)$val->details->count() : '0';

            if($val->winner_id != NULL){
                $status = 'awarded';
            }else if($val->winner_id == null && $val->end_time >= now()->timestamp){
                $status = 'active';
            }else{
                $status = 'expired';
            }  
            $bids['bid_placed_status'] = $status;

            if ( $val->myBidDetails->count()) {
                $bids['my_max_bid']           = (string)$val->myBidDetails[0]->bid_amount;
            } else {
                $bids['my_max_bid']           = '';
            }

            if ( $val->maxBid != null && $val->maxBid->user) {
                $bids['max_bid']           = (string)$val->maxBid->bid_amount;
                if($val->maxBid->user != null){
                    $bids['max_bid_user_id']           = (string)$val->maxBid->user->id;
                }else{
                    $bids['max_bid_user_id']           = '';
                }
            } else {
                $bids['max_bid']           = '';
                $bids['max_bid_user_id']           = '';
            }

            if ($val->unit != null && $val->unit->local) {
                if($val->unit->local->count()){
                    $bids['unit']           = $val->unit->local[0]->local_title;
                }else{
                    $bids['unit']           = '';
                }
            } else {
                $bids['unit']           = '';
            }

            if ( $val->vendor != null) {
                $bids['wholesaler_name']           = $val->vendor->full_name;
                $bids['wholesaler_id']           = $val->vendor->id;
            } else {
                $bids['wholesaler_name']           = '';
                $bids['wholesaler_id']           =  '';
            }

            if ( $val->details->count()) {
                foreach($val->details as $detailsKey => $detailsVal){
                    $img['user_id'] = $detailsVal->user ? $detailsVal->user->id : '';
                    $img['name'] = $detailsVal->user ? $detailsVal->user->full_name : '';
                    $img['amount'] = $detailsVal->bid_amount;
                    $img['created_at'] = $detailsVal->created_at->format('F j, Y');
                    $img['user_img'] = $detailsVal->user ? $userImageUrl.$detailsVal->user->profile_image : '';

                    $bids['bidder_list'][] = $img;
                    unset($img);
                }
                
            } else {
                $bids['bidder_list']           = [];
            }
            if ($val->productVendorLocal != null && $val->productVendorLocal->count()) {
                if($val->productVendorLocal->count()){
                    $bids['product_title']           = $val->productVendorLocal[0]->local_name;
                    $bids['product_description']     = Helper::cleanString($val->productVendorLocal[0]->description);
                }else{
                    $bids['product_title']           = '';
                    $bids['product_description']    = '';
                }
            } else {
                $bids['product_title']           = '';
                $bids['product_description']    = '';
            }

            if ( $val->bidLocal->count()) {
                $bids['bid_title']           = $val->bidLocal[0]->bid_title;
                $bids['bid_description']           = $val->bidLocal[0]->bid_rules;
            } else {
                $bids['bid_title']           = '';
                $bids['bid_description']           = '';
            }

            return \Response::json(ApiHelper::generateResponseBody('BC-DB-0002#bids_details', $bids));
        }else{
            
            return \Response::json(ApiHelper::generateResponseBody('BC-DB-0003#bids_details', trans('custom.wholesaler_no_bids_found'), false, 200));
        }
    }
}
