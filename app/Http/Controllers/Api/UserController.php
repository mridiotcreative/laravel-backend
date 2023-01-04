<?php

namespace App\Http\Controllers\Api;

use App\Models\Users_story;
use App\Models\UsersVideoPhoto;
use App\Models\Banner;
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

    public function uploadVideoPhoto(Request $request)
    {
        $this->validate(
            $request,
            [
                'upload_type' => 'required|in:1,2,3',
                'upload_url' => 'required|file|mimes:mp4,jpg,jpeg,png|max:20000',
            ]
        );

        $user = new UsersVideoPhoto();
        $data = $request->all();

        $data['upload_type'] = $request->upload_type;

        if ($request->hasFile('upload_url')) {
            $now = date('ymds') . '-';
            $upload_url = $request->file('upload_url');
            $upload_urlName = $now . AppHelper::replaceSpaceIntoDash($upload_url->getClientOriginalName());
            $request->upload_url->storeAs(config('path.user'), $upload_urlName);
            $data['upload_url'] = $upload_urlName;
        }

        $data['user_id'] = $request->user()->id;
        $status = $user->fill($data)->save();

        if($status){
            return $this->success(Lang::get('messages.user_add'));
        }
        return $this->failure(Lang::get('messages.user_add_failed'), Response::HTTP_CONFLICT);
    }
}
