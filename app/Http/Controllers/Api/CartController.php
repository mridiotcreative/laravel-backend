<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Models\Product;
use  App\Models\Cart;
use App\Helpers\AppHelper;
use Illuminate\Support\Facades\Validator;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

class CartController extends ApiController
{
    use HttpResponseTraits;

    // cart products listing
    public function index(){
   
        // $cart = Cart::select('id','product_id','quantity')->where('customer_id', auth()->user()->id)->where('order_id', null)->with('product:id,title,slug,photo as image,price,cat_id')->get();
        $cart = Cart::select('id','product_id','quantity')->where('customer_id', auth()->user()->id)->where('order_id', null)->with('product:id,title,slug,photo,price')->get();

        if (count($cart) > 0) {
            $data['cart_data'] = $cart;
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
        // $validator = Validator::make($request->all(), [
        //     'product_id' => 'required|exists:products,id',
        //     'quantity' => 'required|numeric',
        //     'price' => 'required|numeric',
        //     // 'amount' => 'required|numeric',
        // ]);

        // if ($validator->fails()) {
        //     return AppHelper::validationMessage(false,'Validation failed',$validator->errors());
        // }
        
        $qty = !empty($request->quantity) ? $request->quantity : 1;
        $product = Product::where('id', $request->product_id)->first();
        
        $already_cart = Cart::where('customer_id', $request->user()->id)->where('product_id', $request->product_id)->where('order_id',null)->first();
        if ($already_cart) {
            $already_cart->quantity = $already_cart->quantity + $qty;
            $already_cart->price = $product->price;
            $already_cart->amount = ($product->price * $already_cart->quantity);
            $already_cart->save();
            return $this->success(Lang::get('messages.update_cart'));
        } else {
            $cart = new Cart;
            $cart->customer_id = $request->user()->id;
            $cart->product_id = $product->id;
            $cart->price = $product->price;
            $cart->quantity = $qty;
            $cart->amount = $product->price * $cart->quantity;
            $cart->save();
            return $this->success(Lang::get('messages.add_cart'));
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
}
