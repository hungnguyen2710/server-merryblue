<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Translate\Http\Controllers\TranslateController;
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

    Route::group(['prefix' => 'translate'], function () {
        Route::group(['prefix' => 'content'], function () {
            Route::post('/string-1', [TranslateController::class, 'translateString1']);
            Route::post('/string-2', [TranslateController::class, 'translateString2']);
            Route::post('/string-3', [TranslateController::class, 'translateString3']);
            Route::post('/string-4', [TranslateController::class, 'translateString4']);
            Route::post('/string-5', [TranslateController::class, 'translateString5']);
            Route::post('/string-6', [TranslateController::class, 'translateString6']);
        });
    });

});
