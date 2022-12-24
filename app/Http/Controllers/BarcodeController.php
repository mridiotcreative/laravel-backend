<?php

namespace App\Http\Controllers;

use App\Models\IncentiveBarcode;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use App\Traits\HttpResponseTraits;
use App\Models\Product;
use DB;
use PDF;
use Str;
use DNS1D;
use DataTables;

class BarcodeController extends Controller
{
    use HttpResponseTraits;

    public function listBarcode(Request $request,$id)
    {
        if ($request->ajax()) {
            $data = IncentiveBarcode::where(['product_id'=>$id]);
            if ($request->batch_number_id) {
                $data = $data->where(['batch_number'=>$request->batch_number_id]);
            }
            $data = $data->orderBy('id', 'desc');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('select_orders', static function ($data) {
                    return '<input type="checkbox" name="barCodeID[]" class="barCodeID" value="'.$data->id.'"/>';
                })->addColumn('barcode_photo', function($data){
                    return '<img src="'.$data->getImage().'" class="img-fluid zoom" alt="'.$data->getImage().'" id="img_'.$data->id.'">';
                })->addColumn('info_status', function($data){
                    $checked = ($data->status == 'active') ? 'checked' : '';
                    return '<div class="custom-control custom-switch">
                        <input class="custom-control-input changeStatus" type="checkbox"
                            data-id="'.$data->id.'" id="customSwitch-'.$data->id.'"
                            '.$checked.'>
                        <label class="custom-control-label" for="customSwitch-'.$data->id.'"></label>
                    </div>';

                })->addColumn('action', function($data){

                    $actionData = '<button class="btn btn-danger btn-sm dltBtn" data-id='.$data->id.'
                            style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                            data-placement="bottom" title="Delete"><i
                                class="fas fa-trash-alt"></i></button>';

                    return $actionData;
                })
                ->rawColumns(['select_orders','barcode_photo','info_status','action'])
                ->make(true);
        }
        $product = $id ? Product::findOrFail($id) : null;

        $incentive_barcode_data = IncentiveBarcode::select("batch_number",DB::raw('COUNT(batch_number) as count_of_barcode'))
        ->having(DB::raw('COUNT(batch_number)'), '>', 1)
        ->where(['product_id'=>$id])
        ->groupBy("batch_number")
        ->get();
        
        return view('backend.product.barcode')->with([
            'product' => $product,
            'incentive_barcode_data' => $incentive_barcode_data
        ]);
    }

    // Generate Barcode
    public function create(Request $request)
    {
        $lastorderId = (IncentiveBarcode::orderBy('id', 'desc')->first() != null) ? IncentiveBarcode::orderBy('id', 'desc')->first()->batch_number : 0;
        $lastIncreament = substr($lastorderId, -3);
        $batch_number = date('Ymd') . str_pad($lastIncreament + 1, 4, 0, STR_PAD_LEFT);

        //dd($batch_number);

        $this->validate($request, [
            'barcode_point' => 'numeric|required',
            'barcode_count' => 'numeric|required',
        ]);

        for ($i=1; $i < $request->barcode_count+1 ; $i++) {

            $code = mt_rand(config('constants.BARCODE_START'), config('constants.BARCODE_END'));
            // Barcode Image
            $name = date('ymdms') . '-' . $request->product_id . '-' . $i;
            $barcodeName = "$name.png";
            \Storage::disk('public')->put(config('path.barcode') . $barcodeName, base64_decode(DNS1D::getBarcodePNG($code, "C39+", 3, 50, array(1, 1, 1), true)));
            // Create Barcode Details Array
            $incentiveBarcode[] = [
                'batch_number' => $batch_number,
                'product_id' => $request->product_id,
                'barcode_number' => $code,
                'photo' => $barcodeName,
                'points' => $request->barcode_point,
                'status' => 'active',
                'created_at' => \Carbon\Carbon::now(),
            ];
        }
        $status = IncentiveBarcode::insert($incentiveBarcode);

        //dd($status);
        if ($status) {
            return $this->success('Barcode successfully generated');
        } else {
            return $this->failure('Error while generatind Barcode');
        }
        return $this->failure('Please try again!!');
    }

    public function getBarcode(Request $request)
    {
        $barcode = IncentiveBarcode::where('is_used', 0)->first();
        // Return Not Found.
        if (empty($barcode)) {
            return Lang::get('messages.not_found');
        }
        return view('barcode.barcode')->with(['barcode' => $barcode]);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $data = IncentiveBarcode::findOrFail($id);
        $status = $data->delete();

        if ($status) {
            return $this->success('Barcode successfully deleted');
        } else {
            return $this->failure('Error while deleting Record');
        }
        return $this->failure('Please try again!!');
    }

    public function changeStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        $dataRow = IncentiveBarcode::find($id);
        if (empty($dataRow)) {
            return $this->failure('Record not found!');
        }
        $dataRow->status = ($status == 1) ?  'active' : 'inactive';
        if ($dataRow->save()) {
            return $this->success('Barcode staus successfully updated.');
        }
        return $this->failure('Please try again!!');
    }
}
