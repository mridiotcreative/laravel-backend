<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    protected $fillable = ['title', 'slug', 'description', 'photo', 'status'];

    public function getPhotoAttribute($value)
    {
        if ($value) {
            return AppHelper::getStorageUrl(config('path.banner'), $value);
        } else {
            return '';
        }
    }
}
