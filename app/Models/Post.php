<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'tags', 'summary', 'slug', 'description', 'photo', 'quote', 'post_cat_id', 'post_tag_id', 'added_by', 'status'];

    public function getImage()
    {
        return AppHelper::getStorageUrl(config('path.post'), $this->photo);
    }

    public function cat_info()
    {
        return $this->hasOne('App\Models\PostCategory', 'id', 'post_cat_id');
    }

    public function tag_info()
    {
        return $this->hasOne('App\Models\PostTag', 'id', 'post_tag_id');
    }

    public function author_info()
    {
        return $this->hasOne('App\User', 'id', 'added_by');
    }
    public static function getAllPost()
    {
        return Post::with(['cat_info', 'author_info'])->orderBy('id', 'DESC')->paginate(10);
    }
    public static function getPostBySlug($slug)
    {
        return Post::with(['tag_info', 'author_info'])->where('slug', $slug)->where('status', 'active')->first();
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class)->whereNull('parent_id')->where('status', 'active')->with('user_info')->orderBy('id', 'DESC');
    }
    public function allComments()
    {
        return $this->hasMany(PostComment::class)->where('status', 'active');
    }
    public static function getBlogByTag($slug)
    {
        return Post::where('tags', $slug)->paginate(8);
    }

    public static function countActivePost()
    {
        $data = Post::where('status', 'active')->count();
        if ($data) {
            return $data;
        }
        return 0;
    }

    // Get Article List
    public function getArticleList($request)
    {
        $articleList = Post::where('status', 'active')
            ->orderBy('id', 'DESC')
            ->select([
                'title',
                'slug',
                'summary',
                'photo',
            ])
            ->get();
        if ($articleList->isEmpty()) {
            return [];
        }
        return $articleList->map(function ($item, $key) {
            $item->photo = $item->getImage();
            $item->summary = AppHelper::removeSlashes($item->summary);
            return $item;
        });
    }

    // Get Article List
    public function getArticleDetail($request, $slug)
    {
        $articleList = Post::where('status', 'active')
            ->where('slug', $slug)
            ->select([
                'title',
                'slug',
                'description',
                'photo',
                'created_at',
            ])
            ->first();
        if (empty($articleList)) {
            return null;
        }
        $articleList->photo = $articleList->getImage();
        $articleList->description = AppHelper::removeSlashes($articleList->description);
        $articleList->date = $articleList->created_at->format('M d, Y');
        return $articleList->only([
            'title', 'slug', 'description', 'photo', 'date'
        ]);
    }

    public function getPhotoAttribute($value)
    {
       
        $image_path = [];
        if ($value) {
            $images = explode(",",$value);
            foreach($images as $image){
                $image_path[] =  asset('storage/uploads/post/' . $image);
            }
        } else {
            $image_path[] = asset('storage/uploads/post/default.png');
        }

        return $image_path;
    }

    // blog search scope
    public function scopeWhereLike($query, $column, $value) 
    {
        return $query->where($column, 'like', '%'.$value.'%');
    }

    // blog search scope
    public function scopeOrWhereLike($query, $column, $value) 
    {
        return $query->orWhere($column, 'like', '%'.$value.'%');
    }

    // public function getDateAttribute($value)
    // {
    //     return $this->date->format('M d, Y');
    // }
}
