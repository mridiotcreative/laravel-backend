<?php

namespace App\Http\Controllers\Api;

use App\Models\Users_story;
use App\Models\UsersVideoPhoto;
use App\Models\videoComments;
use App\Models\videoLikes;
use App\Models\VideoDislikes;
use App\Models\FollowUsers;
use App\Models\Banner;
use App\User;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Traits\HttpResponseTraits;
use App\Helpers\AppHelper;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

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
        $data['description'] = $request->description ? $request->description : "";
        $data['user_id'] = $request->user()->id;
        $status = $user->fill($data)->save();

        if($status){
            return $this->success(Lang::get('messages.user_add'));
        }
        return $this->failure(Lang::get('messages.user_add_failed'), Response::HTTP_CONFLICT);
    }

    public function addLikeAndDislike(Request $request)
    {
        $this->validate(
            $request,
            [
                'type' => 'required|in:1,2',
                'video_id' => 'required|exists:App\Models\UsersVideoPhoto,id',
            ]
        );

        $data = $request->all();

        if ($request->type == 1) {
            $modelObj = videoLikes::where(['video_id'=>$request->video_id,'user_id'=>$request->user()->id])->first();
            if (!$modelObj) {
                $modelObj = new videoLikes();
            }
            $modelObj->video_id = $request->video_id;
            $modelObj->user_id = $request->user()->id;
        }
        if ($request->type == 2) {
            $modelObj = VideoDislikes::where(['video_id'=>$request->video_id,'user_id'=>$request->user()->id])->first();
            if (!$modelObj) {
                $modelObj = new VideoDislikes();
            }
            $modelObj->video_id = $request->video_id;
            $modelObj->user_id = $request->user()->id;
        }

        $status = $modelObj->save();

        if($status){
            return $this->success(Lang::get('messages.user_add'));
        }
        return $this->failure(Lang::get('messages.user_add_failed'), Response::HTTP_CONFLICT);
    }

    public function addCommentAndShare(Request $request)
    {
        $this->validate(
            $request,
            [
                'type' => 'required|in:1,2',
                'video_id' => 'required|exists:App\Models\UsersVideoPhoto,id',
                'user_comment' => 'required_if:type,1',
            ]
        );

        $data = $request->all();

        if ($request->type == 1) {
            $modelObj = new videoComments();
            $modelObj->user_comment = $request->user_comment;
            $modelObj->video_id = $request->video_id;
            $modelObj->user_id = $request->user()->id;
        }
        if ($request->type == 2) {
            $modelObj = UsersVideoPhoto::findOrFail($request->video_id);
            $modelObj->share_count = $modelObj->share_count + 1;
        }

        $status = $modelObj->save();

        if($status){
            return $this->success(Lang::get('messages.user_add'));
        }
        return $this->failure(Lang::get('messages.user_add_failed'), Response::HTTP_CONFLICT);
    }

    public function removeLike(Request $request)
    {
        $this->validate(
            $request,
            [
                'id' => 'required|exists:App\Models\videoLikes,id,deleted_at,NULL',
                'video_id' => 'required|exists:App\Models\UsersVideoPhoto,id',
            ]
        );

        $modelObj = videoLikes::find($request->id);
        if ($modelObj) {
            $status = $modelObj->delete();

            if($status){
                return $this->success('Unlike successfully');
            }
        }
        return $this->failure(Lang::get('messages.not_found'), Response::HTTP_CONFLICT);
    }

    public function removeComment(Request $request)
    {
        $this->validate(
            $request,
            [
                'id' => 'required|exists:App\Models\videoComments,id,deleted_at,NULL',
                'video_id' => 'required|exists:App\Models\UsersVideoPhoto,id',
            ]
        );

        $modelObj = videoComments::find($request->id);
        if ($modelObj) {
            $status = $modelObj->delete();

            if($status){
                return $this->success(Lang::get('messages.data_deleted'));
            }
        }
        return $this->failure(Lang::get('messages.not_found'), Response::HTTP_CONFLICT);
    }

    public function removeDislike(Request $request)
    {
        $this->validate(
            $request,
            [
                'id' => 'required|exists:App\Models\VideoDislikes,id,deleted_at,NULL',
                'video_id' => 'required|exists:App\Models\UsersVideoPhoto,id',
            ]
        );

        $modelObj = VideoDislikes::find($request->id);
        if ($modelObj) {
            $status = $modelObj->delete();

            if($status){
                return $this->success('Unlike successfully');
            }
        }
        return $this->failure(Lang::get('messages.not_found'), Response::HTTP_CONFLICT);
    }

    public function followUser(Request $request)
    {
        $this->validate(
            $request,
            [
                'follow_user_id' => 'required|exists:App\User,id',
            ]
        );

        if ($request->follow_user_id == $request->user()->id) {
            return $this->success("You can't follow your self");
        }

        $data = $request->all();

        $modelObj = FollowUsers::where(['follow_user_id'=>$request->follow_user_id,'user_id'=>$request->user()->id])->first();
        if (!$modelObj) {
            $modelObj = new FollowUsers();
        }
        $modelObj->follow_user_id = $request->follow_user_id;
        $modelObj->user_id = $request->user()->id;

        $status = $modelObj->save();

        if($status){
            return $this->success(Lang::get('messages.user_add'));
        }
        return $this->failure(Lang::get('messages.user_add_failed'), Response::HTTP_CONFLICT);
    }

    public function unFollowUser(Request $request)
    {
        $this->validate(
            $request,
            [
                'follow_user_id' => 'required|exists:App\User,id',
            ]
        );

        $modelObj = FollowUsers::where(['follow_user_id'=>$request->follow_user_id,'user_id'=>$request->user()->id])->first();
        if ($modelObj) {
            $status = $modelObj->delete();

            if($status){
                return $this->success('Un Follow Successfully');
            }
        }
        return $this->failure(Lang::get('messages.not_found'), Response::HTTP_CONFLICT);
    }

    public function getUserProfile(Request $request)
    {
        $this->validate(
            $request,
            [
                'user_id' => 'required|exists:App\User,id',
            ]
        );
        $user = User::findOrFail($request->user_id);
        $user = Arr::except($user,['created_at','updated_at']);
        $user->photo = ($user->photo != "") ? $user->getImage() : "";
        $user->dob = ($user->dob != "") ? \Carbon\Carbon::parse($user->dob)->format('d-m-Y') : "";
        $user->address = CustomerAddress::where('customer_id',$request->user_id)->first();

        return $this->success(Lang::get('messages.success'), $user);

        return $this->failure(Lang::get('messages.something_went_wrong'));
    }

    public function getFollower(Request $request)
    {
        $this->validate(
            $request,
            [
                'follow_user_id' => 'required|exists:App\Models\FollowUsers,follow_user_id',
            ]
        );
       $result = FollowUsers::where('follow_user_id',$request->follow_user_id)->with('user')->inRandomOrder()->limit(10)->get()->pluck('user');
       if (count($result) > 0) {
            return $this->success(Lang::get('messages.success'), $result);
       }
       return $this->failure(Lang::get('messages.not_found'), Response::HTTP_CONFLICT);
    }

    public function getFollowing(Request $request)
    {
        $this->validate(
            $request,
            [
                'following_user_id' => 'required|exists:App\Models\FollowUsers,user_id',
            ]
        );
        $result = FollowUsers::where('user_id',$request->following_user_id)->with('followingUserData')->inRandomOrder()->limit(10)->get()->pluck('followingUserData');
        if (count($result) > 0) {
             return $this->success(Lang::get('messages.success'), $result);
        }
        return $this->failure(Lang::get('messages.not_found'), Response::HTTP_CONFLICT);
    }
}
