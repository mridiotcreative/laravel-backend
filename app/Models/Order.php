<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['customer_id','building_name','street_name','pincode','city', 'order_number', 'payment_id', 'status', 'points', 'sub_total_amount', 'total_amount', 'payment_method', 'payment_status', 'payment_response_log'];

    const RULES = [
       'address_id' => 'required|exists:customer_addresses,id',
       'payment_method' => 'required|alpha',
       'payment_id' => 'required_if:payment_method,==,paypal',
       'payment_response_log' => 'required_if:payment_method,==,paypal',
       'sub_total_amount' => 'required',
       'total_amount' => 'required'
    ];

    public function cart_info()
    {
        return $this->hasMany('App\Models\Cart', 'order_id', 'id')->with('product');
    }
    public static function getAllOrder($id)
    {
        return Order::with('cart_info')->find($id);
    }
    public static function countActiveOrder()
    {
        $data = Order::count();
        if ($data) {
            return $data;
        }
        return 0;
    }
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function shipping()
    {
        return $this->belongsTo(Shipping::class, 'shipping_id');
    }
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
    public static function getOrderHistory($id)
    {
        return Order::with('cart_info')->where('customer_id',$id)->get();
    }
}
