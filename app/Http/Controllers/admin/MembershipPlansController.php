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
					'plan_id'           => 'required',
                    'period_id'         => 'required',
                    'amount'            => 'required|regex:/^[1-9]\d*(\.\d+)?$/',
                    'no_of_downloads'   => 'required|min:1|regex:/^[0-9]+$/',
				);
				$validationMessages = array(
					'package_id.required'       => 'Please select plan',
					'period_id.required'        => 'Please select period',
					'amount.required'           => 'Please enter amount',
                    'amount.regex'              => 'Please enter valid amount',
                    'no_of_downloads.required'  => 'Please enter number of downloads',
                    'no_of_downloads.min'       => 'Number of downloads should be at least 1',
                    'no_of_downloads.regex'     => 'Please enter valid number',
				);
				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.membershipPlan.add')->withErrors($Validator)->withInput();
				} else {
                    $checkingAlreadyExist = MembershipPlan::where(['plan_id' => $request->plan_id, 'period_id' => $request->period_id])->whereNull('deleted_at')->count();
                    if ($checkingAlreadyExist > 0) {
                        $request->session()->flash('alert-warning', 'This membership plan and period already exist');
                        return redirect()->back()->withInput();
                    } else {
                        $newMembershipPlan                  = new MembershipPlan;
                        $newMembershipPlan->plan_id         = $request->plan_id;
                        $newMembershipPlan->period_id       = $request->period_id;
                        $newMembershipPlan->amount   	    = $request->amount;
                        $newMembershipPlan->no_of_downloads =  $request->no_of_downloads;
                        $saveMembershipPlan             	= $newMembershipPlan->save();
                        if ($saveMembershipPlan) {
                            $request->session()->flash('alert-success', 'Membership plan has been added successfully');
                            return redirect()->route('admin.membershipPlan.list');
                        } else {
                            $request->session()->flash('alert-danger', 'An error occurred while adding the membership plan');
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
			return redirect()->route('admin.membershipPlan.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # Function name : edit
    # Params        : Request $request, $id
    /*****************************************************/
    public function edit(Request $request, $id = null)
    {
        $data['page_title']  = 'Edit Membership Plan';
        $data['panel_title'] = 'Edit Membership Plan';

        try
        {
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.membershipPlan.list');
                }
                
                $validationCondition = array(
					'plan_id'           => 'required',
                    'period_id'         => 'required',
                    'amount'            => 'required|regex:/^[1-9]\d*(\.\d+)?$/',
                    'no_of_downloads'   => 'required|min:1|regex:/^[0-9]+$/',
				);
				$validationMessages = array(
					'package_id.required'       => 'Please select plan',
					'period_id.required'        => 'Please select period',
					'amount.required'           => 'Please enter amount',
                    'amount.regex'              => 'Please enter valid amount',
                    'no_of_downloads.required'  => 'Please enter number of downloads',
                    'no_of_downloads.min'       => 'Number of downloads should be at least 1',
                    'no_of_downloads.regex'     => 'Please enter valid number',
				);
				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->back()->withErrors($Validator)->withInput();
				} else {
                    $checkingAlreadyExist = MembershipPlan::where('id', '!=', $id)->where(['plan_id' => $request->plan_id, 'period_id' => $request->period_id])->whereNull('deleted_at')->count();
                    if ($checkingAlreadyExist > 0) {
                        $request->session()->flash('alert-warning', 'This plan and period already exist');
                        return redirect()->back()->withInput();
                    } else {
                        $updateData = array(
                            'plan_id'           => $request->plan_id,
                            'period_id'         => $request->period_id,
                            'amount'            => $request->amount,
                            'no_of_downloads'   => $request->no_of_downloads,
                        );
                        $saveData = MembershipPlan::where('id', $id)->update($updateData);
                        if ($saveData) {
                            $request->session()->flash('alert-success', 'Membership plan has been updated successfully');
                            return redirect()->route('admin.membershipPlan.list', ['page' => $pageNo]);
                        } else {
                            $request->session()->flash('alert-danger', 'An error occurred while updating the membership plan');
                            return redirect()->route('admin.membershipPlan.list', ['page' => $pageNo]);
                        }
                    }
                }
            }
            
            $data['id']   = $id;
            $data['details'] = MembershipPlan::find($id);

            $periodList = Period::select('id','title')->where(['status' => '1'])->whereNull('deleted_at')->get();
            $data['periodList'] = $periodList;

            $planList = Plan::select('id','title')->where(['status' => '1'])->whereNull('deleted_at')->get();
            $data['planList'] = $planList;

            return view('admin.membership_plan.edit', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.membershipPlan.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.membershipPlan.list');
            }
            $details = MembershipPlan::where('id', $id)->first();
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
                return redirect()->route('admin.membershipPlan.list')->with('error', 'Invalid membership plan');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.membershipPlan.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.membershipPlan.list');
            }

            $details = MembershipPlan::where('id', $id)->first();
            if ($details != null) {
                $delete = $details->delete();
                if ($delete) {
                    $request->session()->flash('alert-danger', 'Membership plan has been deleted successfully');
                } else {
                    $request->session()->flash('alert-danger', 'An error occurred while deleting the membership plan');
                }
                return redirect()->back();
            } else {
                $request->session()->flash('alert-danger', 'Invalid membership plan');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.membershipPlan.list')->with('error', $e->getMessage());
        }
    }
    
}