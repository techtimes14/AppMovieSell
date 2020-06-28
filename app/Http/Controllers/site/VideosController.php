<?php
/*****************************************************/
# Page/Class name   : VideosController
# Purpose           : all video related functions
/*****************************************************/
namespace App\Http\Controllers\site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Support\Facades\Session;
use Auth;
use Hash;
use \Validator;
use Helper;
use Image;
use AdminHelper;
use \Response;
Use Redirect;
use App\User;
use App\Cms;
use App\Video;
use App\Category;
use App\VideoCategory;
use App\VideoBrand;
use App\Brand;
use App\FavouriteVideo;
use App;

class VideosController extends Controller
{
    /*****************************************************/
    # Function name : getVideoDetails
    # Params        : 
    /*****************************************************/
    public function getVideoDetails($videoId = null)
    {
        $response['has_error']  = 0;
        $response['id']   = $videoId;

        $videoDetails = Video::where('id',$videoId)->first();
        $response['video_id']   = $videoDetails->video_id;
        $response['video_url']  = $videoDetails->video_url;
        $response['title']      = $videoDetails->title;
        $tags = '<ul>';
        foreach ($videoDetails->videoTags as $vt) {
            $tags .= '<li>'.$vt->tagDetails->title.'</li>';
        }
        $tags .= '</ul>';
        $response['video_tags'] = $tags;
        
        echo json_encode($response);
        // exit(0);
    }
    
    /*****************************************************/
    # Function name : videoDetailsFavouriteChecking
    # Params        : 
    /*****************************************************/
    public function videoDetailsFavouriteChecking($videoId = null)
    {
        $existCount = 0;
        $videoDetails = FavouriteVideo::where(['video_id' => $videoId, 'user_id' => Auth::user()->id])->count();
        if ($videoDetails > 0) {
            $existCount = 1;
        }
        
        echo $existCount;
        // exit(0);
    }
    
    /*****************************************************/
    # Function name : trendingVideos
    # Params        : Request $request
    /*****************************************************/
    public function trendingVideos(Request $request)
    {
        $homeData   = Helper::getData('cms', '6');

        $favVideoIds = [];

        $trendingVideosList = Video::whereNull('deleted_at')
                                    ->where(['status' => '1']);
        //Sory by section
        //low to high price -> low_to_high_view_count, high to low price -> high_to_low_view_count, old to new -> oldest, new to old -> newest
        $filterSoryBy = false;
        $sortBy       = '';
        if(isset($request->sort_by)  && $request->sort_by != ''){
            $sortBy = $request->sort_by;
            $filterSoryBy = true;
        }        
        if ($filterSoryBy) {
            if ($sortBy == 'low_to_high') {
                $trendingVideosList->orderBy('video_duration', 'asc');
            } else if ($sortBy == 'high_to_low') {
                $trendingVideosList->orderBy('video_duration', 'desc');
            } else if ($sortBy == 'oldest') {
                $trendingVideosList->orderBy('created_at', 'asc');
            } else if ($sortBy == 'newest') {
                $trendingVideosList->orderBy('created_at', 'desc');
            } else {
                $trendingVideosList->orderBy('view_count', 'desc');
            }
        } else {
            $trendingVideosList->orderBy('view_count', 'desc');
        }

        $trendingVideosList = $trendingVideosList->paginate(Helper::TRENDING_VIDEO_LIMIT);
        
        // Loggedin user faviourite video ids
        if (Auth::user()) {
            $favVideoIds = Helper::userFavouiteVideoIds();
        }
        
        return view('site.trending_videos',[
            'title'                 => $homeData['title'],
            'keyword'               => $homeData['keyword'], 
            'description'           => $homeData['description'],
            'trendingVideosList'    => $trendingVideosList,
            'favVideoIds'           => $favVideoIds,
        ]);
    }

