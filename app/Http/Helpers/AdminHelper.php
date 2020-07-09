<?php
/*****************************************************/
# Globals
# Page/Class name   : Globals
# Purpose           : for global purpose
/*****************************************************/

namespace App\Http\Helpers;

use App\User;
use App\Category;
use App\Tag;
use App\Brand;
use DateInterval;

class AdminHelper
{
   public const ADMIN_USER_LIMIT    = 15;    // pagination limit for user list in admin panel
   public const ADMIN_LIST_LIMIT = 15;             

   
   public const IMAGE_MAX_UPLOAD_SIZE = 5120; // Image upload max size (5mb)
   public const ICON_MAX_UPLOAD_SIZE = 1024; // Image upload max size (1mb)

   public const ADMIN_BANNER_THUMB_IMAGE_WIDTH  = '1920';   // Admin BANNER thumb image width
   public const ADMIN_BANNER_THUMB_IMAGE_HEIGHT = '745';   // Admin BANNER thumb image height

   public const ADMIN_PRODUCT_MAX_NUMBER_OF_IMAGE_UPLOAD = 10;   // Admin PRODUCT maximum no of image upload
   public const ADMIN_PRODUCT_SLIDER_IMAGE_WIDTH  = '750';   // Admin PRODUCT SLIDER image width
   public const ADMIN_PRODUCT_SLIDER_IMAGE_HEIGHT = '430';   // Admin PRODUCT SLIDER image height
   public const ADMIN_PRODUCT_THUMB_IMAGE_WIDTH  = '80';   // Admin PRODUCT thumb image width
   public const ADMIN_PRODUCT_THUMB_IMAGE_HEIGHT = '80';   // Admin PRODUCT thumb image height
   public const ADMIN_PRODUCT_LIST_THUMB_IMAGE_WIDTH  = '160';   // Admin PRODUCT LIST thumb image width
   public const ADMIN_PRODUCT_LIST_THUMB_IMAGE_HEIGHT = '193';   // Admin PRODUCT LIST thumb image height

   public const ADMIN_SERVICE_THUMB_IMAGE_WIDTH  = '385';   // Admin PRODUCT LIST thumb image width
   public const ADMIN_SERVICE_THUMB_IMAGE_HEIGHT = '300';   // Admin PRODUCT LIST thumb image height

   public const UPLOADED_IMAGE_FILE_TYPES = ['jpeg', 'jpg', 'png', 'svg']; //Uploaded image file types

   
   /*****************************************************/
   # Function name : formatToTwoDecimalPlaces
   # Purpose       : Format data to 2 decimal places
   # Params        : $data
   /*****************************************************/
   public static function formatToTwoDecimalPlaces($data)
   {
      return number_format((float)$data, 2, '.', '');
   }

   /*****************************************************/
   # Function name : paginationMessage
   # Purpose       : Format data to 2 decimal places
   # Params        : $data = null
   /*****************************************************/
   public static function paginationMessage($data = null)
   {
      return 'Records '.$data->firstItem().' - '.$data->lastItem().' of '.$data->total();
   }
   
   /*****************************************************/
   # Function name : getYoutubeIdFromUrl
   # Purpose       : Get id from youtube url
   # Params        : $youTubeUrl
   /*****************************************************/
   public static function getYoutubeIdFromUrl($youTubeUrl)
   {
      $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
      $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

      $youtubeId = '';
   
      if (preg_match($longUrlRegex, $youTubeUrl, $matches)) {
         $youtubeId = $matches[count($matches) - 1];
      }   
      if (preg_match($shortUrlRegex, $youTubeUrl, $matches)) {
         $youtubeId = $matches[count($matches) - 1];
      }
      return $youtubeId;
   }

