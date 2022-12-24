<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CmsDetails extends Model
{
    protected $table = 'cms_details';

    protected $fillable = ['title', 'slug', 'description', 'status'];
}
