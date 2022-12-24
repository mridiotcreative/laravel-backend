<?php

namespace App\Http\Controllers\Api;

use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

class CustomerAddressController extends ApiController
{
    use HttpResponseTraits;

    private $model = null;

    public function __construct()
    {
        $this->model = new CustomerAddress;
    }

    /**
     * Get customer address list
     *
     * @param Request $request
     * @return json
     */
    public function getAddressList(Request $request)
    {
        $addresses = $this->model->getAddressList($request);
        if ($addresses->isEmpty()) {
            return $this->failure(Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
        }
        $data['address_list'] = $addresses;
        return $this->success(Lang::get('messages.success'), $data);
    }

    /**
     * Get customer address details
     *
     * @param Request $request
     * @return json
     */
    public function getAddressDetails(Request $request)
    {
        $address = $this->model->getAddressDetails($request);
        if (empty($address)) {
            return $this->failure(Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
        }
        $data['address_details'] = $address;
        return $this->success(Lang::get('messages.success'), $data);
    }

    /**
     * Delete customer address
     *
     * @param Request $request
     * @param [type] $id
     * @return json
     */
    public function deleteAddress(Request $request, $id)
    {
        if ($this->model->addressDestroy($request, $id)) {
            return $this->success(Lang::get('messages.address_deleted'));
        }
        return $this->failure(Lang::get('messages.something_went_wrong'));
    }

    /**
     * Add customer address
     *
     * @param Request $request
     * @return json
     */
    
    public function addAddress(Request $request)
    {
        if ($this->apiValidation($request, CustomerAddress::RULES)) {
            return $this->errors(Lang::get('messages.validation_error'), $this->errorsMessages);
        }
        if ($this->model->storeOrUpdateAddress($request)) {
            return $this->success(Lang::get('messages.address_created'));
        }
        return $this->failure(Lang::get('messages.something_went_wrong'));
    }

    /**
     * Update customer address
     *
     * @param Request $request
     * @return json
     */
    public function updateAddress(Request $request)
    {
        if ($this->apiValidation($request, CustomerAddress::RULES)) {
            return $this->errors(Lang::get('messages.validation_error'), $this->errorsMessages);
        }
        if ($this->model->storeOrUpdateAddress($request)) {
            return $this->success(Lang::get('messages.address_updated'));
        }
        return $this->failure(Lang::get('messages.something_went_wrong'));
    }

    /**
     * Change customer primary address
     *
     * @param Request $request
     * @return json
     */
    public function changePrimaryAddress(Request $request)
    {
        return $this->model->changePrimaryAddress($request);
    }
}