   /*****************************************************/
   # Function name : youtubeVideoDetails
   # Purpose       : Get whole data from a youtube Url
   # Params        : $youTubeVideoUrl = null
   /*****************************************************/
   public static function youtubeVideoDetails($youTubeVideoUrl = null)
   {
      $apiKey  = env('YOUTUBE_API_KEY', 'AIzaSyAdMeovMKZJ1mCWZRwcW5ZqTV6qkM4tLHI');
      $details = [];
      $youTubeId = self::getYoutubeIdFromUrl($youTubeVideoUrl);

      if ($youTubeId != '') {
         $apiUrl  = 'https://www.googleapis.com/youtube/v3/videos?part=snippet%2CcontentDetails%2Cstatistics&id='.$youTubeId.'&key='.$apiKey;
        
         $data = json_decode(file_get_contents($apiUrl), true);
         // echo '<pre>'; print_r($data['items'][0]); die;
         if (!empty($data['items'][0])) {
            $details['video_id']          = $data['items'][0]['id'];
            $details['video_title']       = $data['items'][0]['snippet']['title'];
            $details['image_default_url'] = isset($data['items'][0]['snippet']['thumbnails']['default']['url']) ? $data['items'][0]['snippet']['thumbnails']['default']['url'] : '';
            $details['image_medium_url']  = isset($data['items'][0]['snippet']['thumbnails']['medium']['url']) ? $data['items'][0]['snippet']['thumbnails']['medium']['url'] : $details['image_default_url'];
            $details['image_high_url']    = isset($data['items'][0]['snippet']['thumbnails']['high']['url']) ? $data['items'][0]['snippet']['thumbnails']['high']['url'] : $details['image_medium_url'];
            $details['image_standard_url'] = isset($data['items'][0]['snippet']['thumbnails']['standard']['url']) ? $data['items'][0]['snippet']['thumbnails']['standard']['url'] : $details['image_high_url'];
            $details['image_maxres_url']  = isset($data['items'][0]['snippet']['thumbnails']['maxres']['url']) ? $data['items'][0]['snippet']['thumbnails']['maxres']['url'] : $details['image_standard_url'];
            $details['view_count']  = isset($data['items'][0]['statistics']['viewCount']) ? $data['items'][0]['statistics']['viewCount'] : 0;
            $details['like_count']  = isset($data['items'][0]['statistics']['likeCount']) ? $data['items'][0]['statistics']['likeCount'] : 0;
            $details['dislike_count']  = isset($data['items'][0]['statistics']['dislikeCount']) ? $data['items'][0]['statistics']['dislikeCount'] : 0;
            $details['favorite_count']  = isset($data['items'][0]['statistics']['favoriteCount']) ? $data['items'][0]['statistics']['favoriteCount'] : 0;
            $details['comment_count']  = isset($data['items'][0]['statistics']['commentCount']) ? $data['items'][0]['statistics']['commentCount'] : 0;

            $videoDuration = $data['items'][0]['contentDetails']['duration'];

            $interval = new DateInterval($videoDuration);
            $details['video_duration']    = $interval->h * 3600 + $interval->i * 60 + $interval->s;            
         }
      }
      return $details;
   }

   /*****************************************************/
   # Function name : getCategories
   # Purpose       : Getting category
   # Params        :
   /*****************************************************/
   public static function getCategories()
   {
      // $categoryList = array('' => 'Select Category');
      $categoryList = array();
      $categoryListing = Category::where(['status' => '1'])->whereNull('deleted_at')->get();
      if (count($categoryListing)) {
         foreach ($categoryListing as $keyCategory => $valueCategory) {
            $categoryList[$valueCategory['id']] = $valueCategory['title'];
         }
      }
      return $categoryList;
   }

   /*****************************************************/
   # Function name : getTags
   # Purpose       : Getting tags
   # Params        :
   /*****************************************************/
   public static function getTags()
   {
      // $tagList = array('' => 'Select Tag');
      $tagList = array();
      $tagListing = Tag::where(['status' => '1'])->whereNull('deleted_at')->get();
      if (count($tagListing)) {
         foreach ($tagListing as $keyTag => $valueTag) {
            $tagList[$valueTag['id']] = $valueTag['title'];
         }
      }
      return $tagList;
   }

   /*****************************************************/
   # Function name : getBrands
   # Purpose       : Getting brands
   # Params        :
   /*****************************************************/
   public static function getBrands()
   {
      // $tagList = array('' => 'Select Brand');
      $brandList = array();
      $brandListing = Brand::where(['status' => '1'])->whereNull('deleted_at')->get();
      if (count($brandListing)) {
         foreach ($brandListing as $keyBrand => $valueBrand) {
            $brandList[$valueBrand['id']] = $valueBrand['title'];
         }
      }
      return $brandList;
   }
   
}
