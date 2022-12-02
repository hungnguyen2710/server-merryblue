<?php

use App\Http\Controllers\API\AppController;
use App\Http\Controllers\API\LanguageController;
use App\Http\Controllers\API\TranslateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'apps'], function () {
        Route::post('/create', [AppController::class, 'createApp'])->name('api.app.create');
        Route::get('/list', [AppController::class, 'listApp'])->name('api.app.list');
    });

    Route::group(['prefix' => 'language'], function () {
        Route::post('/create', [LanguageController::class, 'createLanguage']);
        Route::get('/list', [LanguageController::class, 'listLanguage']);
    });

});


Route::group(['prefix' => 'v2'], function () {
    Route::group(['prefix' => 'translate'], function () {
        Route::post('/content', [TranslateController::class, 'translateContentV2']);
    });

});
