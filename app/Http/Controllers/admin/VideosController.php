<?php
/*****************************************************/
# Page/Class name   : VideosController
/*****************************************************/
namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Helper;
use AdminHelper;
use App\Video;
use App\VideoCategory;
use App\VideoTag;
use App\VideoBrand;

class VideosController extends Controller
{
    /*****************************************************/
    # Function name : list
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'Video List';
        $data['panel_title']= 'Video List';
        
        try
        {
            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';
            
            $query = Video::whereNull('deleted_at');

            $data['searchText'] = $key = $request->searchText;

            if ($key) {
                // if the search key is provided, proceed to build query for search
                $query->where(function ($q) use ($key) {
                    $q->where('title', 'LIKE', '%' . $key . '%');
                    
                });
            }
            $exists = $query->count();
            if ($exists > 0) {
                $list = $query->orderBy($data['order_by'], $data['order'])
                                            ->paginate(AdminHelper::ADMIN_LIST_LIMIT);
                $data['list'] = $list;
            } else {
                $data['list'] = array();
            }       
            return view('admin.video.list', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.video.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : add
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request) {
        $data['page_title']     = 'Add Video';
        $data['panel_title']    = 'Add Video';
    
        try
        {
            $data['categoryList']   = AdminHelper::getCategories();
            $data['tagList']        = AdminHelper::getTags();
            $data['brandList']      = AdminHelper::getBrands();

        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
					'title'     => 'required|min:2|max:255|unique:'.(new Video)->getTable().',title',
				);
				$validationMessages = array(
					'title.required'    => 'Please enter title',
					'title.min'         => 'Title should be should be at least 2 characters',
                    'title.max'         => 'Title should not be more than 255 characters',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.video.add')->withErrors($Validator)->withInput();
				} else {
                    $videoDetails = AdminHelper::youtubeVideoDetails($request->video_url);
                    if (empty($videoDetails)) {
                        $request->session()->flash('alert-danger', 'Please enter valid youtube url');
                        return redirect()->back()->withInput();
                    } else {
                        $new = new Video;
                        $new->title                 = trim($request->title, ' ');
                        $new->video_url             = trim($request->video_url, ' ');
                        $new->video_id              = $videoDetails['video_id'];
                        $new->video_title           = $videoDetails['video_title'];
                        $new->image_default_url     = $videoDetails['image_default_url'];
                        $new->image_medium_url      = $videoDetails['image_medium_url'];
                        $new->image_high_url        = $videoDetails['image_high_url'];
                        $new->image_standard_url    = $videoDetails['image_standard_url'];
                        $new->image_maxres_url      = $videoDetails['image_maxres_url'];
                        $new->video_duration        = $videoDetails['video_duration'];
                        $new['view_count']          = $videoDetails['view_count'];
                        $new['like_count']          = $videoDetails['like_count'];
                        $new['dislike_count']       = $videoDetails['dislike_count'];
                        $new['favorite_count']      = $videoDetails['favorite_count'];
                        $new['comment_count']       = $videoDetails['comment_count'];
                        $save = $new->save();
                    
                        if ($save) {
                            // Video Categories
                            if (count($request->categories) > 0) {
                                foreach ($request->categories as $category) {
                                    $newVideoCategory = new VideoCategory;
                                    $newVideoCategory->video_id     = $new->id;
                                    $newVideoCategory->category_id  = $category;
                                    $newVideoCategory->save();
                                }
                            }
                            // Video Tags
                            if (count($request->tags) > 0) {
                                foreach ($request->tags as $tag) {
                                    $newVideoTag = new VideoTag;
                                    $newVideoTag->video_id  = $new->id;
                                    $newVideoTag->tag_id    = $tag;
                                    $newVideoTag->save();
                                }
                            }
                            // Video Brands
                            if (count($request->brands) > 0) {
                                foreach ($request->brands as $brand) {
                                    $newVideoBrand = new VideoBrand;
                                    $newVideoBrand->video_id    = $new->id;
                                    $newVideoBrand->Brand_id    = $brand;
                                    $newVideoBrand->save();
                                }
                            }
                            $request->session()->flash('alert-success', 'Video has been added successfully');
                            return redirect()->route('admin.video.list');
                        } else {
                            $request->session()->flash('alert-danger', 'An error occurred while adding the video');
                            return redirect()->back();
                        }
                    }
				}
			}
			return view('admin.video.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.video.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # Function name : edit
    # Params        : Request $request, $id
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']  = 'Edit Video';
        $data['panel_title'] = 'Edit Video';

        try
        {           
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;

            $data['categoryList']   = AdminHelper::getCategories();
            $data['tagList']        = AdminHelper::getTags();
            $data['brandList']      = AdminHelper::getBrands();
            $data['id']             = $id;
            $data['details']        = Video::where('id',$id)->first();
            $categoryIds = $tagIds = $brandIds = [];
            foreach ($data['details']->videoCategories as $vc) {
                $categoryIds[] = $vc->category_id;
            }
            foreach ($data['details']->videoTags as $vt) {
                $tagIds[] = $vt->tag_id;
            }
            foreach ($data['details']->videoBrands as $vb) {
                $brandIds[] = $vb->brand_id;
            }
            $data['categoryIds']= $categoryIds;
            $data['tagIds']     = $tagIds;
            $data['brandIds']   = $brandIds;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.video.list');
                }
                $validationCondition = array(
                    'title'         => 'required|min:2|max:255|unique:' .(new Video)->getTable().',title,' .$id,
                );
                $validationMessages = array(
                    'title.required'    => 'Please enter title',
                    'title.min'         => 'Title should be should be at least 2 characters',
                    'title.max'         => 'Title should not be more than 255 characters',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {                   
                    $update['title'] = trim($request->title, ' ');

                    // if ($data['details']['video_url'] != $request->video_url) {
                        $videoDetails = AdminHelper::youtubeVideoDetails($request->video_url);
                        if (empty($videoDetails)) {
                            $request->session()->flash('alert-danger', 'Please enter valid youtube url');
                            return redirect()->back()->withInput();
                        } else {
                            $update['video_url']            = trim($request->video_url, ' ');
                            $update['video_id']             = $videoDetails['video_id'];
                            $update['video_title']          = $videoDetails['video_title'];
                            $update['image_default_url']    = $videoDetails['image_default_url'];
                            $update['image_medium_url']     = $videoDetails['image_medium_url'];
                            $update['image_high_url']       = $videoDetails['image_high_url'];
                            $update['image_standard_url']   = $videoDetails['image_standard_url'];
                            $update['image_maxres_url']     = $videoDetails['image_maxres_url'];
                            $update['video_duration']       = $videoDetails['video_duration'];
                            $update['view_count']           = $videoDetails['view_count'];
                            $update['like_count']           = $videoDetails['like_count'];
                            $update['dislike_count']        = $videoDetails['dislike_count'];
                            $update['favorite_count']       = $videoDetails['favorite_count'];
                            $update['comment_count']        = $videoDetails['comment_count'];
                        }
                    // }

                    $save = Video::where('id', $id)->update($update);                        
                    if ($save) {
                        // Video Categories
                        if (count($request->categories) > 0) {
                            VideoCategory::where('video_id', $id)->delete();
                            foreach ($request->categories as $category) {
                                $newVideoCategory = new VideoCategory;
                                $newVideoCategory->video_id     = $id;
                                $newVideoCategory->category_id  = $category;
                                $newVideoCategory->save();
                            }
                        }
                        // Video Tags
                        if (count($request->tags) > 0) {
                            VideoTag::where('video_id', $id)->delete();
                            foreach ($request->tags as $tag) {
                                $newVideoTag = new VideoTag;
                                $newVideoTag->video_id  = $id;
                                $newVideoTag->tag_id    = $tag;
                                $newVideoTag->save();
                            }
                        }
                        // Video Brands
                        if (count($request->brands) > 0) {
                            VideoBrand::where('video_id', $id)->delete();
                            foreach ($request->brands as $brand) {
                                $newVideoBrand = new VideoBrand;
                                $newVideoBrand->video_id    = $id;
                                $newVideoBrand->brand_id    = $brand;
                                $newVideoBrand->save();
                            }
                        }
                        $request->session()->flash('alert-success', 'Video has been updated successfully');
                        return redirect()->route('admin.video.list', ['page' => $pageNo]);

                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the video');
                        return redirect()->back();
                    }
                }
            }
            return view('admin.video.edit')->with(['data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.video.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : status
    # Params        : Request $request, $id
    /*****************************************************/
    public function status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.video.list');
            }
            $details = Video::where('id', $id)->first();
            if ($details != null) {
                if ($details->status == 1) {
                    $details->status = '0';
                    $details->save();
                    
                    $request->session()->flash('alert-success', 'Status updated successfully');
                    return redirect()->back();
                } else if ($details->status == 0) {
                    $details->status = '1';
                    $details->save();

                    $request->session()->flash('alert-success', 'Status updated successfully');
                    return redirect()->back();
                } else {
                    $request->session()->flash('alert-danger', 'Something went wrong');
                    return redirect()->back();
                }
            } else {
                return redirect()->route('admin.video.list')->with('error', 'Invalid video');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.video.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : delete
    # Params        : Request $request, $id
    /*****************************************************/
    public function delete(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.video.list');
            }

            $details = Video::where('id', $id)->first();
            if ($details != null) {
               $delete = $details->delete();
                if ($delete) {
                    $request->session()->flash('alert-danger', 'Video has been deleted successfully');
                    return redirect()->back();
                } else {
                    $request->session()->flash('alert-danger', 'An error occurred while deleting the video');
                    return redirect()->back();
                }
            } else {
                $request->session()->flash('alert-danger', 'Invalid video');
                return redirect()->back();
            }
        } catch (Exception $e) {
            return redirect()->route('admin.video.list')->with('error', $e->getMessage());
        }
    }
    
}