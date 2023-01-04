<?php

namespace Modules\Fitness\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\FitnessCategory;
use App\Models\FitnessExercise;
use App\Models\FitnessUser;
use App\Models\FitnessUserCategory;
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
            'sort_order' => $request->sort_order,
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

    public function listCategoryByUser(Request $request){
        $request->validate(
          [
              'name' => 'required'
          ]
        );

        $user = FitnessUser::where('name', $request->name)->first();
        if ($user){
            $userExercise = FitnessUserCategory::where('fitness_user_id',$user->id)->all()->pluck('fitness_exercise_id');
            $exercise = FitnessExercise::whereIn('id',$userExercise)->get();
            return $this->responseAPI(true, '', $exercise, 200);
        }else{
            return $this->responseAPI(false, 'Người dùng không tồn tại', null, 400);
        }
    }


}
