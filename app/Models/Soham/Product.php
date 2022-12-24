<?php

namespace App\Models\Soham;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mysql2';

    public function category()
    {
        return $this->belongsTo('App\Models\Soham\Category', 'category_id');
    }

    public function product_price()
    {
        return $this->hasMany('App\Models\Soham\ProductPrice', 'product_id')
            ->select(['qty', 'price']);
    }
}
