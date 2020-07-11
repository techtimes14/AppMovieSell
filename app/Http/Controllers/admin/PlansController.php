<?php
/*****************************************************/
# Page/Class name   : PlansController
# Purpose           : Package related operations
/*****************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Plan;
use App\PlanFeature;
use App\MembershipPlan;
use Helper;
use AdminHelper;

class PlansController extends Controller
{
    /*****************************************************/
    # Function name : list
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'Plan List';
        $data['panel_title']= 'Plan List';
                
        try
        {
            $pageNo = $request->input('page');
            Session::put('pageNo',$pageNo);
            
            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $query = Plan::whereNull('deleted_at');

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
            return view('admin.plan.list', $data);           

        } catch (Exception $e) {
            return redirect()->route('admin.plan.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : add
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request)
    {
        $data['page_title']     = 'Add Plan';
        $data['panel_title']    = 'Add Plan';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				// Checking validation
				$validationCondition = array(
					'title'             => 'required|min:2|max:255|unique:'.(new Plan)->getTable().',title,NULL,NULL,deleted_at,NULL',
				); // validation condition
				$validationMessages = array(
					'title.required'            => 'Please enter title',
					'title.min'                 => 'Title should be should be at least 2 characters',
					'title.max'                 => 'Title should not be more than 255 characters',
				);
				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.plan.add')->withErrors($Validator)->withInput();
				} else {
                    $newSlug = Helper::generateUniqueSlug(new Plan(), trim($request->title, ' '));

                    $newPlan        = new Plan;
                    $newPlan->title = trim($request->title, ' ');
                    $newPlan->slug  = $newSlug;
					$savePlan       = $newPlan->save();
					if ($savePlan) {
						if (isset($request->features) && count($request->features) > 0) {
                            foreach ($request->features as $val) {
                                $newPlanFeature = new PlanFeature();
                                $newPlanFeature->plan_id = $newPlan->id;
                                $newPlanFeature->feature = $val;
                                $newPlanFeature->save();
                            }
                        }
						
						$request->session()->flash('alert-success', 'Plan has been added successfully');
						return redirect()->route('admin.plan.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the plan');
						return redirect()->back();
					}
				}
			}
			return view('admin.plan.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.plan.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # Function name : edit
    # Params        : Request $request, $id
    /*****************************************************/
    public function edit(Request $request, $id = null)
    {
        $data['page_title']  = 'Edit Plan';
        $data['panel_title'] = 'Edit Plan';

        $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
        $data['pageNo'] = $pageNo;

        try
        {
            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.plan.list');
                }
                // Checking validation
                $validationCondition = array(
                    'title'         => 'required|min:2|max:255|unique:' .(new Plan)->getTable().',title,' .$id.',id,deleted_at,NULL',
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
                    $newSlug = Helper::generateUniqueSlug(new Plan(), trim($request->title, ' '), $id);

                    $updateData = array(
                        'title' => trim($request->title, ' '),
                        'slug'  => $newSlug,
                    );
                    $save = Plan::where('id', $id)->update($updateData);
                    if ($save) {
                        $deletePlanFeatures = PlanFeature::where('plan_id',$id)->delete();
                        
                        if (isset($request->features) && count($request->features) > 0) {
                            foreach ($request->features as $val) {
                                $newPlanFeature = new PlanFeature();
                                $newPlanFeature->plan_id = $id;
                                $newPlanFeature->feature = $val;
                                $newPlanFeature->save();
                            }
                        }
                        
                        $request->session()->flash('alert-success', 'Plan data has been updated successfully');
                        return redirect()->route('admin.plan.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the plan');
                        return redirect()->route('admin.plan.list', ['page' => $pageNo]);
                    }
                }
            }
            
            $planDetail = Plan::find($id);
            $data['id']   = $id;
            return view('admin.plan.edit')->with(['details' => $planDetail, 'data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.plan.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.plan.list');
            }
            $details = Plan::where('id', $id)->first();
            if ($details != null) {
                if ($details->status == 1) {
                    // Checking this period is already assigned to membership plan
                    $membershipPlanCount = MembershipPlan::where('plan_id', $id)->count();
                    if ($membershipPlanCount > 0) {
                        $request->session()->flash('alert-warning', 'This plan is already assigned to membership plan');
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
                } else {
                    $request->session()->flash('alert-danger', 'Something went wrong, please try again later');
                }
                return redirect()->back();
            } else {
                return redirect()->route('admin.plan.list')->with('error', 'Invalid plan');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.plan.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.plan.list');
            }

            $details = Plan::where('id', $id)->first();
            if ($details != null) {
                // Checking this period is already assigned to membership plan
                $membershipPlanCount = MembershipPlan::where('plan_id', $id)->count();
                if ($membershipPlanCount > 0) {
                    $request->session()->flash('alert-warning', 'This plan is already assigned to membership plan');
                    return redirect()->back();
                }                
                $delete = $details->delete();
                if ($delete) {
                    $request->session()->flash('alert-danger', 'Plan has been deleted successfully');
                } else {
                    $request->session()->flash('alert-danger', 'An error occurred while deleting the plan');
                }
            } else {
                $request->session()->flash('alert-danger', 'Invalid plan');
            }
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->route('admin.plan.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : deletePlanFeature
    # Params        : Request $request
    /*****************************************************/
    public function deletePlanFeature(Request $request) {
        $featureId = $request->feature_id;
        $planId   = $request->plan_id;

        $result['has_error']= 1;
        $result['status']   = 1;
        $result['message']  = 'Something went wrong, please try again later';

        $details = PlanFeature::where(['id' => $featureId, 'plan_id' => $planId])->first();
        if ($details != null) {
            // checking atleast one feature exist or not
            $checkingFeatureExist = PlanFeature::where(['plan_id' => $planId])->where('id', '!=', $featureId)->count();
            if ($checkingFeatureExist == 0) {
                $result['has_error']= 1;
                $result['status']   = 0;
                $result['message']  = 'There must be at least one feature';
            } else {
                PlanFeature::where(['id' => $featureId, 'plan_id' => $planId])->delete();

                $result['has_error']= 0;
                $result['status']   = 0;
                $result['message']  = '';
            }
        }
        echo json_encode($result);
    }
    
}