    /*****************************************************/
    # Function name : categoryVideos
    # Params        : Request $request
    /*****************************************************/
    public function categoryVideos(Request $request)
    {
        $homeData   = Helper::getData('cms', '8');
        $slug = $request->slug;

        $listVideoIds = $videoList = $favVideoIds = [];
        $categoryDetails = Category::select('id','title')->where(['slug' => $slug, 'status' => '1'])->whereNull('deleted_at')->first();
        if ($categoryDetails != null) {
            $videoCategoryList = VideoCategory::select('video_id')->where('category_id', $categoryDetails->id)->get();
            if ($videoCategoryList->count() > 0) {
                foreach ($videoCategoryList as $videoCategory) {
                    $listVideoIds[] = $videoCategory->video_id;
                }
            }
            // To show it in banner
            $typeName = $categoryDetails->title;
        } else {
            $typeName = $slug;
        }
        
        if (count($listVideoIds) > 0) {
            $videoList = Video::whereNull('deleted_at')
                                ->whereIn('id', $listVideoIds)
                                ->where(['status' => '1']);
            //Sory by section
            //low to high price -> low_to_high_view_count, high to low price -> high_to_low_view_count, old to new -> oldest, new to old -> newest
            $filterSoryBy = false;
            $sortBy       = '';
            if(isset($request->sort_by)  && $request->sort_by != ''){
                $sortBy = $request->sort_by;
                $filterSoryBy = true;
            }        
            if ($filterSoryBy) {
                if ($sortBy == 'low_to_high') {
                    $videoList->orderBy('video_duration', 'asc');
                } else if ($sortBy == 'high_to_low') {
                    $videoList->orderBy('video_duration', 'desc');
                } else if ($sortBy == 'oldest') {
                    $videoList->orderBy('created_at', 'asc');
                } else if ($sortBy == 'newest') {
                    $videoList->orderBy('created_at', 'desc');
                } else {
                    $videoList->orderBy('view_count', 'desc');
                }
            } else {
                $videoList->orderBy('id', 'desc');
            }

            $videoList = $videoList->paginate(Helper::CATEGORY_VIDEO_LIMIT);
        }

        // Loggedin user faviourite video ids
        if (Auth::user()) {
            $favVideoIds = Helper::userFavouiteVideoIds();
        }

        return view('site.category_videos',[
            'title'         => $homeData['title'],
            'keyword'       => $homeData['keyword'], 
            'description'   => $homeData['description'],
            'slug'          => $slug,
            'typeName'      => $typeName,
            'videoList'     => $videoList,
            'favVideoIds'   => $favVideoIds,
        ]);
    }

    /*****************************************************/
    # Function name : brandVideos
    # Params        : Request $request
    /*****************************************************/
    public function brandVideos(Request $request)
    {
        $cmsData   = Helper::getData('cms', '9');

        $favVideoIds = [];

        $brandIds = Helper::userBrandVideos();
        if (count($brandIds) == 0) {
            return redirect()->route('site.users.profile');
        } else {
            $brandDetails = Brand::whereIn('id', $brandIds)->where('status', '1')->whereNull('deleted_at')->get();
            $brandVideosList = VideoBrand::whereIn('brand_id', $brandIds)
                                            ->whereHas('videoDetails', function ($query) {
                                                $query->where('status', '1');
                                                $query->whereNull('deleted_at');
                                            })
                                            ->orderBy('video_id', 'desc')
                                            ->paginate(Helper::BRAND_VIDEO_LIMIT);

            // Loggedin user faviourite video ids
            if (Auth::user()) {
                $favVideoIds = Helper::userFavouiteVideoIds();
            }
            
            return view('site.brand_videos',[
                'title'             => $cmsData['title'],
                'keyword'           => $cmsData['keyword'], 
                'description'       => $cmsData['description'],
                'cmsData'           => $cmsData,
                'brandDetails'      => $brandDetails,
                'brandVideosList'   => $brandVideosList,
                'favVideoIds'       => $favVideoIds,
            ]);
        }
    }

}
