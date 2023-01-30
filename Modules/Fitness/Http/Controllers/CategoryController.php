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
use Stichoza\GoogleTranslate\GoogleTranslate;

class CategoryController extends AppBaseController
{
    public function createCategory(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'time' => 'required',
            'total_workout' => 'required',
            'calories' => 'required',
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
            'description' => $request->description,
            'time' => $request->time,
            'total_workout' => $request->total_workout,
            'calories' => $request->calories,
            'icon' => $icon_path,
            'thumbnail' => $thumbnail_path,
            'sort_order' => $request->sort_order,
            'type' => $request->type,
        ];

        $category = FitnessCategory::create($dataInput);
        return $this->responseAPI(true, '', $category, 200);
    }

    public function listCategory(Request $request)
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


            $category = FitnessCategory::when($language == 'en', function ($q){
                $q->where('language_code', null)->orWhere('language_code','en');
            })
                ->when($language != 'en', function ($q) use ($language){
                    $q->where('language_code', $language);
                })
                ->get();

       if ($language != 'en'){
           $categoryTemporary = FitnessCategory::where('language_code', null)->orWhere('language_code','en')->get();
           $categoryDelete = FitnessCategory::where('language_code', $language)->get();
           if ((count($categoryTemporary) > count($categoryDelete))){
               if (count($categoryDelete) > 0){
                   foreach ($categoryDelete as $value){
                       $value->delete();
                   }
               }
               $tr = new GoogleTranslate($language);
               foreach ($categoryTemporary as $value){
                   $dataInputTran = [
                       'title' => $tr->translate($value->title),
                       'description' => $tr->translate($value->description),
                       'time' => $value->time,
                       'total_workout' => $value->total_workout,
                       'calories' => $value->calories,
                       'icon' => $value->icon,
                       'thumbnail' => $value->thumbnail,
                       'sort_order' => $value->sort_order,
                       'type' => $value->type,
                       'language_code' => $language,
                       'parent_id' => $value->id,
                   ];
                   FitnessCategory::create($dataInputTran);
               }
           }
           $category = FitnessCategory::where('language_code', $language)->get();
           $category->map(function ($item){
               $item['thumbnail']= str_replace(config('app.storage_url').config('app.storage_url'),'',$item->thumbnail);
               $item['icon']= str_replace(config('app.storage_url').config('app.storage_url'),'',$item->icon);
           });
       }
        return $this->responseAPI(true, '', $category, 200);
    }

    public function listCategoryByUser(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
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

        $user = FitnessUser::where('name', $request->name)->first();
        if ($user) {
            if ($language == 'en'){
                $userCategory = FitnessUserCategory::where('fitness_user_id', $user->id)->where(function ($q){
                    $q->where('language_code', null)->orWhere('language_code','en');
                })->get()->pluck('fitness_category_id')->toArray();
                $category = FitnessCategory::whereIn('id', $userCategory)
                    ->get();
                return $this->responseAPI(true, '', $category, 200);
            }else{
                $checkUserCategory = FitnessUserCategory::where('fitness_user_id', $user->id)->where(function ($q) use ($language){
                    $q->where('language_code', $language);
                })->get()->pluck('fitness_category_id')->toArray();

                if (count($checkUserCategory) <= 0){

                    $userCategory = FitnessUserCategory::where('fitness_user_id', $user->id)->where(function ($q){
                        $q->where('language_code', null)->orWhere('language_code','en');
                    })->get()->pluck('fitness_category_id')->toArray();
                    $checkCategory = FitnessCategory::whereIn('parent_id', $userCategory)->where('language_code',$language)
                        ->get();

                    if (count($checkCategory) <= 0){
                        $category = FitnessCategory::whereIn('id', $userCategory)
                            ->get();
                        $tr = new GoogleTranslate($language);
                        foreach ($category as $value){
                            $dataInputTran = [
                                'title' => $tr->translate($value->title),
                                'description' => $tr->translate($value->description),
                                'time' => $value->time,
                                'total_workout' => $value->total_workout,
                                'calories' => $value->calories,
                                'icon' => $value->icon,
                                'thumbnail' => $value->thumbnail,
                                'sort_order' => $value->sort_order,
                                'type' => $value->type,
                                'language_code' => $language,
                                'parent_id' => $value->id,
                            ];
                            FitnessCategory::create($dataInputTran);
                        }

                        $checkCategory2 = FitnessCategory::whereIn('parent_id', $userCategory)->where('language_code',$language)
                            ->get();
                        if (count($checkCategory2) > 0){

                            foreach ($checkCategory2 as $value){
                                $userCategoryInput2 = [
                                    'fitness_user_id'  => $user->id,
                                    'fitness_category_id'  => $value->id,
                                    'language_code'  => $language,
                                ];

                                FitnessUserCategory::create($userCategoryInput2);
                            }
                            $checkUserCategory3 = FitnessUserCategory::where('fitness_user_id', $user->id)->where(function ($q) use ($language){
                                $q->where('language_code', $language);
                            })->get()->pluck('fitness_category_id')->toArray();
                            $category3 = FitnessCategory::whereIn('id', $checkUserCategory3)
                                ->get();
                            $category3->map(function ($item){
                                $item['thumbnail']= str_replace(config('app.storage_url').config('app.storage_url'),'',$item->thumbnail);
                                $item['icon']= str_replace(config('app.storage_url').config('app.storage_url'),'',$item->icon);
                            });
                            return $this->responseAPI(true, '', $category3, 200);
                        }
                    }else{
                        foreach ($checkCategory as $value){
                            $userCategoryInput3 = [
                                'fitness_user_id'  => $user->id,
                                'fitness_category_id'  => $value->id,
                                'language_code'  => $language,
                            ];

                            FitnessUserCategory::create($userCategoryInput3);
                        }
                        $checkUserCategory4 = FitnessUserCategory::where('fitness_user_id', $user->id)->where(function ($q) use ($language){
                            $q->where('language_code', $language);
                        })->get()->pluck('fitness_category_id')->toArray();
                        $category4 = FitnessCategory::whereIn('id', $checkUserCategory4)
                            ->get();
                        $category4->map(function ($item){
                            $item['thumbnail']= str_replace(config('app.storage_url').config('app.storage_url'),'',$item->thumbnail);
                            $item['icon']= str_replace(config('app.storage_url').config('app.storage_url'),'',$item->icon);
                        });
                        return $this->responseAPI(true, '', $category4, 200);
                    }
                }else{
                    $checkUserCategory = FitnessUserCategory::where('fitness_user_id', $user->id)->where(function ($q) use ($language){
                        $q->where('language_code', $language);
                    })->get()->pluck('fitness_category_id')->toArray();
                    $category = FitnessCategory::whereIn('id', $checkUserCategory)
                        ->get();
                    $category->map(function ($item){
                        $item['thumbnail']= str_replace(config('app.storage_url'),'',$item->thumbnail);
                        $item['icon']= str_replace(config('app.storage_url'),'',$item->icon);
                        return $item;
                    });
                    return $this->responseAPI(true, '', $category, 200);
                }
            }
        } else {
            return $this->responseAPI(false, 'Người dùng không tồn tại', null, 400);
        }
    }

    public function updateThumbnail(Request $request){
        $request->validate([
            'category_id' => 'required',
            'icon' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'thumbnail' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        $icon_path = '';
        $thumbnail_path = '';
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $icon_path = $icon->store('icon/i', ['disk' => 'public']);
        }

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnail_path = $thumbnail->store('image/thumbnail', ['disk' => 'public']);
        }

        $category = FitnessCategory::where('id',$request->category_id)->first();
        if ($category){
            $dataInput = [
              'thumbnail' => $thumbnail_path ? $thumbnail_path : $category->thumbnail,
              'icon' => $icon_path ? $icon_path : $category->icon,
            ];
            $category->update($dataInput);
            return $this->responseAPI(true, '', $category, 200);
        }else{
            return $this->responseAPI(false, 'Category khong ton tai', null, 400);
        }
    }

}
