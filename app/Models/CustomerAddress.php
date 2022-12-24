<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Support\Facades\Lang;
use App\Traits\HttpResponseTraits;

class CustomerAddress extends Model
{
    use HttpResponseTraits;

    protected $fillable = [
        'building_name',
        'street_name',
        'pincode',
        'city',
        'customer_id',
        'is_primary',
    ];

    protected $hidden = [
        'customer_id',
        'created_at',
        'updated_at',
    ];

    const RULES = [
        'building_name' => 'required',
        'street_name' => 'required',
        'pincode' => 'required|numeric',
        'city' => 'required',
    ];

    // Get Address List
    public function getAddressList($request)
    {
        return CustomerAddress::where('customer_id', AppHelper::getUserByGuard()->id)
            ->orderByDesc('is_primary')
            ->orderByDesc('updated_at')
            //->orderByDesc('created_at')
            ->get();
    }

    // Get Address Details
    public function getAddressDetails($request)
    {
        return CustomerAddress::where('id', $request->address_id)
            ->where('customer_id', AppHelper::getUserByGuard()->id)
            ->first();
    }

    // Address Create Or Update
    public function storeOrUpdateAddress($request)
    {
        $address = !empty($request->address_id) ? CustomerAddress::find($request->address_id) : new CustomerAddress;
        $data = $request->all();
        $data['customer_id'] = AppHelper::getUserByGuard()->id;
        if (CustomerAddress::where('customer_id', $data['customer_id'])->count() < 1) {
            $data['is_primary'] = config('constants.IS_PRIMARY');
        }
        if (method_exists($address, 'fill')) {
            $address->fill($data);
            return $address->save();
        }
        return false;
    }

    // Delete Address
    public function addressDestroy($request, $id)
    {
        $address = CustomerAddress::where('id', $id)
            ->where('customer_id', AppHelper::getUserByGuard()->id)
            ->first();
        if ($address) {
            return $address->delete();
        }
        return false;
    }

    public function changePrimaryAddress($request)
    {
        $userId = AppHelper::getUserByGuard()->id;
        $address = CustomerAddress::where('id', $request->address_id)
            ->where('customer_id', $userId)
            ->first();
        if (empty($address)) {
            return $this->failure(Lang::get('messages.not_found'));
        }
        CustomerAddress::where('customer_id', $userId)
            ->update(['is_primary' => 0]);
        $address->is_primary = 1;
        if ($address->save()) {
            return $this->success(Lang::get('messages.change_primary_address'));
        }
        return $this->failure(Lang::get('messages.something_went_wrong'));
    }
}
