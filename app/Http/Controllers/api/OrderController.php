<?php
/*****************************************************/
# OrderController
# Page/Class name   : OrderController
# Author            :
# Created Date      : 29-07-2019
# Functionality     : myOrderListing
# Purpose           : All orders related functions
/*****************************************************/
namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App;
use Auth;
use Redirect;
use \App\Order;
use \App\OrderDetail;
use \App\OrderShippingDetail;
use \App\UserAddress;
use \App\PushNotification;
use \App\State;
use \App\City;
use \App\SiteSetting;
use \App\OrderAppliedCoupon;
use App\Http\Helpers\ApiHelper;
use App\Http\Helpers\Helper;
use App\Http\Helpers\NotificationHelper;
use \Validator;

class OrderController extends Controller
{
    /*****************************************************/
    # OrderController
    # Function name : myOrders
    # Author        :
    # Created Date  : 29-07-2019
    # Purpose       : My order listing
    # Params        :
    /*****************************************************/
    public function myOrders(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('OC-MO-0001#orders_my_orders',  trans('custom.not_authorized'), false, 400));
        }

        $offset = 0;
        $limit  = Helper::WHOLESALER_PRODUCT_LIST_LIMIT;
        $page   = isset($request->page)?$request->page:0;
        if ($page > 0) {
            $offset = ($limit * $page);
        }

        $order = Order::where('type', 'O')->where('user_id', $userData->id)->with([
            'orderDetails' => function ($q) use ($lang) {
                $q->with([
                    'orderDetailLocals' => function ($q) use ($lang) {
                        $q->where('lang_code', '=', $lang);
                    },
                    
                ]);

            },
            'orderAppliedCoupon',
        ])->orderBy('created_at', 'desc');
        $orderCount = $order->count();
        $order = $order->skip($offset)
        ->take($limit)->get();
            
        $orderList = [];
        if(!$order->count()){
            $orderCount = 0;
        }
        $baseUrl = asset('');
        $productsUrl = 'uploads/products/thumbs/';
        $productImageUrl = $baseUrl . $productsUrl;
        foreach ($order as $key => $val) {

            $orderVal['order_id'] = $val->id;
            $orderVal['order_code'] = $val->unique_order_id;
            $orderVal['order_date'] = $val->purchase_date ? $val->purchase_date : '';
            $orderVal['status'] = $val->status;
            $orderVal['order_product_count'] = $val->orderDetails->count() ? $val->orderDetails->count() - 1 : 0;
            if ($val->orderAppliedCoupon != null) {
                $orderVal['coupon_code'] = $val->orderAppliedCoupon->coupon_code;
                $orderVal['coupon_discount_amount'] = Helper::formatToTwoDecimalPlaces($val->orderAppliedCoupon->coupon_discount_amount);
            }else{
                $orderVal['coupon_code'] = '';
                $orderVal['coupon_discount_amount'] = '';
            }

            $amount = 0;
            $quantity = 0;
            if ($val->orderDetails->count()) {

                if ($val->orderDetails[0]->orderDetailLocals->count()) {
                    $orderVal['product_title'] = $val->orderDetails[0]->orderDetailLocals[0]->local_product_name;
                    $orderVal['unit'] = $val->orderDetails[0]->orderDetailLocals[0]->local_unit_name;
                } else {
                    $orderVal['product_title'] = '';
                    $orderVal['unit'] = '';
                }
                if ($val->orderDetails[0]->productVendor && $val->orderDetails[0]->productVendor->productDefaultImage->count()) {
                    $productImage = $val->orderDetails[0]->productVendor->productDefaultImage[0]->image_name;
                    $orderVal['product_image'] = $productImageUrl.$productImage;
                } else {
                    $orderVal['product_image'] = '';
                }

                if ($val->orderDetails[0]->productVendor && $val->orderDetails[0]->productVendor->vendorDetails) {

                    $orderVal['vendor_name'] = $val->orderDetails[0]->productVendor->vendorDetails->shop_name;
                } else {
                    $orderVal['vendor_name'] = '';
                }

                $orderVal['pack_value'] = $val->orderDetails[0]->pack_value ? (string) $val->orderDetails[0]->pack_value : '';
                $orderVal['product_selling_price'] = $val->orderDetails[0]->product_selling_price ? (string) $val->orderDetails[0]->product_selling_price : '';
                $orderVal['vat_price'] = $val->orderDetails[0]->vat_price ? (string) $val->orderDetails[0]->vat_price : '';
                $orderVal['order_status'] = $val->orderDetails[0]->order_status;

                foreach ($val->orderDetails as $key1 => $val1) {
                    $quantity += $val1->quantity;
                    $amount += $val1->total_price;
                }
            } else {
                $orderVal['product_title'] = '';
                $orderVal['pack_value'] = '';
                $orderVal['unit'] = '';
                $orderVal['product_image'] = '';
                $orderVal['vendor_name'] = '';
                $orderVal['product_selling_price'] = '';
                $orderVal['vat_price'] = '';
                $orderVal['order_status'] = '';

            }
            $orderVal['total_price'] = $amount;
            $orderVal['quantity'] = $quantity;
            $orderList[] = $orderVal;
            unset($orderVal);
        }
        // dd( $orderList);
        return \Response::json(ApiHelper::generateResponseBody('OC-MO-0002#orders_my_orders', ['orders_list' => $orderList,'total_orders'=>$orderCount]));
        
    }

    /*****************************************************/
    # OrderController
    # Function name : orderDetail
    # Author        :
    # Created Date  : 16-07-2019
    # Purpose       : My order details
    # Params        :
    /*****************************************************/
    public function orderDetail(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('OC-OD-0001#orders_order_detail',  trans('custom.not_authorized'), false, 400));
        }

        $cartUserId = 0;
        $totalCartCount = 0;
        $getCartData = array();

        $cartUserId = $userData->id;
        $order_id = $request->order_id;
        $cartConditions = ['user_id' => $cartUserId, 'type' => 'O', 'id' => $order_id];

        $getOrderDetails = Order::where($cartConditions)->with([
            'orderDetails' => function ($query) use ($lang) {
                $query->orderBy('created_at', 'desc');
                $query->with([
                    'orderDetailLocals' => function ($query) use ($lang) {
                        $query->where('lang_code', '=', $lang);
                    },
                ]);
            },
            'orderAppliedCoupon',
        ])->first();
        // dd($getOrderDetails);

        $grandCartPrice = 0.00;
        $totalCartPrice = 0.00;
        $totalVat = 0.00;
        $cartArray = array();
        $productIds = [];
        $cartOrderId = 0;
        $order = array();
        if ($getOrderDetails != null) {

            $baseUrl = asset('');
            $productsUrl = 'uploads/products/thumbs/';
            $productImageUrl = $baseUrl . $productsUrl;
            $cartOrderId = $getOrderDetails->id;
            //Main Cart array
            $order['user_name'] = $getOrderDetails->userDetails ? $getOrderDetails->userDetails->full_name : '';
            $order['shipping_phone_number'] = $getOrderDetails->shipping_phone_number ? (string) $getOrderDetails->shipping_phone_number : '';
            $order['shipping_address'] = $getOrderDetails->shipping_address ? $getOrderDetails->shipping_address : '';
            $order['shipping_city'] = $getOrderDetails->shippingCity ? $getOrderDetails->shippingCity->name : '';
            $order['shipping_zipcode'] = $getOrderDetails->shipping_zipcode ? (string) $getOrderDetails->shipping_zipcode : '';
            $order['payment_method'] = ($getOrderDetails->payment_method != null) ? (string) $getOrderDetails->payment_method : '';
            $order['order_code'] = $getOrderDetails->unique_order_id;
            $order['order_date'] = $getOrderDetails->purchase_date ? $getOrderDetails->purchase_date : '';
            $order['status'] = $getOrderDetails->status;
            $order['coupon_code'] = '';
            $order['coupon_discount_amount'] = 0;

            if (isset($getOrderDetails->orderDetails) && count($getOrderDetails->orderDetails) > 0) {
                $i = 0;
                foreach ($getOrderDetails->orderDetails as $orderDetails) {
                    $productImage = '';
                    $vatAmount = 0;
                    $cartArray[$i]['order_detail_id'] = $orderDetails->id;
                    $cartArray[$i]['order_id'] = $orderDetails->order_id;
                    // $cartArray[$i]['product_vendor_id'] = $orderDetails->product_vendor_id;
                    $cartArray[$i]['mrp'] = $orderDetails->mrp ? Helper::formatToTwoDecimalPlaces($orderDetails->mrp) : '';
                    $cartArray[$i]['product_selling_price'] = $orderDetails->product_selling_price ? Helper::formatToTwoDecimalPlaces($orderDetails->product_selling_price) : '';
                    $cartArray[$i]['quantity'] = $orderDetails->quantity ? $orderDetails->quantity : 0;
                    $cartArray[$i]['total_price'] = $orderDetails->total_price ? Helper::formatToTwoDecimalPlaces($orderDetails->total_price) : '';
                    $cartArray[$i]['pack_value'] = $orderDetails->pack_value ? Helper::formatToTwoDecimalPlaces($orderDetails->pack_value) : '';
                    $cartArray[$i]['vendor_id'] = $orderDetails->vendor_id;
                    $cartArray[$i]['order_status'] = $orderDetails->order_status;
                    $cartArray[$i]['status'] = $orderDetails->order_status == 'D' ? $orderDetails->order_status : $orderDetails->status;
                    $cartArray[$i]['expected_delivery'] = $orderDetails->expected_delivery ? (string) $orderDetails->expected_delivery : '';
                    // $cartArray[$i]['order_shipping_details'] = $orderDetails->orderShippingDetails->count() ? array_reverse($orderDetails->orderShippingDetails->toArray()) : [];
                    
                    $orderShippedArr = ['D','S','P'];
                    $shippedDetailsArray = $orderDetails->orderShippingDetails->count() ? array_reverse($orderDetails->orderShippingDetails->toArray()) : [];
                    $countArr = 0;
                    for($shipLoop = 0; $shipLoop < 3; $shipLoop++){
                        
                        if(array_key_exists($countArr,$shippedDetailsArray)){
                            $yes = true;
                        }else{
                            $yes = false;
                        }
                        
                        if($yes && $shippedDetailsArray[$countArr]['order_status'] == $orderShippedArr[$shipLoop]){
                            $cartArray[$i]['order_shipping_details'][$shipLoop]['id'] = $shippedDetailsArray[$countArr]['id'];
                            $cartArray[$i]['order_shipping_details'][$shipLoop]['order_id'] = $shippedDetailsArray[$countArr]['order_id'];
                            $cartArray[$i]['order_shipping_details'][$shipLoop]['order_details_id'] = $shippedDetailsArray[$countArr]['order_details_id'];
                            $cartArray[$i]['order_shipping_details'][$shipLoop]['order_status'] = $shippedDetailsArray[$countArr]['order_status'];
                            $cartArray[$i]['order_shipping_details'][$shipLoop]['comment'] = $shippedDetailsArray[$countArr]['comment'] ? $shippedDetailsArray[$countArr]['comment'] : '';
                            $cartArray[$i]['order_shipping_details'][$shipLoop]['created_at'] = $shippedDetailsArray[$countArr]['created_at'];
                            $cartArray[$i]['order_shipping_details'][$shipLoop]['updated_at'] = $shippedDetailsArray[$countArr]['updated_at'];
                            $countArr++;
                        }else{
                            $cartArray[$i]['order_shipping_details'][$shipLoop]['id'] = '';
                            $cartArray[$i]['order_shipping_details'][$shipLoop]['order_id'] = '';
                            $cartArray[$i]['order_shipping_details'][$shipLoop]['order_details_id'] = '';
                            $cartArray[$i]['order_shipping_details'][$shipLoop]['order_status'] = '';
                            $cartArray[$i]['order_shipping_details'][$shipLoop]['comment'] = '';
                            $cartArray[$i]['order_shipping_details'][$shipLoop]['created_at'] = '';
                            $cartArray[$i]['order_shipping_details'][$shipLoop]['updated_at'] = '';
                        }
                    }

                    if ($orderDetails->vat_price != null) {
                        $vatAmount += $orderDetails->vat_price * $orderDetails->quantity;
                    }
                    $cartArray[$i]['vat_price'] = Helper::formatToTwoDecimalPlaces($vatAmount);

                    if (isset($orderDetails->productVendor) && $orderDetails->productVendor->vendorDetails != null) {
                        $cartArray[$i]['wholesaler_shop_name'] = $orderDetails->productVendor->vendorDetails->shop_name;
                    } else {
                        $cartArray[$i]['wholesaler_shop_name'] = '';
                    }

                    if (isset($orderDetails->productVendor->productDefaultImage) && $orderDetails->productVendor->productDefaultImage != null) {
                        if (count($orderDetails->productVendor->productDefaultImage)) {
                            $productImage = $orderDetails->productVendor->productDefaultImage[0]->image_name;
                            $cartArray[$i]['product_image'] = $productImageUrl.$productImage;
                        } else {
                            $cartArray[$i]['product_image'] = '';
                        }
                    } else {
                        $cartArray[$i]['product_image'] = '';
                    }

                    if (count($orderDetails->orderDetailLocals) > 0) {
                        $cartArray[$i]['product_title'] = $orderDetails->orderDetailLocals[0]->local_product_name;
                        $cartArray[$i]['name'] = $orderDetails->orderDetailLocals[0]->local_product_name;
                        $cartArray[$i]['unit_name'] = $orderDetails->orderDetailLocals[0]->local_unit_name;

                    } else {
                        $cartArray[$i]['name'] = '';
                        $cartArray[$i]['unit_name'] = '';
                        $cartArray[$i]['product_title'] = '';
                    }
                    //Total price
                    $totalCartPrice += $orderDetails->total_price;
                    $totalVat += $vatAmount;

                    $i++;
                }
                if ($getOrderDetails->orderAppliedCoupon != null) {
                    $payableAmount = $totalCartPrice -  $getOrderDetails->orderAppliedCoupon['coupon_discount_amount'];
                    $order['coupon_code'] = $getOrderDetails->orderAppliedCoupon['coupon_code'];
                    $order['coupon_discount_amount'] = Helper::formatToTwoDecimalPlaces($getOrderDetails->orderAppliedCoupon['coupon_discount_amount']);
                    
                }else{
                    $payableAmount = $totalCartPrice;
                    $order['coupon_code'] = '';
                    $order['coupon_discount_amount'] = '';
                }
            }
        }
        $grandCartPrice = Helper::formatToTwoDecimalPlaces($totalCartPrice);
        $payableAmount = Helper::formatToTwoDecimalPlaces($payableAmount);
        
        $totalCartCount = count($cartArray);
        $cartDetailArray = array('cartOrderId' => $cartOrderId, 'itemDetails' => $cartArray, 'order' => $order, 'totalItem' => $totalCartCount, 'totalCartPrice' => Helper::formatToTwoDecimalPlaces($totalCartPrice), 'totalVat' => Helper::formatToTwoDecimalPlaces($totalVat), 'grandCartPrice' => $grandCartPrice,'payableAmount' => $payableAmount);

        return \Response::json(ApiHelper::generateResponseBody('OC-OD-0002#orders_order_detail', $cartDetailArray));
    }

    /*****************************************************/
    # OrderController
    # Function name : orderDetailCancel
    # Author        :
    # Created Date  : 31-07-2019
    # Purpose       : My order details cancel
    # Params        : Request $request
    /*****************************************************/
    public function orderDetailCancel(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('OC-ODC-0001#orders_cancel_order_detail',  trans('custom.not_authorized'), false, 400));
        }

        $orderDetailId = $request->orderDetailId;
        if ($orderDetailId) {
            $orderDetails = OrderDetail::find($orderDetailId);
            if ($orderDetails) {
                if($orderDetails->order_status == 'O' || $orderDetails->order_status == 'P'){
                    $orderDetails->status = 'C';
                    if ($orderDetails->save()) {

                        NotificationHelper::sendNotificationOrderCancel($orderDetails);

                        $check = 0;
                        foreach ($orderDetails->order->orderDetails as $key => $val) {
                            if ($val->status == 'P') {
                                $check = 1;
                            }
                        }
                        if ($check == 1) {
                            $orderDetails->order->status = 'PC';
                        } else {
                            $orderDetails->order->status = 'C';
                        }
                        if ($orderDetails->order->save()) {
                            return \Response::json(ApiHelper::generateResponseBody('OC-ODC-0002#orders_cancel_order_detail',  trans('custom.successful')));
                            
                        } else {
                            return \Response::json(ApiHelper::generateResponseBody('OC-ODC-0003#orders_cancel_order_detail',  trans('custom.something_went_wrong'), false, 400));
                            
                        }
                    } else {
                        return \Response::json(ApiHelper::generateResponseBody('OC-ODC-0004#orders_cancel_order_detail',  trans('custom.something_went_wrong'), false, 400));
                        
                    }
                }else{
                    return \Response::json(ApiHelper::generateResponseBody('OC-ODC-0005#orders_cancel_order_detail',  trans('custom.order_cannot_be_canceled'), false, 400));
                    
                }
                
            } else {
                return \Response::json(ApiHelper::generateResponseBody('OC-ODC-0006#orders_cancel_order_detail',  trans('custom.something_went_wrong'), false, 400));
                
            }
        } else {
            return \Response::json(ApiHelper::generateResponseBody('OC-ODC-0007#orders_cancel_order_detail',  trans('custom.something_went_wrong'), false, 400));
            

        }
    }

    /*****************************************************/
    # OrderController
    # Function name : wholesalerOrders
    # Author        :
    # Created Date  : 7-08-2019
    # Purpose       : Wholesaler order listing
    # Params        : Request $request
    /*****************************************************/
    public function wholesalerOrders(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('OC-WO-0001#orders_wholesaler_orders',  trans('custom.not_authorized'), false, 400));
        }

        if($userData->is_wholesaler != 1){
            return \Response::json(ApiHelper::generateResponseBody('OC-WO-0002#orders_wholesaler_orders', trans('custom.not_authorized'), false, 400));
        }

        $offset = 0;
        $limit  = Helper::WHOLESALER_PRODUCT_LIST_LIMIT;
        $page   = isset($request->page)?$request->page:0;
        if ($page > 0) {
            $offset = ($limit * $page);
        }

        $orderDetail = OrderDetail::select('id','vendor_id','status','created_at','order_id')->where('vendor_id',$userData->id)->where('status','P')->orderBy('created_at','desc')->get()->groupBy('order_id');
        $orderIds = array();
        foreach($orderDetail as $ODKey => $ODVal){
            array_push($orderIds,$ODKey);
        }

        $order = Order::whereIn('id', $orderIds)->where('type','O');

        $order = $order->orderBy('created_at','desc');
        $orderCount = $order->count();
        $order = $order->skip($offset)
        ->take($limit)->get();
           
        //dd($order);
        $orderList = [];
        $baseUrl            = asset('');
        $productsUrl        = 'uploads/products/thumbs/';
        $productImageUrl    = $baseUrl.$productsUrl;
        foreach($order as $key => $val){
            
            $orderVal['order_id'] = $val->id;
            // $orderVal['order_detail_id'] = Helper::customEncryptionDecryption($val[0]->id,'encrypt');
            if($val){
                $orderVal['order_code'] = $val->unique_order_id;
                $orderVal['order_date'] = $val->purchase_date ? date('dS M, Y', strtotime($val->purchase_date)) : '';
                $orderVal['shipping_customer_name'] = $val->shipping_full_name ? $val->shipping_full_name : '';
                $orderVal['shipping_phone_number'] = $val->shipping_phone_number ? $val->shipping_phone_number : '';
                $orderVal['shipping_city'] = $val->shipping_city ? $val->shipping_city : '';
                $orderVal['shipping_state'] = $val->shipping_state ? $val->shipping_state : '';
                $orderVal['shipping_zipcode'] = $val->shipping_zipcode ? $val->shipping_zipcode : '';
                $orderVal['shipping_address'] = $val->shipping_address ? $val->shipping_address : '';
                $orderVal['shipping_landmark'] = $val->shipping_landmark ? $val->shipping_landmark : '';
            }else{
                $orderVal['order_code'] = '';
                $orderVal['order_date'] = '';
                $orderVal['shipping_customer_name'] = '';
                $orderVal['shipping_phone_number'] = '';
                $orderVal['shipping_city'] = '';
                $orderVal['shipping_state'] = '';
                $orderVal['shipping_zipcode'] = '';
                $orderVal['shipping_address'] = '';
                $orderVal['shipping_landmark'] = '';
            }
            $totalPrice = 0;
            $quantity = 0;
            foreach($val->orderDetails as $key1 => $val1){ 
                $totalPrice += $val1->total_price;
                $quantity += $val1->quantity;
            }
            $orderVal['total_price'] = (string)$totalPrice;
            $orderVal['quantity'] = (string)$quantity;
            $orderList[] = $orderVal;
            unset($orderVal);
        
        }
        // dd( $orderList);
        return \Response::json(ApiHelper::generateResponseBody('OC-WO-0003#orders_wholesaler_orders', ['orders_list' => $orderList,'total_orders'=>$orderCount]));
    }

    /*****************************************************/
    # OrderController
    # Function name : wholesalerOrderDetail
    # Author        :
    # Created Date  : 7-08-2019
    # Purpose       : Wholesaler order details
    # Params        : Request $request
    /*****************************************************/
    public function wholesalerOrderDetail(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('OC-WOD-0001#orders_wholesaler_order_detail',  trans('custom.not_authorized'), false, 400));
        }

        if($userData->is_wholesaler != 1){
            return \Response::json(ApiHelper::generateResponseBody('OC-WOD-0002#orders_wholesaler_order_detail', trans('custom.not_authorized'), false, 400));
        }
        
        $order_id = $request->order_id;
        $val = Order::where('id',$order_id)->with([
            'orderDetails'=> function($query) use ($lang,$userData) {
                $query->where('vendor_id', $userData->id);
                $query->with([
                    'order',
                    'orderDetailLocals'=> function($query) use ($lang) {
                         $query->where('lang_code','=', $lang);
                     },
                     'orderShippingDetails',
                     
                 ]);
            },
            'orderAppliedCoupon'
        ])
        ->first();
        // dd($val->orderDetails);
        $totalCartPrice = 0.00;
        $totalVat = 0.00;
        if($val){
            $orderVal = [];
            $baseUrl            = asset('');
            $productsUrl        = 'uploads/products/thumbs/';
            $productImageUrl    = $baseUrl.$productsUrl;
            $orderVal['order_id'] = $val->id;
            
            if($val){
                $orderVal['order_code'] = $val->unique_order_id;
                $orderVal['order_date'] = $val->purchase_date ? date('dS M, Y', strtotime($val->purchase_date)) : '';
                $orderVal['payment_method'] = $val->payment_method;
                $orderVal['shipping_customer_name'] = $val->shipping_full_name ? $val->shipping_full_name : '';
                $orderVal['shipping_phone_number'] = $val->shipping_phone_number ? $val->shipping_phone_number : '';
                $orderVal['shipping_city'] = $val->shipping_city ? $val->shipping_city : '';
                $orderVal['shipping_state'] = $val->shipping_state ? $val->shipping_state : '';
                $orderVal['shipping_zipcode'] = $val->shipping_zipcode ? $val->shipping_zipcode : '';
                $orderVal['shipping_address'] = $val->shipping_address ? $val->shipping_address : '';
                $orderVal['shipping_landmark'] = $val->shipping_landmark ? $val->shipping_landmark : '';
                $orderVal['coupon_applied'] = $val->orderAppliedCoupon ? true : false;
            }else{
                $orderVal['order_code'] = '';
                $orderVal['order_date'] = '';
                $orderVal['payment_method'] = '';
                $orderVal['shipping_customer_name'] = '';
                $orderVal['shipping_phone_number'] = '';
                $orderVal['shipping_city'] = '';
                $orderVal['shipping_state'] = '';
                $orderVal['shipping_zipcode'] = '';
                $orderVal['shipping_address'] = '';
                $orderVal['shipping_landmark'] = '';
                $orderVal['coupon_applied'] = '';
            }
            $orderVal['orderDetail'] = array();
            foreach($val->orderDetails as $key1 => $val1){
                $orderDetail['order_detail_id'] = $val1->id;
                if($val1->orderDetailLocals->count()){
                    $orderDetail['product_title'] = $val1->orderDetailLocals[0]->local_product_name;
                    $orderDetail['unit_name'] = $val1->orderDetailLocals[0]->local_unit_name;
                }else{
                    $orderDetail['product_title'] = '';
                    $orderDetail['unit_name'] = '';
                }
        
                if (isset($val1->productVendor->productDefaultImage) && $val1->productVendor->productDefaultImage != null) {
                    if (count($val1->productVendor->productDefaultImage)) {
                        $productImage = $val1->productVendor->productDefaultImage[0]->image_name;
                        $orderDetail['product_image'] = $productImageUrl.$productImage;
                    }else{
                        $orderDetail['product_image'] = '';
                    }
                }else{
                    $orderDetail['product_image'] = '';
                }
                
                $orderDetail['total_price'] = $val1->total_price ? (string)$val1->total_price : '';
                $orderDetail['product_selling_price'] = $val1->product_selling_price ? (string)$val1->product_selling_price : '';
                $orderDetail['quantity'] = $val1->quantity? (string)$val1->quantity : '';
                $orderDetail['vat_price'] = $val1->vat_price? (string)$val1->vat_price : '';
                $orderDetail['order_status'] = $val1->order_status;
                $orderDetail['status'] = $val1->status;
                $orderDetail['expected_delivery'] = $val1->expected_delivery ? date('dS M, Y', $val1->expected_delivery) : '';
                $orderVal['orderDetail'][$key1] = $orderDetail;
                $vatAmount = 0;
                if ($val1->vat_price != null) {
                    $vatAmount += $val1->vat_price * $val1->quantity;
                }
                //Total price
                $totalCartPrice += $val1->total_price;
                $totalVat += $vatAmount;

                unset($orderDetail);
            }
            $orderVal['totalCartPrice'] = $totalCartPrice;
            $orderVal['totalVat'] = $totalVat;
            // $orderVal['order_shipping_details'] = $val->orderShippingDetails;
                
            
            // dd( $orderVal);
            return \Response::json(ApiHelper::generateResponseBody('OC-WOD-0003#orders_wholesaler_order_detail', $orderVal));
        }else{
            return \Response::json(ApiHelper::generateResponseBody('OC-WOD-0004#orders_wholesaler_order_detail', trans('custom.order_not_found'),false,400));
        }
        
    }

    /*****************************************************/
    # OrderController
    # Function name : wholesalerOrderStatusChange
    # Author        :
    # Created Date  : 12-08-2019
    # Purpose       : Wholesaler order details status change
    # Params        : Request $request
    /*****************************************************/
    public function wholesalerOrderStatusChange(Request $request)
    {

        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('OC-WOSC-0001#orders_wholesaler_order_status_change',  trans('custom.not_authorized'), false, 400));
        }

        if($userData->is_wholesaler != 1){
            return \Response::json(ApiHelper::generateResponseBody('OC-WOSC-0002#orders_wholesaler_order_status_change', trans('custom.not_authorized'), false, 400));
        }

        $status = $request->status;
        $orderDetailId = $request->orderDetailId;
        $expectedDate = $request->expected_date;
        $comment = $request->comment;
        if($status && $orderDetailId && $expectedDate){
            $orderDetailId = $orderDetailId;
            $orderDetail = OrderDetail::find($orderDetailId);
            if($orderDetail){
                if($status == 'O' || $status == 'P' || $status == 'S' || $status == 'D'){


                    if ($orderDetail->order_status == 'O') {
                        if ($status == 'P' || $status == 'S' || $status == 'D') {
                            //proceed
                        } else {
                            return \Response::json(ApiHelper::generateResponseBody('OC-WOSC-0007#orders_wholesaler_order_status_change', trans('custom.please_update_order_status'), false, 400));
                        }
                    } else if ($orderDetail->order_status == 'P') {
                        if ($status == 'S' || $status == 'D') {
                            //proceed
                        } else {
                            return \Response::json(ApiHelper::generateResponseBody('OC-WOSC-0008#orders_wholesaler_order_status_change', trans('custom.please_update_order_status'), false, 400));
                        }
                    } else if ($orderDetail->order_status == 'S') {
                        if ($status == 'D') {
                            //proceed
                        } else {
                            return \Response::json(ApiHelper::generateResponseBody('OC-WOSC-0009#orders_wholesaler_order_status_change', trans('custom.please_update_order_status'), false, 400));
                        }
                    }else{
                        return \Response::json(ApiHelper::generateResponseBody('OC-WOSC-00010#orders_wholesaler_order_status_change', trans('custom.order_has_already_been_delivered'), false, 400));
                    }


                    $orderDetail->order_status = $status;
                    $orderDetail->expected_delivery = strtotime($expectedDate);
                    if( $orderDetail->save()){

                        NotificationHelper::sendNotificationOrderStatus($orderDetail);

                        $orderShippingDetail = new OrderShippingDetail;
                        $orderShippingDetail->order_id = $orderDetail->order_id;
                        $orderShippingDetail->order_details_id = $orderDetail->id;
                        $orderShippingDetail->order_status = $status;
                        $orderShippingDetail->comment = $comment;
                        
                        $orderShippingDetail->save();
                        $checkAllDelivered = OrderDetail::select('id','order_status','order_id','status')->where('order_id',$orderDetail->order_id)->where('status','P')->where('order_status','D')->get();
                        
                        $orderDetailCount = OrderDetail::where('order_id',$orderDetail->order_id)->where('status','P')->get();
                        
                        if($checkAllDelivered->count() == $orderDetailCount->count()){
                            Order::where('id', $orderDetail->order_id)
                            ->update(['status' => "D"]);
                        }
                        return \Response::json(ApiHelper::generateResponseBody('OC-WOSC-0003#orders_wholesaler_order_status_change', trans('custom.order_status_updated')));
                    }else{
                        return \Response::json(ApiHelper::generateResponseBody('OC-WOSC-0004#orders_wholesaler_order_status_change', trans('custom.something_went_wrong'), false, 400));
                    }
                }else{
                    return \Response::json(ApiHelper::generateResponseBody('OC-WOSC-0005#orders_wholesaler_order_status_change', trans('custom.something_went_wrong'), false, 400));
                }
                
            }else{
                return \Response::json(ApiHelper::generateResponseBody('OC-WOSC-0006#orders_wholesaler_order_status_change', trans('custom.something_went_wrong'), false, 400));
            }
        }else{
            return \Response::json(ApiHelper::generateResponseBody('OC-WOSC-0006#orders_wholesaler_order_status_change', trans('custom.something_went_wrong'), false, 400));
        }
    }

    /*****************************************************/
    # OrderController
    # Function name : reportOverview
    # Author        :
    # Created Date  : 14-08-2019
    # Purpose       : Wholesaler order report overview
    # Params        : Request $request
    /*****************************************************/
    public function reportOverview(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('OC-RO-0001#orders_report_overview',  trans('custom.not_authorized'), false, 400));
        }

        if($userData->is_wholesaler != 1){
            return \Response::json(ApiHelper::generateResponseBody('OC-RO-0002#orders_report_overview', trans('custom.not_authorized'), false, 400));
        }

        $order = 0;
        $sales = 0.00;
        $completedOrders = 0;
        $pendingOrder = 0;
        $canceledOrder = 0;
        $orderDetail = OrderDetail::select('id','vendor_id','status','created_at','order_id','total_price','order_status')->where('vendor_id',$userData->id)->where('order_status','!=','IC');
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        if($fromDate && $toDate){
            $orderDetail = $orderDetail->whereHas('order', function($q) use($fromDate,$toDate){
                $q->where('purchase_date', '>=', $fromDate);
                $q->where('purchase_date', '<=', $toDate);
            })->get();
        }else{
            $orderDetail = $orderDetail->get();
        }
        
        
        if($orderDetail->count()){
            $order = $orderDetail->count();
            $co = 0;
            $po = 0;
            $cano = 0;
            foreach($orderDetail as $key => $val){
                
                if( $val->order_status ==  'D'){
                    $co++;
                    $sales += $val->total_price;
                }else{
                    if($val->status !=  'C'){
                        $po++;
                    }
                    
                }
                if($val->status ==  'C'){
                    $cano++;
                }
            }
            $completedOrders = $co;
            $pendingOrder = $po;
            $canceledOrder = $cano;
        }
        

        $orderArray = array(
            'order' => $order,
            'sales' => $sales,
            'completedOrders' => $completedOrders,
            'pendingOrder' => $pendingOrder,
            'canceledOrder' => $canceledOrder

        );
        return \Response::json(ApiHelper::generateResponseBody('OC-RO-0004#orders_report_overview', $orderArray));
        
    }

    /*****************************************************/
    # OrderController
    # Function name : placeOrder
    # Author        :
    # Created Date  : 21-08-2019
    # Purpose       : checkout details with log checking
    # Params        : Request $request
    /*****************************************************/
    public function placeOrder(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        $userData  = ApiHelper::getUserFromHeader($request);
        if(!$userData){
            return \Response::json(ApiHelper::generateResponseBody('OC-PO-0001#orders_place_order',  trans('custom.not_authorized'), false, 400));
        }        

        $cartDetails = ApiHelper::getCartItemDetails($userData,$lang);
        if ($request->isMethod('POST')) {
            $validator = Validator::make($request->all(),
                [
                    'billing_full_name' => 'required',
                    'billing_contact_number' => 'required',
                    'billing_city' => 'required',
                    'billing_state' => 'required',
                    'billing_zipcode' => 'required',
                    'billing_street_address' => 'required',
                    'billing_landmark' => 'required',
                    'shipping_full_name' => 'required',
                    'shipping_contact_number'=> 'required',
                    'shipping_city' => 'required',
                    'shipping_state' => 'required',
                    'shipping_zipcode' => 'required',
                    'shipping_street_address' => 'required',
                    'shipping_landmark' => 'required',
                    'payment_type' => 'required',
                ],
                [
                    'billing_full_name.required'      => trans('custom.full_name_required'),
                    'billing_contact_number.required' => trans('custom.mobile_number_required'),
                    'billing_city.required'           => trans('custom.update_billing_city'),
                    'billing_state.required'          => trans('custom.update_billing_state'),
                    'billing_zipcode.required'        => trans('custom.update_billing_zipcode'),
                    'billing_street_address.required' => trans('custom.billing_street_address'),
                    'billing_landmark.required'       => trans('custom.update_billing_landmark'),
                    'shipping_full_name.required'     => trans('custom.full_name_required'),
                    'shipping_contact_number.required' => trans('custom.mobile_number_required'),
                    'shipping_city.required'           => trans('custom.update_shipping_city'),
                    'shipping_state.required'          => trans('custom.update_shipping_state'),
                    'shipping_zipcode.required'        => trans('custom.update_shipping_zipcode'),
                    'shipping_street_address.required' => trans('custom.shipping_street_address'),
                    'shipping_landmark.required'       => trans('custom.update_shipping_landmark'),
                    'payment_type.required'       => trans('custom.payment_mode_required'),
                ]
            );
            $errors = $validator->errors()->all();
            if ($errors) {
                return \Response::json(ApiHelper::generateResponseBody('OC-PO-0002#orders_place_order', ["errors" => $errors], false, 100));
            } else {
                $userId = $userData->id;
                $conditions = [];
                $conditions = ['user_id' => $userId, 'type' => 'C', 'order_status' => 'IC'];
                $orderUpdate = Order::where($conditions)->first();
                if($orderUpdate){
                    
                
                    if ($request->billing_address_check == '1' || $request->shipping_address_check == '1') {
                        if ($request->billing_address_check == '1') {
                            $userAddress = new UserAddress;
                            $userAddress->user_id = $userId;
                            $userAddress->latitude = null;
                            $userAddress->longitude = null;
                            $userAddress->address_type = 2;
    
                            $userAddress->full_name = $request->billing_full_name;
                            
                            $userAddress->phone_no = $request->billing_contact_number;
                            
                            $userAddress->city_id = $request->billing_city;
                            $userAddress->state_id = $request->billing_state;
                            $userAddress->zip_code = $request->billing_zipcode;
                            $userAddress->street_address = $request->billing_street_address;
                            $userAddress->land_mark = $request->billing_landmark;
                            
                            $userAddress->save();
    
                        }
    
                        if ($request->shipping_address_check == '1' ) {
                            $userAddress = new UserAddress;
                            $userAddress->user_id = $userId;
                            $userAddress->latitude = null;
                            $userAddress->longitude = null;
                            $userAddress->address_type = 1;
    
                            $userAddress->full_name = $request->shipping_full_name;
                            
                            $userAddress->phone_no = $request->shipping_contact_number;
                            
                            $userAddress->city_id = $request->shipping_city;
                            $userAddress->state_id = $request->shipping_state;
                            $userAddress->zip_code = $request->shipping_zipcode;
                            $userAddress->street_address = $request->shipping_street_address;
                            $userAddress->land_mark = $request->shipping_landmark;
                            
                            $userAddress->save();
    
                          
                        } 
                    }
                    
                    $orderUpdate->payment_method = $request->payment_type;
                    $orderUpdate->payment_status = 'C';
                    $orderUpdate->type = 'O';
                    $orderUpdate->order_status = 'O';
                    $orderUpdate->transaction_id = $request->transaction_id;
                    // $orderUpdate->transaction_response = $request->transaction_response;
                    //billing details fill up
                    $orderUpdate->billing_full_name = $request->billing_full_name;
                    
                    $orderUpdate->billing_phone_number = $request->billing_contact_number;
                    
                    $stateCheck = State::where('id', $request->billing_state)->first();
                    if ($stateCheck) {
                        $stateName = $stateCheck->name;
                    } else {
                        $stateName = null;
                    }
                    
                    $cityCheck = City::where('id', $request->billing_city)->first();
                    if ($cityCheck) {
                        $cityName = $cityCheck->name;
                    } else {
                        $cityName = null;
                    }
    
                    $orderUpdate->billing_city = $cityName;
                    $orderUpdate->billing_state = $stateName;
                    $orderUpdate->billing_zipcode = $request->billing_zipcode;
                    $orderUpdate->billing_address = $request->billing_street_address;
                    $orderUpdate->billing_landmark = $request->billing_landmark;
    
                    //shipping details fill up
                    
                    $orderUpdate->shipping_full_name = $request->shipping_full_name;
                   
                    
                    $orderUpdate->shipping_phone_number = $request->shipping_contact_number;
                    
                    $stateCheckShipping = State::where('id', $request->shipping_state)->first();
                    if ($stateCheckShipping) {
                        $stateName = $stateCheckShipping->name;
                    } else {
                        $stateName = null;
                    }
    
                    $cityCheckShipping = City::where('id', $request->shipping_city)->first();
                    if ($cityCheckShipping) {
                        $cityName = $cityCheckShipping->name;
                    } else {
                        $cityName = null;
                    }
    
                    $orderUpdate->shipping_city = $cityName;
                    $orderUpdate->shipping_state = $stateName;
                    $orderUpdate->shipping_zipcode = $request->shipping_zipcode;
                    $orderUpdate->shipping_address = $request->shipping_street_address;
                    $orderUpdate->shipping_landmark = $request->shipping_landmark;
                    $orderUpdate->purchase_date = now();
                    $orderUpdate->delivery_note = $request->shipping_delivery_note;
                    $orderUpdate->save();
    
                    $orderUniqueId = $orderUpdate->unique_order_id;
    
                    // update coupon table
                    
                    if (Helper::formatToTwoDecimalPlaces($cartDetails['couponAmount']) > 0) {
                        $couponId = $cartDetails['orderCoupon']['id'];
                        $couponData = OrderAppliedCoupon::where('id', $couponId)->first();
                        $couponData->coupon_discount_amount = Helper::formatToTwoDecimalPlaces($cartDetails['couponAmount']);
                        $couponData->save();
                    }
                    //update order details table
                    OrderDetail::where('order_id', $orderUpdate->id)->update(array('order_status' => 'O'));
    
                    //add to push notification
                    NotificationHelper::sendNotificationOrder($orderUpdate);
                    //add to push notification
    
                    //getting all update order details
                    $orderDetails  = [];
                    $orderDetails['order_id']         = $orderUpdate->id;
                    $orderDetails['unique_order_id']  = $orderUpdate->unique_order_id;
                    $orderDetails['payment_method']   = $orderUpdate->payment_method;
                    $orderDetails['payment_status']   = $orderUpdate->payment_status;
                    $orderDetails['order_status']           = $orderUpdate->order_status;
                    $orderDetails['billing_full_name']      = $orderUpdate->billing_full_name;
                    $orderDetails['billing_phone_number']   = $orderUpdate->billing_phone_number;
                    $orderDetails['billing_city']           = $orderUpdate->billing_city;
                    $orderDetails['billing_state']          = $orderUpdate->billing_state;
                    $orderDetails['billing_zipcode']        = $orderUpdate->billing_zipcode;
                    $orderDetails['billing_address']        = $orderUpdate->billing_address;
                    $orderDetails['billing_landmark']       = $orderUpdate->billing_landmark;
                    $orderDetails['shipping_full_name']     = $orderUpdate->shipping_full_name;
                    $orderDetails['shipping_phone_number']  = $orderUpdate->shipping_phone_number;
                    $orderDetails['shipping_city']          = $orderUpdate->shipping_city;
                    $orderDetails['shipping_state']         = $orderUpdate->shipping_state;
                    $orderDetails['shipping_zipcode']       = $orderUpdate->shipping_zipcode;
                    $orderDetails['shipping_address']       = $orderUpdate->shipping_address;
                    $orderDetails['shipping_landmark']      = $orderUpdate->shipping_landmark;
                    $orderDetails['purchase_date']          = $orderUpdate->purchase_date;
                    $orderDetails['delivery_note']          = $orderUpdate->delivery_note;
                    $orderDetails['status']                 = $orderUpdate->status;
                    $orderDetails['couponAmount']           = Helper::formatToTwoDecimalPlaces($cartDetails['couponAmount']);
    
                    //get all orders for mail
                    $allPlacedOrder =  OrderDetail::where('order_id', $orderUpdate->id)
                                                    ->with([
                                                        'orderDetailLocals' => function($query) use ($lang){
                                                            $query->where('lang_code','=', $lang);
                                                        }
                                                    ])
                                                    ->get();
                    
                    $siteSetting = SiteSetting::first();
                   
                    //mail to contact person
                    if ($userData->email != null) {
                        \Mail::send('email_templates.site.thanks_for_order',
                            [
                                'user'           => $userData,
                                'orderDetails'   => $orderDetails,
                                'allPlacedOrder' => $allPlacedOrder,      
                                'thanks_order' => [
                                    'appname'           => $siteSetting->website_title,
                                    'appLink'           => Helper::getBaseUrl(),
                                    'controllerName'    => 'users.orders.order-detail',
                                    'currentLang'       => $lang,
                                    'headerTitle'       => 'Thanks to send order'    
                                    
                                ],
                            ], function ($m) use ($userData) {                            
                                $m->to($userData->email, $userData->full_name)->subject('Thanks You for placing order');
                            });
                    }                
                    
                    //mail to admin for order response
                    \Mail::send('email_templates.site.response_to_order',
                        [
                            'user'           => $userData,
                            'orderDetails'   => $orderDetails,
                            'allPlacedOrder' => $allPlacedOrder, 
                            'response_order' => [
                                'appname'           => $siteSetting->website_title,
                                'appLink'           => Helper::getBaseUrl(),
                                'controllerName'    => 'users.orders.order-detail',
                                'currentLang'       => $lang,
                                'headerTitle'       => 'Thanks to send order'   
                            ],
                        ], function ($m) use ($siteSetting) {
                            $m->to($siteSetting->to_email, $siteSetting->website_title)->subject('Response to Order');
                        });
    
                    return \Response::json(ApiHelper::generateResponseBody('OC-PO-0003#orders_place_order', trans('custom.order_placed')));
                }else{
                    return \Response::json(ApiHelper::generateResponseBody('OC-PO-0004#orders_place_order', trans('custom.something_went_wrong'), false, 400));
                }
                
                
                
            }
        }       
        
    }
}
