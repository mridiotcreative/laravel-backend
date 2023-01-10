<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class videoLikes extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id', 'video_id'];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id')->select('id','name','email','photo');
    }
}
