<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateController extends AppBaseController
{
    public function translateContent(Request $request)
    {
        $request->validate([
            'content_translate' => 'required',
            'language_code' => 'required',
        ]);

        try
        {

            $tr = new GoogleTranslate($request->language_code,null);
            $contentTranslated = $tr->translate($request->content_translate);
            return $this->responseAPI(true, 'translate success', $contentTranslated, 200);
        } catch (\Exception $e) {
            return $this->responseAPI(false, 'error', $e, 400);
        }

    }

    public function translateContentV2(Request $request)
    {
        $request->validate([
            'content_translate' => 'required',
            'language_code' => 'required',
        ]);

        try
        {
            $tr = new GoogleTranslate($request->language_code,null);
            $contentTranslated = $tr->translate($request->content_translate);
            $dataOutput = [];
            $dataOutput['language_present'] = $tr->getLastDetectedSource();
            $dataOutput['translate'] = $contentTranslated;
            return $this->responseAPI(true, 'translate success', $dataOutput, 200);
        } catch (\Exception $e) {
            return $this->responseAPI(false, 'error', $e, 400);
        }

    }
}
