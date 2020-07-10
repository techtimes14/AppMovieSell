<?php
/*****************************************************/
# PackagesController
# Page/Class name   : PackagesController
# Author            :
# Created Date      : 18-03-2020
# Functionality     : list, add, edit, change status,
#                     delete
# Purpose           : Package related operations
/*****************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Package;
use App\PackageLocal;
use App\PackagePeriod;
use App\PackageDuration;
use App\Coupon;
use Helper;
use AdminHelper;

class PackagesController extends Controller
{
    /*****************************************************/
    # PackagesController
    # Function name : list
    # Author        :
    # Created Date  : 18-03-2020
    # Purpose       : Showing list of packages
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'Package List';
        $data['panel_title']= 'Package List';
        $data['order_by']   = 'created_at';
        $data['order']      = 'desc';
        
        try
        {
            $packageQuery = Package::whereNull('deleted_at');

            $data['searchText'] = $key = $request->searchText;
            $data['searchText'] = $key1 = $request->searchText;
            
            if ($key) {
                // if the search key is provided, proceed to build query for search
                $packageQuery->where(function ($q) use ($key) {
                    $q->where('title', 'LIKE', '%' . $key . '%');
                    $q->orWhere('grace_period', 'LIKE', '%' . $key . '%');
                    $q->orWhere('allow_user', 'LIKE', '%' . $key . '%');
                    
                });
            }
           

            $packageExists = $packageQuery->count();
            if ($packageExists > 0) {
                $packageList = $packageQuery->orderBy($data['order_by'], $data['order'])
                                            ->paginate(AdminHelper::ADMIN_PACKAGE_LIMIT);
                $data['packageList'] = $packageList;
            } else {
                $data['packageList'] = array();
            }   
                    
            return view('admin.package.list', $data);
            

        } catch (Exception $e) {
            return redirect()->route('admin.package.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # PackagesController
    # Function name : add
    # Author        :
    # Created Date  : 18-03-2020
    # Purpose       : Add a package
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request)
    {
        $data['page_title']     = 'Add Package';
        $data['panel_title']    = 'Add Package';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				// Checking validation
				$validationCondition = array(
					'title'             => 'required|min:2|max:255|unique:'.(new Package)->getTable().',title',
					'title_ar'          => 'required|min:2|max:255|unique:'.(new PackageLocal)->getTable().',local_title',
					'description_en'	=> 'required|min:10',
					'description_ar' 	=> 'required|min:10',
					'grace_period'      => 'required',
					'allow_user'        => 'required',
				); // validation condition
				$validationMessages = array(
					'title.required'            => 'Please enter title',
					'title.min'                 => 'Title should be should be at least 2 characters',
					'title.max'                 => 'Title should not be more than 255 characters',
					'title_ar.required'         => 'Please enter title (arabic)',
					'title_ar.min'              => 'Title (arabic) should be should be at least 2 characters',
					'title_ar.max'              => 'Title (arabic) should not be more than 255 characters',
					'description_en.required'	=> 'Please enter description',
					'description_en.min'        => 'Description should be should be at least 10 characters',
					'description_ar.required'	=> 'Please enter description (arabic)',
					'description_ar.min'     	=> 'Description (arabic) should be should be at least 10 characters',
					'grace_period.required'     => 'Please select grace period',
					'allow_user.required'       => 'Please enter allow user',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.package.add')->withErrors($Validator)->withInput();
				} else {
					$newPackage              	= new Package;
					$newPackage->title       	= trim($request->title, ' ');
					$newPackage->grace_period	= $request->grace_period;
					$newPackage->allow_user   	= $request->allow_user;
					$savePackage             	= $newPackage->save();
					
					if ($savePackage) {
						$insertedPackageId   	= $newPackage->id;

						foreach(AdminHelper::ADMIN_LANGUAGES as $language){
							$newPackageLocal            	= new PackageLocal;
							$newPackageLocal->package_id   	= $insertedPackageId;
							$newPackageLocal->lang_code 	= $language;
							if ($language == 'EN') {
								$newPackageLocal->local_title = trim($request->title, ' ');
								$newPackageLocal->local_description = $request->description_en;
							} else {
								$newPackageLocal->local_title = trim($request->title_ar, ' ');
								$newPackageLocal->local_description = $request->description_ar;
							}
							$savePackageLocal = $newPackageLocal->save();
						}
						$request->session()->flash('alert-success', 'Package has been added successfully');
						return redirect()->route('admin.package.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the package');
						return redirect()->back();
					}
				}
			}
			return view('admin.package.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.package.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # PackageController
    # Function name : edit
    # Author        :
    # Created Date  : 18-03-2020
    # Purpose       : Edit a Package 
    # Params        : Request $request, $id
    /*****************************************************/
    public function edit(Request $request, $id = null)
    {
        $data['page_title']  = 'Edit Package';
        $data['panel_title'] = 'Edit Package';

        $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
        $data['pageNo'] = $pageNo;

        try
        {
            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.packagePeriod.list');
                }
                // Checking validation
                $validationCondition = array(
                    'title'         => 'required|min:2|max:255|unique:' .(new Package)->getTable().',title,' .$id,
                    'title_ar'      => 'required|min:2|max:255|unique:'.(new PackageLocal)->getTable().',local_title,'.$id.',package_id',
                    'description_en'	=> 'required|min:10',
					'description_ar' 	=> 'required|min:10',
					'grace_period'      => 'required',
					'allow_user'        => 'required',
                    
                );
                $validationMessages = array(
                    'title.required'    => 'Please enter title',
                    'title.min'         => 'Title should be should be at least 2 characters',
                    'title.max'         => 'Title should not be more than 255 characters',
                    'title_ar.required' => 'Please enter title (arabic)',
                    'title_ar.min'      => 'Title (arabic) should be should be at least 2 characters',
                    'title_ar.max'      => 'Title (arabic) should not be more than 255 characters',
                    'description_en.required'	=> 'Please enter description',
					'description_en.min'        => 'Description should be should be at least 10 characters',
					'description_ar.required'	=> 'Please enter description (arabic)',
                    'description_ar.min'     	=> 'Description (arabic) should be should be at least 10 characters',
                    'grace_period.required'     => 'Please select grace period',
					'allow_user.required'       => 'Please enter allow user',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    $updatePackageData = array(
                        'title'  => trim($request->title, ' '),
                        'grace_period' => $request->grace_period,
                        'allow_user' => $request->allow_user

                    );
                    $savePackageData = Package::where('id', $id)->update($updatePackageData);
                    if ($savePackageData) {
                        $deletePackageLocal = PackageLocal::where('package_id',$id)->delete();
                        
                        //Insertion into Package Period Local (for langauage)
                        foreach(AdminHelper::ADMIN_LANGUAGES as $language){
                            $newPackageLocal            = new PackageLocal;
                            $newPackageLocal->package_id   = $id;
                            $newPackageLocal->lang_code = $language;
                            if ($language == 'EN') {
                                $newPackageLocal->local_title = trim($request->title, ' ');
								$newPackageLocal->local_description = $request->description_en;
                               
                            } else {
                                $newPackageLocal->local_title = trim($request->title_ar, ' ');
								$newPackageLocal->local_description = $request->description_ar;
                               
                            }
                            $savePackageLocal = $newPackageLocal->save();
                        }
                        
                        $request->session()->flash('alert-success', 'Package data has been updated successfully');
                        return redirect()->route('admin.package.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error took place while updating the Package');
                        return redirect()->route('admin.package.list', ['page' => $pageNo]);
                    }
                }
            }
            
            $packageDetail = Package::find($id);
            $data['id']   = $id;
            if (count($packageDetail->local) > 0) {
                foreach ($packageDetail->local as $packageLocal) {
                    if ($packageLocal->lang_code === 'EN') {
                        $data['title']             = $packageLocal->local_title;
                        $data['description_en']    = $packageLocal->local_description;
                    } else {
                        $data['title_ar']             = $packageLocal->local_title;
                        $data['description_ar']       = $packageLocal->local_description;  
                    }
                }
            }
            return view('admin.package.edit')->with(['details' => $packageDetail, 'data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.package.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # PackageController
    # Function name : status
    # Author        :
    # Created Date  : 18-03-2020
    # Purpose       : Change Status a Package 
    # Params        : Request $request, $id
    /*****************************************************/
    public function status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.package.list');
            }
            $details = Package::where('id', $id)->first();
            if ($details != null) {
                if ($details->status == 1) {
                    // Checking this package  is already assigned to package duration.
                    $packageDurationCount = PackageDuration::where('package_id', $id)->count();
                    if ($packageDurationCount > 0) {
                        $request->session()->flash('alert-danger', 'This package is already assigned to package duration');
                        return redirect()->back();
                    }

                    // Checking this package  is already assigned to coupon.
                    $couponCount = Coupon::where('package_id', $id)->count();
                    if ($couponCount > 0) {
                        $request->session()->flash('alert-danger', 'This package is already assigned to coupon');
                        return redirect()->back();
                    }
                    
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
                return redirect()->route('admin.package.list')->with('error', 'Invalid package');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.package.list')->with('error', $e->getMessage());
        }
    }

     /*****************************************************/
    # Package Controller
    # Function name : delete
    # Author        :
    # Created Date  : 18-03-2020
    # Purpose       : Delete a Package 
    # Params        : Request $request, $id
    /*****************************************************/
    public function delete(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.package.list');
            }

            $details = Package::where('id', $id)->first();
            if ($details != null) {
                // Checking this package  is already assigned to Package duration
                $packageDurationCount = PackageDuration::where('package_id', $id)->count();
                if ($packageDurationCount > 0) {
                    $request->session()->flash('alert-danger', 'This package is already assigned to package duration');
                    return redirect()->back();
                }
                //Checking this package is already assigned to coupon.
                $couponCount = Coupon::where('package_id', $id)->count();
                if($couponCount > 0){
                    $request->session()->flash('alert-danger', 'This package already assigned to coupon');
                    return redirect()->back();

                    }

                $deletepackage = $details->delete();
                if ($deletepackage) {
                    $request->session()->flash('alert-danger', 'Package has been deleted successfully');
                    return redirect()->back();
                } else {
                    $request->session()->flash('alert-danger', 'An error occurred while deleting the package');
                    return redirect()->back();
                }
            } else {
                $request->session()->flash('alert-danger', 'Invalid package');
                return redirect()->back();
            }
        } catch (Exception $e) {
            return redirect()->route('admin.package.list')->with('error', $e->getMessage());
        }
    }

    
}