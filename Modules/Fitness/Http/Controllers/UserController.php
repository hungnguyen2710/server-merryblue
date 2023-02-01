<?php

namespace Modules\Fitness\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\FitnessCategory;
use App\Models\FitnessExercise;
use App\Models\FitnessLogs;
use App\Models\FitnessRating;
use App\Models\FitnessUser;
use App\Models\FitnessUserCategory;
use App\Models\FitnessUserHistory;
use App\Models\FitnessUserInfo;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserController extends AppBaseController
{
    public function createUser(Request $request)
    {
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


            if ($user) {
                $userInfoInput = [
                    'fitness_user_id' => $user->id,
                    'gender' => $request->gender,
                    'weight' => $request->weight,
                    'height' => $request->height,
                ];
                FitnessUserInfo::create($userInfoInput);

                if (count($request->categories) > 0) {
                    foreach ($request->categories as $value) {
                        $userCategoryInput = [
                            'fitness_user_id' => $user->id,
                            'fitness_category_id' => $value,
                        ];

                        FitnessUserCategory::create($userCategoryInput);
                    }
                }
            }

            DB::commit();
            return $this->responseAPI(true, '', $user, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseAPI(false, '', null, 400);
        }
    }

    public function me(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $user = FitnessUser::where('name', $request->name)->first();
        return $this->responseAPI(true, '', $user, 200);
    }

    public function addToHistory(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'fitness_exercise_id' => 'required',
        ]);
        $user = FitnessUser::where('name', $request->name)->first();
        if ($user) {
            $dataInput = [
                'fitness_user_id' => $user->id,
                'fitness_exercise_id' => $request->fitness_exercise_id,
            ];
            $history = FitnessUserHistory::create($dataInput);
            return $this->responseAPI(true, '', $history, 200);
        } else {
            return $this->responseAPI(false, 'Người dùng không tồn tại', null, 400);
        }
    }

    public function listHistory(Request $request)
    {
        $request->validate(
            [
                'name' => 'required'
            ]
        );

        $user = FitnessUser::where('name', $request->name)->first();
        if ($user) {
            $userExercise = FitnessUserHistory::where('fitness_user_id', $user->id)->get();

            $ex = [];

            foreach ($userExercise as $key => $value) {
                $data = FitnessExercise::where('id', $value->fitness_exercise_id)->first();
                $data['history_time'] = $value->created_at;
                $ex[$key] = $data;
            }

            return $this->responseAPI(true, '', $ex, 200);
        } else {
            return $this->responseAPI(false, 'Người dùng không tồn tại', null, 400);
        }
    }

    public function countUser()
    {
        $dataOutput = [];
        $dataOutput['today'] = FitnessUser::where('created_at', '>=', Carbon::today()->toDateString())->count();
        $dataOutput['week'] = FitnessUser::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $dataOutput['month'] = FitnessUser::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
        $dataOutput['last_month'] = FitnessUser::whereMonth(
            'created_at', '=', Carbon::now()->subMonth()->month
        )->count();
        return $this->responseAPI(true, '', $dataOutput, 200);
    }

    public function createRating(Request $request)
    {
        $request->validate([
            'fitness_user_id' => 'required',
            'star' => 'required',
        ]);

        $dataInput = [
            'fitness_user_id' => $request->fitness_user_id,
            'star' => $request->star,
            'comment' => $request->comment,
        ];

        $rate = FitnessRating::create($dataInput);

        return $this->responseAPI(true, '', $rate, 200);
    }

    public function listRating()
    {
        $rating = FitnessRating::orderBy('created_at', 'DESC')->paginate(100);
        return $this->responseAPI(true, '', $rating, 200);
    }

    public function createLog(Request $request)
    {
        $request->validate([
            'fitness_user_id' => 'required',
        ]);

        $check = FitnessLogs::where('fitness_user_id', $request->fitness_user_id)->where('created_at', '>=', Carbon::today()->toDateString())->first();
        $getData = FitnessLogs::where('fitness_user_id', $request->fitness_user_id)->orderBy('created_at', 'DESC')->first();
        if (!$check || $check == null) {
            $dataInput = [
                'fitness_user_id' => $request->fitness_user_id,
                'day_count' => (isset($getData->day_count) ? $getData->day_count : 0) + 1,
            ];

            FitnessLogs::create($dataInput);
            return $this->responseAPI(true, '', 'oke', 200);
        } else {
            return $this->responseAPI(false, 'Da ton tai', null, 400);
        }
    }

    public function chartLog(){
        $arrNumber = FitnessLogs::orderBy('created_at','DESC')->pluck('day_count')->toArray();
        sort($arrNumber);
        $arrNumber =  array_unique($arrNumber);
        dd($arrNumber);
        $dataOutput = [];
        if (count($arrNumber) > 0){
            foreach ($arrNumber as $key => $value){
                $dataOutput[$key]['number_day'] = $value;
                $dataOutput[$key]['number_user'] = FitnessLogs::where('day_count', $value)->count();
            }
        }
        return $this->responseAPI(true, '', $dataOutput, 200);
    }
}
