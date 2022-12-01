<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Models\App;
use Illuminate\Http\Request;

class  AppController extends AppBaseController
{
    public function createApp(Request $request){
        $request->validate([
            'name_project' => 'required|unique:apps',
            'app_id' => 'required',
            'icon' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $icon_path = $icon->store('icon/app', ['disk' => 'public']);
        }

        $dataInput = [
            'name_project' => $request->name_project,
            'name_in_store' => $request->name_in_store,
            'app_id' => $request->app_id,
            'category_name' => $request->category_name,
            'company' => $request->company,
            'size' => $request->size,
            'icon' => $icon_path,
            'description' => $request->description,
            'link_ios' => $request->link_ios,
            'link_android' => $request->link_android,
            'time_update_store' => $request->time_update_store,
        ];

        $app = App::create($dataInput);
        return $this->responseAPI(true, 'create success !', $app, 201);
    }

    public function listApp(){
        $apps = App::orderBy('name_project','DESC')->where('status', 1)->get();
        $apps->map(function ($item){
            $item['name'] = $item->name_project;
            $item['category_name'] = $item->description;
        });
        return $this->responseAPI(true, '', $apps, 200);
    }


    public function editApp(Request $request){
        $request->validate([
            'id' => 'required',
        ]);

        $app = App::findOrFail($request->id);
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $icon_path = $icon->store('icon/app', ['disk' => 'public']);
        }
        $dataInput = [
            'name_project' => $request->name_project,
            'name_in_store' => $request->name_in_store,
            'app_id' => $request->app_id,
            'category_name' => $request->category_name,
            'company' => $request->company,
            'size' => $request->size,
            'icon' => $icon_path,
            'description' => $request->description,
            'link_ios' => $request->link_ios,
            'link_android' => $request->link_android,
            'time_update_store' => $request->time_update_store,
        ];

        $app->update($dataInput);

        return $this->responseAPI(true, 'edit success !', $app, 200);
    }

    public function addGroup(Request $request){
        $request->validate([
            'group_id' => 'required',
            'app_arr_id' => 'required',
        ]);
    }
}
