<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class commentLikes extends Model
{
    protected $fillable = ['user_id', 'comment_id'];

    public function commentLikesUsers()
    {
        return $this->hasOne('App\User', 'id', 'user_id')->select('id','name','email','photo');
    }
}
