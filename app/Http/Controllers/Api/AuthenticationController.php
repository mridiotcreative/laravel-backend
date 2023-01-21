<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Customer;
use App\User;
use App\Models\State;
use Illuminate\Http\Request;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Svg\Tag\Rect;
use Laravel\Passport\HasApiTokens;

class AuthenticationController extends ApiController
{
    use HttpResponseTraits;

    /**
     * Get User Role List
     *
     * @param Request $request
     * @return void
     */
    public function getUserTypes(Request $request)
    {
        $userTypes = AppHelper::getRoles();
        if ($userTypes->isEmpty()) {
            return $this->failure(Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
        }
        return $this->success(Lang::get('messages.success'), [
            'user_types' => $userTypes
        ]);
    }

    /**
     * Get State List
     *
     * @param Request $request
     * @return json
     */
    public function getState(Request $request)
    {
        $state = AppHelper::getState();
        if ($state->isEmpty()) {
            return $this->failure(Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
        }
        return $this->success(Lang::get('messages.success'), [
            'state' => $state
        ]);
    }

    /**
     * Get City List
     *
     * @param Request $request
     * @return json
     */
    public function getCity(Request $request)
    {
        $city = AppHelper::getCity();
        if ($city->isEmpty()) {
            return $this->failure(Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
        }
        return $this->success(Lang::get('messages.success'), [
            'city' => $city
        ]);
    }

    /**
     * Get State with City
     *
     * @param Request $request
     * @return json
     */
    public function getStateWithCity(Request $request)
    {
        $stateWithCity = AppHelper::stateWithCity();
        if ($stateWithCity->isEmpty()) {
            return $this->failure(Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
        }
        return $this->success(Lang::get('messages.success'), [
            'city_with_state' => $stateWithCity
        ]);
    }

    /**
     * Customer Login and create passport token for API Auth
     *
     * @param Request $request
     * @return json
     */
    public function login(Request $request)
    {
        // Validation Rules
        $rules = [
            'email' => 'string|required',
            'password' => 'required'
        ];
        // Validation
        if ($this->apiValidation($request, $rules)) {
            return $this->errors(Lang::get('messages.validation_error'), $this->errorsMessages);
        }
        $credentials = $request->only(['email', 'password']);
        if (!auth('user')->attempt($credentials)) {
            return $this->failure(Lang::get('messages.login_error'), Response::HTTP_UNAUTHORIZED);
        }
        // Check User Activation
        $user = auth('user')->user();

        // if ($user->is_verified != AppHelper::ACTIVE['status_code']) {
        //     return $this->failure(Lang::get('messages.account_pending'), Response::HTTP_UNAUTHORIZED);
        // } elseif ($user->getRawOriginal('status') != AppHelper::ACTIVE['status_code']) {
        //     return $this->failure(Lang::get('messages.account_inactive'), Response::HTTP_UNAUTHORIZED);
        // }

        // if (strtoupper($user->getRawOriginal('status')) != strtoupper(AppHelper::ACTIVE['status'])) {
        //     return $this->failure(Lang::get('messages.account_inactive'), Response::HTTP_UNAUTHORIZED);
        // }

        $token = $user->createToken('API_TOKEN')->accessToken;
        if (!empty($token)) {
            return $this->success(Lang::get('messages.login_success'), ['accessToken' => $token]);
        }
        return $this->failure(Lang::get('messages.something_went_wrong'));
    }

    /**
     * Customer register
     *
     * @param Request $request
     * @return json
     */
    public function register(Request $request)
    {
        // Validation
        if ($this->apiValidation($request, User::getRules($request), User::RULES_MSG)) {
            return $this->errors(Lang::get('messages.validation_error'), $this->errorsMessages);
        }
        $status = (new User)->createCustomer($request);
        if (!empty($status)) {
            return $this->success('Register successfully. Please do login');
        }
        return $this->failure(Lang::get('messages.something_went_wrong'));
    }

    /**
     * Customer forgot password
     *
     * @param Request $request
     * @return json
     */
    public function forgotPassword(Request $request)
    {
        // Validation
        $rule = [
            'email' => 'required|email|exists:customers',
        ];
        if ($this->apiValidation($request, $rule)) {
            return $this->errors(Lang::get('messages.validation_error'), $this->errorsMessages);
        }
        $customer = Customer::where('email', $request->email)
            ->where('status', 1)
            ->first();
        if (empty($customer)) {
            return $this->failure(Lang::get('messages.pending_activation'));
        }
        $customer->forgotPassword($request);
        return $this->success(Lang::get('messages.forgot_mail_send'), []);
    }

    /**
     * Customer logout
     *
     * @param Request $request
     * @return json
     */
    public function logout(Request $request)
    {
        if ($request->user()->token()->revoke()) {
            return $this->success(Lang::get('messages.logout_success'));
        }
        return $this->failure(Lang::get('messages.something_went_wrong'));
    }
}
