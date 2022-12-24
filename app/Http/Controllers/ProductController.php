<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Traits\HttpResponseTraits;
use DataTables;

use Illuminate\Support\Str;

class ProductController extends Controller
{
    use HttpResponseTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $products = Product::getAllProduct();
    //     return view('backend.product.index')->with('products', $products);
    // }

    public function index(Request $request)
    {
        // $data = Product::with(['cat_info', 'sub_cat_info', 'barcode_info'])->orderBy('id', 'desc')->get();
        // dd($data);
        if ($request->ajax()) {
            $data = Product::with(['cat_info', 'sub_cat_info', 'barcode_info'])->orderBy('id', 'desc');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('select_orders', static function ($data) {
                    return '<input type="checkbox" name="rowID[]" class="rowID" value="'.$data->id.'"/>';
                })
                ->addColumn('cat_info_title', function($data){
                    $cat_info_title = ($data->cat_info != null) ? $data->cat_info->title : '';
                    // if ($data->sub_cat_info) {
                    //     $cat_info_title .= '<sub>'.$data->sub_cat_info->title.'</sub>';
                    // }
                    return $cat_info_title;
                })->addColumn('is_featured', function($data){
                    return $data->is_featured == 1 ? 'Yes' : 'No';
                })->addColumn('product_price', function($data){
                    return 'Rs. '.$data->price.' /-';
                })->addColumn('product_discount', function($data){
                    return $data->discount .'% OFF';
                })->addColumn('product_stock', function($data){
                    if($data->stock > 0){
                        return '<span class="badge badge-primary">'.$data->stock.'</span>';
                    } else {
                        return '<span class="badge badge-danger">'.$data->stock.'</span>';
                    }
                })->addColumn('product_photo', function($data){

                    return '<img src="'.$data->photo[0].'" class="img-fluid zoom"
                    style="max-width:80px" alt="'.$data->photo[0].'">';
                })->addColumn('info_status', function($data){
                    // if ($data->status == 'active'){
                    //     return '<span class="badge badge-success">'.$data->status.'</span>';
                    // } else {
                    //     return '<span class="badge badge-warning">'.$data->status.'</span>';
                    // }

                    $checked = ($data->status == 'active') ? 'checked' : '';
                    return '<div class="custom-control custom-switch">
                        <input class="custom-control-input changeStatus" type="checkbox"
                            data-id="'.$data->id.'" id="customSwitch-'.$data->id.'"
                            '.$checked.'>
                        <label class="custom-control-label" for="customSwitch-'.$data->id.'"></label>
                    </div>';

                })->addColumn('action', function($data){

                    $actionData = '<div class="product_action"><a href="'.route('product.create', $data->id).'"
                        class="btn btn-primary btn-sm float-left mr-1"
                        style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                        title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="'.route('product.destroy', [$data->id]).'">
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button class="btn btn-danger btn-sm dltBtn" data-id='.$data->id.'
                            style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                            data-placement="bottom" title="Delete"><i
                                class="fas fa-trash-alt"></i></button>
                    </form></div>';

                    return $actionData;
                })->addColumn('barcode_image', function($data){
                    return ($data->barcode_info != null) ? '<a href="' . route('barcode.list', $data->id) . '"><img src="' . $data->barcode_info->getImage() . '" class="img-fluid zoom"
                    style="max-width:80px" alt="' . $data->photo[0] . '"></a>' : '<a href="' . route('barcode.list', $data->id) . '"><img style="max-width:80px" src="' . asset('backend/img/edit-barcode.png') . '" alt="" /></a>';
                    //return '<a href="'.route('barcode.list', $data->id).'"><img src="'.$data->photo[0].'" class="img-fluid" style="max-width:80px" alt="'.$data->photo[0].'"></a>';
                })
                ->rawColumns(['select_orders','cat_info_title','is_featured','product_price','product_discount','product_stock','product_photo','barcode_image','info_status','action'])
                ->make(true);
        }
        return view('backend.product.index');
    }

    /**
     * Common Method Used For Create Or Edit Form
     */
    public function createOrEdit($id = null)
    {
        $product = $id ? Product::findOrFail($id) : null;
        $category = Category::get();
        $items = $id ? Product::where('id', $id)->get() : null;
        $brands = Brand::where('status', 'active')->get();
        return view('backend.product.productCommonPage')->with([
            'product' => $product,
            'categories' => $category,
            'items' => $items,
            'brands' => $brands,
        ]);
    }

