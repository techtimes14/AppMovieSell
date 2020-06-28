<?php
/*****************************************************/
# Page/Class name   : Helper
# Purpose           : for global purpose
/*****************************************************/
namespace App\Http\Helpers;

use Auth;
use App\Category;
use App\Cms;
use App\SiteSetting;
use App\Banner;
use App\BrandUser;
use App\FavouriteVideo;
use \Illuminate\Support\Facades\Session;
use App\Http\Helpers\NotificationHelper;
use \Illuminate\Support\Str;

class Helper
{
    public const NO_IMAGE = 'no-image.png'; // No image

    public const UPLOADED_DOC_FILE_TYPES = ['doc', 'docx', 'xls', 'xlsx', 'pdf', 'txt', 'ods', 'odp', 'odt']; //Uploaded document file types

    public const UPLOADED_IMAGE_FILE_TYPES = ['jpeg', 'jpg', 'png', 'svg']; //Uploaded image file types

    public const PROFILE_IMAGE_MAX_UPLOAD_SIZE = 5120; // profile image upload max size (5mb)

    public const PROFILE_VIDEO_LIMIT = 12;    // pagination limit for PROFILE video page

    public const TRENDING_VIDEO_LIMIT = 16;    // pagination limit for trending video page

    public const CATEGORY_VIDEO_LIMIT = 16;    // pagination limit for category wise video page

    public const MY_FAVOURITE_VIDEO_LIMIT = 12;    // pagination limit for my favourite page

    public const FAVOURITE_BOARD_VIDEO_LIMIT = 16;    // pagination limit for favourite board page

    public const BRAND_VIDEO_LIMIT = 12;    // pagination limit for brand page

    public const NUMBER_OF_MENU_CATEGORY = 15;  // Number of category for menu

    public const MAX_LIMIT_CREATE_BOARD = 4;  // Number of board to create board

    
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
    # Function name : getMenuCategory
    # Params        :
    /*****************************************************/
    public static function getMenuCategory()
    {
        $categoryList = Category::where(['status' => '1'])
                                    ->whereNull('deleted_at')            
                                    ->select('id', 'title', 'slug')
                                    ->orderBy('created_at', 'asc')
                                    ->take(self::NUMBER_OF_MENU_CATEGORY)
                                    ->get();        
        return $categoryList;
    }

    /*****************************************************/
    # Function name : getAllMenuCategoryTreeView
    # Params        :
    /*****************************************************/
    public static function getAllMenuCategoryTreeView()
    {
        $webLang = \App::getLocale();

        $allCategoryList = [];
        $allParentCategoryListing = Category::where(['parent_id' => null, 'status' => '1'])
            ->whereNull('deleted_at')
            ->with([
                'local' => function ($query) use ($webLang) {
                    $query->where('lang_code', $webLang);
                },
            ])
            ->select('id', 'name', 'slug', 'parent_id')
            ->orderBy('created_at', 'asc')
            //->take(3)
            ->get();
        if (count($allParentCategoryListing)) { //Parent Category
            $arrayKey = 0;
            foreach ($allParentCategoryListing as $keyCategory => $valueCategory) {
                $parentCategoryId = '';
                $parentCategoryId = $valueCategory['id'];

                $allCategoryList[$arrayKey]['parent_category_id'] = $parentCategoryId;
                $allCategoryList[$arrayKey]['name'] = $valueCategory->local[0]->local_name;
                $allCategoryList[$arrayKey]['slug'] = $valueCategory['slug'];

                //First Level Category
                $firstLevelCategory = Category::where(['parent_id' => $parentCategoryId, 'status' => '1'])
                    ->whereNull('deleted_at')
                    ->with([
                        'local' => function ($query) use ($webLang) {
                            $query->where('lang_code', $webLang);
                        },
                    ])
                    ->select('id', 'name', 'slug', 'parent_id')
                    ->orderBy('created_at', 'asc')
                    ->get();
                if ($firstLevelCategory->count() > 0) {
                    foreach ($firstLevelCategory as $keyFirstLevel => $firstLevel) {
                        $firstLevelCategoryId = $firstLevel['id'];

                        $allCategoryList[$arrayKey]['sub_category'][$keyFirstLevel]['sub_category_id'] = $firstLevelCategoryId;
                        $allCategoryList[$arrayKey]['sub_category'][$keyFirstLevel]['name'] = $firstLevel->local[0]->local_name;
                        $allCategoryList[$arrayKey]['sub_category'][$keyFirstLevel]['slug'] = $firstLevel['slug'];
                    }
                }
                $arrayKey++;
            }
        }
        //echo '<pre>'; print_r($allCategoryList); die;

        $menuCategories = [];
        if (!empty($allCategoryList)) {
            for($i=0, $column = 1;  $i < count($allCategoryList); $i++){
                $menuCategories['column_'.$column][] = $allCategoryList[$i];
                if ($column != 4)
                    $column++;
                else
                    $column = 1;
            }
        }
        //echo '<hr><pre>'; print_r($menuCategories); die;

        return $menuCategories;
    }

