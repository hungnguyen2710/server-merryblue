<?php

use Illuminate\Http\Request;
use Modules\Music\Http\Controllers\MusicController;
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

    Route::group(['prefix' => 'music'], function () {
        Route::get('/search', [MusicController::class, 'search']);
        Route::get('/track-detail', [MusicController::class, 'trackDetail']);
    });

    Route::group(['prefix' => 'video'], function () {
        Route::get('/search', [MusicController::class, 'searchVideo']);
        Route::get('/popular', [MusicController::class, 'getPopularVideos']);
        Route::get('/get-link', [MusicController::class, 'downloadVideo']);
    });

});
