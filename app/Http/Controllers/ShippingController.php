<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipping;
use App\Models\Coupon;
use DataTables;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $shipping = Shipping::orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));
    //     return view('backend.shipping.index')->with('shippings', $shipping);
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            
            $data = Shipping::orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('shipping_price', function($data){
                    return '$'. $data->price;
                })
                ->addColumn('info_status', function($data){
                    if($data->status == 'active'){
                        return '<span class="badge badge-success">'.$data->status.'</span>';
                    } else {
                        return '<span class="badge badge-warning">'.$data->status.'</span>';
                    }
                })->addColumn('action', function($data){

                    $actionData = '<a href="'.route('shipping.create', $data->id).'"
                        class="btn btn-primary btn-sm float-left mr-1"
                        style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                        title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="'.route('shipping.destroy', [$data->id]).'">
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button class="btn btn-danger btn-sm dltBtn" data-id='.$data->id.'
                            style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                            data-placement="bottom" title="Delete"><i
                                class="fas fa-trash-alt"></i></button>
                    </form>';

                    return $actionData;
                })
                ->rawColumns(['shipping_price','info_status','action'])
                ->make(true);
        }
        return view('backend.shipping.index');
    }

    /**
     * Common Method Used For Create Or Edit Form
     */
    public function createOrEdit($id = null)
    {
        $shipping = $id ? Shipping::findOrFail($id) : null;
        return view('backend.shipping.shippingCommonPage')->with('shipping', $shipping);
    }

    /**
     * Common Method Used For Store Or Update Data
     */
    public function storeOrUpdate(Request $request, $id = null)
    {
        $shipping = $id ? Shipping::findOrFail($id) : new Shipping;
        $this->validate($request, [
            'type' => 'string|required',
            'price' => 'nullable|numeric',
            'status' => 'required|in:active,inactive'
        ]);
        $data = $request->all();
        // return $data;
        $status = $shipping->fill($data)->save();
        if ($status) {
            $msg = $id ? 'Shipping successfully updated' : 'Shipping successfully created';
            request()->session()->flash('success', $msg);
        } else {
            request()->session()->flash('error', 'Error, Please try again');
        }
        return redirect()->route('shipping.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shipping = Shipping::find($id);
        if ($shipping) {
            $status = $shipping->delete();
            if ($status) {
                request()->session()->flash('success', 'Shipping successfully deleted');
            } else {
                request()->session()->flash('error', 'Error, Please try again');
            }
            return redirect()->route('shipping.index');
        } else {
            request()->session()->flash('error', 'Shipping not found');
            return redirect()->back();
        }
    }
}
