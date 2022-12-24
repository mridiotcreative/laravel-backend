<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferMaster extends Model
{
    protected $table = 'offer_master';

    protected $fillable = ['product_id', 'special_price', 'start_date', 'end_date', 'created_by', 'status'];

    public function product_info()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public static function getAllProduct()
    {
        return OfferMaster::with(['product_info'])->orderBy('id', 'desc');
    }
}
