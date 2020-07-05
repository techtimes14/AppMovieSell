<?php
/*****************************************************/
# Page/Class name   : Helper
# Purpose           : for global purpose
/*****************************************************/
namespace App\Http\Helpers;

use Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\NotificationHelper;
use App\Category;
use App\Cms;
use App\SiteSetting;
use App\Banner;
use App\Order;
use App\OrderDetail;
use App\Allergen;
use App\OrderAttributeLocal;
use App\DeliverySlot;
use App\OrderReview;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Helper
{
    public const NO_IMAGE = 'no-image.png'; // No image

    public const WEBSITE_DEFAULT_LANGUAGE = 'en';

    public const WEBITE_LANGUAGES = ['en', 'de']; // Admin language array

    public const UPLOADED_DOC_FILE_TYPES = ['doc', 'docx', 'xls', 'xlsx', 'pdf', 'txt', 'ods', 'odp', 'odt']; //Uploaded document file types

    public const UPLOADED_IMAGE_FILE_TYPES = ['jpeg', 'jpg', 'png', 'svg']; //Uploaded image file types

    public const PROFILE_IMAGE_MAX_UPLOAD_SIZE = 5120; // profile image upload max size (5mb)

    public const IMAGE_MAX_UPLOAD_SIZE = 5120; // image upload max size (5mb)

    public const MINIMUM_ORDER_AMOUNT = 20; // Minimum order amount
    
    public const PROFILE_THUMB_IMAGE_WIDTH = '100'; // Profile image width

    public const PROFILE_THUMB_IMAGE_HEIGHT = '100'; // Profile image height
    
    public const MY_ORDER_LISTING = 5; // My orders

    
    /*****************************************************/
    # Function name : getAppName
    # Params        :
    /*****************************************************/
    public static function getAppName()
    {
        //$getAppName = env('APP_NAME');
        $siteSettings = self::getSiteSettings();
        $appName = $siteSettings->website_title;
        return $appName;
    }

    /*****************************************************/
    # Function name : getAppNameFirstLetters
    # Params        :
    /*****************************************************/
    public static function getAppNameFirstLetters()
    {
        $siteSettings = self::getSiteSettings();
        $getAppName = $siteSettings->website_title;
        $explodedAppNamewords = explode(' ', $getAppName);
        $appLetters = '';
        foreach ($explodedAppNamewords as $letter) {
            $appLetters .= $letter[0];
        }
        return $appLetters;
    }

    /*****************************************************/
    # Function name : generateUniqueSlug
    # Params        : $model, $slug (name/title), $id
    /*****************************************************/
    public static function generateUniqueSlug($model, $slug, $id = null)
    {
        $slug = Str::slug($slug);
        $currentSlug = '';
        if ($id) {
            $currentSlug = $model->where('id', '=', $id)->value('slug');
        }

        if ($currentSlug && $currentSlug === $slug) {
            return $slug;
        } else {
            $slugList = $model->where('slug', 'LIKE', $slug . '%')->pluck('slug');
            if ($slugList->count() > 0) {
                $slugList = $slugList->toArray();
                if (!in_array($slug, $slugList)) {
                    return $slug;
                }
                $newSlug = '';
                for ($i = 1; $i <= count($slugList); $i++) {
                    $newSlug = $slug . '-' . $i;
                    if (!in_array($newSlug, $slugList)) {
                        return $newSlug;
                    }
                }
                return $newSlug;
            } else {
                return $slug;
            }
        }
    }

    /*****************************************************/
    # Function name : getSiteSettings
    # Params        :
    /*****************************************************/
    public static function getSiteSettings()
    {
        $siteSettingData = SiteSetting::first();
        return $siteSettingData;
    }

    /*****************************************************/
    # Function name : getBaseUrl
    # Params        :
    /*****************************************************/
    public static function getBaseUrl()
    {
        $baseUrl = url('/');
        return $baseUrl;
    }

    /*****************************************************/
    # Function name : getRolePermissionPages
    # Params        :
    /*****************************************************/
    public static function getRolePermissionPages()
    {
        $routePermissionArray = [];
        
        if (Auth::guard('admin')->user()->id != '') {
            if (Auth::guard('admin')->user()->role_id != 1) {
                $userRolePermission = Auth::guard('admin')->user()->allRolePermissionForUser;
                if (count($userRolePermission) > 0) {
                    foreach ($userRolePermission as $permission) {
                        if ($permission->page != null) {
                            $routePermissionArray[] = $permission->page->routeName;
                        }
                    }
                }
            }
        }
        return $routePermissionArray;
    }

    /*****************************************************/
    # Function name : formattedDate
    # Params        : $getDate
    /*****************************************************/
    public static function formattedDate($getDate = null)
    {
        $formattedDate = date('dS M, Y');
        if ($getDate != null) {
            $formattedDate = date('dS M, Y', strtotime($getDate));
        }
        return $formattedDate;
    }

    /*****************************************************/
    # Function name : formattedDateTime
    # Params        : $getDateTime = unix timestamp
    /*****************************************************/
    public static function formattedDateTime($getDateTime = null)
    {
        $formattedDateTime = '';
        if ($getDateTime != null) {
            $formattedDateTime = date('dS M, Y H:i', $getDateTime);
        }
        return $formattedDateTime;
    }

    /*****************************************************/
    # Function name : formattedDatefromTimestamp
    # Params        : $getDateTime = unix timestamp
    /*****************************************************/
    public static function formattedDatefromTimestamp($getDateTime = null)
    {
        $formattedDateTime = '';
        if ($getDateTime != null) {
            $formattedDateTime = date('dS M, Y', $getDateTime);
        }
        return $formattedDateTime;
    }

    /*****************************************************/
    # Function name : formattedTimestamp
    # Params        : $getDateTime = unix timestamp
    /*****************************************************/
    public static function formattedTimestamp($getDateTime = null)
    {
        $timestamp = '';
        if ($getDateTime != null) {
            $timestamp = \Carbon\Carbon::createFromFormat('m/d/Y', $getDateTime)->timestamp;
        }
        return $timestamp;
    }

    /*****************************************************/
    # Function name : formattedTimestampBid
    # Params        : $getDateTime = unix timestamp
    /*****************************************************/
    public static function formattedTimestampBid($getDateTime = null)
    {
        $timestamp = '';
        if ($getDateTime != null) {
            $timestamp = date('Y-m-d H:i:s', $getDateTime);
        }
        return $timestamp;
    }

    /*****************************************************/
    # Function name : differnceBtnTimestampDateFrmCurrentDateInDays
    # Params        : $getDate = null
    /*****************************************************/
    public static function differnceBtnTimestampDateFrmCurrentDateInDays($getDate = null)
    {
        $days = '';
        if ($getDate != null) {
            $currentDate = date('Y-m-d');
            $diff   = abs($getDate - strtotime($currentDate));
            $years  = floor($diff / (365*60*60*24)); 
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
            $days   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

            if ($getDate < strtotime($currentDate)) {
                $days = '-'.$days;
            } else {
                $days = '+'.$days;
            }

        }
        return $days;
    }

    /*****************************************************/
    # Function name : getData
    # Params        :
    /*****************************************************/
    public static function getData($table = 'SiteSetting', $where = '')
    {
        if ($table == 'cms') {
            $metaData = Cms::where('id', $where)->first();
        } else {
            $metaData = SiteSetting::first();
        }
        return $metaData;
    }

    /*****************************************************/
    # Function name : customEncryptionDecryption
    # Params        :
    /*****************************************************/
    public static function customEncryptionDecryption($string, $action = 'encrypt')
    {
        $secretKey = 'c7tpe291z';
        $secretVal = 'GfY7r512';
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secretKey);
        $iv = substr(hash('sha256', $secretVal), 0, 16);

        if ($action == 'encrypt') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    /*****************************************************/
    # Function name : formatToTwoDecimalPlaces
    # Params        : $data
    /*****************************************************/
    public static function formatToTwoDecimalPlaces($data)
    {
        return number_format((float)$data, 2, '.', '');
    }
    
    /*****************************************************/
    # Function name : generateCsv
    # Params        : 
    /*****************************************************/
    public static function generateCsv($columnNames, $dataToPrint, $fileName) {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $fileName,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        $callback = function() use ($columnNames, $dataToPrint ) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columnNames);
            foreach ($dataToPrint as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    /*****************************************************/
    # Function name : cleanString
    # Params        : $content
    /*****************************************************/
    public static function cleanString($content) {
        $content = preg_replace("/&#?[a-z0-9]+;/i","",$content); 
        $content = preg_replace("/[\n\r]/","",$content);
        $content = strip_tags($content);
        return $content;
    }

    /*****************************************************/
    # Function name : getMetaData
    # Params        :
    /*****************************************************/
    public static function getMetaData($table = 'SiteSetting', $where = '')
    {
        $currentLang = \App::getLocale();
        if ($table == 'cms') {
            $metaData = Cms::where('slug', $where)
                            ->with([
                                'local'=> function($query) use ($currentLang) {
                                    $query->where('lang_code','=', $currentLang);
                                }
                            ])
                            ->first();
            $return['title'] = $metaData['name'];
            $return['keyword'] = $metaData['meta_keyword'];
            $return['description'] = $metaData['meta_description'];
            $return['local_description'] = $metaData->local[0]->description;
            return $return;
        } else {
            $metaData = SiteSetting::select('default_meta_title', 'default_meta_keywords', 'default_meta_description')->first();
            $return['title'] = $metaData['default_meta_title'];
            $return['keyword'] = $metaData['default_meta_keywords'];
            $return['description'] = $metaData['default_meta_description'];
            return $return;
        }
    }

    /*****************************************************/
    # Function name : generateUniquesOrderId
    # Params        :
    /*****************************************************/
    public static function generateUniquesOrderId()
    {
        $timeNow = date("his");
        $randNumber = strtoupper(substr(sha1(time()), 0, 4));
        return $unique = 'CDX' . $timeNow . $randNumber;
    }

    /*****************************************************/
    # Function name : getCartItemDetails
    # Params        :
    /*****************************************************/
    public static function getCartItemDetails()
    {
        $cartSessionId = '';
        if (Session::get('cartSessionId') != '') {
            $cartSessionId = Session::get('cartSessionId');
        }

        $cartUserId = 0; $totalCartCount = 0;
        $getCartData = array();
        if (Auth::user()) {
            $cartUserId = Auth::user()->id;
            $cartConditions = ['user_id' => $cartUserId, 'type' => 'C'];
        } else {
            $cartConditions = ['session_id' => $cartSessionId, 'type' => 'C'];
        }

        $getOrderDetails = Order::where($cartConditions)->first();
        // dd($getOrderDetails->orderDetails);

        $totalCartPrice = 0;
        $cartArray = array();
        $cartOrderId = 0;
        $paymentMethod = 0;
        $productExistFlag = 0;
        
        if ($getOrderDetails != null) {
            $cartOrderId = $getOrderDetails->id;
            $paymentMethod = $getOrderDetails->payment_method;
            // Main Cart array
            if (isset($getOrderDetails->orderDetails) && count($getOrderDetails->orderDetails) > 0) {
                $i = 0;
                foreach ($getOrderDetails->orderDetails as $orderDetails) {
                    $productImage = ''; $vatAmount = 0;
                    $cartArray[$i]['id']                = $orderDetails->id;
                    $cartArray[$i]['order_id']          = $orderDetails->order_id;
                    $cartArray[$i]['product_id']        = $orderDetails->product_id;
                    $cartArray[$i]['drink_id']          = $orderDetails->drink_id;
                    $cartArray[$i]['special_menu_id']   = $orderDetails->special_menu_id;
                    $cartArray[$i]['has_ingredients']   = $orderDetails->has_ingredients;
                    $cartArray[$i]['has_attribute']     = $orderDetails->has_attribute;
                    $cartArray[$i]['attribute_id']      = $orderDetails->attribute_id;
                    $cartArray[$i]['quantity']          = $orderDetails->quantity;
                    $cartArray[$i]['price']             = $orderDetails->price;
                    $cartArray[$i]['total_price']       = $orderDetails->total_price;
                    
                    // order product locals
                    if (count($orderDetails->orderDetailLocals) > 0) {
                        foreach ($orderDetails->orderDetailLocals as $detailLocal) {
                            $cartArray[$i]['local_details'][$detailLocal->lang_code]['local_title']     = $detailLocal->local_title;
                        }
                    } else {
                        $cartArray[$i]['local_details'] = [];
                    }

                    // ingredients locals
                    if (count($orderDetails->orderIngredients) > 0) {
                        foreach ($orderDetails->orderIngredients as $key => $orderIngredient) {
                            $cartArray[$i]['ingredient_local_details'][$key]['order_ingredient_id'] = $orderIngredient->id;
                            $cartArray[$i]['ingredient_local_details'][$key]['ingredient_id']       = $orderIngredient->ingredient_id;
                            $cartArray[$i]['ingredient_local_details'][$key]['product_id']          = $orderIngredient->product_id;
                            $cartArray[$i]['ingredient_local_details'][$key]['quantity']            = $orderIngredient->quantity;
                            $cartArray[$i]['ingredient_local_details'][$key]['price']               = $orderIngredient->price;
                            $cartArray[$i]['ingredient_local_details'][$key]['total_price']         = $orderIngredient->total_price;
                            foreach ($orderIngredient->orderIngredientLocals as $detailIngredientLocal) {
                                $cartArray[$i]['ingredient_local_details'][$key][$detailIngredientLocal->lang_code]['local_title'] = $detailIngredientLocal->local_ingredient_title;
                            }
                        }
                    } else {
                        $cartArray[$i]['ingredient_local_details'] = [];
                    }

                    // attributes locals
                    if ($orderDetails->has_attribute == 'Y') {
                        $orderAttributeLocalDetails = OrderAttributeLocal::where([
                                                                                'order_id'          => $orderDetails->order_id,
                                                                                'order_details_id'  => $orderDetails->id,
                                                                                'product_id'        => $orderDetails->product_id,
                                                                                'attribute_id'      => $orderDetails->attribute_id,
                                                                            ])
                                                                            ->get();
                        
                        foreach ($orderAttributeLocalDetails as $key => $detailAttributeLocal) {
                            $cartArray[$i]['attribute_local_details'][$detailAttributeLocal->lang_code]['local_title'] = $detailAttributeLocal->local_attribute_title;
                        }
                    } else {
                        $cartArray[$i]['attribute_local_details'] = [];
                    }                

                    if ($orderDetails->product_id != '') {
                        $productExistFlag = 1;
                    }

                    //Total price
                    $totalCartPrice += $orderDetails->total_price;
                    $i++;
                }
            }
        }
        // echo '<pre>'; print_r($cartArray); die;

        $totalCartCount = count($cartArray);
        $cartDetailArray = array(
                            'cartOrderId'       => $cartOrderId,
                            'productExist'      => $productExistFlag,
                            'itemDetails'       => $cartArray,
                            'totalItem'         => $totalCartCount,
                            'totalCartPrice'    => (float)self::formatToTwoDecimalPlaces($totalCartPrice),
                            'payableAmount'     => (float)self::formatToTwoDecimalPlaces($totalCartPrice),
                            'paymentMethod'     => $paymentMethod
                        );
        return $cartDetailArray;
    }
    
    /*****************************************************/
    # Function name : getAllergenList
    # Params        :
    /*****************************************************/
    public static function getAllergenList()
    {
        $currentLang = \App::getLocale();
        $allergenList   = Allergen::where(['status' => '1'])
									->whereNull('deleted_at')
									->with([
                                        'local'=> function($query) use ($currentLang) {
                                            $query->where('lang_code','=', $currentLang);
                                        }
                                    ])
									->orderBy('sort', 'asc')->get();
        
        return $allergenList;
    }

    /*****************************************************/
    # Function name : generateDeliverySlot
    # Params        :
    /*****************************************************/
    public static function generateDeliverySlot()
    {
        $siteSettings = self::getSiteSettings();
        $minimumDeliveryDelayTime = isset($siteSettings->min_delivery_delay) ? $siteSettings->min_delivery_delay : 0;

        $today = date('l');
        $shopOpenCloseTimeAccordingToDay = DeliverySlot::where('day_title', $today)->first();

        $slots = [];

        // If not holiday
        if ($shopOpenCloseTimeAccordingToDay->holiday == 0) {

            $currentTimeStamp   = strtotime(date('H:i'));
            // current time + minimum delivery delay = Delivery start time
            $deliveryStartTime = strtotime("+".$minimumDeliveryDelayTime." minutes", $currentTimeStamp);
            
            // Shop open and close time
            $shopOpenTime       = date('H:i', $shopOpenCloseTimeAccordingToDay->start_time);
            $shopCloseTime      = $shopOpenCloseTimeAccordingToDay->end_time;

            // Shop open Hour & minute
            $shopOpenTimeInHour     = date('H', $shopOpenCloseTimeAccordingToDay->start_time);
            $shopOpenTimeInMinute   = date('i', $shopOpenCloseTimeAccordingToDay->start_time);
            
            $remainder = $shopOpenTimeInMinute % 15;
            
            $remainingFromMinute = $shopOpenTimeInMinute - $remainder;            
            
            if ($remainingFromMinute / 15 == 0) {
                $slotStartTime = strtotime($shopOpenTimeInHour.':15');
            }
            else if ($remainingFromMinute / 15 == 1) {
                $slotStartTime = strtotime($shopOpenTimeInHour.':30');
            }
            else if ($remainingFromMinute / 15 == 2) {
                $slotStartTime = strtotime($shopOpenTimeInHour.':45');
            }
            else if ($remainingFromMinute / 15 == 3) {
                $slotStartTime = strtotime(($shopOpenTimeInHour+1).':00');
            }

            // slot break up
            if ($slotStartTime < $shopCloseTime) {
                for ($slotStartTime; $slotStartTime <= $shopCloseTime;) {
                    if ($slotStartTime > $deliveryStartTime) {
                        $slots[] = date('H:i', $slotStartTime);
                    }                   

                    $slotStartTime = strtotime("+15 minutes", $slotStartTime);
                }
            }

            // If slot 2 exist START
            if ($shopOpenCloseTimeAccordingToDay->start_time2 != null && $shopOpenCloseTimeAccordingToDay->end_time2 != null) {
                // Shop open and close time
                $shopOpenTime2      = date('H:i', $shopOpenCloseTimeAccordingToDay->start_time2);
                $shopCloseTime2     = $shopOpenCloseTimeAccordingToDay->end_time2;

                // Shop open Hour & minute
                $shopOpenTimeInHour2     = date('H', $shopOpenCloseTimeAccordingToDay->start_time2);
                $shopOpenTimeInMinute2   = date('i', $shopOpenCloseTimeAccordingToDay->start_time2);
                
                $remainder2 = $shopOpenTimeInMinute2 % 15;
                
                $remainingFromMinute2 = $shopOpenTimeInMinute2 - $remainder2;            
                
                if ($remainingFromMinute2 / 15 == 0) {
                    $slotStartTime2 = strtotime($shopOpenTimeInHour2.':15');
                }
                else if ($remainingFromMinute2 / 15 == 1) {
                    $slotStartTime2 = strtotime($shopOpenTimeInHour2.':30');
                }
                else if ($remainingFromMinute2 / 15 == 2) {
                    $slotStartTime2 = strtotime($shopOpenTimeInHour2.':45');
                }
                else if ($remainingFromMinute2 / 15 == 3) {
                    $slotStartTime2 = strtotime(($shopOpenTimeInHour2+1).':00');
                }            

                // slot break up
                if ($slotStartTime2 < $shopCloseTime2) {
                    for ($slotStartTime2; $slotStartTime2 <= $shopCloseTime2;) {
                        if ($slotStartTime2 > $deliveryStartTime) {
                            $slots[] = date('H:i', $slotStartTime2);
                        }                   

                        $slotStartTime2 = strtotime("+15 minutes", $slotStartTime2);
                    }
                }
            }
            // If slot 2 exist END
        }
        // dd($slots);

        return $slots;
    }

    /*****************************************************/
    # Function name : getOrderDetails
    # Params        :
    /*****************************************************/
    public static function getOrderDetails($orderId, $userId)
    {
        $lang = \App::getLocale();
        $totalAmount = 0;
        $orderVal = [];
        $cartConditions = ['user_id' => $userId, 'type' => 'O', 'id' => $orderId];

        $getOrderDetails = Order::where($cartConditions)->with([
                                                            'orderDetails' => function ($query) use ($lang) {
                                                                // $query->orderBy('created_at', 'desc');
                                                                $query->with([
                                                                    'orderDetailLocals' => function ($query) use ($lang) {
                                                                        $query->where('lang_code', '=', $lang);
                                                                    },
                                                                    'orderAttributeLocalDetails' => function ($query) use ($lang) {
                                                                        $query->where('lang_code', '=', $lang);
                                                                    },
                                                                    'orderIngredients' => function ($query) use ($lang) {
                                                                        $query->with([
                                                                            'orderIngredientLocals' => function ($query) use ($lang) {
                                                                                $query->where('lang_code', '=', $lang);
                                                                            }
                                                                        ]);
                                                                    },
                                                                ]);
                                                            },
                                                        ])
                                                        ->first();
        if ($getOrderDetails->orderDetails) {
            foreach ($getOrderDetails->orderDetails as $keyOrdDtls => $valOrdDtls) {
                $orderVal['product_details'][$keyOrdDtls]['title']              = $valOrdDtls->orderDetailLocals[0]->local_title;
                $orderVal['product_details'][$keyOrdDtls]['quantity']           = $valOrdDtls->quantity;
                $orderVal['product_details'][$keyOrdDtls]['price']              = $valOrdDtls->price;
                $orderVal['product_details'][$keyOrdDtls]['unit_total_price']   = $valOrdDtls->unit_total_price;
                $orderVal['product_details'][$keyOrdDtls]['total_price']        = $valOrdDtls->total_price;

                // Ingredients
                if ($valOrdDtls->has_ingredients == 'Y') {
                    foreach ($valOrdDtls->orderIngredients as $keyOrderIngredient => $valOrderIngredient) {
                        $orderVal['product_details'][$keyOrdDtls]['ingredients'][$keyOrderIngredient]['title'] = $valOrderIngredient->orderIngredientLocals[0]->local_ingredient_title;
                        $orderVal['product_details'][$keyOrdDtls]['ingredients'][$keyOrderIngredient]['quantity'] = $valOrderIngredient->quantity;
                        $orderVal['product_details'][$keyOrdDtls]['ingredients'][$keyOrderIngredient]['price'] = Helper::formatToTwoDecimalPlaces($valOrderIngredient->price);
                    }
                } else {
                    $orderVal['product_details'][$keyOrdDtls]['ingredients'] = [];
                }

                // Attributes
                if ($valOrdDtls->has_attribute == 'Y') {
                    $orderVal['product_details'][$keyOrdDtls]['attribute'] = $valOrdDtls->orderAttributeLocalDetails[0]->local_attribute_title;
                } else {
                    $orderVal['product_details'][$keyOrdDtls]['attribute'] = '';
                }

                $totalAmount += $valOrdDtls->total_price;
            }
        }

        $orderVal['total_price'] = Helper::formatToTwoDecimalPlaces($totalAmount);
        
        return $orderVal;
    }

    /*****************************************************/
    # Function name : gettingReviews
    # Params        :
    /*****************************************************/
    public static function gettingReviews()
    {        
        $getAllRating = new OrderReview;
        $allData      = $getAllRating->limit(5)->orderBy('created_at','desc')->get();
        $userReviewList = [];
        if ($allData->count() > 0) {
            foreach ($allData as $key => $data) {
                $userReviewList[$key]['full_name']        = $data->userDetails->full_name;
                $starRating = 0.0;
                if ($data->avg_rating == 0.5 || $data->avg_rating == 1.5 || $data->avg_rating == 2.5 || $data->avg_rating == 3.5 || $data->avg_rating == 4.5) {
                    $starRating   = $data->avg_rating;
                }
                else {                    
                    $starRating   = (float)number_format($data->avg_rating, 1);            
                }
                $userReviewList[$key]['avg_star_rating']  = $starRating;
                $userReviewList[$key]['short_review']     = $data->short_review;
                $userReviewList[$key]['reviewed_on']      = date('d/m/Y', strtotime($data->created_at));
            }
        }
        // dd($userReviewList);

        // All rating
        $sumOfAllRating     = $getAllRating->sum('avg_rating');
        $countOfAllRating   = $getAllRating->count();

        $avgAllRating = $starAvgAllRating = 0.0;
        if ($countOfAllRating > 0) {
            $avgAllRating       = $sumOfAllRating / $countOfAllRating;
            $starAvgAllRating   = $avgAllRating;
            if ($avgAllRating == 0.5 || $avgAllRating == 1.5 || $avgAllRating == 2.5 || $avgAllRating == 3.5 || $avgAllRating == 4.5) {
                $staravgAllRating   = $avgAllRating;
            }
            else {
                $starAvgAllRating   = round($avgAllRating);
                $avgOverallRating       = (float)number_format($avgAllRating, 1);            
            }
        }
        $rating['starAvgAllRating'] = $starAvgAllRating;
        $rating['avgAllRating']     = $avgAllRating;

        // Overall rating
        $getOverallRating       = OrderReview::whereDate('created_at', '>', Carbon::now()->subDays(90));
        $sumOfRating            = $getOverallRating->sum('avg_rating');
        $countOfOverallRating   = $getOverallRating->count();

        $avgOverallRating = $starAvgOverallRating = 0.0;
        if ($countOfOverallRating > 0) {
            $avgOverallRating       = $sumOfRating / $countOfOverallRating;
            $starAvgOverallRating   = $avgOverallRating;
            if ($avgOverallRating == 0.5 || $avgOverallRating == 1.5 || $avgOverallRating == 2.5 || $avgOverallRating == 3.5 || $avgOverallRating == 4.5) {
                $starAvgOverallRating   = $avgOverallRating;
            }
            else {
                $starAvgOverallRating   = round($avgOverallRating);
                $avgOverallRating       = (float)number_format($avgOverallRating, 1);            
            }
        }
        $rating['starAvgOverallRating'] = $starAvgOverallRating;
        $rating['avgOverallRating']     = $avgOverallRating;

        // Below section calculation
        $totalReviews   = OrderReview::get();

        $total5Star = $total4Star = $total3Star = $total2Star = $total1Star = 0;
        $total5StarPercent = $total4StarPercent = $total3StarPercent = $total2StarPercent = $total1StarPercent = 0;
        $totalFoodQualityRating = $totalDeliveryTimeRating = $totalDriverFriendlinessRating = 0;

        $rating['starAvgFoodDeliveryRating'] = $rating['avgFoodDeliveryRating'] = $rating['starAvgDeliveryTimeRating'] = $rating['avgDeliveryTimeRating'] = $rating['starAvgDriverFriendlinessRating'] = $rating['avgDriverFriendlinessRating'] = 0;

        if ($totalReviews->count() > 0) {
            foreach ($totalReviews as $review) {
                if ($review->avg_rating == 5) {
                    $total5Star++;
                }
                if ($review->avg_rating == 4) {
                    $total4Star++;
                }
                if ($review->avg_rating == 3) {
                    $total3Star++;
                }
                if ($review->avg_rating == 2) {
                    $total2Star++;
                }
                if ($review->avg_rating == 1) {
                    $total1Star++;
                }

                $totalFoodQualityRating         += $review->food_quality;
                $totalDeliveryTimeRating        += $review->delivery_time;
                $totalDriverFriendlinessRating  += $review->driver_friendliness;
            }

            // Percent calculation
            if ($total5Star != 0) {
                $total5StarPercent = round(($total5Star / $totalReviews->count()) * 100);
            }
            if ($total4Star != 0) {
                $total4StarPercent = round(($total4Star / $totalReviews->count()) * 100);
            }
            if ($total3Star != 0) {
                $total3StarPercent = round(($total3Star / $totalReviews->count()) * 100);
            }
            if ($total2Star != 0) {
                $total2StarPercent = round(($total2Star / $totalReviews->count()) * 100);
            }
            if ($total1Star != 0) {
                $total1StarPercent = round(($total1Star / $totalReviews->count()) * 100);
            }

            // For individual type
            $avgFoodDeliveryRating = $avgDeliveryTimeRating = $avgDriverFriendlinessRating = $starAvgFoodDeliveryRating = $starAvgDeliveryTimeRating = $starAvgDriverFriendlinessRating = 0.0;
            if ($totalReviews->count() > 0) {
                // Food quality
                $avgFoodDeliveryRating       = $totalFoodQualityRating / $totalReviews->count();
                $starAvgFoodDeliveryRating   = $avgFoodDeliveryRating;
                if ($avgFoodDeliveryRating == 0.5 || $avgFoodDeliveryRating == 1.5 || $avgFoodDeliveryRating == 2.5 || $avgFoodDeliveryRating == 3.5 || $avgOverallRating == 4.5) {
                    $starAvgFoodDeliveryRating   = $avgFoodDeliveryRating;
                }
                else {
                    $starAvgFoodDeliveryRating   = round($avgFoodDeliveryRating);
                    $avgFoodDeliveryRating       = (float)number_format($avgFoodDeliveryRating, 1);            
                }
                $rating['starAvgFoodDeliveryRating'] = $starAvgFoodDeliveryRating;
                $rating['avgFoodDeliveryRating']     = $avgFoodDeliveryRating;

                // Delivery Time
                $avgDeliveryTimeRating       = $totalDeliveryTimeRating / $totalReviews->count();
                $starAvgDeliveryTimeRating   = $avgDeliveryTimeRating;
                if ($avgDeliveryTimeRating == 0.5 || $avgDeliveryTimeRating == 1.5 || $avgDeliveryTimeRating == 2.5 || $avgDeliveryTimeRating == 3.5 || $avgOverallRating == 4.5) {
                    $starAvgDeliveryTimeRating   = $avgDeliveryTimeRating;
                }
                else {
                    $starAvgDeliveryTimeRating          = round($avgDeliveryTimeRating);
                    $avgDeliveryTimeRating              = (float)number_format($avgDeliveryTimeRating, 1);            
                }
                $rating['starAvgDeliveryTimeRating']    = $starAvgDeliveryTimeRating;
                $rating['avgDeliveryTimeRating']        = $avgDeliveryTimeRating;

                // Driver Friendliness
                $avgDriverFriendlinessRating            = $totalDriverFriendlinessRating / $totalReviews->count();
                $starAvgDriverFriendlinessRating        = $avgDriverFriendlinessRating;
                if ($avgDriverFriendlinessRating == 0.5 || $avgDriverFriendlinessRating == 1.5 || $avgDriverFriendlinessRating == 2.5 || $avgDriverFriendlinessRating == 3.5 || $avgOverallRating == 4.5) {
                    $starAvgDriverFriendlinessRating    = $avgDriverFriendlinessRating;
                }
                else {
                    $starAvgDriverFriendlinessRating    = round($avgDriverFriendlinessRating);
                    $avgDriverFriendlinessRating        = (float)number_format($avgDriverFriendlinessRating, 1);            
                }
                $rating['starAvgDriverFriendlinessRating'] = $starAvgDriverFriendlinessRating;
                $rating['avgDriverFriendlinessRating']     = $avgDriverFriendlinessRating;
            }
        }
        
        $rating['totalReviews']         = $totalReviews;
        $rating['total5StarPercent']    = $total5StarPercent;
        $rating['total4StarPercent']    = $total4StarPercent;
        $rating['total3StarPercent']    = $total3StarPercent;
        $rating['total2StarPercent']    = $total2StarPercent;
        $rating['total1StarPercent']    = $total1StarPercent;       
        $rating['userReviewList']       = $userReviewList;  
        // dd($rating);

        return $rating;
    }

}
