<?php
/*****************************************************/
# Page/Class name   : CmsController
# Purpose           : CMS content Management
/*****************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Helper;
use Redirect;
use Validator;
use Image;
use View;
use \App\Cms;
use AdminHelper;

class CmsController extends Controller
{
    /*****************************************************/
    # Function name : list
    # Params        : Request $request
    /*****************************************************/
    function list(Request $request) {
        $data['page_title'] = 'CMS List';
        $data['panel_title']= 'CMS List';
        $pageNo = $request->input('page');
        Session::put('pageNo',$pageNo);
        $data['listData']   = Cms::orderBy('id','asc');
        $data['searchText'] = $key = $request->searchText; //search text for searching facility
        if ($key) {
            // if the search key is provided, proceed to build query for search
            $data['listData']->where(function ($q) use ($key) {
                $q->where('name', 'LIKE', '%' . $key . '%');
            });

        }
        $data['listData'] = $data['listData']->paginate(AdminHelper::ADMIN_LIST_LIMIT);
        return view('admin.cms.list', $data);
    }

    /*****************************************************/
    # CmsController
    # Function name : edit
    # Author        :
    # Created Date  : 01.04.2020
    # Purpose       : edit cms page content
    # Params        : $id, Request $request
    /*****************************************************/
    public function edit($id, Request $request)
    {
        $data['page_title']  = 'Edit CMS';
        $data['panel_title'] = 'Edit CMS';        

        try
        {
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;

            $cmsDetail = Cms::where('id',$id)->first();
            $data['id']   = $id;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.CMS.list');
                }
                // Checking validation
                $validationCondition = array(
                    'name'          => 'required|min:2|max:255',
                    // 'image'         => 'dimensions:min_width='.AdminHelper::ADMIN_CMS_THUMB_IMAGE_WIDTH.', min_height='.AdminHelper::ADMIN_CMS_THUMB_IMAGE_HEIGHT,
                    'image'         => 'mimes:jpeg,jpg,png,svg|max:'.AdminHelper::IMAGE_MAX_UPLOAD_SIZE,
                    'description'	=> 'required|min:10',
                    // 'description2'  => 'min:10',
                );
                $validationMessages = array(
                    'name.required'         => 'Please enter page name',
                    'name.min'              => 'Page name should be should be at least 2 characters',
                    'name.max'              => 'Page name should not be more than 255 characters',
                    // 'image.dimensions'       => 'Image width and height not match with min width and height',
                    'description.required'	=> 'Please enter description',
                    'description.min'       => 'Description should be should be at least 10 characters',
                    // 'description2.min'     	=> 'Description 2 should be should be at least 10 characters',
                );                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    $filename = $cmsDetail->image;
                    
                    $image = $request->file('image');
                    if ($image != '') {
                        $originalFileName =  $imageEn->getClientOriginalName();
                        $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
                        $filename = 'cms_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
                        
                        $imageResize = Image::make($image->getRealPath());
                        $imageResize->save(public_path('uploads/cms/' . $filename));
                        // // $imageResize->resize(AdminHelper::ADMIN_CMS_THUMB_IMAGE_WIDTH, AdminHelper::ADMIN_CMS_THUMB_IMAGE_HEIGHT, function ($constraint) {
                        //     $constraint->aspectRatio();
                        // });
                        $imageResize->save(public_path('uploads/cms/thumbs/' . $filename));

                        $largeImage = public_path().'/uploads/cms/'.$cmsDetail->image;
                        @unlink($largeImage);
                        $thumbImage = public_path().'/uploads/cms/thumbs/'.$cmsDetail->image;
                        @unlink($thumbImage);
                    }

                    $newSlug = Helper::generateUniqueSlug(new Cms(), $request->name, $id);
                    $updateCmsData['name']              = $request->name;
                    $updateCmsData['slug']              = $newSlug;
                    $updateCmsData['title']             = $request->title;
                    $updateCmsData['description']       = $request->description;
                    $updateCmsData['description2']      = $request->description2;
                    $updateCmsData['updated_by']        = \Auth::guard('admin')->user()->id;
                    $updateCmsData['meta_title']        = $request->meta_title;
                    $updateCmsData['meta_keyword']      = $request->meta_keyword;
                    $updateCmsData['meta_description']  = $request->meta_description;
                    
                    $saveCmsData = Cms::where('id', $id)->update($updateCmsData);                            
                    if ($saveCmsData) {
                        $request->session()->flash('alert-success', 'Cms data has been updated successfully');
                        return redirect()->route('admin.CMS.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the cms');
                        return redirect()->back();
                    }
                }
            }                
            return view('admin.cms.edit')->with(['details' => $cmsDetail, 'data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.CMS.list')->with('error', $e->getMessage());
        }
    }

}