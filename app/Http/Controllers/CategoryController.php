<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Helpers\SohamErpApiHelper;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Traits\HttpResponseTraits;
use DataTables;
use Excel;

class CategoryController extends Controller
{
    use HttpResponseTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     // $categoryList = SohamErpApiHelper::getAllCategory();
    //     // $category = !empty($categoryList) ? collect($categoryList) : [];
    //     $category = Category::getAllCategory();
    //     return view('backend.category.index')->with('categories', $category);
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            
            $data = Category::with('parent_info');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('select_orders', static function ($data) {
                    return '<input type="checkbox" name="rowID[]" class="rowID" value="'.$data->id.'"/>';
                })
                ->addColumn('is_parent', function($data){
                    return $data->is_parent == 1 ? 'Yes' : 'No';
                })->addColumn('is_featured', function($data){
                    return ($data->is_featured == 1) ? 'Is Featured' : '';
                })->addColumn('image', function($data){
                    return '<img src="'.$data->photo.'" class="img-fluid" style="max-width:80px" alt="'.$data->title.'">';
                })->addColumn('info_status', function($data){
                    // if($data->status == 'active'){
                    //     $info_status = '<span class="badge badge-success">'.$data->status.'</span>';
                    // } else {
                    //     $info_status = '<span class="badge badge-warning">'.$data->status.'</span>';
                    // }

                    $checked = ($data->status == 'active') ? 'checked' : '';
                    $info_status = '<div class="custom-control custom-switch">
                        <input class="custom-control-input changeStatus" type="checkbox"
                            data-id="'.$data->id.'" id="customSwitch-'.$data->id.'"
                            '.$checked.'>
                        <label class="custom-control-label" for="customSwitch-'.$data->id.'"></label>
                    </div>';
                    return $info_status;
                })->addColumn('action', function($data){
                    $actionData = '<a href="'.route("category.create", $data->id).'"
                            class="btn btn-primary btn-sm float-left mr-1"
                            style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                            title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="'.route("category.destroy", [$data->id]).'">
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button class="btn btn-danger btn-sm dltBtn" data-id='.$data->id.'
                                style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                data-placement="bottom" title="Delete"><i
                                    class="fas fa-trash-alt"></i></button>
                        </form>';
                    return $actionData;
                })
                ->rawColumns(['select_orders','is_parent','parent_title','image','info_status','action'])
                ->make(true);
        }
        return view('backend.category.index');
    }

    /**
     * Common Method Used For Create Or Edit Form
     */
    public function createOrEdit($id = null)
    {
        $parent_cats = Category::where('is_parent', 1)->orderBy('title', 'ASC')->get();
        $category = $id ? Category::findOrFail($id) : null;
        return view('backend.category.commonCategoryPage')->with('category', $category)->with('parent_cats', $parent_cats);
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

        $category = $id ? Category::findOrFail($id) : new Category;
        $validPhoto = $fileCheck ? 'image|sometimes' : 'image|required';
        $this->validate($request, [
            'title' => 'string|required|max:50|min:3',
            'summary' => 'string|nullable',
            'photo' => $validPhoto,
            'status' => 'required|in:active,inactive',
            // 'is_parent' => 'sometimes|in:1,0',
            // 'parent_id' => 'nullable|exists:categories,id',
        ]);
        $data = $request->all();
        $data['is_parent'] = $request->input('is_parent', 0);
        $data['is_featured'] = $request->input('is_featured', 0);
        if ($data['is_parent'] != 0) {
            $data['parent_id'] = null;
        }
        if (!$id) {
            $slug = Str::slug($request->title);
            $count = Category::where('slug', $slug)->count();
            if ($count > 0) {
                $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
            }
            $data['slug'] = $slug;
        }

        $filter_image_data = '';
        if (!empty($request->filter_image)) {
            $image = $request->filter_image;
            $explode_value = explode(",", $image);
            $filter_image_data = $explode_value[1];
        }

        $remove_deleted_value_from_image_array = '';
        if (!empty($request->photo)) {
            $image_all = $request->photo;
            $image1 = base64_encode(file_get_contents($image_all));
            if ($image1 == $filter_image_data) {
                $remove_deleted_value_from_image_array = $image_all;
            }
        }

        $image_name = '';

        if ($remove_deleted_value_from_image_array != '') {
            $imagefile = $remove_deleted_value_from_image_array;
            $now = date('ymds') . '-';
            $imageName = $now . AppHelper::replaceSpaceIntoDash($imagefile->getClientOriginalName());
            $imagefile->storeAs(config('path.category'), $imageName);
            $image_name = $imageName;
        }
        if (!empty($request->previous_image)) {
            $image_name = $request->previous_image;
        }
        $data['photo'] = $image_name;

        // Upload Image
        // if ($request->hasFile('photo')) {
        //     $now = date('ymds') . '-';
        //     $photo = $request->file('photo');
        //     $photoName = $now . AppHelper::replaceSpaceIntoDash($photo->getClientOriginalName());
        //     $request->photo->storeAs(config('path.category'), $photoName);
        //     $data['photo'] = $photoName;
        // }
        $status = $category->fill($data)->save();
        if ($status) {
            $msg = $id ? 'Category successfully updated' : 'Category successfully added';
            request()->session()->flash('success', $msg);
        } else {
            request()->session()->flash('error', 'Error occurred, Please try again!');
        }
        return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $child_cat_id = Category::where('parent_id', $id)->pluck('id');
        $status = $category->delete();

        if ($status) {
            if (count($child_cat_id) > 0) {
                Category::shiftChild($child_cat_id);
            }
            request()->session()->flash('success', 'Category successfully deleted');
        } else {
            request()->session()->flash('error', 'Error while deleting category');
        }
        return redirect()->route('category.index');
    }

    public function getChildByParent(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $child_cat = Category::getChildByParentID($request->id);
        if (count($child_cat) <= 0) {
            return response()->json(['status' => false, 'msg' => '', 'data' => null]);
        } else {
            return response()->json(['status' => true, 'msg' => '', 'data' => $child_cat]);
        }
    }

    public function ExportCategoryCSV(Request $request)
    {
        try {
            $fileName = "Add_Category.csv";
            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );
    
            $columns = array('Title', 'Summary', 'Is Parent (1 Yes , 2 No)', 'Status(active,inactive)');
    
            $callback = function() use($columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                fclose($file);
            };
    
            return response()->stream($callback, 200, $headers);
        } catch (Exception $error) {
            throw $error;
        }
    }

    public function changeStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        //dd($status);
        $dataRow = Category::find($id);
        if (empty($dataRow)) {
            return $this->failure('Record not found!');
        }
        $dataRow->status = ($status == 1) ?  'active' : 'inactive';
        if ($dataRow->save()) {
            return $this->success('Category staus successfully updated.');
        }
        return $this->failure('Please try again!!');
    }

    public function deleteMultipleCategory(Request $request)
    {
        $status = Category::whereIn('id', $request->ids)->delete();
        if ($status > 0) {
            return $this->success('Data successfully deleted');
        } else {
            return $this->failure('Error while deleting Data');
        }
        return $this->failure('Please try again!!');
    }
}
