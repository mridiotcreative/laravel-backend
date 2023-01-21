<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use App\Models\Settings;
use App\User;
use App\Rules\MatchOldPassword;
use Hash;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Lang;

class AdminController extends Controller
{
    public function index()
    {
        $data = User::select(\DB::raw("COUNT(*) as count"), \DB::raw("DAYNAME(created_at) as day_name"), \DB::raw("DAY(created_at) as day"))
            ->where('created_at', '>', Carbon::today()->subDay(6))
            ->groupBy('day_name', 'day')
            ->orderBy('day')
            ->get();
        $array[] = ['Name', 'Number'];
        foreach ($data as $key => $value) {
            $array[++$key] = [$value->day_name, $value->count];
        }
        return view('backend.index')->with('users', json_encode($array));
    }

    public function profile()
    {
        $profile = Auth()->user();
        return view('backend.users.profile')->with('profile', $profile);
    }

    public function profileUpdate(Request $request, $id)
    {
        $validPhoto = $id ? 'image|sometimes' : 'image|required';
        $this->validate(
            $request,
            [
                'name' => 'string|required|max:30',
                'role' => 'required|in:admin,user',
                'photo' => $validPhoto,
            ]
        );
        $user = User::findOrFail($id);
        $data = $request->all();
        // Upload Image
        if ($request->hasFile('photo')) {
            $now = date('ymds') . '-';
            $photo = $request->file('photo');
            $photoName = $now . AppHelper::replaceSpaceIntoDash($photo->getClientOriginalName());
            $request->photo->storeAs(config('path.user'), $photoName);
            $data['photo'] = $photoName;
        }
        $status = $user->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', 'Successfully updated your profile');
        } else {
            request()->session()->flash('error', 'Please try again!');
        }
        return redirect()->back();
    }

    public function settings()
    {
        $data = Settings::get();
        return view('backend.setting')->with('data', $data);
    }

    // public function settingsUpdate(Request $request)
    // {
    //     $this->validate($request, [
    //         'short_des' => 'required|string',
    //         'description' => 'required|string',
    //         'photo' => 'sometimes|image',
    //         'logo' => 'sometimes|image',
    //         'address' => 'required|string',
    //         'email' => 'required|email',
    //         'phone' => 'required|string',
    //     ]);
    //     $data = $request->all();
    //     // Upload Logo
    //     if ($request->hasFile('logo')) {
    //         $now = date('ymds') . '-';
    //         $photo = $request->file('logo');
    //         $photoName = $now . AppHelper::replaceSpaceIntoDash($photo->getClientOriginalName());
    //         $request->logo->storeAs(config('path.site_logo'), $photoName);
    //         $data['logo'] = $photoName;
    //     }
    //     // Upload Image
    //     if ($request->hasFile('photo')) {
    //         $now = date('ymds') . '-';
    //         $photo = $request->file('photo');
    //         $photoName = $now . AppHelper::replaceSpaceIntoDash($photo->getClientOriginalName());
    //         $request->photo->storeAs(config('path.site_image'), $photoName);
    //         $data['photo'] = $photoName;
    //     }
    //     $settings = Settings::first();
    //     $status = $settings->fill($data)->save();
    //     if ($status) {
    //         request()->session()->flash('success', 'Setting successfully updated');
    //     } else {
    //         request()->session()->flash('error', 'Please try again');
    //     }
    //     return redirect()->route('admin');
    // }

    public function settingsUpdate(Request $request)
    {
        // $this->validate($request, [
        //     'values_data.*' => 'required|string',
        // ]);
        try {
            $data = $request->all();
            foreach ($data['keys_data'] as $key => $value) {
                $id = $data['keys_data_id'][$value];
                $obj_settings = Settings::where('id',$id)->first();
                if (@$request->file('values_data')[$value]) {
                    $now = date('ymds') . '-';
                    $photo = $request->file('values_data')[$value];
                    $photoName = $now . AppHelper::replaceSpaceIntoDash($photo->getClientOriginalName());
                    $photo->storeAs(config('path.site_logo'), $photoName);
                    $obj_settings->values_data = $photoName;
                } else {
                    $obj_settings->values_data = @$data['values_data'][$value] ?? $obj_settings->values_data;
                }
                $obj_settings->keys_data = $value;
                $obj_settings->save();
            }
            if ($obj_settings) {
                request()->session()->flash('success', 'Setting successfully updated');
            } else {
                request()->session()->flash('error', 'Please try again');
            }
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
        }
        return redirect()->route('admin');
    }

    public function changePassword()
    {
        return view('backend.layouts.changePassword');
    }
    public function changPasswordStore(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('admin')->with('success', 'Password successfully changed');
    }

    // Pie chart
    public function userPieChart(Request $request)
    {
        $data = User::select(\DB::raw("COUNT(*) as count"), \DB::raw("DAYNAME(created_at) as day_name"), \DB::raw("DAY(created_at) as day"))
            ->where('created_at', '>', Carbon::today()->subDay(6))
            ->groupBy('day_name', 'day')
            ->orderBy('day')
            ->get();
        $array[] = ['Name', 'Number'];
        foreach ($data as $key => $value) {
            $array[++$key] = [$value->day_name, $value->count];
        }
        return view('backend.index')->with('course', json_encode($array));
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        request()->session()->flash('success', Lang::get('messages.logout_success'));
        return back();
    }
}
