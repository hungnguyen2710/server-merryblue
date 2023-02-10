<?php

namespace Modules\Translate\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateController extends AppBaseController
{
    public function translateString1(Request $request){
        set_time_limit(-1);
        $request->validate(
            [
                'content_translate' => 'required',
            ]
        );
        $arrLanguage = ['af',
            'sq',
            'ar',
            'hy',
            'az',
            'eu',
            'be',
            'bg',
            'ca',
            'zh-CN'
            ];

        $translated = [];

        foreach ($arrLanguage as $value){
            $tr = new GoogleTranslate($value);
            $translated[]['language_code'] = $value;
            $translated[]['content_translated'] = $tr->translate($request->content_translate);
        }

        return $this->responseAPI(true,'',$translated, 200);
    }

    public function translateString2(Request $request){
        set_time_limit(-1);
        $request->validate(
            [
                'content_translate' => 'required',
            ]
        );
        $arrLanguage = [
            'zh-TW',
            'hr',
            'cs',
            'da',
            'nl',
            'en',
            'et',
            'tl',
            'fi',
            'fr'
        ];

        $translated = [];

        foreach ($arrLanguage as $value){
            $tr = new GoogleTranslate($value);
            $translated[]['language_code'] = $value;
            $translated[]['content_translated'] = $tr->translate($request->content_translate);
        }

        return $this->responseAPI(true,'',$translated, 200);
    }

    public function translateString3(Request $request){
        set_time_limit(-1);
        $request->validate(
            [
                'content_translate' => 'required',
            ]
        );
        $arrLanguage = [
            'gl',
            'ka',
            'de',
            'el',
            'ht',
            'iw',
            'hi',
            'hu',
            'is',
            'id'
        ];

        $translated = [];

        foreach ($arrLanguage as $value){
            $tr = new GoogleTranslate($value);
            $translated[]['language_code'] = $value;
            $translated[]['content_translated'] = $tr->translate($request->content_translate);
        }

        return $this->responseAPI(true,'',$translated, 200);
    }

    public function translateString4(Request $request){
        set_time_limit(-1);
        $request->validate(
            [
                'content_translate' => 'required',
            ]
        );
        $arrLanguage = [
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
        ];

        $translated = [];

        foreach ($arrLanguage as $value){
            $tr = new GoogleTranslate($value);
            $translated[]['language_code'] = $value;
            $translated[]['content_translated'] = $tr->translate($request->content_translate);
        }

        return $this->responseAPI(true,'',$translated, 200);
    }

    public function translateString5(Request $request){
        set_time_limit(-1);
        $request->validate(
            [
                'content_translate' => 'required',
            ]
        );
        $arrLanguage = [
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
        ];

        $translated = [];

        foreach ($arrLanguage as $value){
            $tr = new GoogleTranslate($value);
            $translated[]['language_code'] = $value;
            $translated[]['content_translated'] = $tr->translate($request->content_translate);
        }

        return $this->responseAPI(true,'',$translated, 200);
    }

    public function translateString6(Request $request){
        set_time_limit(-1);
        $request->validate(
            [
                'content_translate' => 'required',
            ]
        );
        $arrLanguage = [
            'sv',
            'th',
            'tr',
            'uk',
            'ur',
            'vi',
            'cy',
            'yi'
        ];

        $translated = [];

        foreach ($arrLanguage as $value){
            $tr = new GoogleTranslate($value);
            $translated[]['language_code'] = $value;
            $translated[]['content_translated'] = $tr->translate($request->content_translate);
        }

        return $this->responseAPI(true,'',$translated, 200);
    }

    public function translateWebsite(Request $request){
        $request->validate([
            'url' => 'required',
            'language_from' => 'required',
            'language_to' => 'required',
        ]);

        $url = $request->url;
        $arrHttp = explode("//", $url, 2);
        $http = $arrHttp[0].'//';
        $stringSource = $arrHttp[1];
        $arrSource =  explode("/", $stringSource);

        $rpUrl = str_replace('.','-',$arrSource[0]).'.translate.goog';
        foreach ($arrSource as $key => $value){
            if ($key == 0){
                $arrSource[0] = $rpUrl;
            }
        }

        $imple = implode("/", $arrSource);
        $urlFinal = $http.$imple.'?_x_tr_sl='.$request->language_from.'&_x_tr_tl='.$request->language_to.'&_x_tr_pto=wapp';
        return $this->responseAPI(true,'',$urlFinal, 200);
    }

}
