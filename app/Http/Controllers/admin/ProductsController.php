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
use App\Category;
use App\ProductImage;
use Auth;
use Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

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
            $pageNo = $request->input('page');
            Session::put('pageNo',$pageNo);
            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $query = Product::with(['productDefaultImage'])->whereNull('deleted_at');

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
                    'title' => 'required|min:2|max:255|unique:'.(new Product)->getTable().',title,NULL,NULL,deleted_at,NULL',
                    'description' => 'required|min:2|max:255',
                    'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                    'category_id' => 'required',
				);
				$validationMessages = array(
					'title.required'    => 'Please enter title',
					'title.min'         => 'Title should be should be at least 2 characters',
                    'title.max'         => 'Title should not be more than 255 characters',
                    'description.required'    => 'Please enter description',
					'description.min'         => 'Description should be should be at least 2 characters',
                    'description.max'         => 'Description should not be more than 255 characters',
                    'price.required'          => 'Please enter Price',
                    'category_id.required'    => 'Please select Category',
                    
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
                    $new->category_id = $request->category_id;
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
            $categoryList = Category::select('id','title')->where(['status' => '1'])->whereNull('deleted_at')->get();
            $data['categoryList'] = $categoryList;
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
        {   $categoryList = Category::select('id','title')->where(['status' => '1'])->whereNull('deleted_at')->get();
            $data['categoryList'] = $categoryList;        
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.product.list');
                }
                $validationCondition = array(
                    'title'         => 'required|min:2|max:255|unique:' .(new Product)->getTable().',title,' .$id.',id,deleted_at,NULL',
                    'description'   => 'required|min:2|max:255',
                    'price'         => 'required|regex:/^\d+(\.\d{1,2})?$/',
                    'category_id'   => 'required',
                );
                $validationMessages = array(
                    'title.required'          => 'Please enter title',
					'title.min'               => 'Title should be should be at least 2 characters',
                    'title.max'               => 'Title should not be more than 255 characters',
                    'description.required'    => 'Please enter description',
					'description.min'         => 'Description should be should be at least 2 characters',
                    'description.max'         => 'Description should not be more than 255 characters',
                    'price.required'          => 'Please enter Price',
                    'category_id.required'    => 'Please select Category',
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
                        'category_id' => $request->category_id,
                        'slug'  => $newSlug
                    ); 
                    $save = Product::where('id', $id)->update($update);                        
                    if ($save) {
                        if (isset($request->feature_value) && $request->feature_value > 0) {
                            $deleteProductFeature = ProductFeature::where('product_id',$id)->delete();
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
                        $request->session()->flash('alert-danger', 'An error occurred while updating the product');
                        return redirect()->back();
                    }
                }
            }
            
            $details = Product::find($id);
            $data['id'] = $id;
            return view('admin.product.edit')->with(['details' => $details, 'data' => $data, 'categoryList' =>$categoryList]);

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
                } else if ($details->status == 0) {
                    $details->status = '1';
                    $details->save();

                    $request->session()->flash('alert-success', 'Status updated successfully');
                } else {
                    $request->session()->flash('alert-danger', 'Something went wrong');
                }
                
                return redirect()->back();
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
            } else {
                $request->session()->flash('alert-danger', 'Invalid product');
                
            }
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->route('admin.product.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : deleteProductFeature
    # Params        : Request $request
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

    public function multifileupload($productId = null){
        $data['page_title']     = 'Product Image Upload';
        $data['panel_title']    = 'Product Image Upload';


        $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
        $data['pageNo'] = $pageNo;

        $products = Product::find($productId);
        $data['products']   = $products;
        return view('admin.product.product_image', $data);
    }

    public function store($product_id = null, Request $request){
        $productId = $product_id;
        $uploadSuccess = false;

        $image = $request->file('file');
        if ($image != null) {
            $filename = $image->getClientOriginalName();
            $location = public_path('/uploads/product/'.$filename);
            if(Image::make($image)->resize(AdminHelper::ADMIN_PRODUCT_SLIDER_IMAGE_WIDTH,AdminHelper::ADMIN_PRODUCT_SLIDER_IMAGE_HEIGHT)->save($location)){
                $product_image = [];
                $product_image['product_id'] = $product_id;
                $product_image['image']      = $filename;

                $thumbLocation = public_path('/uploads/product/thumbs/'.$filename);
                Image::make($image)->resize(AdminHelper::ADMIN_PRODUCT_THUMB_IMAGE_WIDTH,AdminHelper::ADMIN_PRODUCT_THUMB_IMAGE_HEIGHT)->save($thumbLocation);

                $listLocation = public_path('/uploads/product/list_thumbs/'.$filename);
                Image::make($image)->resize(AdminHelper::ADMIN_PRODUCT_THUMB_IMAGE_WIDTH,AdminHelper::ADMIN_PRODUCT_THUMB_IMAGE_HEIGHT)->save($listLocation);

                $countDefaultImage = ProductImage::where(['product_id' => $product_id, 'default_image' => 'Y'])->count();
                if($countDefaultImage == 0){
                    $product_image['default_image'] = 'Y';
                }
                if(ProductImage::create($product_image)){
                    $uploadSuccess = $filename;
                }
            }
        }

        if ($uploadSuccess) {
            return response()->json(['success'=>$uploadSuccess]);
        }
        // Else, return error 400
        else {
            return response()->json('error', 400);
        }
    }

    public function imageDelete(Request $request){
        $filename =  $request->filename;
        ProductImage::where(['image'=>$filename])->delete();
        $path = public_path('/uploads/product/'.$filename);
        $path = public_path('/uploads/product/thumb/'.$filename);
        if (file_exists($path)) {
            unlink($path);
        }
        return $filename;
    }

    public function deleteProductImage($id = null, $product_id = null, Request $request){
        if($id == null){
            return redirect()->route('admin.dashboard');
        }
        
        $get_image_data = ProductImage::where(['id' => $id,'product_id'=>$product_id])->first();
        // dd($get_image_data);

        if($get_image_data->default_image == 'N'){
            @unlink(public_path() . '/uploads/product/' . $get_image_data->image);
            @unlink(public_path() . '/uploads/product/list_thumbs/' . $get_image_data->image);
            @unlink(public_path() . '/uploads/product/thumbs/' . $get_image_data->image);
            $get_image_data->delete();
            $request->session()->flash('alert-success', 'Image successfully deleted.');
            return redirect()->back();
        }else{
            $request->session()->flash('alert-danger', 'Sorry! Default image can not deleted.');
            return redirect()->back();
        }

    }

    public function makeDefaultImage(Request $request){

        $product_id = base64_decode($request->product_id);
        // dd($product_id);
        $image_id = base64_decode($request->image_id);
        $res = 0;
        if($product_id >0 && $image_id >0){
            if(ProductImage::where(['product_id' => $product_id])->update(['default_image' => 'N'])){

                if(ProductImage::where(['product_id' => $product_id,'id'=>$image_id])->update(['default_image' => 'Y'])){
                    $res = 1;
                }

            }else{
                $res = 0;
            }
        }
        echo $res;
    }

    public function productUpload(Request $request){
        $products = new ProductImage;
        return view('admin.product.product_image', ['products' => $products]);
    }
    
}
