<?php
/*****************************************************/
# Page/Class name   : CategoriesController
/*****************************************************/
namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Helper;
use AdminHelper;
use App\Category;
use App\VideoCategory;

class CategoriesController extends Controller
{
    /*****************************************************/
    # Function name : list
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'Category List';
        $data['panel_title']= 'Category List';
        
        try
        {
            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $query = Category::whereNull('deleted_at');

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
            return view('admin.category.list', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.category.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : add
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request) {
        $data['page_title']     = 'Add Category';
        $data['panel_title']    = 'Add Category';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
					'title' => 'required|min:2|max:255|unique:'.(new Category)->getTable().',title',
				);
				$validationMessages = array(
					'title.required'    => 'Please enter title',
					'title.min'         => 'Title should be should be at least 2 characters',
					'title.max'         => 'Title should not be more than 255 characters',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.category.add')->withErrors($Validator)->withInput();
				} else {
                    $newSlug = Helper::generateUniqueSlug(new Category(), $request->title);

                    $new = new Category;
                    $new->title = trim($request->title, ' ');
                    $new->slug  = $newSlug;
                    $save = $new->save();
                
					if ($save) {						
						$request->session()->flash('alert-success', 'Category has been added successfully');
						return redirect()->route('admin.category.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the category');
						return redirect()->back();
					}
				}
			}
			return view('admin.category.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.category.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # Function name : edit
    # Params        : Request $request, $id
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']  = 'Edit Category';
        $data['panel_title'] = 'Edit Category';

        try
        {           
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.category.list');
                }
                $validationCondition = array(
                    'title'         => 'required|min:2|max:255|unique:' .(new Category)->getTable().',title,' .$id,
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
                    $newSlug = Helper::generateUniqueSlug(new Category(), $request->title, $id);

                    $update = array(
                        'title' => trim($request->title, ' '),
                        'slug'  => $newSlug
                    ); 
                    $save = Category::where('id', $id)->update($update);                        
                    if ($save) {
                        $request->session()->flash('alert-success', 'Category has been updated successfully');
                        return redirect()->route('admin.category.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error took place while updating the category');
                        return redirect()->route('admin.category.list', ['page' => $pageNo]);
                    }
                }
            }
            
            $details = Category::find($id);
            $data['id'] = $id;
            return view('admin.category.edit')->with(['details' => $details, 'data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.category.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.category.list');
            }
            $details = Category::where('id', $id)->first();
            if ($details != null) {
                if ($details->status == 1) {
                    // Cheking category exist in video
                    $countVideoCategory = VideoCategory::where(['category_id' => $id])
                                            ->whereHas('videoGetDetails', function ($query) {
                                                $query->whereNull('deleted_at');
                                            })
                                            ->count();
                    if ($countVideoCategory > 0) {
                        $request->session()->flash('alert-warning', 'This category is already associated with a video');
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
                return redirect()->route('admin.category.list')->with('error', 'Invalid category');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.category.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.category.list');
            }

            $details = Category::where('id', $id)->first();
            if ($details != null) {

                // Cheking category exist in video
                $countVideoCategory = VideoCategory::where(['category_id' => $id])
                                        ->whereHas('videoGetDetails', function ($query) {
                                            $query->whereNull('deleted_at');
                                        })
                                        ->count();
                if ($countVideoCategory > 0) {
                    $request->session()->flash('alert-warning', 'This category is already associated with a video');
                } else {
                    $delete = $details->delete();
                    if ($delete) {
                        $request->session()->flash('alert-danger', 'Category has been deleted successfully');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while deleting the category');
                    }
                }
                return redirect()->back();
                
            } else {
                $request->session()->flash('alert-danger', 'Invalid category');
                return redirect()->back();
            }
        } catch (Exception $e) {
            return redirect()->route('admin.category.list')->with('error', $e->getMessage());
        }
    }
    
}