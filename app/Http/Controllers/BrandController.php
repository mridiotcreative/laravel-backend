<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Str;
use DataTables;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $brand = Brand::orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));
    //     return view('backend.brand.index')->with('brands', $brand);
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            
            $data = Brand::orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('info_status', function($data){
                    if($data->status == 'active') {
                        return '<span class="badge badge-success">'.$data->status.'</span>';
                    } else {
                        return '<span class="badge badge-warning">'.$data->status.'</span>';
                    }
                })->addColumn('action', function($data){

                    $actionData = '<a href="'.route('brand.create', $data->id).'"
                        class="btn btn-primary btn-sm float-left mr-1"
                        style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                        title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="'.route('brand.destroy', [$data->id]).'">
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button class="btn btn-danger btn-sm dltBtn" data-id='.$data->id.'
                            style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                            data-placement="bottom" title="Delete"><i
                                class="fas fa-trash-alt"></i></button>
                    </form>';

                    return $actionData;
                })
                ->rawColumns(['info_status','action'])
                ->make(true);
        }
        return view('backend.brand.index');
    }

    /**
     * Common Method Used For Create Or Edit Form
     */
    public function createOrEdit($id = null)
    {
        $brand = $id ? Brand::findOrFail($id) : null;
        return view('backend.brand.commonBrandPage')->with('brand', $brand);
    }

    /**
     * Common Method Used For Store Or Update Data
     */
    public function storeOrUpdate(Request $request, $id = null)
    {
        $brand = $id ? Brand::findOrFail($id) : new Brand;
        $this->validate($request, [
            'title' => 'string|required|max:50|min:3',
        ]);
        $data = $request->all();
        if (!$id) {
            $slug = Str::slug($request->title);
            $count = Brand::where('slug', $slug)->count();
            if ($count > 0) {
                $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
            }
            $data['slug'] = $slug;
        }
        $status = $brand->fill($data)->save();
        if ($status) {
            $msg = $id ? 'Brand successfully updated' : 'Brand successfully created';
            request()->session()->flash('success', $msg);
        } else {
            request()->session()->flash('error', 'Error, Please try again');
        }
        return redirect()->route('brand.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::find($id);
        if ($brand) {
            $status = $brand->delete();
            if ($status) {
                request()->session()->flash('success', 'Brand successfully deleted');
            } else {
                request()->session()->flash('error', 'Error, Please try again');
            }
            return redirect()->route('brand.index');
        } else {
            request()->session()->flash('error', 'Brand not found');
            return redirect()->back();
        }
    }
}
