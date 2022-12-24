<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceMaster extends Model
{
    protected $table = 'price_master';

    protected $fillable = ['product_id', 'user_id', 'special_price', 'created_by', 'status'];

    public function product_info()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }
    
    public function customer_info()
    {
        return $this->hasOne('App\Models\Customer', 'id', 'user_id');
    }

    public static function getAllProduct()
    {
        return PriceMaster::with(['product_info', 'customer_info'])->orderBy('id', 'desc')->get();
    }
}
