<?php

namespace App\Http\Controllers\Api;

use App\Models\Users_story;
use App\Models\Banner;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Response;

class HomeController extends ApiController
{
    use HttpResponseTraits;

    public function index()
    {
       $data = array();

       $data['Users_story'] = Users_story::with('users')->inRandomOrder()->limit(10)->get();

       $banner = Banner::inRandomOrder()->limit(10)->get(['id','title','photo','description']);

       $data['Banner'] = $banner;

       $data['AllData'] = [];

       $object['home_data'] = $data;

       return $this->success(Lang::get('messages.home_page_data'), $object);
    }
}
