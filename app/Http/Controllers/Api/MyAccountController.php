<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Traits\HttpResponseTraits;

class MyAccountController extends ApiController
{
    use HttpResponseTraits;

    /**
     * Get customer profile
     *
     * @param Request $request
     * @return json
     */
    public function getProfile(Request $request)
    {
        if ($request->user()) {
            $user = $request->user();
            $user->photo = ($user->photo != "") ? $user->photo : "";
            return $this->success(Lang::get('messages.success'), $user);
        }
        return $this->failure(Lang::get('messages.something_went_wrong'));
    }

    /**
     * Update customer profile
     *
     * @param Request $request
     * @return json
     */
    public function updateProfile(Request $request)
    {
        $data = $request->except([
            'email',
            'password',
            'gst_no',
            'gst_document',
            'drug_licence_no',
            'drug_document',
            'id_proof_document',
        ]);
        $customer = $request->user();
        $customer->fill($data);
        if ($customer->save()) {
            return $this->success(Lang::get('messages.profile_updated'));
        }
        return $this->failure(Lang::get('messages.something_went_wrong'));
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
