<?php

namespace App\Http\Controllers\Api;

use App\Models\Users_story;
use App\Models\Banner;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Traits\HttpResponseTraits;
use App\Helpers\AppHelper;
use Illuminate\Http\Response;

class UserController extends ApiController
{
    use HttpResponseTraits;

    public function createStory(Request $request)
    {
        $this->validate(
            $request,
            [
                'video' => 'required|file|mimetypes:video/mp4|max:20000',
            ]
        );

        $user = new Users_story();
        $data = $request->all();

        if ($request->hasFile('video')) {
            $now = date('ymds') . '-';
            $video = $request->file('video');
            $videoName = $now . AppHelper::replaceSpaceIntoDash($video->getClientOriginalName());
            $request->video->storeAs(config('path.user'), $videoName);
            $data['video_url'] = $videoName;
        }

        $data['user_id'] = $request->user()->id;
        $status = $user->fill($data)->save();

        if($status){
            return $this->success(Lang::get('messages.user_story_add'));
        }
        return $this->failure(Lang::get('messages.user_story_add_failed'), Response::HTTP_CONFLICT);
    }
}
