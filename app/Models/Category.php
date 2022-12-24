<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['title', 'slug', 'summary', 'photo', 'status', 'is_featured', 'is_parent', 'parent_id', 'added_by'];

    public function getImage()
    {
        return AppHelper::getStorageUrl(config('path.category'), $this->photo);
    }

    public function getPhotoAttribute($value)
    {
        if ($value) {
            $image_path =  asset('storage/uploads/category/' . $value);
        } else {
            $image_path = asset('storage/uploads/category/default.png');
        }

        return $image_path;
    }

    public function parent_info()
    {
        return $this->hasOne('App\Models\Category', 'id', 'parent_id');
    }
    public static function getAllCategory()
    {
        return  Category::orderBy('id', 'DESC')->with('parent_info')->paginate(10);
    }

    public static function shiftChild($cat_id)
    {
        return Category::whereIn('id', $cat_id)->update(['is_parent' => 1]);
    }
    public static function getChildByParentID($id)
    {
        return Category::where('parent_id', $id)->orderBy('id', 'ASC')->pluck('title', 'id');
    }

    public function child_cat()
    {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id')->where('status', 'active');
    }
    public static function getAllParentWithChild()
    {
        return Category::with('child_cat')->where('is_parent', 1)->where('status', 'active')->orderBy('title', 'ASC')->get();
    }
    public function products()
    {
        return $this->hasMany('App\Models\Product', 'cat_id', 'id')->where('status', 'active');
    }
    public function sub_products()
    {
        return $this->hasMany('App\Models\Product', 'child_cat_id', 'id')->where('status', 'active');
    }
    public static function getProductByCat($slug)
    {
        return Category::with('products')->where('slug', $slug)->first();
    }
    public static function getProductBySubCat($slug)
    {
        return Category::with('sub_products')->where('slug', $slug)->first();
    }
    public static function countActiveCategory()
    {
        $data = Category::where('status', 'active')->count();
        if ($data) {
            return $data;
        }
        return 0;
    }

    // product search scope
    public function scopeWhereLike($query, $column, $value) 
    {
        return $query->where($column, 'like', '%'.$value.'%');
    }

    // product search scope
    public function scopeOrWhereLike($query, $column, $value) 
    {
        return $query->orWhere($column, 'like', '%'.$value.'%');
    }

    public function getImageAttribute($value)
    {
        if ($value){
            $image_path =  asset('storage/uploads/category/' . $value);
        }else {
            $image_path = asset('storage/uploads/category/default.png');
        }

        return $image_path;
    }

    public function product_info()
    {
        return $this->hasMany('App\Models\Product', 'cat_id', 'id')
        ->where( function($query) {
            return $query->take(5);
        });
    }
}