    /*****************************************************/
    # Function name : getProductListCategories
    # Params        : Request $request
    /*****************************************************/
    public static function getProductListCategories()
    {
        $webLang = \App::getLocale();
        $totalSubcategoryProducts = 0;
        $allCategoryList = [];
        $allParentCategoryListing = Category::where(['parent_id' => null, 'status' => '1'])
            ->whereNull('deleted_at')
            ->with([
                'local' => function ($query) use ($webLang) {
                    $query->where('lang_code', $webLang);
                },
            ])
            ->select('id', 'name', 'slug', 'parent_id')
            ->orderBy('created_at', 'asc')
            ->get();
        if (count($allParentCategoryListing)) { //Parent Category
            foreach ($allParentCategoryListing as $keyCategory => $valueCategory) {
                $parentCategoryId = '';
                $totalCategoryProducts = 0;
                $totalSubcategoryProducts = 0;
                $parentCategoryId = $valueCategory['id'];

                $allCategoryList[$parentCategoryId]['slug'] = $valueCategory['slug'];

                if (Auth::user()) {
                    if (count($valueCategory->categoryRelatedProductVendors) > 0) {
                        foreach ($valueCategory->categoryRelatedProductVendors as $categoryProduct) {
                            if (Auth::user()->id != $categoryProduct['vendor_id']) {
                                $totalCategoryProducts++;
                            }
                        }
                    }
                } else {
                    $totalCategoryProducts = count($valueCategory->categoryRelatedProductVendors);
                }

                if (count($valueCategory->getChildCategories) > 0) {
                    foreach ($valueCategory->getChildCategories as $firstLevel) {
                        $firstLevelCategoryId = $firstLevel['id'];

                        if (Auth::user()) {
                            if (count($firstLevel->subcategoryRelatedProductVendors) > 0) {
                                foreach ($firstLevel->subcategoryRelatedProductVendors as $subcategoryProduct) {
                                    if (Auth::user()->id != $subcategoryProduct['vendor_id']) {
                                        $totalSubcategoryProducts++;
                                    }
                                }
                            }
                        } else {
                            $totalSubcategoryProducts = count($firstLevel->subcategoryRelatedProductVendors);
                        }

                        $allCategoryList[$parentCategoryId]['first_level_category'][$firstLevelCategoryId]['name'] = $firstLevel->local[0]->local_name . ' (' . $totalSubcategoryProducts . ')';
                        $allCategoryList[$parentCategoryId]['first_level_category'][$firstLevelCategoryId]['slug'] = $firstLevel['slug'];
                        
                        $totalSubcategoryProducts = 0;
                    }
                    //$allCategoryList[$parentCategoryId]['name'] = $valueCategory->local[0]->local_name . ' (' . $totalSubcategoryProducts . ')';
                    $allCategoryList[$parentCategoryId]['name'] = $valueCategory->local[0]->local_name . ' (' . $totalCategoryProducts . ')';

                    $totalCategoryProducts = 0;
                } else {
                    $allCategoryList[$parentCategoryId]['name'] = $valueCategory->local[0]->local_name . ' (' . $totalCategoryProducts . ')';
                }
            }
        }
        return $allCategoryList;
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
    # Function name : getParentCategory
    # Params        :
    /*****************************************************/
    public static function getParentCategory()
    {
        $parentCategoryList = array('' => 'Select Category');
        $parentCategoryListing = Category::where(['parent_id' => null, 'status' => '1'])->whereNull('deleted_at')->get();
        if (count($parentCategoryListing)) { //Parent Category
            foreach ($parentCategoryListing as $keyCategory => $valueCategory) {
                $parentCategoryList[$valueCategory['id']] = $valueCategory['name'];
            }
        }
        return $parentCategoryList;
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
    # Function name : pageBanners
    # Params        : 
    /*****************************************************/
    public static function pageBanners() {
        $banners = Banner::where(['status' => '1'])->whereNull('deleted_at')->get();
        return $banners;
    }

    /*****************************************************/
    # Function name : userBrandVideos
    # Params        : 
    /*****************************************************/
    public static function userBrandVideos() {
        $brandIds = [];
        if (Auth::user()) {
            $userBrands = BrandUser::where(['user_id' => Auth::user()->id])->get();
            if ($userBrands->count() > 0) {
                foreach ($userBrands as $brand) {
                    $brandIds[] = $brand->brand_id;
                }
            }
        }
        return $brandIds;
    }

    /*****************************************************/
    # Function name : userFavouiteVideoIds
    # Params        : $videoId, $userId
    /*****************************************************/
    public static function userFavouiteVideoIds() {
        $videoIds = [];
        if (Auth::user()) {
            $userVideos = FavouriteVideo::where('user_id', Auth::user()->id)->get();
            if ($userVideos->count() > 0) {
                foreach ($userVideos as $video) {
                    $videoIds[] = $video->video_id;
                }
            }
        }
        return $videoIds;
    }

    /*****************************************************/
    # Function name : generateRandomPassword
    # Params        : $length = 8
    /*****************************************************/
    public static function generateRandomPassword($length = 5) {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet);

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max-1)];
        }

        $finalPassword = $token.'@'.substr(str_shuffle("0123456789"), 0, 3);
        
        return $finalPassword;
    }

}
