<?php

namespace App;

use App\Helpers\AppHelper;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Rules\MatchCustomerOldPassword;
use App\Mail\AccountVerifyMail;
use App\Mail\ResetPasswordMail;
use Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;
use Auth;

class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'photo', 'status', 'dob', 'gender', 'phone'
    ];

    public function getImage()
    {
        return url('storage/' . config('path.user') . $this->photo);
    }

    // public function getPhotoAttribute($value)
    // {
    //     if ($value) {
    //         return url('storage/' . config('path.user') . $value);
    //     } else {
    //         return '';
    //     }
    // }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getRules($request, $id = null)
    {
        $password = $id ? 'nullable' : 'required|min:6|max:8';
        return [
            'name' => 'required|max:50|min:3',
            'email' => "string|required|unique:users,email,{$id}",
            'password' => $password,
        ];
    }

    public static function getChangePasswordRules()
    {
        return [
            'old_password' => ['required', new MatchCustomerOldPassword],
            'new_password' => 'required|min:6|different:old_password',
            'confirm_password' => 'required|required_with:new_password|same:new_password'
        ];
    }

    const RULES_MSG = [
        'name.required' => 'Please enter your full name',
    ];

    // Insert Data into customer table
    public function createCustomer($request, $id = null)
    {
        // Create Customer Object
        $user = $id ? User::find($id) : new User;
        $data = $request->all();
        // Image Directory
        $now = date('ymd') . '-';

        // When new Customer
        if (!$id) {
            // Set default status is pending
            $data['status'] = AppHelper::PENDING['status_code'];
            // Password Make Hash
            $data['password'] = Hash::make($request->password);
        }
        $data['role'] = 'user';
        // Save Data into Customer Table
        $status = $user->fill($data)->save();
        if ($status && empty($id)) {
            // Send Mail
            //Mail::to($request->email)->send(new AccountVerifyMail($data['status']));
        }
        return $status;
    }
}
