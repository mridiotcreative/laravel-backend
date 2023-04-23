<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class videoComments extends Model
{
    // use SoftDeletes;
    protected $fillable = ['user_id', 'video_id', 'user_comment'];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id')->select('id','name','email','photo');
    }

    public function replyComments()
    {
        return $this->hasMany('App\Models\replyComments', 'comment_id', 'id')->with('replyUser')->select('id','user_id','comment_id','user_comment');
    }

    public function commentsLikes()
    {
        return $this->hasMany('App\Models\commentLikes', 'comment_id', 'id')->with('commentLikesUsers')->select('id','user_id','comment_id');
    }
}
