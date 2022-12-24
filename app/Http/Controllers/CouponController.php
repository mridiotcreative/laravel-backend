<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Models\Cart;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupon = Coupon::orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));
        return view('backend.coupon.index')->with('coupons', $coupon);
    }

    /**
     * Common Method Used For Create Or Edit Form
     */
    public function createOrEdit($id = null)
    {
        $coupon = $id ? Coupon::findOrFail($id) : null;
        return view('backend.coupon.commonCouponPage')->with('coupon', $coupon);
    }

    /**
     * Common Method Used For Store Or Update Data
     */
    public function storeOrUpdate(Request $request, $id = null)
    {
        $coupon = $id ? Coupon::findOrFail($id) : new Coupon;
        $this->validate($request, [
            'code' => 'string|required',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric',
            'status' => 'required|in:active,inactive'
        ]);
        $data = $request->all();
        $status = $coupon->fill($data)->save();
        if ($status) {
            $msg = $id ? 'Coupon Successfully updated' : 'Coupon Successfully added';
            request()->session()->flash('success', $msg);
        } else {
            request()->session()->flash('error', 'Please try again!!');
        }
        return redirect()->route('coupon.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $coupon = Coupon::find($id);
        if ($coupon) {
            $status = $coupon->delete();
            if ($status) {
                request()->session()->flash('success', 'Coupon successfully deleted');
            } else {
                request()->session()->flash('error', 'Error, Please try again');
            }
            return redirect()->route('coupon.index');
        } else {
            request()->session()->flash('error', 'Coupon not found');
            return redirect()->back();
        }
    }

    public function couponStore(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();
        if (!$coupon) {
            request()->session()->flash('error', 'Invalid coupon code, Please try again');
            return back();
        }
        if ($coupon) {
            $total_price = Cart::where('user_id', auth()->user()->id)->where('order_id', null)->sum('price');
            session()->put('coupon', [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'value' => $coupon->discount($total_price)
            ]);
            request()->session()->flash('success', 'Coupon successfully applied');
            return redirect()->back();
        }
    }
}
