<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use Session;
use App\Models\Customer;
use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\PasswordReset;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Facades\Lang;

class LoginController extends Controller
{
    use HttpResponseTraits;

    private $model = null;

    public function __construct()
    {
        $this->model = new Customer;
    }

    public function login()
    {
        return view('frontend.pages.login');
    }

    public function loginSubmit(Request $request)
    {
        $this->validate($request, [
            'email' => 'string|required',
            'password' => 'required',
            'role_type' => 'required|in:1,2',
        ]);
        $data = $request->all();
        unset($data['radio-group']);
        if (!Auth::guard('customer')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            request()->session()->flash('error', Lang::get('messages.login_error'));
            return redirect()->back();
        }
        $user = Auth::guard('customer')->user();
        // Check Role Type
        $role = $user->roles()->first();
        if ($request->role_type == 2 && $role->slug != 'mr') {
            Auth::guard('customer')->logout();
            request()->session()->flash('error', Lang::get('messages.login_error'));
            return redirect()->back();
        } elseif ($request->role_type == 1 && $role->slug == 'mr') {
            Auth::guard('customer')->logout();
            request()->session()->flash('error', Lang::get('messages.login_error'));
            return redirect()->back();
        }
        if ($user->is_verified != AppHelper::ACTIVE['status_code']) {
            request()->session()->flash('error', Lang::get('messages.account_pending'));
        } elseif ($user->getRawOriginal('status') != AppHelper::ACTIVE['status_code']) {
            request()->session()->flash('error', Lang::get('messages.account_inactive'));
        } else {
            request()->session()->flash('success', Lang::get('messages.login_success'));
            return redirect()->route('home');
        }
        Auth::guard('customer')->logout();
        return redirect()->back();
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        request()->session()->flash('success', Lang::get('messages.logout_success'));
        return back();
    }

    public function register()
    {
        return view('frontend.pages.register');
    }

    public function registerSubmit(Request $request)
    {
        $this->validate($request, Customer::getRules($request), Customer::RULES_MSG);
        $status = $this->model->createCustomer($request);
        if ($status) {
            request()->session()->flash('success', Lang::get('messages.register_success'));
            return redirect()->route('home');
        } else {
            request()->session()->flash('error', Lang::get('messages.something_went_wrong'));
            return back();
        }
    }

    public function resetPasswordForm()
    {
        return view('frontend.pages.reset_password');
    }

    public function resetPasswordSubmit(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:customers',
        ]);
        $customer = Customer::where('email', $request->email)
            ->where('status', 1)
            ->first();
        if (empty($customer)) {
            request()->session()->flash('error', Lang::get('messages.pending_activation'));
            return redirect()->back();
        }
        $customer->forgotPassword($request);
        request()->session()->flash('success', Lang::get('messages.forgot_mail_send'));
        return redirect()->back();
    }

    /**
     * Customer set new password view
     *
     * @param Request $request
     * @param [type] $token
     * @return view
     */
    public function setNewPasswordForm(Request $request, $token)
    {
        if (empty(PasswordReset::getTokenData($token))) {
            request()->session()->flash('error', Lang::get('messages.invalid_token'));
            return redirect()->route('home');
        }
        return view('frontend.pages.set_new_password')->with(['token' => $token]);
    }

    /**
     * Customer set new password
     *
     * @param Request $request
     * @param [type] $token
     * @return redirect
     */
    public function setNewPasswordSubmit(Request $request, $token)
    {
        $tokenData = PasswordReset::getTokenData($token);
        if (empty($tokenData)) {
            request()->session()->flash('error', Lang::get('messages.invalid_token'));
            return redirect()->route('home');
        }
        $this->validate($request, [
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|required_with:new_password|same:new_password'
        ]);
        $this->model->resetPassword($request, $tokenData);
        request()->session()->flash('success', Lang::get('messages.password_updated'));
        return redirect()->route('home');
    }

    /**
     * Get city using by state id
     *
     * @param Request $request
     * @return json
     */
    public function getCityByState(Request $request)
    {
        $stateId = isset($request->state_id) ? $request->state_id : null;
        $city = AppHelper::getCityByState($stateId);
        if ($city->isEmpty()) {
            return $this->failure(Lang::get('messages.empty_city'));
        }
        return $this->success('Success', $city);
    }
}
