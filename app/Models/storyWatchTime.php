<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class storyWatchTime extends Model
{
    protected $fillable = ['user_id', 'story_id', 'watch_time'];
}
