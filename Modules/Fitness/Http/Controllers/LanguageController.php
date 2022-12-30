<?php

namespace Modules\Fitness\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\FitnessLanguage;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LanguageController extends AppBaseController
{
    public function createLanguage(Request $request){
        $request->validate([
           'name' => 'required',
           'code' => 'required',
           'flag' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('flag')) {
            $flag = $request->file('flag');
            $flag_path = $flag->store('icon/flag', ['disk' => 'public']);
        }


        $dataInput = [
          'name' => $request->name,
          'code' => $request->code,
          'flag' => $flag_path,
        ];

        $language = FitnessLanguage::create($dataInput);
        return $this->responseAPI(true, '', $language, 200);
    }

    public function listLanguage(){
        $languages = FitnessLanguage::all();
        return $this->responseAPI(true, '', $languages, 200);
    }
}
