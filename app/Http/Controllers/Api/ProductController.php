<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Helpers\AppHelper;
use App\Models\Category;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Svg\Tag\Rect;

class ProductController extends ApiController
{
    use HttpResponseTraits;
    
    // products listing
    public function index(Request $request){

        $category_id = null;
        if($request->has('category_id')){
            $category_id = $request->category_id == 0 ? null : $request->category_id;
        }

        // search key
        $request->has('search_key') && $request->search_key ? $search_key = $request->search_key : $search_key = '';

        // sort key value
        $request->has('sort_key') && $request->sort_key ? $sort_key = $request->sort_key : $sort_key = 'ASC';

        // sort by value
        $request->has('sort_by') && $request->sort_by  ? $sort_by = $request->sort_by : $sort_by = 'title';
        
        $limit = config('constants.PER_PAGE');

        $products = Product::select('id','title','slug','cat_id','status','price','photo')
            // with('cat_info:id,title')
            ->where(function($q) use ($category_id) {
                if($category_id){
                    $q->where('cat_id', $category_id);
                }   
            })
            ->where( function($query) use($search_key) {
                $query->whereLike('title',$search_key)
                ->orWhereLike('description',$search_key)
                ->orWhereLike('summary',$search_key)
                ->orWhereLike('created_at',$search_key);
            })
            ->orderBy($sort_by,$sort_key)
          
            ->paginate($limit);

        if($products->count() > 0){
            $products = AppHelper::key_change($products,'product_data');
            $data['product_list'] = $products;
            return $this->success(Lang::get('messages.get_all_product'), $data);
        }else{ 
            return $this->failure(Lang::get('messages.no_product_found'), Response::HTTP_NOT_FOUND);  
        }

    }

    // get product category wise
    public function getCategoryWise(Request $request){

        // search key
        $request->has('search_key') && $request->search_key ? $search_key = $request->search_key : $search_key = '';

        $categories = Category::select('id','title','slug')
            ->has('products') 
            ->with(['products' => function ($query){
                $query->select('id','title','cat_id','price','photo')
                ->where( function($query) {
                    return $query->take(5);
                });
            }])
            ->whereLike('title',$search_key)
            ->orWhereHas('products', function ($query) use ($search_key) {
                $query->whereLike('title',$search_key);
            })
            ->get();
        
        if($categories->count() > 0){
            $data['category_list'] = $categories;
            return $this->success(Lang::get('messages.get_all_category'), $data);
        }else{ 
            return $this->failure(Lang::get('messages.no_category_found'), Response::HTTP_NOT_FOUND);  
        }
    }

    // product details
    public function details($slug){

        $products = Product::select('id','title','slug','cat_id','price','photo','description','created_at')
            // ->with('cat_info:title')
            ->where('slug',$slug)->first();

        $products->price = number_format($products->price,2);
        // $products->offer_discount = $products->offer_discount;
        $products->description = AppHelper::removeSlashes($products->description);
        // $products->summary = AppHelper::removeSlashes($products->summary);
        $products->date = $products->created_at->format('M d, Y');

        $review = array();
        $review['avg_rating'] = $products->avg_rating;
        $review['total_review'] = $products->total_review;

        $review_data = $products->review_data;

        $rev_arr = [];
        foreach($review_data as $rev){
            $data_arr = (object)[];
            $data_arr->rate = $rev->rate;
            $data_arr->review = $rev->review;
            $data_arr->user_name = $rev->user_info ? $rev->user_info->firm_name : '';

            $rev_arr[] = $data_arr;
        }

        $review['reviews_data'] = $rev_arr;

        $products->reviews = $review;
        
        unset($products->created_at,$products->review_data); 

        if($products){
            $data['product_data'] = $products;
            return $this->success(Lang::get('messages.product_details'), $data);
        }else{   
            return $this->failure(Lang::get('messages.no_product_detail_found'), Response::HTTP_NOT_FOUND);  
        }

    }


}
