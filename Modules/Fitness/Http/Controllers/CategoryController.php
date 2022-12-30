<?php

namespace Modules\Fitness\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\FitnessCategory;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoryController extends AppBaseController
{
    public function createCategory(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'icon' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'thumbnail' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $icon_path = $icon->store('icon/i', ['disk' => 'public']);
        }

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnail_path = $thumbnail->store('image/thumbnail', ['disk' => 'public']);
        }


        $dataInput = [
            'title' => $request->title,
            'icon' => $icon_path,
            'thumbnail' => $thumbnail_path,
            'type' => $request->type,
        ];

        $category = FitnessCategory::create($dataInput);
        return $this->responseAPI(true, '', $category, 200);
    }

    public function listCategory()
    {
        $category = FitnessCategory::all();
        return $this->responseAPI(true, '', $category, 200);
    }
}
