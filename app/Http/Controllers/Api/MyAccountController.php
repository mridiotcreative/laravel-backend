<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Arr;
use App\Helpers\AppHelper;

class MyAccountController extends ApiController
{
    use HttpResponseTraits;

    /**
     * Get user profile
     *
     * @param Request $request
     * @return json
     */
    public function getProfile(Request $request)
    {
        if ($request->user()) {
            $user = $request->user();
            $user = Arr::except($user,['created_at','updated_at']);
            $user->photo = ($user->photo != "") ? $user->getImage() : "";
            $user->dob = ($user->dob != "") ? \Carbon\Carbon::parse($user->dob)->format('d-m-Y') : "";
            $user->address = CustomerAddress::where('customer_id',$request->user()->id)->first();
            return $this->success(Lang::get('messages.success'), $user);
        }
        return $this->failure(Lang::get('messages.something_went_wrong'));
    }

    /**
     * Update user profile
     *
     * @param Request $request
     * @return json
     */
    public function updateProfile(Request $request)
    {
        $id = $request->user()->id;
        $this->validate(
            $request,
            [
                'name' => 'required',
                'dob' => 'required',
                'gender' => 'required|in:1,2,3',
                'phone' => 'numeric|required',
                //'email' => "email|required|unique:users,email,{$id}",
                'building_name' => 'required',
                'street_name' => 'required',
                'pincode' => 'required|numeric',
                'city' => 'required',
            ]
        );
        $data = $request->except([
            'building_name',
            'street_name',
            'pincode',
            'city',
        ]);
        $data['dob'] = \Carbon\Carbon::parse($data['dob'])->format('Y-m-d');
        $user = $request->user();
        $user->fill($data);
        if ($user->save()) {
            $address = CustomerAddress::where('customer_id',$request->user()->id)->first();
            if (!$address) {
                $address = new CustomerAddress;
            }
            $addressData = $request->all();

            $addressData = $request->except([
                'name',
                'dob',
                'gender',
                'phone',
            ]);

            $addressData['customer_id'] = $request->user()->id;

            if (CustomerAddress::where('customer_id', $addressData['customer_id'])->count() < 1) {
                $addressData['is_primary'] = config('constants.IS_PRIMARY');
            }

            if (method_exists($address, 'fill')) {
                $address->fill($addressData);
                $address->save();
            }

            return $this->success(Lang::get('messages.profile_updated'));
        }
        return $this->failure(Lang::get('messages.something_went_wrong'));
    }

    /**
     * Update user profile
     *
     * @param Request $request
     * @return json
     */
    public function addUpdateProfilePhoto(Request $request)
    {
        $id = $request->user()->id;
        $this->validate(
            $request,
            [
                'photo' => 'required|file|mimes:jpg,jpeg,png|max:20000',
            ]
        );

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $now = date('ymds') . '-';
            $photo = $request->file('photo');
            $photoName = $now . AppHelper::replaceSpaceIntoDash($photo->getClientOriginalName());
            $request->photo->storeAs(config('path.user'), $photoName);
            $data['photo'] = $photoName;
        }
        $user = $request->user();
        $status = $user->fill($data)->save();
        if($status){
            return $this->success(Lang::get('messages.profile_updated'));
        }
        return $this->failure(Lang::get('messages.something_went_wrong'), Response::HTTP_CONFLICT);
    }

    /**
     * Change customer password
     *
     * @param Request $request
     * @return json
     */
    public function changePassword(Request $request)
    {
        // Validation
        if ($this->apiValidation($request, Customer::getChangePasswordRules())) {
            return $this->errors(Lang::get('messages.validation_error'), $this->errorsMessages);
        }
        $customer = new Customer;
        if ($customer->changePassword($request)) {
            return $this->success(Lang::get('messages.password_changed'));
        }
        return $this->failure(Lang::get('messages.something_went_wrong'));
    }
}
