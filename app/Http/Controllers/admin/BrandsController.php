<?php
/*****************************************************/
# Page/Class name   : BrandsController
/*****************************************************/
namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Helper;
use AdminHelper;
use App\Brand;
use App\User;
use App\BrandUser;
use App\VideoBrand;
use Image;

class BrandsController extends Controller
{
    /*****************************************************/
    # Function name : list
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'Brand List';
        $data['panel_title']= 'Brand List';
        
        try
        {
            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';
            
            $query = Brand::whereNull('deleted_at');

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
            return view('admin.brand.list', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.brand.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : add
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request) {
        $data['page_title']     = 'Add Brand';
        $data['panel_title']    = 'Add Brand';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'title'             => 'required|min:2|max:255|unique:'.(new Brand)->getTable().',title',
                    'short_description' => 'required',
                    'image'             => 'required',
                    'image'             => 'dimensions:min_width='.AdminHelper::ADMIN_BRAND_THUMB_IMAGE_WIDTH.', min_height='.AdminHelper::ADMIN_BRAND_THUMB_IMAGE_HEIGHT,
                    'image'             => 'mimes:jpeg,jpg,png,svg|max:'.AdminHelper::IMAGE_MAX_UPLOAD_SIZE,
				);
				$validationMessages = array(
					'title.required'            => 'Please enter title',
					'title.min'                 => 'Title should be should be at least 2 characters',
                    'title.max'                 => 'Title should not be more than 255 characters',
                    'short_description.required'=> 'Please enter short description',
                    'image.required'            => 'Please select image',
                    'image.dimensions'          => 'Image width and height not match with min width and height',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.brand.add')->withErrors($Validator)->withInput();
				} else {
                    $filename = '';
                    $image = $request->file('image');
                    if ($image != '') {
                        $originalFileName   = $image->getClientOriginalName();
                        $extension          = pathinfo($originalFileName, PATHINFO_EXTENSION);
                        $filename           = 'brand_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;                        
                        $imageResize        = Image::make($image->getRealPath());
                        $imageResize->save(public_path('uploads/brand/' . $filename));
                        $imageResize->resize(AdminHelper::ADMIN_BRAND_THUMB_IMAGE_WIDTH, AdminHelper::ADMIN_BRAND_THUMB_IMAGE_HEIGHT,
                            function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        $imageResize->save(public_path('uploads/brand/thumbs/' . $filename));
                    }

                    $new = new Brand;
                    $new->title             = trim($request->title, ' ');
                    $new->short_description = trim($request->short_description, ' ');
                    $new->image             = $filename;
                    $save = $new->save();
                
					if ($save) {
                        // Brand user insertion
                        if (isset($request->users) && count($request->users) > 0) {
                            foreach ($request->users as $keyUserId => $valUserId) {
                                if ($valUserId != null) {
                                    $newBrandUser = new BrandUser;
                                    $newBrandUser->brand_id = $new->id;
                                    $newBrandUser->user_id = $valUserId;
                                    $newBrandUser->save();
                                }                                
                            }
                        }

						$request->session()->flash('alert-success', 'Brand has been added successfully');
						return redirect()->route('admin.brand.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the brand');
						return redirect()->back();
					}
				}
            }
            
            $userList = User::select('id','first_name','last_name','full_name','email')->where('id', '!=', 1)->where(['status' => '1'])->get();
            $brandUserList = BrandUser::get();
            $disabledUserIds = $brandUserIds = [];
            if ($brandUserList->count() > 0) {
                foreach ($brandUserList as $bu) {
                    $brandUserIds[] = $bu->user_id;
                }
            }
            $disabledUserIds = $brandUserIds;
            // dd($brandUserIds);
            return view('admin.brand.add', $data)->with(['userList' => $userList, 'brandUserIds' => $brandUserIds, 'disabledUserIds' => $disabledUserIds]);
            
		} catch (Exception $e) {
			return redirect()->route('admin.brand.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # Function name : edit
    # Params        : Request $request, $id
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']  = 'Edit Brand';
        $data['panel_title'] = 'Edit Brand';

        try
        {           
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;

            $details = Brand::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.brand.list');
                }
                $validationCondition = array(
                    'title'             => 'required|min:2|max:255|unique:'.(new Brand)->getTable().',title,' .$id,
                    'short_description' => 'required',
                    'image'             => 'dimensions:min_width='.AdminHelper::ADMIN_BRAND_THUMB_IMAGE_WIDTH.', min_height='.AdminHelper::ADMIN_BRAND_THUMB_IMAGE_HEIGHT,
                    'image'             => 'mimes:jpeg,jpg,png,svg|max:'.AdminHelper::IMAGE_MAX_UPLOAD_SIZE,
                );
                $validationMessages = array(
                    'title.required'            => 'Please enter title',
                    'title.min'                 => 'Title should be should be at least 2 characters',
                    'title.max'                 => 'Title should not be more than 255 characters',
                    'short_description.required'=> 'Please enter short description',
                    'image.dimensions'          => 'Image width and height not match with min width and height',
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
                        $filename           = 'brand_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;                        
                        $imageResize        = Image::make($image->getRealPath());
                        $imageResize->save(public_path('uploads/brand/' . $filename));
                        $imageResize->resize(AdminHelper::ADMIN_BRAND_THUMB_IMAGE_WIDTH, AdminHelper::ADMIN_BRAND_THUMB_IMAGE_HEIGHT,
                            function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        $imageResize->save(public_path('uploads/brand/thumbs/' . $filename));

                        $largeImage = public_path().'/uploads/brand/'.$details->image;
                        @unlink($largeImage);
                        $thumbImage = public_path().'/uploads/brand/thumbs/'.$details->image;
                        @unlink($thumbImage);

                        $update['image']= $filename;
                    }

                    $save = Brand::where('id', $id)->update($update);
                    if ($save) {
                        // Brand user insertion
                        if (isset($request->users) && count($request->users) > 0) {
                            BrandUser::where('brand_id',$id)->delete();
                            foreach ($request->users as $keyUserId => $valUserId) {
                                if ($valUserId != null) {
                                    $newBrandUser = new BrandUser;
                                    $newBrandUser->brand_id = $id;
                                    $newBrandUser->user_id = $valUserId;
                                    $newBrandUser->save();
                                }
                            }
                        }

                        $request->session()->flash('alert-success', 'Brand has been updated successfully');
                        return redirect()->route('admin.brand.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error took place while updating the brand');
                        return redirect()->route('admin.brand.list', ['page' => $pageNo]);
                    }
                }
            }

            $userList = User::select('id','first_name','last_name','full_name','email')->where('id', '!=', 1)->where(['status' => '1'])->get();
            
            $brandUserList = BrandUser::get();
            $brandUserIds = [];
            if ($brandUserList->count() > 0) {
                foreach ($brandUserList as $bu) {
                    $brandUserIds[] = $bu->user_id;
                }
            }
            // echo '<pre>'; print_r($brandUserIds);

            $brandRelatedUserIds = [];
            if (count($details->brandUsers) > 0) {
                foreach ($details->brandUsers as $user) {
                    $brandRelatedUserIds[] = $user->user_id;
                }                
            }
            // print_r($brandRelatedUserIds);           

            $disabledUserIds = [];
            if(count($brandUserIds) > 0) {
                $disabledUserIds = array_diff($brandUserIds, $brandRelatedUserIds);
            }
            // dd($disabledUserIds);

            return view('admin.brand.edit')->with([
                                                'details'       => $details,
                                                'data'          => $data,
                                                'userList'      => $userList,
                                                'brandUserIds'  => $brandUserIds,
                                                'brandRelatedUserIds' => $brandRelatedUserIds,
                                                'disabledUserIds'=> $disabledUserIds
                                            ]);

        } catch (Exception $e) {
            return redirect()->route('admin.brand.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.brand.list');
            }
            $details = Brand::where('id', $id)->first();
            if ($details != null) {
                if ($details->status == 1) {
                    // Cheking brand exist in video
                    $countVideoBrand = VideoBrand::where(['brand_id' => $id])
                                            ->whereHas('videoGetDetails', function ($query) {
                                                $query->whereNull('deleted_at');
                                            })
                                            ->count();
                    if ($countVideoBrand > 0) {
                        $request->session()->flash('alert-warning', 'This brand is already associated with a video');
                    } else {
                        $details->status = '0';
                        $details->save();
                        
                        $request->session()->flash('alert-success', 'Status updated successfully');
                    }
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
                return redirect()->route('admin.brand.list')->with('error', 'Invalid brand');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.brand.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.brand.list');
            }

            $details = Brand::where('id', $id)->first();
            if ($details != null) {
                // Cheking brand exist in video
                $countVideoBrand = VideoBrand::where(['brand_id' => $id])
                                        ->whereHas('videoGetDetails', function ($query) {
                                            $query->whereNull('deleted_at');
                                        })
                                        ->count();
                if ($countVideoBrand > 0) {
                    $request->session()->flash('alert-warning', 'This brand is already associated with a video');
                } else {
                    $delete = $details->delete();
                    if ($delete) {
                        $request->session()->flash('alert-danger', 'Brand has been deleted successfully');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while deleting the brand');
                    }
                }
                return redirect()->back();
                
            } else {
                $request->session()->flash('alert-danger', 'Invalid brand');
                return redirect()->back();
            }
        } catch (Exception $e) {
            return redirect()->route('admin.brand.list')->with('error', $e->getMessage());
        }
    }
    
}