<?php

namespace Modules\Fitness\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\FitnessCategory;
use App\Models\FitnessExercise;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ExerciseController extends AppBaseController
{
    public function createExercise(Request $request)
    {
        $request->validate([
            'fitness_category_id' => 'required',
            'title' => 'required',
            'time' => 'required',
            'description' => 'required',
            'number_of_reps' => 'required',
            'rest_time' => 'required',
            'tips' => 'required',
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
            'number_of_reps' => $request->number_of_reps,
            'rest_time' => $request->rest_time,
            'tips' => $request->tips,
            'calories' => $request->calories,
            'description' => $request->description,
            'image_action' => $image_action_path,
            'thumbnail' => $thumbnail_path,
        ];

        $exercise = FitnessExercise::create($dataInput);
        return $this->responseAPI(true, '', $exercise, 200);
    }

    public function listExercise(Request $request, $categoryId)
    {
        $request->validate(
            [
                'language_code' => 'required'
            ]
        );
        $language = $request->language_code;
        $arrLanguage = ['af',
            'sq',
            'ar',
            'hy',
            'az',
            'eu',
            'be',
            'bg',
            'ca',
            'zh-CN',
            'zh-TW',
            'hr',
            'cs',
            'da',
            'nl',
            'en',
            'et',
            'tl',
            'fi',
            'fr',
            'gl',
            'ka',
            'de',
            'el',
            'ht',
            'iw',
            'hi',
            'hu',
            'is',
            'id',
            'ga',
            'it',
            'ja',
            'ko',
            'lv',
            'lt',
            'mk',
            'ms',
            'mt',
            'no',
            'fa',
            'pl',
            'pt',
            'ro',
            'ru',
            'sr',
            'sk',
            'sl',
            'es',
            'sw',
            'sv',
            'th',
            'tr',
            'uk',
            'ur',
            'vi',
            'cy',
            'yi'];

        $language = in_array($language, $arrLanguage) ? $language : 'en';
        if ($language == 'en') {
            $exercise = FitnessExercise::where('fitness_category_id', $categoryId)
                ->where(function ($q) {
                    $q->where('language_code', null)->orWhere('language_code','en');
                })
                ->orderBy('created_at', 'DESC')->get();
            return $this->responseAPI(true, '', $exercise, 200);
        }else{
            $exercise = FitnessExercise::where('fitness_category_id', $categoryId)->get();
            if (count($exercise) <= 0){
                $categoryCheck = FitnessCategory::where('id',$categoryId)->first();
                $exerciseByParent = FitnessExercise::where('fitness_category_id', $categoryCheck->parent_id)->get();
                if (count($exerciseByParent) > 0){
                    $tr = new GoogleTranslate($language);
                    foreach ($exerciseByParent as $value){
                        $dataInput = [
                            'fitness_category_id' => $categoryId,
                            'title' => $tr->translate($value->title),
                            'time' => $value->time,
                            'number_of_reps' => $value->number_of_reps,
                            'rest_time' => $value->rest_time,
                            'tips' => $tr->translate($value->tips),
                            'calories' => $value->calories,
                            'description' => $tr->translate($value->description),
                            'image_action' => $value->image_action,
                            'thumbnail' => $value->thumbnail,
                            'language_code' => $language,
                        ];

                        FitnessExercise::create($dataInput);
                    }
                    $exerciseByCategory = FitnessExercise::where('fitness_category_id', $categoryId)->get();
                    return $this->responseAPI(true, '', $exerciseByCategory, 200);
                }
            }else{
                $categoryCheck = FitnessCategory::where('id',$categoryId)->first();
                $exerciseByParent = FitnessExercise::where('fitness_category_id', $categoryCheck->parent_id)->get();
                if (count($exercise) < count($exerciseByParent)){
                    foreach ($exercise as $value){
                        $value->delete();
                    }
                    if (count($exerciseByParent) > 0){
                        $tr = new GoogleTranslate($language);
                        foreach ($exerciseByParent as $value){
                            $dataInput = [
                                'fitness_category_id' => $categoryId,
                                'title' => $tr->translate($value->title),
                                'time' => $value->time,
                                'number_of_reps' => $value->number_of_reps,
                                'rest_time' => $value->rest_time,
                                'tips' => $tr->translate($value->tips),
                                'calories' => $value->calories,
                                'description' => $tr->translate($value->description),
                                'image_action' => $value->image_action,
                                'thumbnail' => $value->thumbnail,
                                'language_code' => $language,
                            ];

                            FitnessExercise::create($dataInput);
                        }
                        $exerciseByCategory = FitnessExercise::where('fitness_category_id', $categoryId)->get();
                        return $this->responseAPI(true, '', $exerciseByCategory, 200);
                    }
                }else{
                    return $this->responseAPI(true, '', $exercise, 200);
                }
            }
        }

    }
}
