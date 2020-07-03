<?php
/*****************************************************/
# Page/Class name   : TagsController
/*****************************************************/

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Helper;
use AdminHelper;
use App\Product;
use App\ProductFeature;

class ProductsController extends Controller
{
    /*****************************************************/
    # Function name : list
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'Product List';
        $data['panel_title']= 'Product List';
        
        try
        {
            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $query = Product::whereNull('deleted_at');

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
            return view('admin.product.list', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.product.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : add
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request) {
        $data['page_title']     = 'Add Product';
        $data['panel_title']    = 'Add Product';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'title' => 'required|min:2|max:255|unique:'.(new Product)->getTable().',title',
                    'description' => 'required|min:2|max:255',
                    'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
				);
				$validationMessages = array(
					'title.required'    => 'Please enter title',
					'title.min'         => 'Title should be should be at least 2 characters',
                    'title.max'         => 'Title should not be more than 255 characters',
                    'description.required'    => 'Please enter description',
					'description.min'         => 'Description should be should be at least 2 characters',
                    'description.max'         => 'Description should not be more than 255 characters',
                    'price.required'          => 'Please enter Price',
                    
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.product.add')->withErrors($Validator)->withInput();
				} else {
                    $newSlug = Helper::generateUniqueSlug(new Product(), $request->title);

                    $new = new Product;
                    $new->title = trim($request->title, ' ');
                    $new->description = $request->description;
                    $new->price = $request->price;
                    $new->slug  = $newSlug;
                    $save = $new->save();
                    
                
					if ($save) {
                        $insertedId   	                  = $new->id;
                        foreach ($request->feature_label as $key => $val) {
                            $newProductFeature                 = new ProductFeature;
                            $newProductFeature->product_id     = $insertedId;
                            $newProductFeature->feature_label  = $val;
                            $newProductFeature->feature_value  = $request->feature_value[$key];
                            $saveProductFeature                = $newProductFeature->save();
                        }
                        
                        

						$request->session()->flash('alert-success', 'Product has been added successfully');
						return redirect()->route('admin.product.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the product');
						return redirect()->back();
					}
				}
			}
			return view('admin.product.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.product.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # Function name : edit
    # Params        : Request $request, $id
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']  = 'Edit Product';
        $data['panel_title'] = 'Edit Product';

        try
        {           
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.product.list');
                }
                $validationCondition = array(
                    'title'         => 'required|min:2|max:255|unique:' .(new Product)->getTable().',title,' .$id,
                    'description'   => 'required|min:2|max:255',
                    'price'         => 'required|regex:/^\d+(\.\d{1,2})?$/',
                );
                $validationMessages = array(
                    'title.required'          => 'Please enter title',
					'title.min'               => 'Title should be should be at least 2 characters',
                    'title.max'               => 'Title should not be more than 255 characters',
                    'description.required'    => 'Please enter description',
					'description.min'         => 'Description should be should be at least 2 characters',
                    'description.max'         => 'Description should not be more than 255 characters',
                    'price.required'          => 'Please enter Price',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {   
                    $newSlug = Helper::generateUniqueSlug(new Product(), $request->title, $id);

                    $update = array(
                        'title' => trim($request->title, ' '),
                        'description' => $request->description,
                        'price' => $request->price,
                        'slug'  => $newSlug
                    ); 
                    $save = Product::where('id', $id)->update($update);                        
                    if ($save) {
                        $deleteProductFeature = ProductFeature::where('product_id',$id)->delete();
                        if (array_key_exists('feature_label', $request)) {
                            foreach ($request->feature_label as $key => $val) {
                                $newProductFeature                 = new ProductFeature;
                                $newProductFeature->product_id     = $id;
                                $newProductFeature->feature_label  = $val;
                                $newProductFeature->feature_value  = $request->feature_value[$key];
                                $saveProductFeature                = $newProductFeature->save();
                            }
                        }

                        $request->session()->flash('alert-success', 'Product has been updated successfully');
                        return redirect()->route('admin.product.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error took place while updating the product');
                        return redirect()->route('admin.product.list', ['page' => $pageNo]);
                    }
                }
            }
            
            $details = Product::find($id);
            $data['id'] = $id;
            return view('admin.product.edit')->with(['details' => $details, 'data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.product.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.product.list');
            }
            $details = Product::where('id', $id)->first();
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
                return redirect()->route('admin.product.list')->with('error', 'Invalid product');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.product.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.product.list');
            }

            $details = Product::where('id', $id)->first();
            if ($details != null) {

               
                    $delete = $details->delete();
                    if ($delete) {
                        $request->session()->flash('alert-danger', 'Product has been deleted successfully');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while deleting the product');
                    }
                
                return redirect()->back();
                
            } else {
                $request->session()->flash('alert-danger', 'Invalid product');
                return redirect()->back();
            }
        } catch (Exception $e) {
            return redirect()->route('admin.product.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : deleteProductFeature
    # Params        : Request $request, $id
    /*****************************************************/
    public function deleteProductFeature(Request $request)
    {
        try
        {
            $title      = 'Error';
            $message    = 'Something went wrong, please try again later';
            $type       = 'error';

            if ($request->isMethod('POST')) {
                $details = ProductFeature::where('id', $request->product_feature_id)->first();
                if ($details != null) {                
                    $delete = $details->delete();
                    if ($delete) {
                        $title      = 'Success';
                        $message    = 'Product feature has been deleted successfully';
                        $type       = 'success';
                    }
                }
            }
            
        } catch (Exception $e) {            
        }

        return response()->json(array(
            'title'     => $title,
            'message'   => $message,
            'type'      => $type,
        ));
    }
    
}
