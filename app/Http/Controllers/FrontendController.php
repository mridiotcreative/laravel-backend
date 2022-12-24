<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\PostTag;
use App\Models\PostCategory;
use App\Models\Post;
use App\Models\Cart;
use App\Models\ContactUs;
use App\User;
use Auth;
use Session;
use Newsletter;
use DB;
use Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

class FrontendController extends Controller
{

    public function index(Request $request)
    {
        return redirect()->route($request->user()->role);
    }

    public function home()
    {
        $featured = Product::where('status', 'active')->where('is_featured', 1)->orderBy('price', 'DESC')->limit(10)->get();
        $posts = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(10)->get();
        $banners = Banner::where('status', 'active')->limit(10)->orderBy('id', 'DESC')->get();
        $products = Product::with(['offer_master_info','price_master_info'])->where('status', 'active')->orderBy('id', 'DESC')->limit(10)->get();
        $category = Category::where('status', 'active')->where('is_parent', 1)->orderBy('title', 'ASC')->get();

        //dd($products);
        return view('frontend.index')
            ->with('featured', $featured)
            ->with('posts', $posts)
            ->with('banners', $banners)
            ->with('product_lists', $products)
            ->with('category_lists', $category);
    }

    public function aboutUs()
    {
        return view('frontend.pages.about-us');
    }

    public function productDetail($slug)
    {
        $product_detail = Product::getProductBySlug($slug);
        if (!empty($product_detail)) {
            return view('frontend.pages.product_detail')->with('product_detail', $product_detail);
        }
        return response()->view('errors.404');
    }

    public function productGrids()
    {
        $products = Product::query();

        if (!empty($_GET['category'])) {
            $slug = explode(',', $_GET['category']);
            $cat_ids = Category::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            $products->whereIn('cat_id', $cat_ids);
        }
        if (!empty($_GET['sortBy'])) {
            if ($_GET['sortBy'] == 'title') {
                $products = $products->where('status', 'active')->orderBy('title', 'ASC');
            }
            if ($_GET['sortBy'] == 'price') {
                $products = $products->orderBy('price', 'ASC');
            }
        }

        if (!empty($_GET['price'])) {
            $price = explode('-', $_GET['price']);

            $products->whereBetween('price', $price);
        }

        $recent_products = Product::with(['offer_master_info','price_master_info'])->where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        // Sort by number
        if (!empty($_GET['show'])) {
            $products = $products->where('status', 'active')->paginate($_GET['show']);
        } else {
            $products = $products->where('status', 'active')->paginate();
        }
        // Sort by name , price, category

        $categories = Category::select('id','title','slug')
            ->has('product_info') 
            ->with(['product_info' => function ($query){
                $query->select('id');
            }])
            ->get();

        return view('frontend.pages.product-grids')->with(['products'=> $products, 'recent_products'=> $recent_products, 'categories'=>$categories]);
    }

    public function productSearch(Request $request)
    {
        $recent_products = Product::with(['offer_master_info','price_master_info'])->where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $product = Product::query();
        if (!empty($request->search)) {
            $product->orwhere('title', 'like', '%' . $request->search . '%')
                ->orwhere('slug', 'like', '%' . $request->search . '%')
                ->orwhere('description', 'like', '%' . $request->search . '%')
                ->orwhere('summary', 'like', '%' . $request->search . '%')
                ->orwhere('price', 'like', '%' . $request->search . '%');
        }
        $products = $product->orderBy('id', 'DESC')
            ->paginate();

        $categories = Category::select('id','title','slug')
        ->has('product_info') 
        ->with(['product_info' => function ($query){
            $query->select('id');
        }])
        ->get();

        return view('frontend.pages.product-grids')->with(['products'=> $products, 'recent_products'=> $recent_products, 'categories'=>$categories]);
    }

    public function blogs()
    {
        $posts = Post::query();

        if (!empty($_GET['category'])) {
            $slug = explode(',', $_GET['category']);
            $cat_ids = PostCategory::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            return $cat_ids;
            $posts->whereIn('post_cat_id', $cat_ids);
        }
        if (!empty($_GET['tag'])) {
            $slug = explode(',', $_GET['tag']);
            $tag_ids = PostTag::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            $posts->where('post_tag_id', $tag_ids);
        }

        if (!empty($_GET['show'])) {
            $posts = $posts->where('status', 'active')->orderBy('id', 'DESC')->paginate($_GET['show']);
        } else {
            $posts = $posts->where('status', 'active')->orderBy('id', 'DESC')->paginate();
        }

        return view('frontend.pages.blog')->with([
            'post' => $posts->first(),
            'posts' => $posts->skip(1)
        ]);
    }

    public function blogDetail($slug)
    {
        $post = Post::getPostBySlug($slug);
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        return view('frontend.pages.blog-detail')->with('post', $post)->with('recent_posts', $rcnt_post);
    }

    public function blogSearch(Request $request)
    {
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $posts = Post::orwhere('title', 'like', '%' . $request->search . '%')
            ->orwhere('quote', 'like', '%' . $request->search . '%')
            ->orwhere('summary', 'like', '%' . $request->search . '%')
            ->orwhere('description', 'like', '%' . $request->search . '%')
            ->orwhere('slug', 'like', '%' . $request->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(8);
        return view('frontend.pages.blog')->with('posts', $posts)->with('recent_posts', $rcnt_post);
    }

    public function blogFilter(Request $request)
    {
        $data = $request->all();
        $catURL = "";
        if (!empty($data['category'])) {
            foreach ($data['category'] as $category) {
                if (empty($catURL)) {
                    $catURL .= '&category=' . $category;
                } else {
                    $catURL .= ',' . $category;
                }
            }
        }

        $tagURL = "";
        if (!empty($data['tag'])) {
            foreach ($data['tag'] as $tag) {
                if (empty($tagURL)) {
                    $tagURL .= '&tag=' . $tag;
                } else {
                    $tagURL .= ',' . $tag;
                }
            }
        }
        return redirect()->route('blog', $catURL . $tagURL);
    }

    public function blogByCategory(Request $request)
    {
        $post = PostCategory::getBlogByCategory($request->slug);
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        return view('frontend.pages.blog')->with('posts', $post->post)->with('recent_posts', $rcnt_post);
    }

    public function blogByTag(Request $request)
    {
        $post = Post::getBlogByTag($request->slug);
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        return view('frontend.pages.blog')->with('posts', $post)->with('recent_posts', $rcnt_post);
    }

    public function subscribe(Request $request)
    {
        if (!Newsletter::isSubscribed($request->email)) {
            Newsletter::subscribePending($request->email);
            if (Newsletter::lastActionSucceeded()) {
                request()->session()->flash('success', 'Subscribed! Please check your email');
                return redirect()->route('home');
            } else {
                Newsletter::getLastError();
                return back()->with('error', 'Something went wrong! please try again');
            }
        } else {
            request()->session()->flash('error', 'Already Subscribed');
            return back();
        }
    }

    public function thankyou(Request $request)
    {
        return view('frontend.pages.thankyou');
    }
}
