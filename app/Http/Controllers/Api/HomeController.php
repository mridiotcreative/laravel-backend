<?php

namespace App\Http\Controllers\Api;

use App\Models\Users_story;
use App\Models\Banner;
use App\Models\UsersVideoPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Response;

class HomeController extends ApiController
{
    use HttpResponseTraits;

    public function index()
    {
       $banner = Banner::inRandomOrder()->limit(10)->get(['id','title','photo','description']);

       return $this->success(Lang::get('messages.home_page_data'), $banner);
    }

    public function userStory()
    {
       $userStory = Users_story::with('user')->inRandomOrder()->limit(10)->get();

       return $this->success(Lang::get('messages.home_page_data'), $userStory);
    }

    public function allVideoPhotoShorts()
    {
       $userStory = UsersVideoPhoto::with(['user','likes','dislikes','comments'])->inRandomOrder()->limit(10)->get();
       return $this->success(Lang::get('messages.home_page_data'), $userStory);
    }
}
