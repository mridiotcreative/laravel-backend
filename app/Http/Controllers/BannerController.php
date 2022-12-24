<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use App\Traits\HttpResponseTraits;
use DataTables;

class BannerController extends Controller
{
    use HttpResponseTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $banner = Banner::orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));
    //     return view('backend.banner.index')->with('banners', $banner);
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Banner::orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('select_orders', static function ($data) {
                    return '<input type="checkbox" name="rowID[]" class="rowID" value="'.$data->id.'"/>';
                })
                ->addColumn('bphoto', function($data){
                    $bphoto = '';
                    if($data->photo) {
                        $bphoto .= '<img src="'.$data->getImage().'" class="img-fluid zoom"
                            style="max-width:80px" alt="'.$data->title.'">';
                    } else {
                        $bphoto .= '<img src="'.asset('backend/img/thumbnail-default.jpg').'"
                            class="img-fluid zoom" style="max-width:100%" alt="avatar.png">';
                    }
                    
                    return $bphoto;
                })
                ->addColumn('info_status', function($data){
                    // $info_status = '';
                    // if($data->status == 'active'){
                    //     $info_status .= '<span class="badge badge-success">'.$data->status.'</span>';
                    // } else {
                    //     $info_status .= '<span class="badge badge-warning">'.$data->status.'</span>';
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

                    $actionData = '<a href="'.route('banner.create', $data->id).'"
                        class="btn btn-primary btn-sm float-left mr-1"
                        style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                        title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="'.route('banner.destroy', [$data->id]).'">
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button class="btn btn-danger btn-sm dltBtn" data-id='.$data->id.' style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                    </form>';

                    return $actionData;
                })
                ->rawColumns(['select_orders','bphoto','info_status','action'])
                ->make(true);
        }
        return view('backend.banner.index');
    }

    /**
     * Common Method Used For Create Or Edit Form
     */
    public function createOrEdit($id = null)
    {
        $banner = $id ? Banner::findOrFail($id) : null;
        return view('backend.banner.commonBannerPage')->with('banner', $banner);
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
        $banner = $id ? Banner::findOrFail($id) : new Banner;
        $validPhoto = $fileCheck ? 'image|sometimes' : 'image|required';
        $this->validate($request, [
            'title' => 'string|required|max:50|min:3',
            'description' => 'string|nullable',
            'photo' => $validPhoto,
            'status' => 'required|in:active,inactive',
        ]);
        $data = $request->all();
        if (!$id) {
            $slug = Str::slug($request->title);
            $count = Banner::where('slug', $slug)->count();
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
            $imagefile->storeAs(config('path.banner'), $imageName);
            $image_name = $imageName;
        }
        if (!empty($request->previous_image)) {
            $image_name = $request->previous_image;
        }
        $data['photo'] = $image_name;
        //dd($data);
        // Upload Image
        // if ($request->hasFile('photo')) {
        //     $now = date('ymds') . '-';
        //     $photo = $request->file('photo');
        //     $photoName = $now . AppHelper::replaceSpaceIntoDash($photo->getClientOriginalName());
        //     $request->photo->storeAs(config('path.banner'), $photoName);
        //     $data['photo'] = $photoName;
        // }
        $status = $banner->fill($data)->save();
        if ($status) {
            $msg = $id ? 'Banner successfully updated' : 'Banner successfully added';
            request()->session()->flash('success', $msg);
        } else {
            request()->session()->flash('error', 'Error occurred, Please try again!');
        }
        return redirect()->route('banner.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $status = $banner->delete();
        if ($status) {
            request()->session()->flash('success', 'Banner successfully deleted');
        } else {
            request()->session()->flash('error', 'Error occurred while deleting banner');
        }
        return redirect()->route('banner.index');
    }

    public function changeStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        //dd($status);
        $banner = Banner::find($id);
        if (empty($banner)) {
            return $this->failure('Record not found!');
        }
        $banner->status = ($status == 1) ?  'active' : 'inactive';
        if ($banner->save()) {
            return $this->success('Banner staus successfully updated.');
        }
        return $this->failure('Please try again!!');
    }

    public function deleteMultipleRecord(Request $request)
    {
        $status = Banner::whereIn('id', $request->ids)->delete();
        if ($status > 0) {
            return $this->success('Data successfully deleted');
        } else {
            return $this->failure('Error while deleting Data');
        }
        return $this->failure('Please try again!!');
    }
}
