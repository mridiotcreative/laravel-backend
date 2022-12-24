<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Notification;
use App\User;
use App\Notifications\StatusNotification;
use App\Models\PostComment;
use App\Traits\HttpResponseTraits;
use DataTables;

class PostCommentController extends Controller
{
    use HttpResponseTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $comments = PostComment::getAllComments();
    //     return view('backend.comment.index')->with('comments', $comments);
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            
            $data = PostComment::with('user_info');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('select_orders', static function ($data) {
                    return '<input type="checkbox" name="rowID[]" class="rowID" value="'.$data->id.'"/>';
                })
                ->addColumn('user_info_name', function($data){
                    return $data->user_info['name'];
                })->addColumn('post_title', function($data){
                    return ($data->post != null) ? $data->post->title : "";
                })->addColumn('comment_date', function($data){
                    return ($data->created_at != null) ? $data->created_at->format('M d D, Y g: i a') : "";
                })->addColumn('info_status', function($data){
                    if ($data->status == 'active'){
                        return '<span class="badge badge-success">'.$data->status.'</span>';
                    }else{
                        return '<span class="badge badge-warning">'.$data->status.'</span>';
                    }
                })->addColumn('action', function($data){
                    $actionData = '<a href="'.route('comment.edit',$data->id).'" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="'.route('comment.destroy',[$data->id]).'">
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button class="btn btn-danger btn-sm dltBtn" data-id='.$data->id.' style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                    </form>';

                    return $actionData;
                })
                ->rawColumns(['select_orders','user_info_name','post_title','comment_date','info_status','action'])
                ->make(true);
        }
        return view('backend.comment.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post_info = Post::getPostBySlug($request->slug);
        $data = $request->all();
        $data['user_id'] = $request->user()->id;
        $data['status'] = 'active';
        $status = PostComment::create($data);
        $user = User::where('role', 'admin')->get();
        $details = [
            'title' => "New Comment created",
            'actionURL' => route('blog.detail', $post_info->slug),
            'fas' => 'fas fa-comment'
        ];
        Notification::send($user, new StatusNotification($details));
        if ($status) {
            request()->session()->flash('success', 'Thank you for your comment');
        } else {
            request()->session()->flash('error', 'Something went wrong! Please try again!!');
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comments = PostComment::find($id);
        if ($comments) {
            return view('backend.comment.edit')->with('comment', $comments);
        } else {
            request()->session()->flash('error', 'Comment not found');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $comment = PostComment::find($id);
        if ($comment) {
            $data = $request->all();
            $status = $comment->fill($data)->update();
            if ($status) {
                request()->session()->flash('success', 'Comment successfully updated');
            } else {
                request()->session()->flash('error', 'Something went wrong! Please try again!!');
            }
            return redirect()->route('comment.index');
        } else {
            request()->session()->flash('error', 'Comment not found');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = PostComment::find($id);
        if ($comment) {
            $status = $comment->delete();
            if ($status) {
                request()->session()->flash('success', 'Post Comment successfully deleted');
            } else {
                request()->session()->flash('error', 'Error occurred please try again');
            }
            return back();
        } else {
            request()->session()->flash('error', 'Post Comment not found');
            return redirect()->back();
        }
    }

    public function deleteMultipleRecord(Request $request)
    {
        $status = PostComment::whereIn('id', $request->ids)->delete();
        if ($status > 0) {
            return $this->success('Data successfully deleted');
        } else {
            return $this->failure('Error while deleting Data');
        }
        return $this->failure('Please try again!!');
    }
}
