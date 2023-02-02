<?php

namespace App\Helpers;

use App\Models\City;
use App\Models\Role;
use App\Models\State;
use App\Models\CmsDetails;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Settings;
use Auth;
use Illuminate\Support\Arr;

class AppHelper
{

    // Manage Status
    const INACTIVE = ['status_code' => '0', 'status' => 'Inactive'];
    const ACTIVE = ['status_code' => '1', 'status' => 'Active'];
    const PENDING = ['status_code' => '2', 'status' => 'Pending'];
    const DECLINED = ['status_code' => '3', 'status' => 'Declined'];
    const REJECTED = ['status_code' => '4', 'status' => 'Rejected'];
    const RESTRICTED = ['status_code' => '5', 'status' => 'Restricted'];
    const COMPLETED = ['status_code' => '6', 'status' => 'Completed'];

    // Get Roles
    public static function getRoles()
    {
        // return Role::all();
        return [];
    }

    // Get State
    public static function getState()
    {
        return State::all();
    }

    // Get City
    public static function getCity()
    {
        return City::all();
    }

    // Get State With City
    public static function stateWithCity()
    {
        return State::with('city')->get();
    }

    // Get City Based On State
    public static function getCityByState($stateId)
    {
        return City::where('state_id', $stateId)->get();
    }

    // Get Status
    public static function getStatus($code)
    {
        switch ($code) {
            case AppHelper::ACTIVE['status_code']:
                return AppHelper::ACTIVE['status'];
                break;
            case AppHelper::PENDING['status_code']:
                return AppHelper::PENDING['status'];
                break;
            case AppHelper::DECLINED['status_code']:
                return AppHelper::DECLINED['status'];
                break;
            case AppHelper::REJECTED['status_code']:
                return AppHelper::REJECTED['status'];
                break;
            case AppHelper::RESTRICTED['status_code']:
                return AppHelper::RESTRICTED['status'];
                break;
            case AppHelper::COMPLETED['status_code']:
                return AppHelper::COMPLETED['status'];
                break;
            default:
                return AppHelper::INACTIVE['status'];
                break;
        }
    }

    // Get Image URL with Prefix Storage Path
    public static function getStorageUrl($path, $fileName)
    {
        $storage = config('path.storage');
        $fullPath =  "{$storage}{$path}{$fileName}";
        return url($fullPath);
    }

    // Get Auth User Details Using Guard
    public static function getUserByGuard()
    {
        return !empty(Auth::guard('customer')->user()) ? Auth::guard('customer')->user() : Auth::guard('api')->user();
    }

    // Get File Name Replace Space Into Dash(-)
    public static function replaceSpaceIntoDash($str = '')
    {
        return str_replace(' ', '-', $str);
    }

    // Remove Slashes
    public static function removeSlashes($str = '')
    {
        return stripslashes($str);
    }

     // Get State With City
     public static function cmsDetails()
     {
         return CmsDetails::where('status','active')->orderBy('id', 'DESC')->get();
     }

     public static function getfeaturedCategoryDetails()
     {
         return Category::where(['status'=>'active','is_featured'=>1])->orderBy('id', 'DESC')->get();
     }


    // message format for api
    public static function formatMessage($statusCode, $message,$result=null)
    {
        $res = [
            'status' => $statusCode,
            'message' => $message,
        ];

        if($result){
            $res['result'] = $result;
        }

        return $res;
    }

    // validation message format for api
    public static function validationMessage($statusCode,$message,$error)
    {
        $res = [
            'status' => $statusCode,
            'message' => $message,
            'error' => $error,
        ];

        return $res;
    }

    // Cart Count
    public static function cartCount($user_id = '')
    {

        if (Auth::guard('customer')->check()) {
            if ($user_id == "") $user_id = auth('customer')->user()->id;
            return Cart::where('customer_id', $user_id)->where('order_id', null)->sum('quantity');
        } else {
            return 0;
        }
    }

    // Total amount cart
    public static function totalCartPrice($user_id)
    {
        return Cart::where('customer_id', $user_id)->where('order_id', null)->sum('amount');
    }

    public static function key_change($result,$key_name){

        $result = $result->toArray();

        $data['current_page'] = $result['current_page'];
        $data['last_page'] = $result['last_page'];
        $data['first_page_url'] = $result['first_page_url'];
        $data['last_page_url'] = $result['last_page_url'];
        $data['next_page_url'] = $result['next_page_url'];
        $data['prev_page_url'] = $result['prev_page_url'];

        $result_data = $data;
        $result_data[$key_name] = $result['data'];

        return $result_data;
    }

    public static function getSettingData()
    {
        $email = "";
        $logo = "";

        $emailData = Settings::where(['keys_data'=>'Email'])->first();
        $logoData = Settings::where(['keys_data'=>'Logo'])->first();
        if ($logoData) {
            $logo = AppHelper::getStorageUrl(config('path.site_logo'), $logoData->values_data);
        }
        if ($emailData) {
            $email = $emailData->values_data;
        }
        $data['email'] = $email;
        $data['logo'] = $logo;

        return $data;
    }
}
