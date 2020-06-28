<?php
/*****************************************************/
# Page/Class name   : TagsController
/*****************************************************/
namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Helper;
use AdminHelper;
use App\Tag;
use App\VideoTag;

class TagsController extends Controller
{
    /*****************************************************/
    # Function name : list
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'Tag List';
        $data['panel_title']= 'Tag List';
        
        try
        {
            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';
            
            $query = Tag::whereNull('deleted_at');

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
            return view('admin.tag.list', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.tag.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : add
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request) {
        $data['page_title']     = 'Add Tag';
        $data['panel_title']    = 'Add Tag';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
					'title' => 'required|min:2|max:255|unique:'.(new Tag)->getTable().',title',

				);
				$validationMessages = array(
					'title.required'    => 'Please enter title',
					'title.min'         => 'Title should be should be at least 2 characters',
					'title.max'         => 'Title should not be more than 255 characters',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.tag.add')->withErrors($Validator)->withInput();
				} else {
                    $newSlug = Helper::generateUniqueSlug(new Tag(), $request->title);

                    $new = new Tag;
                    $new->title = trim($request->title, ' ');
                    $new->slug  = $newSlug;
                    $save = $new->save();
                
					if ($save) {						
						$request->session()->flash('alert-success', 'Tag has been added successfully');
						return redirect()->route('admin.tag.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the tag');
						return redirect()->back();
					}
				}
			}
			return view('admin.tag.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.tag.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # Function name : edit
    # Params        : Request $request, $id
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']  = 'Edit Tag';
        $data['panel_title'] = 'Edit Tag';

        try
        {           
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.tag.list');
                }
                $validationCondition = array(
                    'title'         => 'required|min:2|max:255|unique:' .(new Tag)->getTable().',title,' .$id,
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
                    $newSlug = Helper::generateUniqueSlug(new Tag(), $request->title, $id);

                    $update = array(
                        'title' => trim($request->title, ' '),
                        'slug'  => $newSlug
                    ); 
                    $save = Tag::where('id', $id)->update($update);                        
                    if ($save) {
                        $request->session()->flash('alert-success', 'Tag has been updated successfully');
                        return redirect()->route('admin.tag.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error took place while updating the tag');
                        return redirect()->route('admin.tag.list', ['page' => $pageNo]);
                    }
                }
            }
            
            $details = Tag::find($id);
            $data['id'] = $id;
            return view('admin.tag.edit')->with(['details' => $details, 'data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.tag.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.tag.list');
            }
            $details = Tag::where('id', $id)->first();
            if ($details != null) {
                if ($details->status == 1) {
                    // Cheking tag exist in video
                    $countVideoTag = VideoTag::where(['tag_id' => $id])
                                            ->whereHas('videoGetDetails', function ($query) {
                                                $query->whereNull('deleted_at');
                                            })
                                            ->count();
                    if ($countVideoTag > 0) {
                        $request->session()->flash('alert-warning', 'This tag is already associated with a video');
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
                return redirect()->route('admin.tag.list')->with('error', 'Invalid tag');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.tag.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.tag.list');
            }

            $details = Tag::where('id', $id)->first();
            if ($details != null) {

                // Cheking tag exist in video
                $countVideoTag = VideoTag::where(['tag_id' => $id])
                                        ->whereHas('videoGetDetails', function ($query) {
                                            $query->whereNull('deleted_at');
                                        })
                                        ->count();
                if ($countVideoTag > 0) {
                    $request->session()->flash('alert-warning', 'This tag is already associated with a video');
                } else {
                    $delete = $details->delete();
                    if ($delete) {
                        $request->session()->flash('alert-danger', 'Tag has been deleted successfully');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while deleting the tag');
                    }                    
                }
                return redirect()->back();
                
            } else {
                $request->session()->flash('alert-danger', 'Invalid tag');
                return redirect()->back();
            }
        } catch (Exception $e) {
            return redirect()->route('admin.tag.list')->with('error', $e->getMessage());
        }
    }
    
}