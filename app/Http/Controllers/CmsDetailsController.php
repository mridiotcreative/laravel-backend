<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use App\Models\CmsDetails;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use App\Traits\HttpResponseTraits;
use DataTables;

class CmsDetailsController extends Controller
{
    use HttpResponseTraits;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            
            $data = CmsDetails::orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('select_orders', static function ($data) {
                    return '<input type="checkbox" name="rowID[]" class="rowID" value="'.$data->id.'"/>';
                })
                ->addColumn('info_status', function($data){
                    $checked = ($data->status == 'active') ? 'checked' : '';
                    $info_status = '<div class="custom-control custom-switch">
                        <input class="custom-control-input changeStatus" type="checkbox"
                            data-id="'.$data->id.'" id="customSwitch-'.$data->id.'"
                            '.$checked.'>
                        <label class="custom-control-label" for="customSwitch-'.$data->id.'"></label>
                    </div>';
                    
                    return $info_status;
                })->addColumn('action', function($data){

                    $actionData = '<a href="'.route('cms.create', $data->id).'"
                        class="btn btn-primary btn-sm float-left mr-1"
                        style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                        title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="'.route('cms.destroy', [$data->id]).'">
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button class="btn btn-danger btn-sm dltBtn" data-id='.$data->id.' style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                    </form>';

                    return $actionData;
                })
                ->rawColumns(['select_orders','info_status','action'])
                ->make(true);
        }
        return view('backend.cms.index');
    }

    public function createOrEdit($id = null)
    {
        $cmsdetails = $id ? CmsDetails::findOrFail($id) : null;
        return view('backend.cms.commonCMSPage')->with('cms', $cmsdetails);
    }

    public function storeOrUpdate(Request $request, $id = null)
    {
        $CmsDetails = $id ? CmsDetails::findOrFail($id) : new CmsDetails;
        $this->validate($request, [
            'slug' => 'string|required|max:50|unique:cms_details,slug,'.$id,
            'title' => 'string|required|max:50|min:3',
            'description' => 'string|nullable',
            'status' => 'required|in:active,inactive',
        ]);
        $data = $request->all();
        if (!$id) {
            $slug = Str::slug($request->slug);
            $count = CmsDetails::where('slug', $slug)->count();
            if ($count > 0) {
                $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
            }
            $data['slug'] = $slug;
        } else {
            unset($data['slug']);
        } 
        $status = $CmsDetails->fill($data)->save();
        if ($status) {
            $msg = $id ? 'CmsDetails successfully updated' : 'CmsDetails successfully added';
            request()->session()->flash('success', $msg);
        } else {
            request()->session()->flash('error', 'Error occurred, Please try again!');
        }
        return redirect()->route('cms.index');
    }

    public function destroy($id)
    {
        $CmsDetails = CmsDetails::findOrFail($id);
        $status = $CmsDetails->delete();
        if ($status) {
            request()->session()->flash('success', 'Cms Details successfully deleted');
        } else {
            request()->session()->flash('error', 'Error occurred while deleting Cms Details');
        }
        return redirect()->route('cms.index');
    }

    public function changeStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        //dd($status);
        $dataRow = CmsDetails::find($id);
        if (empty($dataRow)) {
            return $this->failure('Record not found!');
        }
        $dataRow->status = ($status == 1) ?  'active' : 'inactive';
        if ($dataRow->save()) {
            return $this->success('Cms Details staus successfully updated.');
        }
        return $this->failure('Please try again!!');
    }

    public function cmsList(Request $request)
    {
        try {
            $data = CmsDetails::where('status','active')->orderBy('id', 'DESC')->get();
            if (!empty($data)) {
                $result['key'] = 1;
                $result['data'] = $data;
                $result['message'] = 'Data Found Successfully';
            } else {
                $result['key'] = 0;
                $result['message'] = 'Data not Found';
            }
            echo json_encode($result);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
        }
    }

    public function viewCMSpage($slug)
    {
        try {
            $cmsdetails = CmsDetails::where(['slug'=>$slug, 'status'=>'active'])->first();
            //dd($cmsdetails);
            if(!empty($cmsdetails)){
                return view('frontend.pages.cms.cmsview')->with('cms', $cmsdetails);
            }
            return response()->view('errors.404');
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
        }
    }

    public function deleteMultipleRecord(Request $request)
    {
        $status = CmsDetails::whereIn('id', $request->ids)->delete();
        if ($status > 0) {
            return $this->success('Data successfully deleted');
        } else {
            return $this->failure('Error while deleting Data');
        }
        return $this->failure('Please try again!!');
    }
}
