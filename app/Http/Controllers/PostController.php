<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Models\PushNotification;
use App\User;
use Illuminate\Support\Facades\Lang;
use App\Traits\HttpResponseTraits;
use DataTables;

class PostController extends Controller
{
    use HttpResponseTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $posts = Post::getAllPost();
    //     return view('backend.post.index')->with('posts', $posts);
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            
            $data = Post::with(['cat_info', 'author_info'])->orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('select_orders', static function ($data) {
                    return '<input type="checkbox" name="rowID[]" class="rowID" value="'.$data->id.'"/>';
                })
                ->addColumn('cat_title', function($data){
                    if ($data->cat_info != null) {
                        return $data->cat_info->title;
                    }
                })->addColumn('author_info', function($data){

                    $author_info = \DB::table('users')
                        ->select('name')
                        ->where('id', $data->added_by)
                        ->get();

                    $auth_info = '';
                    foreach ($author_info as $data1){
                        $auth_info .= $data1->name;
                    }
                    return $auth_info;
                })->addColumn('postimage', function($data){
                    return '<img src="'.$data->photo[0].'" class="img-fluid zoom" style="max-width:80px"
                    alt="'.$data->title.'">';
                })->addColumn('info_status', function($data){
                    if ($data->status == 'active'){
                        return '<span class="badge badge-success">'.$data->status.'</span>';
                    }else{
                        return '<span class="badge badge-warning">'.$data->status.'</span>';
                    }
                })->addColumn('action', function($data){
                    $actionData = '<a href="'.route('post.create', $data->id).'" class="btn btn-primary btn-sm float-left mr-1"
                        style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                        title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="'.route('post.destroy', [$data->id]).'">
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button class="btn btn-danger btn-sm dltBtn" data-id='.$data->id.'
                            style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                            data-placement="bottom" title="Delete"><i
                                class="fas fa-trash-alt"></i></button>
                    </form>';

                    return $actionData;
                })
                ->rawColumns(['select_orders','cat_title','author_info','postimage','info_status','action'])
                ->make(true);
        }
        return view('backend.post.index');
    }

    /**
     * Common Method Used For Create Or Edit Form
     */
    public function createOrEdit($id = null)
    {
        $post = $id ? Post::findOrFail($id) : null;
        $categories = PostCategory::get();
        $tags = PostTag::get();
        // $users = User::get();
        return view('backend.post.commonPostPage')->with('categories', $categories)->with('tags', $tags)->with('post', $post);
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

        $post = $id ? Post::findOrFail($id) : new Post;
        $validPhoto = $fileCheck ? 'image|sometimes' : 'image|required';
        $this->validate($request, [
            'title' => 'string|required',
            'quote' => 'string|nullable',
            'summary' => 'string|required',
            'description' => 'string|nullable',
            'photo' => $validPhoto,
            'tags' => 'nullable',
            'added_by' => 'nullable',
            'post_cat_id' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        $data = $request->all();
        if (!$id) {
            $slug = Str::slug($request->title);
            $count = Post::where('slug', $slug)->count();
            if ($count > 0) {
                $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
            }
            $data['slug'] = $slug;
        }
        $tags = $request->input('tags');
        if ($tags) {
            $data['tags'] = implode(',', $tags);
        } else {
            $data['tags'] = '';
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
            $imagefile->storeAs(config('path.post'), $imageName);
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
        //     $request->photo->storeAs(config('path.post'), $photoName);
        //     $data['photo'] = $photoName;
        // }
        $status = $post->fill($data)->save();
        if ($status) {
            $msg = $id ? 'Post Successfully updated' : 'Post Successfully added';
            if ($id == null && $post->getRawOriginal('status') == 'active') {
                $push = new PushNotification;
                $push->makePushNotification([
                    'title' => Lang::get('notification.new_article'),
                    'body' => $post->title,
                    'type' => 1,
                    'table_rec_id' => $post->id,
                    'slug' => $post->slug,
                    'is_single' => 0,
                ]);
            }
            request()->session()->flash('success', $msg);
        } else {
            request()->session()->flash('error', 'Please try again!!');
        }
        return redirect()->route('post.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        $status = $post->delete();

        if ($status) {
            request()->session()->flash('success', 'Post successfully deleted');
        } else {
            request()->session()->flash('error', 'Error while deleting post ');
        }
        return redirect()->route('post.index');
    }

    public function deleteMultipleRecord(Request $request)
    {
        $status = Post::whereIn('id', $request->ids)->delete();
        if ($status > 0) {
            return $this->success('Data successfully deleted');
        } else {
            return $this->failure('Error while deleting Data');
        }
        return $this->failure('Please try again!!');
    }
}
