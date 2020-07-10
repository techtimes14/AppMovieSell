<?php
/*****************************************************/
# Page/Class name   : PeriodsController
# Purpose           : 
/*****************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Period;
use Helper;
use AdminHelper;

class PeriodsController extends Controller
{
    /*****************************************************/
    # Function name : list
    # Params        : Request $request
    /*****************************************************/    
    public function list(Request $request)
    {
        $data['page_title'] = 'Period List';
        $data['panel_title']= 'Period List';

        try
        {
            $pageNo = $request->input('page');
            Session::put('pageNo',$pageNo);
            
            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $query = Period::whereNull('deleted_at');

            $data['searchText'] = $key = $request->searchText;

            if ($key) {
                // if the search key is provided, proceed to build query for search
                $query->where(function ($q) use ($key) {
                    $q->where('title', 'LIKE', '%' . $key . '%');
                    $q->orWhere('period','LIKE', '%'.$key.'%');                    
                });
            }
            $exists = $query->count();
            if ($exists > 0) {
                $list = $query->orderBy($data['order_by'], $data['order'])->paginate(AdminHelper::ADMIN_LIST_LIMIT);
                $data['list'] = $list;
            } else {
                $data['list'] = array();
            }       
            return view('admin.period.list', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.period.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : add
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request)
    {
        $data['page_title']     = 'Add Period';
        $data['panel_title']    = 'Add Period';

        try
        {
            if ($request->isMethod('POST'))
            {
                // Checking validation
                $validationCondition = array(
                    'title'         => 'required|min:2|max:255|unique:'.(new Period)->getTable().',title,NULL,NULL,deleted_at,NULL',
                    'period'        => 'required|regex:/^[0-9]+$/',
                );
                $validationMessages = array(
                    'title.required'    => 'Please enter title',
                    'title.min'         => 'Title should be should be at least 2 characters',
                    'title.max'         => 'Title should not be more than 255 characters',
                    'period.required'   => 'Please enter period',
                    'period.regex'      => 'Period must consist only numbers',
                );

                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->route('admin.period.add')->withErrors($Validator)->withInput();
                } else {
                    $newPeriod              = new Period;
                    $newPeriod->title       = trim($request->title, ' ');
                    $newPeriod->period      = $request->period;
                    $savePeriod             = $newPeriod->save();
                    if ($savePeriod) {
                        $insertedPeriodId   = $newPeriod->id;

                        $request->session()->flash('alert-success', 'Period has been added successfully');
                        return redirect()->route('admin.period.list');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while adding the period');
                        return redirect()->back();
                    }
                }
            }
            return view('admin.period.add', $data);

        } catch (Exception $e) {
            return redirect()->route('admin.period.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : edit
    # Params        : Request $request, $id
    /*****************************************************/
    public function edit(Request $request, $id = null)
    {
        $data['page_title']  = 'Edit Period';
        $data['panel_title'] = 'Edit Period';

        try
        {
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.period.list');
                }
                // Checking validation
                $validationCondition = array(
                    'title'         => 'required|min:2|max:255|unique:' .(new Period)->getTable().',title,' .$id.',id,deleted_at,NULL','period'        => 'required|regex:/^[0-9]+$/',
                );
                $validationMessages = array(
                    'title.required'    => 'Please enter title',
                    'title.min'         => 'Title should be should be at least 2 characters',
                    'title.max'         => 'Title should not be more than 255 characters',
                    'period.required'   => 'Please enter period',
                    'period.regex'      => 'Period must consist only numbers',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    $updateData = array(
                        'title'  => trim($request->title, ' '),
                        'period' => $request->period
                    );
                    $save = Period::where('id', $id)->update($updateData);
                    if ($save) {
                        $request->session()->flash('alert-success', 'Period data has been updated successfully');
                        return redirect()->route('admin.period.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the period');
                        return redirect()->back();
                    }
                }
            }
            
            $periodDetail = Period::find($id);
            $data['id']   = $id;
            return view('admin.period.edit')->with(['details' => $periodDetail, 'data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.period.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.period.list');
            }
            $details = Period::where('id', $id)->first();
            if ($details != null) {
                if ($details->status == 1) {
                    // // Checking this package period is already assigned to Package duration
                    // $packageDurationCount = PackageDuration::where('package_period_id', $id)->count();
                    // if ($packageDurationCount > 0) {
                    //     $request->session()->flash('alert-danger', 'This package period is already assigned to package duration');
                    //     return redirect()->back();
                    // }

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
                    $request->session()->flash('alert-danger', 'Something went wrong, please try again later');
                    return redirect()->back();
                }
            } else {
                return redirect()->route('admin.period.list')->with('error', 'Invalid period');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.period.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.period.list');
            }

            $details = Period::where('id', $id)->first();
            if ($details != null) {
                // Checking this package period is already assigned to Package duration
                // $packageDurationCount = PackageDuration::where('package_period_id', $id)->count();
                // if ($packageDurationCount > 0) {
                //     $request->session()->flash('alert-danger', 'This package period is already assigned to package duration');
                //     return redirect()->back();
                // }
                
                $delete = Period::find($id)->delete();
                if ($delete) {
                    $request->session()->flash('alert-danger', 'Period has been deleted successfully');
                    return redirect()->back();
                } else {
                    $request->session()->flash('alert-danger', 'An error occurred while deleting the period');
                    return redirect()->back();
                }
            } else {
                $request->session()->flash('alert-danger', 'Invalid period');
                return redirect()->back();
            }
        } catch (Exception $e) {
            return redirect()->route('admin.period.list')->with('error', $e->getMessage());
        }
    }

}