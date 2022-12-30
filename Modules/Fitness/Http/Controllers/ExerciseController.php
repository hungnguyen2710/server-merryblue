<?php

namespace Modules\Fitness\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\FitnessExercise;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExerciseController extends AppBaseController
{
    public function createExercise(Request $request)
    {
        $request->validate([
            'fitness_category_id' => 'required',
            'title' => 'required',
            'time' => 'required',
            'image_action' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'thumbnail' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image_action')) {
            $image_action = $request->file('image_action');
            $image_action_path = $image_action->store('image/image_action', ['disk' => 'public']);
        }

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnail_path = $thumbnail->store('image/thumbnail', ['disk' => 'public']);
        }


        $dataInput = [
            'fitness_category_id' => $request->fitness_category_id,
            'title' => $request->title,
            'time' => $request->time,
            'calories' => $request->calories,
            'description' => $request->description,
            'image_action' => $image_action_path,
            'thumbnail' => $thumbnail_path,
        ];

        $exercise = FitnessExercise::create($dataInput);
        return $this->responseAPI(true, '', $exercise, 200);
    }

    public function listExercise($categoryId)
    {
        $exercise = FitnessExercise::where('fitness_category_id',$categoryId)->orderBy('created_at','DESC')->get();
        return $this->responseAPI(true, '', $exercise, 200);
    }
}
