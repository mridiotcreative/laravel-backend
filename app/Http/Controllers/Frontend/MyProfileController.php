<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Role;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class MyProfileController extends Controller
{
    /**
     * Get my profile for view details
     *
     * @param Request $request
     * @return view
     */
    public function myProfile(Request $request)
    {
        try {
            $customer = Auth::guard('customer')->user();
            $roles = AppHelper::getRoles();
            $state = AppHelper::getState();
            $city = AppHelper::getCityByState($customer->state_id);
            return view('frontend.pages.customer.profile')->with([
                'customer' => $customer,
                'roles' => $roles,
                'state' => $state,
                'city' => $city,
            ]);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
        }
    }

    /**
     * Update customer profile
     *
     * @param Request $request
     * @return redirect
     */
    public function updateMyProfile(Request $request)
    {
        try {
            $this->validate($request, [
                'full_name' => 'required',
                'firm_name' => 'required',
                'state_id' => 'required',
                'city_id' => 'required',
                'contact_no_1' => 'required',
                'contact_no_2' => 'required',
                'pincode' => 'required',
            ]);
            $data = $request->except([
                'email',
                'password',
                'gst_no',
                'gst_document',
                'drug_licence_no',
                'drug_document',
                'id_proof_document',
            ]);
            $customer = Auth::guard('customer')->user();
            $customer->fill($data);
            if ($customer->save()) {
                request()->session()->flash('success', 'Profile updated.');
            } else {
                request()->session()->flash('error', 'Please try again!');
            }
            return redirect()->back();
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
        }
    }

    /**
     * Change customer password view
     *
     * @param Request $request
     * @return view
     */
    public function userChangePassword(Request $request)
    {
        return view('frontend.pages.customer.change-password');
    }

    /**
     * Update customer password
     *
     * @param Request $request
     * @return redirect
     */
    public function userChangePasswordStore(Request $request)
    {
        try {
            $this->validate($request, Customer::getChangePasswordRules());
            $customer = new Customer;
            if ($customer->changePassword($request)) {
                Auth::guard('customer')->logout();
                return redirect()->route('login.form');
            }
            $request->session()->flash('error', Lang::get('messages.something_went_wrong'));
            return redirect()->back();
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
        }
    }

    /**
     * Get customer order history
     *
     * @param Request $request
     * @return view
     */
    public function orderHistory(Request $request)
    {
        try {
            $customer_data = Auth::guard('customer')->user();
            $id = $customer_data->id;
            // $orderData = Order::getAllOrder('2');
            // dd($orderData);
            $orderData = Order::getOrderHistory($id);
            return view('frontend.pages.customer.order-history')->with(['orderData' => $orderData]);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
        }
    }

    public function orderByID(Request $request)
    {
        try {
            $customer_data = Auth::guard('customer')->user();
            $id = $request->id;
            $orderData = Order::getAllOrder($id);
            if (!empty($orderData)) {
                $orderData['sub_total_amount'] = number_format($orderData->sub_total_amount, 2);
                $orderData['total_amount'] = number_format($orderData->total_amount, 2);
                $orderData['points'] = number_format($orderData->points, 2);

                foreach ($orderData->cart_info as $key => $value) {
                    $orderData->cart_info[$key]['price'] = number_format($value->price, 2);
                    $orderData->cart_info[$key]['amount'] = number_format($value->amount, 2);
                    $orderData->cart_info[$key]->product->price = number_format($value->product->price, 2);
                    $orderData->cart_info[$key]->product->photo = asset('storage/uploads/product/'.$value->product->photo);
                    $orderData->cart_info[$key]->product->url = route('product-detail', $orderData->cart_info[$key]->product->slug);
                }
                $result['key'] = 1;
                $result['data'] = $orderData;
                $result['message'] = 'Data Found Successfully';
            } else {
                $result['key'] = 0;
                $result['message'] = 'Data not Found';
            }
            echo json_encode($result);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }

    /**
     * Get customer order past items
     *
     * @param Request $request
     * @return view
     */
    public function orderFromPastItems(Request $request)
    {
        return view('frontend.pages.customer.order-from-past-items');
    }

    /**
     * Get customer wallet points
     *
     * @param Request $request
     * @return view
     */
    public function myPoints(Request $request)
    {
        $customer = AppHelper::getUserByGuard();
        return view('frontend.pages.customer.my-points')->with(['customer' => $customer]);
    }
}
