<?php

namespace App\Models;

use Helper;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'product_id', 'order_id', 'quantity', 'amount', 'price', 'status'];

    const RULES = [
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|numeric',
        // 'price' => 'required|numeric',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function getPaymentDetails()
    {
        $subTotalAmount = Helper::totalCartPrice();
        $points = 0;
        $totalAmount = $subTotalAmount;
        if (session()->has('points')) {
            $points = session('points');
            $totalAmount = $subTotalAmount - $points;
        }
        return [
            'cart_total' => Helper::cartCount(),
            'sub_total' => number_format($subTotalAmount, 2),
            'points' => number_format($points),
            'total_amount' => number_format($totalAmount, 2),
        ];
    }
}
