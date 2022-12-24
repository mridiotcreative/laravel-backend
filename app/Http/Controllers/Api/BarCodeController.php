<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use App\Models\IncentiveBarcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Response;
use Auth;

class BarCodeController extends ApiController
{
    use HttpResponseTraits;

    public function useBarcode(Request $request)
    {
        $rules = [
            'barcode' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ];
        if ($this->apiValidation($request, $rules)) {
            return $this->errors(Lang::get('messages.validation_error'), $this->errorsMessages);
        }

        $barcode = IncentiveBarcode::where(['barcode_number'=>$request->barcode, 'status'=>'active'])
            ->first();

        if (empty($barcode)) {
            return $this->failure(Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
        }

        if ($barcode->is_used != '') {
            return $this->failure(Lang::get('messages.barcode_already'), Response::HTTP_BAD_REQUEST);
        }

        $customer = $request->user();
        $barcode->customer_id = $customer->id;
        $barcode->is_used = \Carbon\Carbon::now();
        $barcode->latitude = $request->latitude;
        $barcode->longitude = $request->longitude;
        //$barcode->expired_date = \Carbon\Carbon::now();
        if ($barcode->save()) {
            $points = $customer->total_points + $barcode->points;
            Customer::where('id', $customer->id)->update([
                'total_points' => $points
            ]);
            return $this->success(Lang::get('messages.barcode_used'), Response::HTTP_OK);
        }
        return $this->failure(Lang::get('messages.something_went_wrong'));
    }
}
