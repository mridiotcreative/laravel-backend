<?php

namespace App\Http\Controllers\Api;

use App\Models\Users_story;
use App\Models\Banner;
use App\Models\UsersVideoPhoto;
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
        $limit = config('constants.PER_PAGE');
        $banner = Banner::where('status','active')->inRandomOrder()->select(['id','title','photo','description'])->paginate($limit);

        if ($banner->count() > 0) {
            foreach ($banner as $key => $value) {
                $value->photo = $value->getImage();
            }
        }

        return $this->success(Lang::get('messages.home_page_data'), $banner);
    }

    public function userStory()
    {
        $limit = config('constants.PER_PAGE');
       $userStory = Users_story::with('user')->inRandomOrder()->paginate($limit);

       return $this->success(Lang::get('messages.home_page_data'), $userStory);
    }

    public function allVideoPhotoShorts()
    {
        $limit = config('constants.PER_PAGE');
       $userStory = UsersVideoPhoto::with(['user','likes','dislikes','comments'])->inRandomOrder()->paginate($limit);
       return $this->success(Lang::get('messages.home_page_data'), $userStory);
    }

    public function getCategory()
    {
        $category = Category::select('id','slug','title','photo')->where('status','active')->get();

        if(count($category) > 0){
            $data['category_data'] = $category;
            return $this->success(Lang::get('messages.category_found'), $data);
        }else{
            return $this->failure(Lang::get('messages.no_category_found'), Response::HTTP_NOT_FOUND);
        }
    }
}
