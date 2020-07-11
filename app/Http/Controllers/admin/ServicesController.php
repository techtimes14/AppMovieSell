<?php
/*****************************************************/
# Page/Class name   : ServicesController
/*****************************************************/

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Helper;
use AdminHelper;
use App\Service;
use Image;

class ServicesController extends Controller
{
    /*****************************************************/
    # Function name : list
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'Service List';
        $data['panel_title']= 'Service List';
        
        try
        {
            $pageNo = $request->input('page');
            Session::put('pageNo',$pageNo);
            
            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $query = Service::whereNull('deleted_at');

            $data['searchText'] = $key = $request->searchText;

            if ($key) {
                // if the search key is provided, proceed to build query for search
                $query->where(function ($q) use ($key) {
                    $q->where('title', 'LIKE', '%' . $key . '%');
                    
                });
            }
            $exists = $query->count();
            if ($exists > 0) {
                $list = $query->orderBy($data['order_by'], $data['order'])->paginate(AdminHelper::ADMIN_LIST_LIMIT);
                $data['list'] = $list;
            } else {
                $data['list'] = array();
            }       
            return view('admin.service.list', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.service.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : add
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request) {
        $data['page_title']     = 'Add Service';
        $data['panel_title']    = 'Add Service';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'title'             => 'required|min:2|max:255|unique:'.(new Service)->getTable().',title,NULL,NULL,deleted_at,NULL',
					'description'	    => 'required|min:10',
					'image'             => 'required',
                    'image'             => 'dimensions:min_width='.AdminHelper::ADMIN_SERVICE_THUMB_IMAGE_WIDTH.', min_height='.AdminHelper::ADMIN_SERVICE_THUMB_IMAGE_HEIGHT,
                    'image'             => 'mimes:jpeg,jpg,png,svg|max:'.AdminHelper::IMAGE_MAX_UPLOAD_SIZE,
				);
				$validationMessages = array(
					'title.required'            => 'Please enter title',
					'title.min'                 => 'Title should be should be at least 2 characters',
					'title.max'                 => 'Title should not be more than 255 characters',
					'description.required'	    => 'Please enter description',
					'description.min'           => 'Description should be should be at least 10 characters',
					'image.required'            => 'Image required',
                    'image.dimensions'          => 'Image width and height not match with min width and height',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.service.add')->withErrors($Validator)->withInput();
				} else {
                    // $newSlug = Helper::generateUniqueSlug(new Tag(), $request->name);
                    $image = $request->file('image');
                    if ($image != '') {
                        $originalFileName =  $image->getClientOriginalName();
                        $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
                        $filename = 'service_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
                        
                        $imageResize = Image::make($image->getRealPath());
                        $imageResize->save(public_path('uploads/service/' . $filename));
                        $imageResize->resize(AdminHelper::ADMIN_SERVICE_THUMB_IMAGE_WIDTH, AdminHelper::ADMIN_SERVICE_THUMB_IMAGE_HEIGHT, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $imageResize->save(public_path('uploads/service/thumbs/'.$filename));
                    }

                    $new = new service;
                    $new->title = trim($request->title, ' ');
                    $new->description  = $request->description;
                    $new->image  = $filename;
                    $save = $new->save();
                
					if ($save) {						
						$request->session()->flash('alert-success', 'service has been added successfully');
						return redirect()->route('admin.service.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the service');
						return redirect()->back();
					}
				}
			}
			return view('admin.service.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.service.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # Function name : edit
    # Params        : Request $request, $id
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']  = 'Edit Service';
        $data['panel_title'] = 'Edit Service';

        try
        {           
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;

            $details = Service::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.service.list');
                }
                $validationCondition = array(
                    'title'             => 'required|min:2|max:255|unique:'.(new Service)->getTable().',title,' .$id.',id,deleted_at,NULL',
                    'description'	    => 'required|min:10',
                );
                $validationMessages = array(
                    'title.required'            => 'Please enter title',
                    'title.min'                 => 'Title should be should be at least 2 characters',
                    'title.max'                 => 'Title should not be more than 255 characters',
                    'description.required'	    => 'Please enter description',
					'description.min'           => 'Description should be should be at least 10 characters',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    $update['title']            = trim($request->title, ' ');
                    $update['description']      = $request->description;

                    $image = $request->file('image');
                    if ($image != '') {                        
                        $originalFileName   = $image->getClientOriginalName();
                        $extension          = pathinfo($originalFileName, PATHINFO_EXTENSION);
                        $filename           = 'service_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;                        
                        $imageResize        = Image::make($image->getRealPath());
                        $imageResize->save(public_path('uploads/service/' . $filename));
                        $imageResize->resize(AdminHelper::ADMIN_SERVICE_THUMB_IMAGE_WIDTH, AdminHelper::ADMIN_SERVICE_THUMB_IMAGE_HEIGHT,
                            function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        $imageResize->save(public_path('uploads/service/thumbs/' . $filename));

                        $largeImage = public_path().'/uploads/service/'.$details->image;
                        @unlink($largeImage);
                        $thumbImage = public_path().'/uploads/service/thumbs/'.$details->image;
                        @unlink($thumbImage);

                        $update['image']= $filename;
                    }

                    $save = Service::where('id', $id)->update($update);
                    if ($save) {
                        $request->session()->flash('alert-success', 'Service has been updated successfully');
                        return redirect()->route('admin.service.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the service');
                         return redirect()->back();;
                    }
                }
            }
            return view('admin.service.edit')->with(['details' => $details, 'data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.service.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.service.list');
            }
            $details = Service::where('id', $id)->first();
            if ($details != null) {
                if ($details->status == 1) {                    
                    $details->status = '0';
                    $details->save();
                        
                    $request->session()->flash('alert-success', 'Status updated successfully');
                } else if ($details->status == 0) {
                    $details->status = '1';
                    $details->save();

                    $request->session()->flash('alert-success', 'Status updated successfully');
                } else {
                    $request->session()->flash('alert-danger', 'Something went wrong, please try again later');
                }
                return redirect()->back();
            } else {
                return redirect()->route('admin.service.list')->with('error', 'Invalid service');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.service.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.service.list');
            }

            $details = Service::where('id', $id)->first();
            if ($details != null) {
                $delete = $details->delete();
                if ($delete) {
                    $request->session()->flash('alert-danger', 'Service has been deleted successfully');
                } else {
                    $request->session()->flash('alert-danger', 'An error occurred while deleting the service');
                }
            } else {
                $request->session()->flash('alert-danger', 'Invalid service');                
            }
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->route('admin.service.list')->with('error', $e->getMessage());
        }
    }
    
}