    /**
     * Common Method Used For Store Or Update Data
     */
    public function storeOrUpdate(Request $request, $id = null)
    {
        $fileCheck = null;
        if (!empty($request->previous_image)) {
            $fileCheck += 1;
        }

        if (!empty($request->filter_image)) {
            $fileCheck += 1;
        }
        //dd($fileCheck);
        $product = $id ? Product::findOrFail($id) : new Product;
        $validPhoto = $fileCheck ? 'sometimes' : 'required';
        $this->validate($request, [
            'title' => 'string|required|max:50|min:3',
            'summary' => 'string|required',
            'description' => 'string|nullable',
            'photo' => $validPhoto,
            //'photo.*' => 'mimes:jpg,png,jpeg,gif,svg',
            //'photo' => $validPhoto,
            //'size' => 'nullable',
            //'stock' => "required|numeric",
            'cat_id' => 'required|exists:categories,id',
            //'child_cat_id' => 'nullable|exists:categories,id',
            //'is_featured' => 'sometimes|in:1',
            'status' => 'required|in:active,inactive',
            //'condition' => 'required|in:default,new,hot',
            'price' => 'required|numeric',
            //'discount' => 'nullable|numeric'
        ]);

        $filter_image_data = [];
        if (!empty($request->filter_image)) {
            foreach ($request->filter_image as $image) {
                $explode_value = explode(",", $image);
                $filter_image_data[] = $explode_value[1];
            }
        }

        $remove_deleted_value_from_image_array = [];
        if (!empty($request->photo)) {
            foreach ($request->photo as $image_all) {
                $image1 = base64_encode(file_get_contents($image_all));
                if (in_array($image1, $filter_image_data)) {
                    $remove_deleted_value_from_image_array[] = $image_all;
                }
            }
        }
        $data = $request->all();
        if (!$id) {
            $slug = Str::slug($request->title);
            $count = Product::where('slug', $slug)->count();
            if ($count > 0) {
                $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
            }
            $data['slug'] = $slug;
        }
        $data['is_featured'] = $request->input('is_featured', 0);
        $data['discount'] = 0;
        $data['condition'] = 'default';
        $data['stock'] = 0;
        //$data['child_cat_id'] = '';
        $size = $request->input('size',0);
        if ($size) {
            $data['size'] = implode(',', $size);
        } else {
            $data['size'] = '';
        }

        $image_name = [];

        if (count($remove_deleted_value_from_image_array) > 0) {
            foreach ($remove_deleted_value_from_image_array as $imagefile) {
                $now = date('ymds') . '-';
                $imageName = $now . AppHelper::replaceSpaceIntoDash($imagefile->getClientOriginalName());
                $imagefile->storeAs(config('path.product'), $imageName);

                $image_name[] = $imageName;
            }
        }
        if (!empty($request->previous_image)) {
            foreach ($request->previous_image as $value) {
                $image_name[] = $value;
            }
        }

        $data['photo'] = implode(",", $image_name);
        //dd($data);

        // Upload Image
        // if ($request->hasFile('photo')) {
        //     $now = date('ymds') . '-';
        //     $photo = $request->file('photo');
        //     $photoName = $now . AppHelper::replaceSpaceIntoDash($photo->getClientOriginalName());
        //     $request->photo->storeAs(config('path.product'), $photoName);
        //     $data['photo'] = $photoName;
        // }

        $status = $product->fill($data)->save();
        if ($status) {
            $msg = $id ? 'Product Successfully updated' : 'Product Successfully added';
            request()->session()->flash('success', $msg);
        } else {
            request()->session()->flash('error', 'Please try again!!');
        }
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $status = $product->delete();

        if ($status) {
            request()->session()->flash('success', 'Product successfully deleted');
        } else {
            request()->session()->flash('error', 'Error while deleting product');
        }
        return redirect()->route('product.index');
    }

    public function changeStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        //dd($status);
        $dataRow = Product::find($id);
        if (empty($dataRow)) {
            return $this->failure('Record not found!');
        }
        $dataRow->status = ($status == 1) ?  'active' : 'inactive';
        if ($dataRow->save()) {
            return $this->success('Product staus successfully updated.');
        }
        return $this->failure('Please try again!!');
    }

    public function deleteMultipleRecord(Request $request)
    {
        $status = Product::whereIn('id', $request->ids)->delete();
        if ($status > 0) {
            return $this->success('Data successfully deleted');
        } else {
            return $this->failure('Error while deleting Data');
        }
        return $this->failure('Please try again!!');
    }
}
