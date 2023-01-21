<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class Product extends Model
{

    protected $fillable = ['title', 'slug', 'summary', 'description', 'cat_id', 'child_cat_id', 'price', 'discount', 'status', 'photo', 'size', 'stock', 'is_featured', 'condition'];

    protected $hidden = [
        'condition','size','stock','discount','child_cat_id','is_featured'
    ];

    //protected $appends = ['category_name','offer_price','offer_discount'];
    protected $appends = ['offer_discount'];
    // public function getPriceAttribute(){
    //     return number_format($this->price,2);
    // }

    public function getImage()
    {
        return AppHelper::getStorageUrl(config('path.product'), $this->photo);
    }

    public function cat_info()
    {
        return $this->hasOne('App\Models\Category', 'id', 'cat_id');
    }
    public function sub_cat_info()
    {
        return $this->hasOne('App\Models\Category', 'id', 'child_cat_id');
    }
    public function offer_master_info()
    {
        $mytime = \Carbon\Carbon::now();
        $date = $mytime->format('Y-m-d');
        return $this->hasOne('App\Models\OfferMaster', 'product_id', 'id')->where('status', 'active')->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date);
    }
    public function price_master_info()
    {
        $user_id = (auth('customer')->user() != null) ? auth('customer')->user()->id : "";
        return $this->hasOne('App\Models\PriceMaster', 'product_id', 'id')->where(['status'=>'active','user_id'=>$user_id]);
    }
    public static function getAllProduct()
    {
        return Product::with(['cat_info', 'sub_cat_info'])->orderBy('id', 'desc')->paginate(10);
    }
    public function rel_prods()
    {
        return $this->hasMany('App\Models\Product', 'cat_id', 'cat_id')->where('status', 'active')->orderBy('id', 'DESC')->limit(8);
    }
    public function getReview()
    {
        return $this->hasMany('App\Models\ProductReview', 'product_id', 'id')->with('user_info')->where('status', 'active')->orderBy('id', 'DESC');
    }
    public static function getProductBySlug($slug)
    {
        return Product::with(['cat_info', 'rel_prods', 'getReview', 'offer_master_info', 'price_master_info'])->where('slug', $slug)->first();
    }
    public static function countActiveProduct()
    {
        $data = Product::where('status', 'active')->count();
        if ($data) {
            return $data;
        }
        return 0;
    }

    public function carts()
    {
        return $this->hasMany(Cart::class)->whereNotNull('order_id');
    }

    // public function wishlists()
    // {
    //     return $this->hasMany(Wishlist::class)->whereNotNull('cart_id');
    // }

    public function getPhotoAttribute($value)
    {

        $image_path = [];
        if ($value) {
            $images = explode(",",$value);
            foreach($images as $image){
                $image_path[] =  asset('storage/uploads/product/' . $image);
            }
        } else {
            $image_path[] = asset('storage/uploads/product/default.png');
        }

        return $image_path;
    }

    // product search scope
    public function scopeWHereLike($query, $column, $value)
    {
        return $query->where($column, 'like', '%'.$value.'%');
    }

    // product search scope
    public function scopeOrWHereLike($query, $column, $value)
    {
        return $query->orWhere($column, 'like', '%'.$value.'%');
    }

    public function getPriceAttribute()
    {
        $price = $this->attributes['price'];
        return number_format($price,2);
    }

    public function getCategoryNameAttribute()
    {
       $category_name = $this->cat_info ? $this->cat_info->title : '';
       unset($this->cat_info);
       return $category_name;
    }

    public function getOfferPriceAttribute()
    {
       $new_price = ($this->price_master_info != null) ? $this->price_master_info->special_price : (($this->offer_master_info != null)  ? $this->offer_master_info->special_price : $this->price);
       unset($this->price_master_info,$this->offer_master_info);
       if(is_double($new_price)){
        $new_price = number_format($new_price,2);
       }
       return $new_price;
    }

    public function getOfferDiscountAttribute()
    {
       $new_price = ($this->price_master_info != null) ? $this->price_master_info->special_price : (($this->offer_master_info != null)  ? $this->offer_master_info->special_price : $this->price);
       $old_price = $this->price;

       $discount = '0%';
       if($old_price !== $new_price){
        $discount = ($new_price * 100 )/ $old_price;
       }

       unset($this->price_master_info,$this->offer_master_info);
       return round($discount,0).'%';
    }

    public function getAvgRatingAttribute()
    {
        return round($this->hasMany('App\Models\ProductReview', 'product_id', 'id')->avg('rate'),1);
    }

    public function getTotalReviewAttribute()
    {
        return $this->hasMany('App\Models\ProductReview', 'product_id', 'id')->count();
    }

    public function review_data()
    {
        return $this->hasMany('App\Models\ProductReview', 'product_id', 'id')->with('user_info')->where('status', 'active')->orderBy('id', 'DESC');
    }

    public function barcode_info()
    {
        return $this->hasOne('App\Models\IncentiveBarcode', 'product_id', 'id');
    }

}
