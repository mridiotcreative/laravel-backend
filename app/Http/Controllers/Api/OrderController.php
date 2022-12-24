<?php

namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use  App\Models\Cart;
use  App\Models\Order;
use  App\Models\CustomerAddress;
use Helper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

class OrderController extends ApiController
{
    use HttpResponseTraits;

    // order listing
    public function index(){

        $orders = Order::select('id','order_number','status','created_at','delivered')->where('customer_id',request()->user()->id)->with('cart_info:product_id,order_id')->get();
       
        foreach($orders as $order){
            $order->date = $order->created_at->format('M d, Y');
            $order->delivered = $order->delivered ? $order->delivered->format('M d, Y') : '';
            unset($order->created_at);

            $product_name = [];
            foreach($order->cart_info as $info){
                $product_name[] = $info->product->title;
                unset($info['product']);
            }
            $order->product_name = implode(",",$product_name);
            $order->total_item = count($order->cart_info);
            unset($order->cart_info);
        }

        if(count($orders) > 0) {
            $data['orders_data'] = $orders;
            return $this->success(Lang::get('messages.order_listing'), $data);
        }
        return $this->failure(Lang::get('messages.order_data_not_found'), Response::HTTP_NOT_FOUND); 
    }

    // place order api
    public function place(Request $request){

        $userId = $request->user()->id;
        
        // validation
        if ($this->apiValidation($request, Order::RULES)) {
            return $this->errors(Lang::get('messages.validation_error'), $this->errorsMessages);
        }

        if (empty(Cart::where('customer_id', $userId)->where('order_id', null)->first())) {
            return $this->failure(Lang::get('messages.cart_empty'), Response::HTTP_NOT_FOUND); 
        }

        // get customer address
        // $customer_address = CustomerAddress::where('customer_id',$userId)->where('is_primary',1)->first();       
        $customer_address = CustomerAddress::find($request->address_id);       

        $order = new Order();
        $order_data = $request->all();
        $order_data['order_number'] = 'ORD-' . strtoupper(Str::random(10));
        $order_data['customer_id'] = $userId;

        $order_data['building_name'] = $customer_address->building_name;
        $order_data['street_name'] = $customer_address->street_name;
        $order_data['pincode'] = $customer_address->pincode;
        $order_data['city'] = $customer_address->city;
       
        // $order_data['sub_total_amount'] = AppHelper::totalCartPrice($userId);
        $order_data['sub_total_amount'] = $request->sub_total_amount;
        // $order_data['total_amount'] = AppHelper::totalCartPrice($userId);
        $order_data['total_amount'] = $request->total_amount;
        
        if (request('payment_method') == 'razorpay') {
            $order_data['payment_method'] = 'razorpay';
            $order_data['payment_status'] = 'paid';
            $order_data['payment_response_log'] = $request->payment_response_log;
        } else {
            $order_data['payment_method'] = 'cod';
            $order_data['payment_status'] = 'unpaid';
        }
        $order->fill($order_data);
        $status = $order->save();
        
        if ($status) {
            Cart::where('customer_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order->id]);
            return $this->success(Lang::get('messages.order_placed'));
        }
        return $this->failure(Lang::get('messages.order_placed_failed'), Response::HTTP_CONFLICT); 
    }

     // order summary
     public function detail($id){

        $order = Order::select('id','order_number','status','created_at','delivered','sub_total_amount','total_amount')
                ->where('id',$id)
                ->where('customer_id',request()->user()->id)
                ->with('cart_info:product_id,order_id,quantity')
                ->first();

        $order->date = $order->created_at->format('M d, Y');
        $order->delivered = $order->delivered ? $order->delivered->format('M d, Y') : '';
        $order->total_item = count($order->cart_info);
      
        $product_info = [];
        foreach($order->cart_info as $info){
            $product_name[] = $info->product->title;

            $product  = (object)[];
            $product->title = $info->product->title;
            $product->quantity = $info->quantity;
            $product->price = $info->product->price;
            $product->photo = $info->product->photo;
            $product->offer_price = $info->product->offer_price;
            $product->offer_discount = $info->product->offer_discount;
            
            $product_info[] = $product;
        }
        $order->product_info = $product_info;
        unset($order->created_at,$order->cart_info);

        if($order) {
            $data['orders_data'] = $order;
            return $this->success(Lang::get('messages.order_details'), $data);
        }
        return $this->failure(Lang::get('messages.order_data_not_found'), Response::HTTP_NOT_FOUND); 
    }
}
