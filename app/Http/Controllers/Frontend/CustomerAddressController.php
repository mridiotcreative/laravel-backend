<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CustomerAddress;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

class CustomerAddressController extends Controller
{
    private $model = null;
    public function __construct()
    {
        $this->model = new CustomerAddress;
    }

    /**
     * Get customer address list
     *
     * @param Request $request
     * @return view
     */
    public function getAddressList(Request $request)
    {
        $addresses = $this->model->getAddressList($request);
        // If ajax request
        if ($request->expectsJson()) {
            if ($addresses->isEmpty()) {
                return response()->json(Lang::get('messages.not_found'), Response::HTTP_BAD_REQUEST);
            }
            return response()->json(['address_list' => $addresses], Response::HTTP_OK);
        }
        return view('frontend.pages.customer.manage-address')->with(['addresses' => $addresses]);
    }

    /**
     * Get customer address details using by id
     *
     * @param Request $request
     * @return json
     */
    public function getAddressDetails(Request $request)
    {
        $address = $this->model->getAddressDetails($request);
        return response()->json($address);
    }

    /**
     * Create or Update address
     *
     * @param Request $request
     * @return json
     */
    public function storeOrUpdateAddress(Request $request)
    {
        $success = $this->model->storeOrUpdateAddress($request);
        $msg = !empty($request->address_id) ? Lang::get('messages.address_updated') : Lang::get('messages.address_created');
        if ($request->expectsJson()) {
            if ($success) {
                return response()->json($msg, Response::HTTP_OK);
            }
            return response()->json(Lang::get('messages.something_went_wrong'), Response::HTTP_BAD_REQUEST);
        }
        if ($success) {
            request()->session()->flash('success', $msg);
        } else {
            request()->session()->flash('error', Lang::get('messages.something_went_wrong'));
        }
        return redirect()->back();
    }

    /**
     * Delete customer address using by id
     *
     * @param Request $request
     * @return json
     */
    public function addressDestroy(Request $request)
    {
        $id = !empty($request->address_id) ? $request->address_id : '';
        if ($this->model->addressDestroy($request, $id)) {
            return response()->json(Lang::get('messages.address_deleted'), Response::HTTP_OK);
        }
        return response()->json(Lang::get('messages.something_went_wrong'), Response::HTTP_BAD_REQUEST);
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
