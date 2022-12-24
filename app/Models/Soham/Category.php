<?php

namespace App\Models\Soham;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // protected $connection = 'mysql2';

    public function product_info()
    {
        return $this->hasMany('App\Models\Product', 'cat_id', 'id')
        ->where( function($query) {
            return $query->take(5);
        });
    }

    // category search scope
    public function scopeWHereLike($query, $column, $value) 
    {
        return $query->where($column, 'like', '%'.$value.'%');
    }

    // product search scope
    public function scopeOrWHereLike($query, $column, $value) 
    {
        return $query->orWhere($column, 'like', '%'.$value.'%');
    }
}
