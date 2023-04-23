<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class videoWatchTime extends Model
{
    protected $fillable = ['user_id', 'video_id', 'watch_time'];
}
