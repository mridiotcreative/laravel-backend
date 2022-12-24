<?php

namespace App\Http\Controllers;

use App\Models\OfferMaster;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use App\Traits\HttpResponseTraits;
use DataTables;
use Redirect;

class OfferMasterController extends Controller
{
    use HttpResponseTraits;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            
            $data = OfferMaster::getAllProduct();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('select_orders', static function ($data) {
                    return '<input type="checkbox" name="rowID[]" class="rowID" value="'.$data->id.'"/>';
                })
                ->addColumn('product_title', function($data){
                    return ($data->product_info != null) ? $data->product_info->title : "";
                })->addColumn('start_date', function($data){
                    return \Carbon\Carbon::parse($data->start_date)->format('d-m-Y');
                })->addColumn('end_date', function($data){
                    return \Carbon\Carbon::parse($data->end_date)->format('d-m-Y');
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

                    $actionData = '<a href="'.route('offermaster.create', $data->id).'"
                        class="btn btn-primary btn-sm float-left mr-1"
                        style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                        title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="'.route('offermaster.destroy', [$data->id]).'">
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button class="btn btn-danger btn-sm dltBtn" data-id='.$data->id.' style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                    </form>';

                    return $actionData;
                })
                ->rawColumns(['select_orders','info_status','action'])
                ->make(true);
        }
        return view('backend.offermaster.index');
    }

    public function createOrEdit($id = null)
    {
        $OfferMaster = $id ? OfferMaster::findOrFail($id) : null;

        $ProductData = Product::where('status','active')->orderBy('id', 'DESC')->get();
        
        return view('backend.offermaster.commonOfferMasterPage')->with(['offermaster'=> $OfferMaster,'productdata'=> $ProductData]);
    }

    public function storeOrUpdate(Request $request, $id = null)
    {
        $created_by = Auth()->user()->id;
        $OfferMaster = $id ? OfferMaster::findOrFail($id) : new OfferMaster;
        $this->validate($request, [
            'product_id' => 'string|required',
            'special_price' => 'numeric|required',
            'start_date' => 'string|required',
            'end_date' => 'string|required',
            'status' => 'required|in:active,inactive',
        ]);
        $data = $request->all();
        $data['start_date'] = \Carbon\Carbon::parse($data['start_date'])->format('Y-m-d');
        $data['end_date'] = \Carbon\Carbon::parse($data['end_date'])->format('Y-m-d');
        $data['created_by'] = $created_by;

        //$checkProduct = OfferMaster::where('product_id',$data['product_id'])->whereBetween('start_date', [$data['start_date'], $data['end_date']])->WhereBetween('end_date', [$data['start_date'], $data['end_date']])->where('id', '!=', $id)->first();

        $checkProduct = OfferMaster::where('product_id',$data['product_id'])->where('id', '!=', $id)
        ->where(function ($query) use ($data){
            $query->whereBetween('start_date', [$data['start_date'], $data['end_date']])->orWhereBetween('end_date', [$data['start_date'], $data['end_date']]);
        })->count();

        $validator = \Validator::make($data, [
            'start_date' => 'required|true_if_reference_is_false:'.$checkProduct,
            'end_date' => 'required|true_if_reference_is_false:'.$checkProduct,
        ],
        [
            'start_date.true_if_reference_is_false' => 'Please change this date as this date is already linked this product',
            'end_date.true_if_reference_is_false' => 'Please change this date as this date is already linked this product'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {
            $status = $OfferMaster->fill($data)->save();
            $status = 1;
            if ($status) {
                $msg = $id ? 'Offer Master successfully updated' : 'Offer Master successfully added';
                request()->session()->flash('success', $msg);
            } else {
                request()->session()->flash('error', 'Error occurred, Please try again!');
            }
            return redirect()->route('offermaster.index');
        }
    }

    public function destroy($id)
    {
        $dataRow = OfferMaster::findOrFail($id);
        $status = $dataRow->delete();
        if ($status) {
            request()->session()->flash('success', 'Price Master successfully deleted');
        } else {
            request()->session()->flash('error', 'Error occurred while deleting Price Master');
        }
        return redirect()->route('offermaster.index');
    }

    public function changeStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        //dd($status);
        $dataRow = OfferMaster::find($id);
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
        $status = OfferMaster::whereIn('id', $request->ids)->delete();
        if ($status > 0) {
            return $this->success('Data successfully deleted');
        } else {
            return $this->failure('Error while deleting Data');
        }
        return $this->failure('Please try again!!');
    }
}
