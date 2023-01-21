<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUsers extends Model
{
    protected $fillable = ['user_id', 'follow_user_id'];

    public function followingUserData()
    {
        return $this->hasOne('App\User', 'id', 'follow_user_id')->select('id','name','email','photo');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id')->select('id','name','email','photo');
    }
}
