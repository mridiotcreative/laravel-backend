<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Models\Product;
use  App\Models\Cart;
use App\Helpers\AppHelper;
use Helper;
use Illuminate\Support\Facades\Validator;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Arr;

class CartController extends ApiController
{
    use HttpResponseTraits;

    // cart products listing
    public function index(){
        $cart = Cart::select('id','product_id','quantity')->where('customer_id', auth()->user()->id)->where('order_id', null)->with('product:id,title,slug,photo,price')->get();

        if (count($cart) > 0) {
            $data['cart_data'] = $cart;
            $data['totalCartPrice'] = Helper::totalCartPrice(auth()->user()->id);
            return $this->success(Lang::get('messages.cart_listing'), $data);
        }
        return $this->failure(Lang::get('messages.cart_empty'), Response::HTTP_NOT_FOUND);
    }

    // add product to cart
    public function addToCart(Request $request)
    {

        // validation
        if ($this->apiValidation($request, Cart::RULES)) {
            return $this->errors(Lang::get('messages.validation_error'), $this->errorsMessages);
        }

        if ($request->quantity > 0) {
            $qty = !empty($request->quantity) ? $request->quantity : 1;
            $product = Product::where('id', $request->product_id)->first();
            $product_price = str_replace(",", "", $product->price);
            $already_cart = Cart::where('customer_id', $request->user()->id)->where('product_id', $request->product_id)->where('order_id',null)->first();
            if ($already_cart) {
                $already_cart->quantity = $already_cart->quantity + $qty;
                $already_cart->price = $product_price;
                $already_cart->amount = ($product_price * $already_cart->quantity);
                $already_cart->save();
                return $this->success(Lang::get('messages.update_cart'));
            } else {
                $cart = new Cart;
                $cart->customer_id = $request->user()->id;
                $cart->product_id = $product->id;
                $cart->price = $product_price;
                $cart->quantity = $qty;
                $cart->amount = ($product_price * $cart->quantity);
                $cart->save();
                return $this->success(Lang::get('messages.add_cart'));
            }
        }
        return $this->failure(Lang::get('messages.cart_add_failed'), Response::HTTP_CONFLICT);
    }

    // delete product from cart
    public function cartDelete(Request $request)
    {
        $cart = Cart::query();
        if($request->has('cart_id')){
            $cart->where('id',$request->cart_id);
        }

        if(!$cart->first()){
            return $this->failure(Lang::get('messages.cart_empty'), Response::HTTP_NOT_FOUND);
        }

        if ($cart->delete()) {
            return $this->success(Lang::get('messages.cart_remove_product'));
        }
        return $this->failure(Lang::get('messages.cart_remove_product_failed'), Response::HTTP_CONFLICT);
    }

    public function cartRemove(Request $request)
    {
        if ($this->apiValidation($request, [
            'cart_id' => 'required|exists:App\Models\Cart,id',
            'product_id' => 'required|exists:App\Models\Cart,product_id',
        ])) {
            return $this->errors(Lang::get('messages.validation_error'), $this->errorsMessages);
        }

        $product_id = !empty($request->product_id) ? $request->product_id : "";
        $qty = !empty($request->quantity) ? $request->quantity : 1;

        $product = Product::where('id', $request->product_id)->first();

        if (empty($product)) {
            return $this->failure(Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
        }

        $already_cart = Cart::where(['customer_id'=>$request->user()->id,'order_id'=>null,'product_id'=>$product->id,'id'=> $request->cart_id])->first();

        if (empty($already_cart)) {
            return $this->failure(Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
        }

        if ($already_cart->quantity == 1) {
            $cart = Cart::where('id',$request->cart_id);
            $cart->delete();
        }

        $already_cart->quantity = $qty;
        $already_cart->amount = $already_cart->price * $already_cart->quantity;
        // if ($already_cart->product->stock < $already_cart->quantity || $already_cart->product->stock <= 0) {
        //     return response()->json(Lang::get('messages.out_of_stock'), Response::HTTP_BAD_REQUEST);
        // }
        //dd($already_cart);
        if ($already_cart->save()) {
            return $this->success(Lang::get('messages.cart_remove_product'));
        }
        return $this->failure(Lang::get('messages.something_went_wrong'), Response::HTTP_BAD_REQUEST);
    }
}
