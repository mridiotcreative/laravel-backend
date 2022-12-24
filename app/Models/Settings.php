<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = ['short_des', 'description', 'photo', 'address', 'phone', 'email', 'logo'];

    public function getLogo()
    {
        return AppHelper::getStorageUrl(config('path.site_logo'), $this->logo);
    }
    public function getImage()
    {
        return AppHelper::getStorageUrl(config('path.site_image'), $this->logo);
    }
}
