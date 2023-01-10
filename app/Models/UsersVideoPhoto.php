<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersVideoPhoto extends Model
{
    protected $table = 'users_video_photos';

    protected $fillable = ['user_id','upload_type','upload_url','description'];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id')->select('id','name','email','photo');
    }

    public function likes()
    {
        return $this->hasMany('App\Models\videoLikes', 'video_id', 'id')->with('user')->select('id','user_id','video_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\videoComments', 'video_id', 'id')->with('user')->select('id','user_id','video_id','user_comment');
    }

    public function dislikes()
    {
        return $this->hasMany('App\Models\VideoDislikes', 'video_id', 'id')->with('user')->select('id','user_id','video_id');
    }

    public function getUploadUrlAttribute($value)
    {
        if ($value) {
            return asset('storage/uploads/user/' . $value);
        } else {
            return '';
        }
    }
}
