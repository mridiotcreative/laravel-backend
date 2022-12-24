<?php

namespace App\Models;

use App\Helpers\AppHelper;
use App\Mail\AccountVerifyMail;
use App\Mail\ResetPasswordMail;
use App\Rules\MatchCustomerOldPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;
use Auth;

class Customer extends Authenticatable
{
    use HasApiTokens;

    protected $guard = 'customer';

    protected $fillable = [
        'firm_name',
        'full_name',
        'email',
        'password',
        'state_id',
        'city_id',
        'pincode',
        'contact_no_1',
        'contact_no_2',
        'gst_no',
        'gst_document',
        'drug_licence_no',
        'drug_document',
        'id_proof_document',
        'designation',
        'is_verified',
        'status',
        'resion',
    ];

    protected $hidden = [
        'password',
    ];

    public static function getRules($request, $id = null)
    {
        $password = $id ? 'nullable' : 'required|min:6|max:8';
        return [
            'full_name' => 'required|max:50|min:3',
            'email' => "string|required|unique:customers,email,{$id}",
            'password' => $password,
            'contact_no_1' => 'required',
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
        'role_slug.required' => 'The User type field is required.',
    ];

    public function getStatusAttribute($value)
    {
        return AppHelper::getStatus($value);
    }

    public function getGstDocument()
    {
        return AppHelper::getStorageUrl(config('path.gst_document'), $this->gst_document);
    }

    public function getDrugDocument()
    {
        return AppHelper::getStorageUrl(config('path.drug_document'), $this->drug_document);
    }

    public function getIdProofDocument()
    {
        return AppHelper::getStorageUrl(config('path.id_proof_document'), $this->id_proof_document);
    }

    // Get Roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'customers_roles');
    }

    // Get State
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    // Get City
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // Insert Data into customer table
    public function createCustomer($request, $id = null)
    {
        // Create Customer Object
        $customer = $id ? Customer::find($id) : new Customer;
        $data = $request->all();
        // Image Directory
        $now = date('ymd') . '-';
        // Upload GST Doc
        if ($request->hasFile('gst_document')) {
            $gstDocument = $request->file('gst_document');
            $ext = $gstDocument->getClientOriginalExtension();
            $fileName = rand(11111, 99999) . '-' . 'gst_document.' . $ext;
            $gstDocumentName = $now . $fileName;
            $request->gst_document->storeAs(config('path.gst_document'), $gstDocumentName);
            $data['gst_document'] = $gstDocumentName;
        }
        // Upload Drug Doc
        if ($request->hasFile('drug_document')) {
            $drugDocument = $request->file('drug_document');
            $ext = $drugDocument->getClientOriginalExtension();
            $fileName = rand(11111, 99999) . '-' . 'drug_document.' . $ext;
            $drugDocumentName = $now . $fileName;
            $drugDocument->storeAs(config('path.drug_document'), $drugDocumentName);
            $data['drug_document'] = $drugDocumentName;
        }
        // Upload Id Prof Doc
        if ($request->hasFile('id_proof_document')) {
            $idProofDocument = $request->file('id_proof_document');
            $ext = $idProofDocument->getClientOriginalExtension();
            $fileName = rand(11111, 99999) . '-' . 'id_proof_document.' . $ext;
            $idProofDocumentName = $now . $fileName;
            $idProofDocument->storeAs(config('path.id_proof_document'), $idProofDocumentName);
            $data['id_proof_document'] = $idProofDocumentName;
        }
        // When new Customer
        if (!$id) {
            // Set default status is pending
            $data['status'] = AppHelper::PENDING['status_code'];
            // Password Make Hash
            $data['password'] = Hash::make($request->password);
        }
        // Save Data into Customer Table
        $status = $customer->fill($data)->save();
        if ($status && empty($id)) {
            // Get role id based on slug
            $role = Role::where('slug', $request->role_slug)->first();
            // Customer Attach Role
            $customer->roles()->attach($role->id);
            // Store FCM Token
            $customer->storeFCM($request);
            // Send Mail
            Mail::to($request->email)->send(new AccountVerifyMail($data['status']));
        }
        return $status;
    }

    // Send Mail For Reset Password Link
    public function forgotPassword($request)
    {
        $token = Str::random(32);
        $resetLink = route('password.setNew', [$token]);
        // Insert Data into password_resets
        PasswordReset::create([
            'email' => $request->email,
            'token' => $token,
            'created_at' => \Carbon\Carbon::now(),
        ]);
        Mail::to($request->email)->send(new ResetPasswordMail($resetLink));
    }

    // Reset Password
    public function resetPassword($request, $tokenData)
    {
        // Update password
        Customer::where('email', $tokenData->email)->update(['password' => Hash::make($request->new_password)]);
        // Remove Token
        PasswordReset::where('token', $tokenData->token)->delete();
    }

    // Change Password
    public function changePassword($request)
    {
        $customer = (Auth::guard('customer')->check()) ? Auth::guard('customer')->user() : Auth::guard('api')->user();
        $customer->password = Hash::make($request->new_password);
        return $customer->save();
    }

    // Store FCM Token
    public function storeFCM($request)
    {
        if (!empty($request->fcm_token)) {
            $this->fcm_token = $request->fcm_token;
            $this->save();
        }
    }
}
