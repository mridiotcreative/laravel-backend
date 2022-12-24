<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostTag;
use Illuminate\Support\Str;
use App\Traits\HttpResponseTraits;
use DataTables;

class PostTagController extends Controller
{
    use HttpResponseTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $postTag = PostTag::orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));
    //     return view('backend.posttag.index')->with('postTags', $postTag);
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            
            $data = PostTag::orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('select_orders', static function ($data) {
                    return '<input type="checkbox" name="rowID[]" class="rowID" value="'.$data->id.'"/>';
                })
                ->addColumn('info_status', function($data){
                    if ($data->status == 'active'){
                        return '<span class="badge badge-success">'.$data->status.'</span>';
                    }else{
                        return '<span class="badge badge-warning">'.$data->status.'</span>';
                    }
                })->addColumn('action', function($data){
                    $actionData = '<a href="'.route('post-category.create', $data->id).'" class="btn btn-primary btn-sm float-left mr-1"
                    style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                    title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="'.route('post-category.destroy', [$data->id]).'">
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button class="btn btn-danger btn-sm dltBtn" data-id='.$data->id.'
                            style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                            data-placement="bottom" title="Delete"><i
                                class="fas fa-trash-alt"></i></button>
                    </form>';

                    return $actionData;
                })
                ->rawColumns(['select_orders','info_status','action'])
                ->make(true);
        }
        return view('backend.posttag.index');
    }

    /**
     * Common Method Used For Create Or Edit Form
     */
    public function createOrEdit($id = null)
    {
        $postTag = $id ? PostTag::findOrFail($id) : null;
        return view('backend.posttag.postTagCommonPage')->with('postTag', $postTag);
    }

    /**
     * Common Method Used For Store Or Update Data
     */
    public function storeOrUpdate(Request $request, $id = null)
    {
        $postTag = $id ? PostTag::findOrFail($id) : new PostTag;
        $this->validate($request, [
            'title' => 'string|required',
            'status' => 'required|in:active,inactive'
        ]);
        $data = $request->all();
        $slug = Str::slug($request->title);
        if (!$id) {
            $count = PostTag::where('slug', $slug)->count();
            if ($count > 0) {
                $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
            }
            $data['slug'] = $slug;
        }
        $status = $postTag->fill($data)->save();
        if ($status) {
            $msg = $id ? 'Post Tag Successfully updated' : 'Post Tag Successfully added';
            request()->session()->flash('success', $msg);
        } else {
            request()->session()->flash('error', 'Please try again!!');
        }
        return redirect()->route('post-tag.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $postTag = PostTag::findOrFail($id);

        $status = $postTag->delete();

        if ($status) {
            request()->session()->flash('success', 'Post Tag successfully deleted');
        } else {
            request()->session()->flash('error', 'Error while deleting post tag');
        }
        return redirect()->route('post-tag.index');
    }

    public function deleteMultipleRecord(Request $request)
    {
        $status = PostTag::whereIn('id', $request->ids)->delete();
        if ($status > 0) {
            return $this->success('Data successfully deleted');
        } else {
            return $this->failure('Error while deleting Data');
        }
        return $this->failure('Please try again!!');
    }
}
