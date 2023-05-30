<?php

namespace Modules\FakeMessage\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\FakeMessageCelebrity;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FakeMessageController extends AppBaseController
{
    public function createCelebrity(Request $request){
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
            $video= $request->file('video');
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

        return $this->responseAPI(true,'', $celebrity, 200);
    }

    public function listCelebrity(){
        $celebrity = FakeMessageCelebrity::limit(10)->orderBy('created_at','DESC')->get();

        return $this->responseAPI(true,'', $celebrity, 200);
    }
}
