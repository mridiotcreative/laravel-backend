<?php

namespace App\Http\Controllers;

use App\Models\PriceMaster;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use App\Traits\HttpResponseTraits;
use DataTables;
use Redirect;

class PriceMasterController extends Controller
{
    use HttpResponseTraits;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            
            $data = PriceMaster::getAllProduct();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('select_orders', static function ($data) {
                    return '<input type="checkbox" name="rowID[]" class="rowID" value="'.$data->id.'"/>';
                })
                ->addColumn('product_title', function($data){
                    return ($data->product_info != null) ? $data->product_info->title : "";
                })->addColumn('user_title', function($data){
                    return ($data->customer_info != null) ? $data->customer_info->full_name : "";
                })->addColumn('info_status', function($data){
                    $checked = ($data->status == 'active') ? 'checked' : '';
                    $info_status = '<div class="custom-control custom-switch">
                        <input class="custom-control-input changeStatus" type="checkbox"
                            data-id="'.$data->id.'" id="customSwitch-'.$data->id.'"
                            '.$checked.'>
                        <label class="custom-control-label" for="customSwitch-'.$data->id.'"></label>
                    </div>';
                    
                    return $info_status;
                })->addColumn('action', function($data){

                    $actionData = '<a href="'.route('pricemaster.create', $data->id).'"
                        class="btn btn-primary btn-sm float-left mr-1"
                        style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                        title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="'.route('pricemaster.destroy', [$data->id]).'">
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button class="btn btn-danger btn-sm dltBtn" data-id='.$data->id.' style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                    </form>';

                    return $actionData;
                })
                ->rawColumns(['select_orders','info_status','action'])
                ->make(true);
        }
        return view('backend.pricemaster.index');
    }

    public function createOrEdit($id = null)
    {
        $PriceMaster = $id ? PriceMaster::findOrFail($id) : null;

        $ProductData = Product::where('status','active')->orderBy('id', 'DESC')->get();
        $CustomerData = Customer::where('status','1')->orderBy('id', 'DESC')->get();
        
        return view('backend.pricemaster.commonPriceMasterPage')->with(['pricemaster'=> $PriceMaster,'productdata'=> $ProductData,'customerdata'=> $CustomerData]);
    }

    public function storeOrUpdate(Request $request, $id = null)
    {
        $created_by = Auth()->user()->id;
        $PriceMaster = $id ? PriceMaster::findOrFail($id) : new PriceMaster;
        $this->validate($request, [
            'product_id' => 'string|required',
            'user_id' => 'string|required',
            'special_price' => 'numeric|required',
            'status' => 'required|in:active,inactive',
        ]);
        $data = $request->all();
        $data['created_by'] = $created_by;

        $checkProduct = PriceMaster::where(['product_id'=>$data['product_id'],'user_id'=>$data['user_id']])->where('id', '!=', $id)->count();

        $validator = \Validator::make($data, [
            'user_id' => 'required|true_if_reference_is_false:'.$checkProduct,
        ],
        [
            'user_id.true_if_reference_is_false' => 'Please change this user is already linked this product'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {
            $status = $PriceMaster->fill($data)->save();
            if ($status) {
                $msg = $id ? 'Price Master successfully updated' : 'Price Master successfully added';
                request()->session()->flash('success', $msg);
            } else {
                request()->session()->flash('error', 'Error occurred, Please try again!');
            }
            return redirect()->route('pricemaster.index');
        }
    }

    public function destroy($id)
    {
        $dataRow = PriceMaster::findOrFail($id);
        $status = $dataRow->delete();
        if ($status) {
            request()->session()->flash('success', 'Price Master successfully deleted');
        } else {
            request()->session()->flash('error', 'Error occurred while deleting Price Master');
        }
        return redirect()->route('pricemaster.index');
    }

    public function changeStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        //dd($status);
        $dataRow = PriceMaster::find($id);
        if (empty($dataRow)) {
            return $this->failure('Record not found!');
        }
        $dataRow->status = ($status == 1) ?  'active' : 'inactive';
        if ($dataRow->save()) {
            return $this->success('Price Master staus successfully updated.');
        }
        return $this->failure('Please try again!!');
    }

    public function deleteMultipleRecord(Request $request)
    {
        $status = PriceMaster::whereIn('id', $request->ids)->delete();
        if ($status > 0) {
            return $this->success('Data successfully deleted');
        } else {
            return $this->failure('Error while deleting Data');
        }
        return $this->failure('Please try again!!');
    }
}
