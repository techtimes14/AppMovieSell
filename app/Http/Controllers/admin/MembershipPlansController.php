<?php
/*****************************************************/
# Page/Class name   : MembershipPlansController
# Purpose           : Package related operations
/*****************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Period;
use App\Plan;
use App\MembershipPlan;
use Helper;
use AdminHelper;

class MembershipPlansController extends Controller
{
    /*****************************************************/
    # Function name : list
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request)
    {
        $data['page_title'] = 'Membership Plan List';
        $data['panel_title']= 'Membership Plan List';
        
        try
        {
            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $pageNo = $request->input('page');
            Session::put('pageNo',$pageNo);

            $query = MembershipPlan::whereNull('deleted_at');

            $data['searchText'] = $key = $request->searchText;
            
            if ($key) {
                // if the search key is provided, proceed to build query for search
                $query->where(function ($q) use ($key) {
                    $q->where('amount', 'LIKE', '%' . $key . '%');
                });
            }

            $exists = $query->count();
            if ($exists > 0) {
                $list = $query->orderBy($data['order_by'], $data['order'])->paginate(AdminHelper::ADMIN_LIST_LIMIT);
                $data['list'] = $list;
            } else {
                $data['list'] = array();
            }
            return view('admin.membership_plan.list', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.membershipPlan.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : add
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request)
    {
        $data['page_title']     = 'Add Membership Plan';
        $data['panel_title']    = 'Add Membership Plan';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
					'package_id'        => 'required',
                    'package_period_id' => 'required',
                    'amount'            => 'required|regex:/^[1-9]\d*(\.\d+)?$/',
				);
				$validationMessages = array(
					'package_id.required'       => 'Please select package',
					'package_period_id.required'=> 'Please select package period',
					'amount.required'           => 'Please enter amount',
                    'amount.regex'              => 'Please enter valid amount',
				);
				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.packageDuration.add')->withErrors($Validator)->withInput();
				} else {
                    $checkingAlreadyExist = PackageDuration::where(['package_id' => $request->package_id, 'package_period_id' => $request->package_period_id])->whereNull('deleted_at')->count();
                    if ($checkingAlreadyExist > 0) {
                        $request->session()->flash('alert-danger', 'This package and period already exist');
                        return redirect()->back()->withInput();
                    } else {
                        $newPackageDuration                     = new PackageDuration;
                        $newPackageDuration->package_id         = $request->package_id;
                        $newPackageDuration->package_period_id	= $request->package_period_id;
                        $newPackageDuration->amount   	        = $request->amount;
                        $savePackageDuration             	    = $newPackageDuration->save();					
                        if ($savePackageDuration) {
                            $request->session()->flash('alert-success', 'Package duration has been added successfully');
                            return redirect()->route('admin.packageDuration.list');
                        } else {
                            $request->session()->flash('alert-danger', 'An error occurred while adding the package duration');
                            return redirect()->back()->withInput();
                        }
                    }
				}
            }
            
            $periodList = Period::select('id','title')->where(['status' => '1'])->whereNull('deleted_at')->get();
            $data['periodList'] = $periodList;

            $planList = Plan::select('id','title')->where(['status' => '1'])->whereNull('deleted_at')->get();
            $data['planList'] = $planList;

			return view('admin.membership_plan.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.membership_plan.list')->with('error', $e->getMessage());
		}        
    }

    /*****************************************************/
    # MembershipPlansController
    # Function name : edit
    # Author        :
    # Created Date  : 18-03-2020
    # Purpose       : Edit a package duration
    # Params        : Request $request, $id
    /*****************************************************/
    public function edit(Request $request, $id = null)
    {
        $data['page_title']  = 'Edit Package Duration';
        $data['panel_title'] = 'Edit Package Duration';

        $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
        $data['pageNo'] = $pageNo;

        try
        {
            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.packagePeriod.list');
                }
                
                $validationCondition = array(
					'package_id'        => 'required',
                    'package_period_id' => 'required',
                    'amount'            => 'required|regex:/^[1-9]\d*(\.\d+)?$/',
				);
				$validationMessages = array(
					'package_id.required'       => 'Please select package',
					'package_period_id.required'=> 'Please select package period',
					'amount.required'           => 'Please enter amount',
                    'amount.regex'              => 'Please enter valid amount',
				);
				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->back()->withErrors($Validator)->withInput();
				} else {
                    $checkingAlreadyExist = PackageDuration::where('id', '!=', $id)->where(['package_id' => $request->package_id, 'package_period_id' => $request->package_period_id])->whereNull('deleted_at')->count();
                    if ($checkingAlreadyExist > 0) {
                        $request->session()->flash('alert-danger', 'This package and period already exist');
                        return redirect()->back()->withInput();
                    } else {
                        $updatePackageDurationData = array(
                            'package_id'        => $request->package_id,
                            'package_period_id' => $request->package_period_id,
                            'amount'            => $request->amount
                        );
                        $savePackageDurationData = PackageDuration::where('id', $id)->update($updatePackageDurationData);
                        if ($savePackageDurationData) {
                            $request->session()->flash('alert-success', 'Package duration has been updated successfully');
                            return redirect()->route('admin.packageDuration.list', ['page' => $pageNo]);
                        } else {
                            $request->session()->flash('alert-danger', 'An error took place while updating the package duration');
                            return redirect()->route('admin.packageDuration.list', ['page' => $pageNo]);
                        }
                    }
                }
            }
            
            $data['id']   = $id;
            $data['packageDurationDetail'] = PackageDuration::find($id);

            $packagePeriodList = PackagePeriod::select('id','title')->where(['status' => '1'])->whereNull('deleted_at')->get();
            $data['packagePeriodList'] = $packagePeriodList;

            $packageList = Package::select('id','title')->where(['status' => '1'])->whereNull('deleted_at')->get();
            $data['packageList'] = $packageList;

            return view('admin.package_duration.edit', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.packageDuration.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # MembershipPlansController
    # Function name : status
    # Author        :
    # Created Date  : 18-03-2020
    # Purpose       : Change Status a package duration
    # Params        : Request $request, $id
    /*****************************************************/
    public function status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.packageDuration.list');
            }
            $details = PackageDuration::where('id', $id)->first();
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
                return redirect()->route('admin.packageDuration.list')->with('error', 'Invalid package duration');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.packageDuration.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # MembershipPlansController
    # Function name : delete
    # Author        :
    # Created Date  : 18-03-2020
    # Purpose       : Delete a package duration
    # Params        : Request $request, $id
    /*****************************************************/
    public function delete(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.packageDuration.list');
            }

            $details = PackageDuration::where('id', $id)->first();
            if ($details != null) {
                $deletePackageDuration = $details->delete();
                if ($deletePackageDuration) {
                    $request->session()->flash('alert-danger', 'Package duration has been deleted successfully');
                    return redirect()->back();
                } else {
                    $request->session()->flash('alert-danger', 'An error occurred while deleting the package duration');
                    return redirect()->back();
                }
            } else {
                $request->session()->flash('alert-danger', 'Invalid package duration');
                return redirect()->back();
            }
        } catch (Exception $e) {
            return redirect()->route('admin.packageDuration.list')->with('error', $e->getMessage());
        }
    }
    
}