<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Notification;
use App\Notifications\StatusNotification;
use App\User;
use App\Models\ProductReview;
use App\Traits\HttpResponseTraits;
use DataTables;

class ProductReviewController extends Controller
{
    use HttpResponseTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $reviews = ProductReview::getAllReview();

    //     return view('backend.review.index')->with('reviews', $reviews);
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            
            $data = ProductReview::with('user_info');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('select_orders', static function ($data) {
                    return '<input type="checkbox" name="rowID[]" class="rowID" value="'.$data->id.'"/>';
                })
                ->addColumn('user_name', function($data){
                    return ($data->user_info != null) ? $data->user_info->name : "";
                })->addColumn('product_title', function($data){
                    return ($data->product != null) ? $data->product->title : "";
                })->addColumn('review_rate', function($data){
                    $review_rate = '<ul style="list-style:none">';
                        for($i=1; $i<=5;$i++){
                            if($data->rate >=$i){
                                $review_rate .= '<li style="float:left;color:#F7941D;"><i class="fa fa-star"></i></li>';
                            }else {
                                $review_rate .= '<li style="float:left;color:#F7941D;"><i class="far fa-star"></i></li>';
                            }
                        }
                    $review_rate .= '</ul>';
                })->addColumn('created_at', function($data){
                    return ($data->created_at != null) ? $data->created_at->format('M d D, Y g: i a') : "";
                })->addColumn('info_status', function($data){
                    if ($data->status == 'active'){
                        return '<span class="badge badge-success">'.$data->status.'</span>';
                    }else{
                        return '<span class="badge badge-warning">'.$data->status.'</span>';
                    }
                })->addColumn('action', function($data){
                    $actionData = '<a href="'.route('review.edit',$data->id).'" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="'.route('review.destroy',[$data->id]).'">
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button class="btn btn-danger btn-sm dltBtn" data-id='.$data->id.' style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                    </form>';

                    return $actionData;
                })
                ->rawColumns(['select_orders','user_name','product_title','review_rate','created_at','info_status','action'])
                ->make(true);
        }
        return view('backend.review.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'rate' => 'required|numeric|min:1'
        ]);
        $product_info = Product::getProductBySlug($request->slug);
        $data = $request->all();
        $data['product_id'] = $product_info->id;
        $data['user_id'] = $request->user()->id;
        $data['status'] = 'active';
        $status = ProductReview::create($data);

        $user = User::where('role', 'admin')->get();
        $details = [
            'title' => 'New Product Rating!',
            'actionURL' => route('product-detail', $product_info->slug),
            'fas' => 'fa-star'
        ];
        Notification::send($user, new StatusNotification($details));
        if ($status) {
            request()->session()->flash('success', 'Thank you for your feedback');
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
        $review = ProductReview::find($id);
        // return $review;
        return view('backend.review.edit')->with('review', $review);
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
        $review = ProductReview::find($id);
        if ($review) {
            $data = $request->all();
            $status = $review->fill($data)->update();
            if ($status) {
                request()->session()->flash('success', 'Review Successfully updated');
            } else {
                request()->session()->flash('error', 'Something went wrong! Please try again!!');
            }
        } else {
            request()->session()->flash('error', 'Review not found!!');
        }

        return redirect()->route('review.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = ProductReview::find($id);
        $status = $review->delete();
        if ($status) {
            request()->session()->flash('success', 'Successfully deleted review');
        } else {
            request()->session()->flash('error', 'Something went wrong! Try again');
        }
        return redirect()->route('review.index');
    }

    public function deleteMultipleRecord(Request $request)
    {
        $status = ProductReview::whereIn('id', $request->ids)->delete();
        if ($status > 0) {
            return $this->success('Data successfully deleted');
        } else {
            return $this->failure('Error while deleting Data');
        }
        return $this->failure('Please try again!!');
    }
}
