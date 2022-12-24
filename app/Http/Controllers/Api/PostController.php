<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use App\Traits\HttpResponseTraits;
use App\Helpers\AppHelper;

class PostController extends ApiController
{
    use HttpResponseTraits;

    protected $model = null;
    public function __construct()
    {
        $this->model = new Post;
    }

    /**
     * Get article list
     *
     * @param Request $request
     * @return json
     */
    public function getArticleList(Request $request)
    {

        // search key
        $request->has('search_key') && $request->search_key ? $search_key = $request->search_key : $search_key = '';

        // sort key value
        $request->has('sort_key') && $request->sort_key ? $sort_key = $request->sort_key : $sort_key = 'ASC';

        // sort by value
        $request->has('sort_by') && $request->sort_by  ? $sort_by = $request->sort_by : $sort_by = 'title';

        $limit = config('constants.PER_PAGE');

        $blogs = Post::select('title','slug','photo','summary')
            // with(['cat_info:id,title,slug','tag_info:id,title,slug','author_info:id,name,email'])
            // ->select('id','title','slug','post_cat_id','post_tag_id','photo as image')
            ->where('status', 'active')
            ->whereLike('title',$search_key)
            ->orWhereLike('description',$search_key)
            ->orWhereLike('summary',$search_key)
            ->orWhereLike('created_at',$search_key)
            ->orWhereHas('cat_info', function ($query) use ($search_key) {
                $query->whereLike('title',$search_key);
                $query->orWhereLike('summary',$search_key);
            })
            ->orWhereHas('tag_info', function ($query) use ($search_key) {
                $query->whereLike('title',$search_key);
                $query->orWhereLike('summary',$search_key);
            })
            ->orderBy($sort_by,$sort_key)
            ->paginate($limit);
        
        $blogs->map(function ($item, $key) {
            // $item->photo = $item->image;
            $item->summary = AppHelper::removeSlashes($item->summary);
            return $item;
        });
        
        if($blogs->count() > 0){
            $blogs = AppHelper::key_change($blogs,'article_data');
            $data['article_list'] = $blogs;
            return $this->success(Lang::get('messages.get_all_articles'), $data);
        }else{ 
            return $this->failure(Lang::get('messages.no_article_found'), Response::HTTP_NOT_FOUND);  
        }
    }

    /**
     * Get article detail
     *
     * @param Request $request
     * @param [type] $slug
     * @return json
     */
    public function getArticleDetail(Request $request, $slug)
    {
       
        // $blog = Post::select('title','slug','post_cat_id','post_tag_id','photo as image','description','created_at')
        $blog = Post::select('title','slug','photo','description','summary','created_at')
            // ->with(['cat_info:id,title,slug','tag_info:id,title,slug','comments'])
            ->where('slug',$slug)->first();

        $blog->description = AppHelper::removeSlashes($blog->description);
        $blog->summary = AppHelper::removeSlashes($blog->summary);
        $blog->date = $blog->created_at->format('M d, Y');

        unset($blog->image,$blog->created_at);    

        if($blog){
            $data['article_details'] = $blog;
            return $this->success(Lang::get('messages.article_details'), $data);
        }else{   
            return $this->failure(Lang::get('messages.no_article_detail_found'), Response::HTTP_NOT_FOUND);  
        }
    }
}
