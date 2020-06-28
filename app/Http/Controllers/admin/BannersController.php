<?php
/*****************************************************/
# Page/Class name   : BannersController
/*****************************************************/
namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Helper;
use AdminHelper;
use App\Banner;
use Image;

class BannersController extends Controller
{
    /*****************************************************/
    # Function name : list
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'Banner List';
        $data['panel_title']= 'Banner List';
        
        try
        {
            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';
            
            $query = Banner::whereNull('deleted_at');

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
            return view('admin.banner.list', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.banner.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : add
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request) {
        $data['page_title']     = 'Add Banner';
        $data['panel_title']    = 'Add Banner';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'title'             => 'required|min:2|max:255|unique:'.(new Banner)->getTable().',title',
                    'short_description' => 'required',
                    'image'             => 'required',
                    'image'             => 'dimensions:min_width='.AdminHelper::ADMIN_BANNER_THUMB_IMAGE_WIDTH.', min_height='.AdminHelper::ADMIN_BANNER_THUMB_IMAGE_HEIGHT,
                    'image'             => 'mimes:jpeg,jpg,png,svg|max:'.AdminHelper::IMAGE_MAX_UPLOAD_SIZE,
                    'mobile_image'      => 'required',
                    'mobile_image'      => 'dimensions:min_width='.AdminHelper::ADMIN_BANNER_MOBILE_THUMB_IMAGE_WIDTH.', min_height='.AdminHelper::ADMIN_BANNER_MOBILE_THUMB_IMAGE_HEIGHT,
                    'mobile_image'      => 'mimes:jpeg,jpg,png,svg|max:'.AdminHelper::IMAGE_MAX_UPLOAD_SIZE,
				);
				$validationMessages = array(
					'title.required'            => 'Please enter title',
					'title.min'                 => 'Title should be should be at least 2 characters',
                    'title.max'                 => 'Title should not be more than 255 characters',
                    'short_description.required'=> 'Please enter short description',
                    'image.required'            => 'Please select image',
                    'image.dimensions'          => 'Image width and height not match with min width and height',
                    'mobile_image.required'     => 'Please select image',
                    'mobile_image.dimensions'   => 'Mobile image width and height not match with min width and height',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.banner.add')->withErrors($Validator)->withInput();
				} else {
                    $filename = '';
                    $image = $request->file('image');
                    if ($image != '') {
                        $originalFileName   =  $image->getClientOriginalName();
                        $extension          = pathinfo($originalFileName, PATHINFO_EXTENSION);
                        $filename           ='banner_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;                        
                        $imageResize        = Image::make($image->getRealPath());
                        $imageResize->save(public_path('uploads/banner/' . $filename));
                        $imageResize->resize(AdminHelper::ADMIN_BANNER_THUMB_IMAGE_WIDTH, AdminHelper::ADMIN_BANNER_THUMB_IMAGE_HEIGHT,
                            function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        $imageResize->save(public_path('uploads/banner/thumbs/' . $filename));
                    }

                    $filenameMobile = '';
                    $imageMobile = $request->file('mobile_image');
                    if ($imageMobile != '') {
                        $originalFileNameMobile   =  $imageMobile->getClientOriginalName();
                        $extensionMobile          = pathinfo($originalFileNameMobile, PATHINFO_EXTENSION);
                        $filenameMobile           ='banner_mobile_'.strtotime(date('Y-m-d H:i:s')).'.'.$extensionMobile;
                        $imageResizeMobile        = Image::make($imageMobile->getRealPath());
                        $imageResizeMobile->save(public_path('uploads/banner/' . $filenameMobile));
                        $imageResizeMobile->resize(AdminHelper::ADMIN_BANNER_MOBILE_THUMB_IMAGE_WIDTH, AdminHelper::ADMIN_BANNER_MOBILE_THUMB_IMAGE_HEIGHT,
                            function ($constraintMobile) {
                                $constraintMobile->aspectRatio();
                            });
                        $imageResizeMobile->save(public_path('uploads/banner/thumbs/' . $filenameMobile));
                    }

                    $new = new Banner;
                    $new->title             = trim($request->title, ' ');
                    $new->short_description = trim($request->short_description, ' ');
                    $new->image             = $filename;
                    $new->mobile_image      = $filenameMobile;
                    $save = $new->save();
                
					if ($save) {						
						$request->session()->flash('alert-success', 'Banner has been added successfully');
						return redirect()->route('admin.banner.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the banner');
						return redirect()->back();
					}
				}
			}
			return view('admin.banner.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.banner.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # Function name : edit
    # Params        : Request $request, $id
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']  = 'Edit Banner';
        $data['panel_title'] = 'Edit Banner';

        try
        {           
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;

            $details = Banner::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.banner.list');
                }
                $validationCondition = array(
                    'title'             => 'required|min:2|max:255|unique:'.(new Banner)->getTable().',title,' .$id,
                    'short_description' => 'required',
                    'image'             => 'dimensions:min_width='.AdminHelper::ADMIN_BANNER_THUMB_IMAGE_WIDTH.', min_height='.AdminHelper::ADMIN_BANNER_THUMB_IMAGE_HEIGHT,
                    'image'             => 'mimes:jpeg,jpg,png,svg|max:'.AdminHelper::IMAGE_MAX_UPLOAD_SIZE,
                    'mobile_image'      => 'dimensions:min_width='.AdminHelper::ADMIN_BANNER_MOBILE_THUMB_IMAGE_WIDTH.', min_height='.AdminHelper::ADMIN_BANNER_MOBILE_THUMB_IMAGE_HEIGHT,
                    'mobile_image'      => 'mimes:jpeg,jpg,png,svg|max:'.AdminHelper::IMAGE_MAX_UPLOAD_SIZE,
                );
                $validationMessages = array(
                    'title.required'            => 'Please enter title',
                    'title.min'                 => 'Title should be should be at least 2 characters',
                    'title.max'                 => 'Title should not be more than 255 characters',
                    'short_description.required'=> 'Please enter short description',
                    'image.dimensions'          => 'Image width and height not match with min width and height',
                    'mobile_image.dimensions'   => 'Mobile image width and height not match with min width and height',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    $update['title']            = trim($request->title, ' ');
                    $update['short_description']= trim($request->short_description, ' ');

                    $image = $request->file('image');
                    if ($image != '') {                        
                        $originalFileName   = $image->getClientOriginalName();
                        $extension          = pathinfo($originalFileName, PATHINFO_EXTENSION);
                        $filename           = 'banner_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;                        
                        $imageResize        = Image::make($image->getRealPath());
                        $imageResize->save(public_path('uploads/banner/' . $filename));
                        $imageResize->resize(AdminHelper::ADMIN_BANNER_THUMB_IMAGE_WIDTH, AdminHelper::ADMIN_BANNER_THUMB_IMAGE_HEIGHT,
                            function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        $imageResize->save(public_path('uploads/banner/thumbs/' . $filename));

                        $largeImage = public_path().'/uploads/banner/'.$details->image;
                        @unlink($largeImage);
                        $thumbImage = public_path().'/uploads/banner/thumbs/'.$details->image;
                        @unlink($thumbImage);

                        $update['image']= $filename;
                    }

                    $imageMobile = $request->file('mobile_image');
                    if ($imageMobile != '') {                        
                        $originalFileNameMobile   = $imageMobile->getClientOriginalName();
                        $extensionMobile          = pathinfo($originalFileNameMobile, PATHINFO_EXTENSION);
                        $filenameMobile           = 'banner_mobile_'.strtotime(date('Y-m-d H:i:s')).'.'.$extensionMobile;
                        $imageResizeMobile        = Image::make($imageMobile->getRealPath());
                        $imageResizeMobile->save(public_path('uploads/banner/' . $filenameMobile));
                        $imageResizeMobile->resize(AdminHelper::ADMIN_BANNER_MOBILE_THUMB_IMAGE_WIDTH, AdminHelper::ADMIN_BANNER_MOBILE_THUMB_IMAGE_HEIGHT,
                            function ($constraintMobile) {
                                $constraintMobile->aspectRatio();
                            });
                        $imageResizeMobile->save(public_path('uploads/banner/thumbs/' . $filenameMobile));

                        $largeImageMobile = public_path().'/uploads/banner/'.$details->mobile_image;
                        @unlink($largeImageMobile);
                        $thumbImage = public_path().'/uploads/banner/thumbs/'.$details->mobile_image;
                        @unlink($thumbImageMobile);

                        $update['mobile_image']= $filenameMobile;
                    }

                    $save = Banner::where('id', $id)->update($update);
                    if ($save) {
                        $request->session()->flash('alert-success', 'Banner has been updated successfully');
                        return redirect()->route('admin.banner.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error took place while updating the Banner');
                        return redirect()->route('admin.banner.list', ['page' => $pageNo]);
                    }
                }
            }
            return view('admin.banner.edit')->with(['details' => $details, 'data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.banner.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.banner.list');
            }
            $details = Banner::where('id', $id)->first();
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
                return redirect()->route('admin.banner.list')->with('error', 'Invalid banner');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.banner.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.banner.list');
            }

            $details = Banner::where('id', $id)->first();
            if ($details != null) {
               $delete = $details->delete();
                if ($delete) {
                    $request->session()->flash('alert-danger', 'Banner has been deleted successfully');
                    return redirect()->back();
                } else {
                    $request->session()->flash('alert-danger', 'An error occurred while deleting the banner');
                    return redirect()->back();
                }
            } else {
                $request->session()->flash('alert-danger', 'Invalid banner');
                return redirect()->back();
            }
        } catch (Exception $e) {
            return redirect()->route('admin.banner.list')->with('error', $e->getMessage());
        }
    }
    
}