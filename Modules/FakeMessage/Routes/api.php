<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\FakeMessage\Http\Controllers\FakeMessageController;
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

    Route::group(['prefix' => 'fake-message'], function () {
        Route::group(['prefix' => 'celebrity'], function () {
            Route::get('/list', [FakeMessageController::class, 'listCelebrity']);
            Route::get('/list/by-category', [FakeMessageController::class, 'listCelebrityByCategory']);
            Route::get('/category', [FakeMessageController::class, 'categoryCelebrity']);
            Route::post('/create', [FakeMessageController::class, 'createCelebrity']);
        });

    });

});
