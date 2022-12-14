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
            'avatar' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatar_path = $avatar->store('images/avatar', ['disk' => 'public']);
        }

        $dataInput = [
            'name' => $request->name,
            'avatar' => $avatar_path,
            'followers' => $request->followers,
            'language_code' => $request->language_code,
        ];

        $celebrity = FakeMessageCelebrity::create($dataInput);

        return $this->responseAPI(true,'', $celebrity, 200);
    }

    public function listCelebrity(){
        $celebrity = FakeMessageCelebrity::orderBy('created_at','DESC')->get();

        return $this->responseAPI(true,'', $celebrity, 200);
    }
}
