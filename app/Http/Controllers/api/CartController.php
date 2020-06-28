<?php
/*****************************************************/
# CartController
# Page/Class name   : CartController
# Author            :
# Created Date      : 15-07-2019
# Functionality     : index, addToCart
# Purpose           : Cart related functions
/*****************************************************/
namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
use Auth;
use Image;
use \App\Order;
use \App\OrderDetail;
use \App\OrderDetailLocal;
use \App\Category;
use \App\ProductCategory;
use \App\Product;
use \App\ProductVendor;
use \App\ProductVendorLocal;
use \App\ProductImages;
use \App\Unit;
use \App\State;
use \App\City;
use \App\UserAddress;
use \App\OrderAppliedCoupon;
use \App\Coupon;
use \Helper;
use \App\Http\Helpers\AdminHelper;
use \App\Http\Helpers\ApiHelper;
use \Validator;

class CartController extends Controller
{
    /*****************************************************/
    # CartController
    # Function name : addToCart
    # Author        :
    # Created Date  : 15-07-2019
    # Purpose       : Add product to cart
    # Params        : Request $request
    /*****************************************************/
    public function addToCart(Request $request)
    {

        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);

        $userData  = ApiHelper::getUserFromHeader($request);
        
        if ($request->isMethod('POST')) {
            $conditions = [];
            
            $userId = $userData->id;
            $conditions = ['user_id' => $userId, 'type' => 'C'];
           
            $productId = isset($request->productId) ? $request->productId : '';
            $quantity = isset($request->quantity) ? $request->quantity : 0;
            
            $orderData = Order::where($conditions)->first();
            if ($orderData != null) {
                $orderId = $orderData->id;
            } else {
                $newOrder = new Order;
                $newOrder->unique_order_id = Helper::generateUniquesOrderId();
                
                $newOrder->user_id = $userId;
                $newOrder->payment_method = '0';
                $newOrder->payment_status = 'P';
                $newOrder->type = 'C';
                // $newOrder->purchase_date = date('Y-m-d H:i:s');
                $newOrder->save();
                $orderId = $newOrder->id;
            }
            
            if ($productId != '' && $quantity > 0) {
                $checkOrderDetail = OrderDetail::where([
                    'order_id' => $orderId,
                    'product_vendor_id' => $productId,
                ])->first();
                    
                $updatedQuantity = 0; $updatedTotalPrice = 0;
                if ($checkOrderDetail == null) { //then insert into order_details table
                    $productsDetails = ProductVendor::whereNull('deleted_at')
                        ->where([
                            'id' => $productId,
                            'status' => '1',
                        ])
                        ->with([
                            'product',
                            'categoryVendorsLocals',
                            'subcategoryVendorsLocals',
                            'productUnitLocals',
                            'productVendorsLocals',
                            'vendorDetails',
                        ])
                        ->first();                    

                    if ($productsDetails != null) {
                        $newOrderDetail = new OrderDetail;
                        
                        $newOrderDetail->order_id               = $orderId;
                        $newOrderDetail->product_vendor_id      = $productId;
                        $newOrderDetail->mrp                    = $productsDetails->mrp;
                        $newOrderDetail->product_selling_price  = $productsDetails->selling_price;
                        $newOrderDetail->quantity               = $quantity;
                        $newOrderDetail->total_price            = ($productsDetails->selling_price) * $quantity;
                        $newOrderDetail->vat_percent            = $productsDetails->vat_percent;
                        $newOrderDetail->vat_price              = $productsDetails->vat_price;
                        $newOrderDetail->price                  = $productsDetails->price;                        
                        $newOrderDetail->unit_id                = $productsDetails->unit_id;
                        $newOrderDetail->pack_value             = $productsDetails->pack_value;
                        $newOrderDetail->vendor_id              = $productsDetails->vendor_id;

                        $newOrderDetail->save();
                        $OrderDetailId = $newOrderDetail->id;

                        if ($OrderDetailId) {
                            //Insertion into locals (for langauage)
                            if (count($productsDetails->productVendorsLocals)) {
                                foreach ($productsDetails->productVendorsLocals as $keyLocal => $valLocal) {
                                    $newOrderDetailLocal = new OrderDetailLocal;
                                    $newOrderDetailLocal->order_details_id          = $OrderDetailId;
                                    $newOrderDetailLocal->lang_code                 = $valLocal->lang_code;
                                    $newOrderDetailLocal->local_product_name        = $valLocal->local_name;
                                    $newOrderDetailLocal->local_product_description = $valLocal->description;
                                    $newOrderDetailLocal->local_unit_name           = $valLocal->local_unit_name;
                                    $newOrderDetailLocal->local_unit_description    = $valLocal->unit_description;
                                    //for category details
                                    if (count($productsDetails->categoryVendorsLocals) > 0) {
                                        if ($productsDetails->categoryVendorsLocals[$keyLocal]->lang_code == $valLocal->lang_code) {
                                            $newOrderDetailLocal->local_category_name = $productsDetails->categoryVendorsLocals[$keyLocal]->local_name;
                                        }
                                    }
                                    //for subcategory details
                                    if (count($productsDetails->subcategoryVendorsLocals) > 0) {
                                        if ($productsDetails->subcategoryVendorsLocals[$keyLocal]->lang_code == $valLocal->lang_code) {
                                            $newOrderDetailLocal->local_subcategory_name = $productsDetails->subcategoryVendorsLocals[$keyLocal]->local_name;
                                        }
                                    }
                                    $newOrderDetailLocal->save();
                                }
                            }
                        }
                        return \Response::json(ApiHelper::generateResponseBody('CC-ATC-0005#add_to_cart', trans('custom.add_to_cart_successful')));
                    }else{
                        return \Response::json(ApiHelper::generateResponseBody('CC-ATC-0003#add_to_cart',  trans('custom.something_went_wrong'), false, 400));
                    }
                } else { //update order_details table
                    if($request->replace_quantity){
                        $updatedQuantity    = ($quantity);
                    }else{
                        $updatedQuantity    = ($checkOrderDetail->quantity + $quantity);
                    }
                    
                    $updatedTotalPrice  = ($checkOrderDetail->product_selling_price * $updatedQuantity);

                    $updateOrderDetails = OrderDetail::where('id', $checkOrderDetail->id)
                                                    ->update([
                                                        'quantity'      => $updatedQuantity,
                                                        'total_price'   => $updatedTotalPrice
                                                    ]);
                    return \Response::json(ApiHelper::generateResponseBody('CC-ATC-0004#add_to_cart', trans('custom.update_cart_successful')));
                }
            }
        }else{
            return \Response::json(ApiHelper::generateResponseBody('CC-ATC-0001#add_to_cart',  trans('custom.something_went_wrong'), false, 400));
        }
    }

    /*****************************************************/
    # CartController
    # Function name : cartList
    # Author        :
    # Created Date  : 16-07-2019
    # Purpose       : Cart listing
    # Params        : Request $request
    /*****************************************************/
    public function cartList(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);

        $userData  = ApiHelper::getUserFromHeader($request);

        if (!$userData) {
            return \Response::json(ApiHelper::generateResponseBody('CC-CL-0001#cart_list',  trans('custom.something_went_wrong'), false, 400));
        }
        $cartDetailArray = ApiHelper::getCartItemDetails($userData,$lang);
        return \Response::json(ApiHelper::generateResponseBody('CC-CL-0002#cart_list', $cartDetailArray ));
        
    }

    /*****************************************************/
    # CartController
    # Function name : removeCartItem
    # Author        :
    # Created Date  : 16-07-2019
    # Purpose       : Remove product from cart for a particular user
    # Params        : Request $request
    /*****************************************************/
    public function removeCartItem(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);

        $userData  = ApiHelper::getUserFromHeader($request);

        if (!$userData) {
            return \Response::json(ApiHelper::generateResponseBody('CC-RCI-0001#remove_cart_item',  trans('custom.something_went_wrong'), false, 400));
        }

        if ($request->isMethod('POST')) {

            $orderId = isset($request->orderId) ? $request->orderId : 0;
            $orderDetailsId = isset($request->orderDetailsId) ? $request->orderDetailsId : 0;

            if ($orderId > 0 && $orderDetailsId > 0) {
                $getOrderDetail = OrderDetail::where(['id' => $orderDetailsId, 'order_id' => $orderId])->first();
                if ($getOrderDetail != null) {
                    $removeOrderDetailLocal = OrderDetailLocal::where(['order_details_id' => $orderDetailsId])->delete();
                    $getOrderDetail->delete();
                }else{
                    return \Response::json(ApiHelper::generateResponseBody('CC-RCI-0005#remove_cart_item',  trans('custom.no_product_found'), false, 400));
                }

                //Checking if NO product exist then delete main ORDER
                $countOrderDetails = OrderDetail::where(['order_id' => $orderId])->count();
                if( $countOrderDetails == 0 ) {
                    $order = Order::find($orderId);
                    $order->delete();
                }
                
                return \Response::json(ApiHelper::generateResponseBody('CC-RCI-0004#remove_cart_item',  trans('custom.cart_remove_success')));
            } else {
                return \Response::json(ApiHelper::generateResponseBody('CC-RCI-0003#remove_cart_item',  trans('custom.something_went_wrong'), false, 400));
            }
        }else {
            return \Response::json(ApiHelper::generateResponseBody('CC-RCI-0002#remove_cart_item',  trans('custom.something_went_wrong'), false, 400));
        }
    }

    /*****************************************************/
    # CartController
    # Function name : stateList
    # Author        :
    # Created Date  : 17-07-2019
    # Purpose       : Get state lists
    # Params        : 
    /*****************************************************/
    public function stateList()
    {
        $state = State::all();
        if($state->count()){
            return \Response::json(ApiHelper::generateResponseBody('CC-SL-0001#state_list',  $state));
        }else{
            return \Response::json(ApiHelper::generateResponseBody('CC-SL-0002#state_list',  []));
        } 
    }

    /*****************************************************/
    # CartController
    # Function name : cityList
    # Author        :
    # Created Date  : 17-07-2019
    # Purpose       : Get city lists with respect to state
    # Params        : Request $request
    /*****************************************************/
    public function cityList(Request $request)
    {
        $stateId = $request->state_id;
        if(!$stateId){
            return \Response::json(ApiHelper::generateResponseBody('CC-CL-0003#city_list',  trans('custom.something_went_wrong'), false, 400));
        }
        $city = City::select('id','name')->where('state_id',$stateId)->get();
        if($city->count()){
            return \Response::json(ApiHelper::generateResponseBody('CC-CL-0001#city_list',  $city));
        }else{
            return \Response::json(ApiHelper::generateResponseBody('CC-CL-0002#city_list',  []));
        }
    }

    /*****************************************************/
    # CartController
    # Function name : userAddressList
    # Author        :
    # Created Date  : 17-07-2019
    # Purpose       : Get user address lists
    # Params        : Request $request
    /*****************************************************/
    public function userAddressList(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);

        $userData  = ApiHelper::getUserFromHeader($request);

        if (!$userData) {
            return \Response::json(ApiHelper::generateResponseBody('CC-UAL-0001#user_address_list',  trans('custom.something_went_wrong'), false, 400));
        }
        $userId = $userData->id;
        $userAddress = UserAddress::select('id','full_name','phone_no','city_id','state_id','zip_code','street_address','land_mark','delivary_note')
        ->with([
            'state'=>function($query)  {
                $query->select('id','name');
            },
            'city'=>function($query)  {
                $query->select('id','name');
            },
        ])
        ->where('user_id',$userId)->get();
        if($userAddress->count()){
            $userAddress = ApiHelper::replaceNulltoEmptyStringAndIntToString($userAddress->toArray());
            return \Response::json(ApiHelper::generateResponseBody('CC-UAL-0003#user_address_list',  $userAddress));
        }else{
            return \Response::json(ApiHelper::generateResponseBody('CC-UAL-0002#user_address_list',  []));
        }
    }

    /*****************************************************/
    # CartController
    # Function name : addAddress
    # Author        :
    # Created Date  : 17-07-2019
    # Purpose       : Add user address
    # Params        : Request $request
    /*****************************************************/
    public function addAddress(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);

        $userData  = ApiHelper::getUserFromHeader($request);

        if (!$userData) {
            return \Response::json(ApiHelper::generateResponseBody('CC-AA-0001#add_address',  trans('custom.something_went_wrong'), false, 400));
        }
        $userId = $userData->id;

        $validator = Validator::make($request->all(),
                    [
                        'state_id'               => 'required|regex:/^[0-9]+$/',
                        'city_id'                => 'required|regex:/^[0-9]+$/',
                        'zip_code'               => 'required',
                        'street_address'         => 'required',
                        'phone_no'               => 'required',
                        'full_name'              => 'required'
                    ],
                    [
                        'state_id.required'      => trans('custom.state_required'),
                        'state_id.regex'         => trans('custom.state_number'),
                        'city_id.required'       => trans('custom.city_required'),
                        'city_id.regex'          => trans('custom.city_number'),
                        'zip_code.required'      => trans('custom.zip_code_required'),
                        'street_address.required'=> trans('custom.address_required'),
                        'phone_no.required'      => trans('custom.phone_no_required'),    
                        'full_name.required'     => trans('custom.full_name_required')
                        
                    ]
                );
        $errors = $validator->errors()->all();
        if ($errors) {
            return \Response::json(ApiHelper::generateResponseBody('CC-AA-0004#add_address', ["errors" => $errors], false, 100));
        } else {
            // set all request data         
            $state_id           = $request->state_id;
            $city_id            = $request->city_id;
            $zip_code           = $request->zip_code;
            $street_address     = $request->street_address;
            $phone_no           = $request->phone_no;
            $full_name          = $request->full_name; 
            $land_mark          = $request->land_mark; 
            
            $newUserAddress = new UserAddress;
                            
            $newUserAddress->state_id           = $state_id;    
            $newUserAddress->city_id            = $city_id;  
            $newUserAddress->zip_code           = $zip_code;                    
            $newUserAddress->street_address     = $street_address;
            $newUserAddress->phone_no           = $phone_no;
            $newUserAddress->full_name          = $full_name;
            $newUserAddress->user_id            = $userId;
            $newUserAddress->land_mark          = $land_mark;
            $newUserAddress->address_type       = 1;
            
            $addressSaved = $newUserAddress->save();
            
            if($addressSaved){
                return \Response::json(ApiHelper::generateResponseBody('CC-AA-0003#add_address',  trans('custom.address_added_success')));
            }else{
                return \Response::json(ApiHelper::generateResponseBody('CC-AA-0002#add_address', trans('custom.something_went_wrong'), false, 400));
            }
        }
    }

    /*****************************************************/
    # CartController
    # Function name : applyCoupon
    # Author        :
    # Created Date  : 22-08-2019
    # Purpose       : Apply coupon
    # Params        : Request $request
    /*****************************************************/
    public function applyCoupon(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $currentLang = $getSetLang;
        $lang       = strtoupper($getSetLang);

        $userData  = ApiHelper::getUserFromHeader($request);

        if (!$userData) {
            return \Response::json(ApiHelper::generateResponseBody('CC-AC-0001#apply_coupon',  trans('custom.something_went_wrong'), false, 400));
        }
        
        if ($request->isMethod('POST')) {
            $validator = Validator::make($request->all(),
                    [
                        'orderId'               => 'required',
                        'couponCode'            => 'required'
                    ],
                    [
                        'orderId.required'      => trans('custom.order_id_required'),
                        'couponCode.required'   => trans('custom.coupon_code_required')
                    ]
                );
            $errors = $validator->errors()->all();
            if ($errors) {
                return \Response::json(ApiHelper::generateResponseBody('CC-AC-0002#apply_coupon', ["errors" => $errors], false, 100));
            } else {
                
                $orderId                        = $request->orderId;
                $couponCode                     = $request->couponCode;
                $couponDiscountAmount           = 0;
                $minCartAmount                  = 0;                
                $wholesalerIds                  = [];
                $wholesalerProductTotalPrice    = 0;
                $productIds                     = [];
                $productTotalPrice              = 0;
                $categoryIds                    = [];
                $categoryProductTotalPrice      = 0;

                $couponExist = OrderAppliedCoupon::where('order_id', $orderId)->count();
                if ($couponExist == 0) {
                    $now = strtotime(date('Y-m-d H:i'));

                    $conditions[] = ['status', '1'];
                    $conditions[] = ['code', $couponCode];
                    $conditions[] = ['start_time', '<=', $now];
                    $conditions[] = ['end_time', '>=', $now];

                    $couponData = Coupon::where($conditions)->first();
                    //dd($couponData);
                    if ($couponData != null) {
                        $getCartDetails = ApiHelper::getCartItemDetails($userData,$lang);
                        $discntAmnt = 0;
                        $totalcartValue = isset($getCartDetails['totalCartPrice']) ? Helper::formatToTwoDecimalPlaces($getCartDetails['totalCartPrice']) : 0;
                        
                        //If related to Minimum Cart start
                        if ($couponData->type == '1') {
                            //If cart value is greater than Minimum cart value
                            if ($getCartDetails['totalCartPrice'] > $couponData->cart_amount) {
                                
                                //coupon discount amount calculation
                                if ($couponData->discount_type == 'F') {    //for flat discount
                                    $discntAmnt = $couponData->amount;
                                } else {    //for percent discount
                                    $discntAmnt = (($totalcartValue * $couponData->amount) / 100);
                                }
                                $discntAmnt = Helper::formatToTwoDecimalPlaces($discntAmnt);
                                
                                //checking for coupon discount amount > total cart amount
                                if ($discntAmnt > $getCartDetails['totalCartPrice']) {
                                    return \Response::json(ApiHelper::generateResponseBody('CC-AC-0003#apply_coupon', trans('custom.cart_total_less_than_coupon_amount_error', ['cartTotalAmount' => $totalcartValue, 'couponDiscountAmount' => $discntAmnt]), false, 400));
                                    // $request->session()->flash('alert-coupondanger', trans('custom.cart_total_less_than_coupon_amount_error', ['cartTotalAmount' => $totalcartValue, 'couponDiscountAmount' => $discntAmnt]));
                                } else {
                                    $appliedCoupon['order_id']              = $orderId;
                                    $appliedCoupon['coupon_id']             = $couponData->id;
                                    $appliedCoupon['applied_for']           = 'WC';
                                    $appliedCoupon['coupon_code']           = $couponData->code;
                                    $appliedCoupon['minimum_cart_amount']   = isset($couponData->cart_amount) ? $couponData->cart_amount : null;
                                    $appliedCoupon['discount_type']         = $couponData->discount_type;
                                    $appliedCoupon['coupon_amount']         = $couponData->amount;
                                    $appliedCoupon['start_time']            = $couponData->start_time;
                                    $appliedCoupon['end_time']              = $couponData->end_time;

                                    OrderAppliedCoupon::create($appliedCoupon);
                                    return \Response::json(ApiHelper::generateResponseBody('CC-AC-0004#apply_coupon', trans('custom.coupon_applied_successful')));
                                    // $request->session()->flash('alert-couponsuccess', trans('custom.coupon_applied_successful')); 
                                }
                            } else {
                                $minCartAmount = Helper::formatToTwoDecimalPlaces($couponData->cart_amount);
                                return \Response::json(ApiHelper::generateResponseBody('CC-AC-0005#apply_coupon', trans('custom.minimum_cart_error', ['minCartAmount' => $minCartAmount]), false, 400));
                                // $request->session()->flash('alert-coupondanger', trans('custom.minimum_cart_error', ['minCartAmount' => $minCartAmount]));
                            }
                        }
                        //If related to Minimum Cart end
                        //If related to Category start
                        else if ($couponData->type == '2') {
                            if (strpos($couponData->category_id, ',') !== false) {
                                $categoryIds = explode(',', $couponData->category_id);
                            } else {
                                $categoryIds[] = $couponData->category_id;
                            }                                
                            //Calculating category products total price
                            if( (count($categoryIds) > 0) && (count($getCartDetails) > 0) && (count($getCartDetails['itemDetails']) > 0) ) {
                                foreach ($getCartDetails['itemDetails'] as $item) {
                                    $categoryId = isset($item['product_category_id']) ? $item['product_category_id'] : 0;
                                    $subcategoryId = isset($item['product_subcategory_id']) ? $item['product_subcategory_id'] : 0;
                                    if (in_array($categoryId, $categoryIds) || in_array($subcategoryId, $categoryIds)) {
                                        $categoryProductTotalPrice += $item['total_price'];
                                    }
                                }
                            }
                            
                            //If category product exist
                            if ($categoryProductTotalPrice > 0) {
                                
                                //coupon discount amount calculation
                                if ($couponData->discount_type == 'F') {    //for flat discount
                                    $discntAmnt = $couponData->amount;
                                } else {    //for percent discount
                                    $discntAmnt = (($categoryProductTotalPrice * $couponData->amount) / 100);
                                }
                                $discntAmnt = Helper::formatToTwoDecimalPlaces($discntAmnt);
                                $categoryProductTotalPrice =Helper::formatToTwoDecimalPlaces($categoryProductTotalPrice);
                                
                                //checking for total category related product amount > coupon discount amount
                                if ($discntAmnt > $categoryProductTotalPrice) {
                                    return \Response::json(ApiHelper::generateResponseBody('CC-AC-0006#apply_coupon', trans('custom.category_cart_total_less_than_coupon_amount_error', ['categoryProductTotalPrice' => $categoryProductTotalPrice, 'couponDiscountAmount' => $discntAmnt]), false, 400));
                                    // $request->session()->flash('alert-coupondanger', trans('custom.category_cart_total_less_than_coupon_amount_error', ['categoryProductTotalPrice' => $categoryProductTotalPrice, 'couponDiscountAmount' => $discntAmnt]));
                                } else {
                                    $appliedCoupon['order_id']      = $orderId;
                                    $appliedCoupon['coupon_id']     = $couponData->id;
                                    $appliedCoupon['applied_for']   = 'C';
                                    $appliedCoupon['category_id']   = $couponData->category_id;
                                    $appliedCoupon['coupon_code']   = $couponData->code;
                                    $appliedCoupon['discount_type'] = $couponData->discount_type;
                                    $appliedCoupon['coupon_amount'] = $couponData->amount;
                                    $appliedCoupon['start_time']    = $couponData->start_time;
                                    $appliedCoupon['end_time']      = $couponData->end_time;

                                    OrderAppliedCoupon::create($appliedCoupon);
                                
                                    // Session::put(['cart.couponid' => $couponData->id]);
                                    return \Response::json(ApiHelper::generateResponseBody('CC-AC-0007#apply_coupon', trans('custom.coupon_applied_successful')));
                                }
                            } else {
                                return \Response::json(ApiHelper::generateResponseBody('CC-AC-0008#apply_coupon', trans('custom.coupon_invalid_expired'), false, 400));
                            }
                        }
                        //If related to Category end
                        //If related to Product start
                        else if ($couponData->type == '3') {
                            if (strpos($couponData->product_id, ',') !== false) {
                                $productIds = explode(',', $couponData->product_id);
                            } else {
                                $productIds[] = $couponData->product_id;
                            }                                
                            //Calculating products total price
                            if( (count($productIds) > 0) && (count($getCartDetails) > 0) && (count($getCartDetails['itemDetails']) > 0) ) {
                                foreach ($getCartDetails['itemDetails'] as $item) {
                                    if (in_array($item['product_vendor_id'], $productIds)) {
                                        $productTotalPrice += $item['total_price'];
                                    }
                                }
                            }
                            //If product exist
                            if ($productTotalPrice > 0) {

                                //coupon discount amount calculation
                                if ($couponData->discount_type == 'F') {
                                    $discntAmnt = $couponData->amount;
                                } else {
                                    $discntAmnt = (($productTotalPrice * $couponData->amount) / 100);
                                }
                                $discntAmnt = Helper::formatToTwoDecimalPlaces($discntAmnt);
                                $productTotalPrice =Helper::formatToTwoDecimalPlaces($productTotalPrice);
                                
                                //checking for total product related amount > coupon discount amount
                                if ($discntAmnt > $productTotalPrice) {
                                    return \Response::json(ApiHelper::generateResponseBody('CC-AC-0009#apply_coupon', trans('custom.product_cart_total_less_than_coupon_amount_error', ['productTotalPrice' => $productTotalPrice, 'couponDiscountAmount' => $discntAmnt]), false, 400));
                                } else {
                                    $appliedCoupon['order_id']      = $orderId;
                                    $appliedCoupon['coupon_id']     = $couponData->id;
                                    $appliedCoupon['applied_for']   = 'P';
                                    $appliedCoupon['product_id']    = $couponData->product_id;
                                    $appliedCoupon['coupon_code']   = $couponData->code;
                                    $appliedCoupon['discount_type'] = $couponData->discount_type;
                                    $appliedCoupon['coupon_amount'] = $couponData->amount;
                                    $appliedCoupon['start_time']    = $couponData->start_time;
                                    $appliedCoupon['end_time']      = $couponData->end_time;

                                    OrderAppliedCoupon::create($appliedCoupon);

                                    return \Response::json(ApiHelper::generateResponseBody('CC-AC-00010#apply_coupon', trans('custom.coupon_applied_successful')));
                                }
                            } else {
                                return \Response::json(ApiHelper::generateResponseBody('CC-AC-00011#apply_coupon', trans('custom.coupon_invalid_expired'), false, 400));
                            }
                        }
                        //If related to Product end
                        //If related to Wholesaler Product start
                        else if ($couponData->type == '4') {
                            if (strpos($couponData->vendor_id, ',') !== false) {
                                $wholesalerIds = explode(',', $couponData->vendor_id);
                            } else {
                                $wholesalerIds[] = $couponData->vendor_id;
                            }
                            //Calculating wholesaler related products total price
                            if( (count($wholesalerIds) > 0) && (count($getCartDetails) > 0) && (count($getCartDetails['itemDetails']) > 0) ) {
                                foreach ($getCartDetails['itemDetails'] as $item) {
                                    if (in_array($item['vendor_id'], $wholesalerIds)) {
                                        $wholesalerProductTotalPrice += $item['total_price'];
                                    }
                                }
                            }
                            //If wholesaler related product exist
                            if ($wholesalerProductTotalPrice > 0) {
                                
                                //coupon discount amount calculation
                                if ($couponData->discount_type == 'F') {
                                    $discntAmnt = $couponData->amount;
                                } else {
                                    $discntAmnt = (($wholesalerProductTotalPrice * $couponData->amount) / 100);
                                }
                                $discntAmnt = Helper::formatToTwoDecimalPlaces($discntAmnt);
                                $wholesalerProductTotalPrice =Helper::formatToTwoDecimalPlaces($wholesalerProductTotalPrice);
                                
                                //checking for total vendor related amount > coupon discount amount
                                if ($discntAmnt > $wholesalerProductTotalPrice) {
                                    return \Response::json(ApiHelper::generateResponseBody('CC-AC-00012#apply_coupon', trans('custom.vendor_cart_total_less_than_coupon_amount_error', ['wholesalerProductTotalPrice' => $wholesalerProductTotalPrice, 'couponDiscountAmount' => $discntAmnt]), false, 400));
                                } else {
                                    $appliedCoupon['order_id']              = $orderId;
                                    $appliedCoupon['coupon_id']             = $couponData->id;
                                    $appliedCoupon['applied_for']           = 'WP';
                                    $appliedCoupon['vendor_id']             = $couponData->vendor_id;
                                    $appliedCoupon['coupon_code']           = $couponData->code;
                                    $appliedCoupon['minimum_cart_amount']   = isset($couponData->cart_amount) ? $couponData->cart_amount : null;
                                    $appliedCoupon['discount_type']         = $couponData->discount_type;
                                    $appliedCoupon['coupon_amount']         = $couponData->amount;
                                    $appliedCoupon['start_time']            = $couponData->start_time;
                                    $appliedCoupon['end_time']              = $couponData->end_time;
                                    //$appliedCoupon['coupon_discount_amount'] = $couponDiscountAmount;

                                    OrderAppliedCoupon::create($appliedCoupon);

                                    return \Response::json(ApiHelper::generateResponseBody('CC-AC-00013#apply_coupon', trans('custom.coupon_applied_successful')));
                                }
                            } else {
                                return \Response::json(ApiHelper::generateResponseBody('CC-AC-00014#apply_coupon', trans('custom.coupon_invalid_expired'), false, 400));
                            }
                        }
                        //If related to Wholesaler Product end

                    } else {
                        return \Response::json(ApiHelper::generateResponseBody('CC-AC-00015#apply_coupon', trans('custom.coupon_invalid_expired'), false, 400));
                    }
                } else {
                    return \Response::json(ApiHelper::generateResponseBody('CC-AC-00016#apply_coupon', trans('custom.coupon_already_applied'), false, 400));
                } 
            }
        }
    }

    /*****************************************************/
    # CartController
    # Function name : removeAppliedCoupon
    # Author        :
    # Created Date  : 22-08-2019
    # Purpose       : Remove coupon from cart
    # Params        : Request $request
    /*****************************************************/
    public function removeAppliedCoupon(Request $request)
    {
        $getSetLang     = ApiHelper::getSetLocale($request);
        $currentLang    = $getSetLang;
        $lang           = strtoupper($getSetLang);

        $userData  = ApiHelper::getUserFromHeader($request);

        if (!$userData) {
            return \Response::json(ApiHelper::generateResponseBody('CC-RAC-0001#remove_applied_coupon',  trans('custom.something_went_wrong'), false, 400));
        }
        if ($request->isMethod('POST')) {

            $orderId = isset($request->orderId) ? $request->orderId : 0;
            $orderAppliedCouponId = isset($request->orderCouponId) ? $request->orderCouponId : 0;
            if ($orderId != 0 && $orderAppliedCouponId != 0) {
                OrderAppliedCoupon::where([['id', $orderAppliedCouponId], ['order_id', $orderId]])->delete();
                return \Response::json(ApiHelper::generateResponseBody('CC-RAC-0002#remove_applied_coupon',  trans('custom.coupon_removed_success')));
            } else {
                return \Response::json(ApiHelper::generateResponseBody('CC-RAC-0003#remove_applied_coupon',  trans('custom.something_went_wrong'), false, 400));
            }
        }
    }
}
