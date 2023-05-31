<?php

namespace Modules\FakeMessage\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\FakeMessageCategoryCelebrity;
use App\Models\FakeMessageCelebrity;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FakeMessageController extends AppBaseController
{
    public function createCelebrity(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'avatar' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'video' => 'required|mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|max:51200',
        ]);

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatar_path = $avatar->store('images/avatar', ['disk' => 'public']);
        }

        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $video_path = $video->store('images/video', ['disk' => 'public']);
        }

        $dataInput = [
            'name' => $request->name,
            'video' => $video_path,
            'avatar' => $avatar_path,
            'followers' => $request->followers,
            'language_code' => $request->language_code,
        ];

        $celebrity = FakeMessageCelebrity::create($dataInput);

        return $this->responseAPI(true, '', $celebrity, 200);
    }

    public function listCelebrity(Request $request)
    {

        $limit = isset($request->limit) ? $request->limit : 10;
        $offset = isset($request->offset) ? $request->offset : 0;

        $celebrity = FakeMessageCelebrity::orderBy('created_at', 'DESC')->offset($offset)->limit($limit)->get();

        return $this->responseAPI(true, '', $celebrity, 200);
    }

    public function categoryCelebrity()
    {
        $categoryCelebrity = FakeMessageCategoryCelebrity::all();
        return $this->responseAPI(true, '', $categoryCelebrity, 200);
    }

    public function listCelebrityByCategory(Request $request)
    {

        $limit = isset($request->limit) ? $request->limit : 10;
        $offset = isset($request->offset) ? $request->offset : 0;
        $categoryCelebrityId = $request->category_id;
        $celebrity = FakeMessageCelebrity::where('fake_message_category_celebrity_id', $categoryCelebrityId)->orderBy('created_at', 'DESC')->offset($offset)->limit($limit)->get();

        return $this->responseAPI(true, '', $celebrity, 200);
    }

    public function updateCelebrity(Request $request, $celebrityId)
    {
        $celebrity = FakeMessageCelebrity::where('id', $celebrityId)->first();

        if ($celebrity) {
            $request->validate([
                'category_celebrity_id' => 'required',
                'avatar' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
                'video' => 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|max:51200',
            ]);
            $avatar_path = '';
            $video_path = '';
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $avatar_path = $avatar->store('images/avatar', ['disk' => 'public']);
            }

            if ($request->hasFile('video')) {
                $video = $request->file('video');
                $video_path = $video->store('images/video', ['disk' => 'public']);
            }

            $dataInput = [
                'name' => $request->name ? $request->name : $celebrity->name,
                'fake_message_category_celebrity_id' => $request->category_celebrity_id ? $request->category_celebrity_id : $celebrity->fake_message_category_celebrity_id,
                'video' => $video_path ? $video_path : $celebrity->video,
                'avatar' => $avatar_path ? $avatar_path : $celebrity->avatar,
                'followers' => $request->followers ? $request->followers : $celebrity->followers,
                'language_code' => $request->language_code ? $request->language_code : $celebrity->language_code,
            ];

            $celebrity->update($dataInput);
            return $this->responseAPI(true, '', $celebrity, 200);
        } else {
            return $this->responseAPI(false, 'error', null, 400);
        }
    }
}
