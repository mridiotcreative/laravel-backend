<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = ['customer_id', 'product_id', 'rate', 'review', 'status'];

    const RULES = [
        'product_id' => 'required|exists:products,id',
        'rate' => 'required|numeric',
        'review' => 'required',
    ];

    public function user_info()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public static function getAllReview()
    {
        return ProductReview::with('user_info')->paginate(10);
    }
    public static function getAllUserReview()
    {
        return ProductReview::where('user_id', auth()->user()->id)->with('user_info')->paginate(10);
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
