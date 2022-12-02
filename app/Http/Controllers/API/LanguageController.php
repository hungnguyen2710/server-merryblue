<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends AppBaseController
{
    public function createLanguage(Request $request){
        $request->validate([
            'code' => 'required|unique:languages',
            'name' => 'required|unique:languages',
        ]);

        $dataInput = [
            'code' => $request->code,
            'name' => $request->name,
        ];

        $language = Language::create($dataInput);

        return $this->responseAPI(true, 'create success!', $language, 201);
    }

    public function listLanguage(){
        $languages = Language::orderBy('name','ASC')->get();
        return $this->responseAPI(true, '', $languages, 200);
    }
}
