<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use Auth;
use Illuminate\Http\Request;
use App\Models\Product;
// use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\CustomerAddress;
use Illuminate\Support\Str;
use Helper;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

class CartController extends Controller
{
    protected $product = null;
    protected $model = null;

    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->model = new Cart;
    }

    public function cart(Request $request)
    {
        $user = Auth::guard('customer')->user();
        return view('frontend.pages.cart')->with([
            'user' => $user
        ]);
    }

    public function cartPoint(Request $request)
    {
        $submit = $request->input('submit');
        $points = $request->input('point');
        $user = AppHelper::getUserByGuard();
        if ($points > $user->total_points) {
            return response()->json(Lang::get('messages.points_greater_wallet'), Response::HTTP_BAD_REQUEST);
        }
        $subTotalAmount = Helper::totalCartPrice();
        $msg = Lang::get('messages.points_removed');
        if (empty($points)) {
            return response()->json(Lang::get('messages.points_required'), Response::HTTP_BAD_REQUEST);
        } elseif ($points > $subTotalAmount) {
            return response()->json(Lang::get('messages.points_greater_amount'), Response::HTTP_BAD_REQUEST);
        } elseif ($submit == 1) {
            session()->put('points', $points);
            $msg = Lang::get('messages.points_applied');
        } else {
            session()->forget('points');
        }
        $response['message'] = $msg;
        $response['result'] = $this->model->getPaymentDetails();
        return response()->json($response, Response::HTTP_OK);
    }

    public function orderSummery(Request $request)
    {
        $addresses = (new CustomerAddress)->getAddressList($request);
        return view('frontend.pages.order-summery')->with([
            'addresses' => $addresses
        ]);
    }

    public function addToCart(Request $request)
    {
        $slug = !empty($request->slug) ? $request->slug : "";
        $qty = !empty($request->quantity) ? $request->quantity : 1;
        $product = Product::where('slug', $slug)->first();
        if (empty($product)) {
            return response()->json(Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
        }
        $already_cart = Cart::where('customer_id', auth('customer')->user()->id)->where('order_id', null)->where('product_id', $product->id)->first();
        if ($already_cart) {
            $already_cart->quantity = $already_cart->quantity + $qty;
            $already_cart->amount = ($already_cart->price * $already_cart->quantity);
            // if ($already_cart->product->stock < $already_cart->quantity || $already_cart->product->stock <= 0) {
            //     return response()->json(Lang::get('messages.out_of_stock'), Response::HTTP_BAD_REQUEST);
            // }
            $already_cart->save();
        } else {
            $cart = new Cart;
            $cart->customer_id = auth('customer')->user()->id;
            $cart->product_id = $product->id;
            $cart->price = ($product->price - ($product->price * $product->discount) / 100);
            $cart->quantity = $qty;
            $cart->amount = $cart->price * $cart->quantity;
            // if ($cart->product->stock < $cart->quantity || $cart->product->stock <= 0) {
            //     return response()->json(Lang::get('messages.out_of_stock'), Response::HTTP_BAD_REQUEST);
            // }
            $cart->save();
        }
        $response['message'] = Lang::get('messages.item_add_cart');
        $response['cart_total'] = Helper::cartCount();
        return response()->json($response, Response::HTTP_OK);
    }

    public function cartDelete(Request $request)
    {
        $cart = Cart::find($request->cart_id);
        if ($cart && $cart->delete()) {
            return response()->json([
                'message' => Lang::get('messages.item_remove_cart'),
                'result' => $this->model->getPaymentDetails(),
            ], Response::HTTP_OK);
        }
        return response()->json(Lang::get('messages.something_went_wrong'), Response::HTTP_BAD_REQUEST);
    }

    public function cartUpdate(Request $request)
    {
        $slug = !empty($request->slug) ? $request->slug : "";
        $qty = !empty($request->quantity) ? $request->quantity : 1;
        $product = Product::where('slug', $slug)->first();
        if (empty($product)) {
            return response()->json(Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
        }
        $already_cart = Cart::where('customer_id', auth('customer')->user()->id)->where('order_id', null)->where('product_id', $product->id)->first();
        $already_cart->quantity = $qty;
        $already_cart->amount = $already_cart->price * $already_cart->quantity;
        // if ($already_cart->product->stock < $already_cart->quantity || $already_cart->product->stock <= 0) {
        //     return response()->json(Lang::get('messages.out_of_stock'), Response::HTTP_BAD_REQUEST);
        // }
        //dd($already_cart);
        if ($already_cart->save()) {
            $response['message'] = Lang::get('messages.item_update_qty');
            $response['result'] = $this->model->getPaymentDetails();
            return response()->json($response, Response::HTTP_OK);
        }
        return response()->json(Lang::get('messages.something_went_wrong'), Response::HTTP_BAD_REQUEST);
    }
}
