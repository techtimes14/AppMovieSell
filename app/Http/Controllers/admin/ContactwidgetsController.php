<?php
/*****************************************************/
# Page/Class name   : ContactwidgetsController
/*****************************************************/

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Helper;
use AdminHelper;
use App\ContactWidget;
use Image;

class ContactwidgetsController extends Controller
{
    /*****************************************************/
    # Function name : list
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'Contact Widget List';
        $data['panel_title']= 'Contact Widget List';
        
        try
        {
            $pageNo = $request->input('page');
            Session::put('pageNo',$pageNo);
            
            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $query = ContactWidget::whereNull('deleted_at');

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
            return view('admin.contactwidget.list', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.contactwidget.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : add
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request) {
        $data['page_title']     = 'Add Contact Widget';
        $data['panel_title']    = 'Add Contact Widget';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'title'             => 'required|min:2|max:255|unique:'.(new ContactWidget)->getTable().',title',
                    'description'	    => 'required|min:10',
                    'icon_class'        => 'required',
				);
				$validationMessages = array(
					'title.required'            => 'Please enter title',
					'title.min'                 => 'Title should be should be at least 2 characters',
					'title.max'                 => 'Title should not be more than 255 characters',
					'description.required'	    => 'Please enter description',
					'description.min'           => 'Description should be should be at least 10 characters',
					'icon_class.required'       => 'Icon class required',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.contactwidget.add')->withErrors($Validator)->withInput();
				} else {

                    $new = new ContactWidget;
                    $new->title = trim($request->title, ' ');
                    $new->description  = $request->description;
                    $new->icon_class  = $request->icon_class;
                    $save = $new->save();
                
					if ($save) {						
						$request->session()->flash('alert-success', 'Contactwidget has been added successfully');
						return redirect()->route('admin.contactwidget.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the contactwidget');
						return redirect()->back();
					}
				}
			}
			return view('admin.contactwidget.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.contactwidget.list')->with('error', $e->getMessage());
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

            $details = ContactWidget::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.contactwidget.list');
                }
                $validationCondition = array(
                    'title'             => 'required|min:2|max:255|unique:'.(new ContactWidget)->getTable().',title,' .$id,
                    'description'	    => 'required|min:10',
                    'icon_class'        => 'required',
                );
                $validationMessages = array(
                    'title.required'            => 'Please enter title',
                    'title.min'                 => 'Title should be should be at least 2 characters',
                    'title.max'                 => 'Title should not be more than 255 characters',
                    'description.required'	    => 'Please enter description',
                    'description.min'           => 'Description should be should be at least 10 characters',
                    'icon_class.required'       => 'Icon class required',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    $update['title']            = trim($request->title, ' ');
                    $update['description']      = $request->description;
                    $update['icon_class']       = $request->icon_class;

                    $save = ContactWidget::where('id', $id)->update($update);
                    if ($save) {
                        $request->session()->flash('alert-success', 'Contactwidget has been updated successfully');
                        return redirect()->route('admin.contactwidget.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the Contactwidget');
                         return redirect()->back();;
                    }
                }
            }
            return view('admin.contactwidget.edit')->with(['details' => $details, 'data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.contactwidget.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.contactwidget.list');
            }
            $details = ContactWidget::where('id', $id)->first();
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
                return redirect()->route('admin.contactwidget.list')->with('error', 'Invalid contactwidget');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.contactwidget.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.contactwidget.list');
            }

            $details = ContactWidget::where('id', $id)->first();
            if ($details != null) {
                $delete = $details->delete();
                if ($delete) {
                    $request->session()->flash('alert-danger', 'Contactwidget has been deleted successfully');
                } else {
                    $request->session()->flash('alert-danger', 'An error occurred while deleting the contactwidget');
                }
            } else {
                $request->session()->flash('alert-danger', 'Invalid contactwidget');                
            }
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->route('admin.contactwidget.list')->with('error', $e->getMessage());
        }
    }
    
}
