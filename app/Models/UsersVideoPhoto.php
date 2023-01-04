<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersVideoPhoto extends Model
{
    protected $table = 'users_video_photos';

    protected $fillable = ['user_id','upload_type','upload_url'];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id')->select('id','name','email');
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
