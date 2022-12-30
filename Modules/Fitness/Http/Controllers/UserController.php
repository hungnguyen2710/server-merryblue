<?php

namespace Modules\Fitness\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\FitnessCategory;
use App\Models\FitnessUser;
use App\Models\FitnessUserCategory;
use App\Models\FitnessUserInfo;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends AppBaseController
{
    public function createUser(Request $request){
        $request->validate([
           'name' => 'required',
           'gender' => 'required',
           'categories' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $dataInput = [
              'name' => $request->name,
            ];
            $user = FitnessUser::create($dataInput);


            if ($user){
                $userInfoInput = [
                    'fitness_user_id' => $user->id,
                    'gender' => $request->gender,
                    'weight' => $request->weight,
                    'height' => $request->height,
                ];
                FitnessUserInfo::create($userInfoInput);

                if (count($request->categories) > 0){
                    foreach ($request->categories as $value){
                        $userCategoryInput = [
                          'fitness_user_id'  => $user->id,
                          'fitness_category_id'  => $value,
                        ];

                        FitnessUserCategory::create($userCategoryInput);
                    }
                }
            }

            DB::commit();
            return $this->responseAPI(true,'', $user, 200);
        }catch (\Exception $e){
            DB::rollBack();
            return $this->responseAPI(false,'', null, 400);
        }
    }

    public function me(Request $request){
        $request->validate([
            'name' => 'required',
        ]);

        $user = FitnessUser::where('name', $request->name)->first();
        return $this->responseAPI(true,'', $user, 200);
    }
}
