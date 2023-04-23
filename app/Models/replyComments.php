<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class replyComments extends Model
{
    protected $fillable = ['user_id', 'comment_id', 'user_comment'];

    public function replyUser()
    {
        return $this->hasOne('App\User', 'id', 'user_id')->select('id','name','email','photo');
    }
}
