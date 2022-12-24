<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users_story extends Model
{
    protected $table = 'users_stories';

    protected $fillable = ['user_id','video_url'];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function users()
    {
        return $this->hasOne('App\User', 'id', 'user_id')->select('id','name','email');
    }

    public function getVideoUrlAttribute($value)
    {
        if ($value) {
            return asset('storage/uploads/user/' . $value);
        } else {
            return '';
        }
    }
}
