<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Fitness\Http\Controllers\LanguageController;
use Modules\Fitness\Http\Controllers\CategoryController;
use Modules\Fitness\Http\Controllers\ExerciseController;
use Modules\Fitness\Http\Controllers\UserController;
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

    Route::group(['prefix' => 'fitness'], function () {
        Route::group(['prefix' => 'language'], function () {
            Route::get('/list', [LanguageController::class, 'listLanguage']);
            Route::post('/create', [LanguageController::class, 'createLanguage']);
        });

        Route::group(['prefix' => 'category'], function () {
            Route::get('/list', [CategoryController::class, 'listCategory']);
            Route::get('/list-by-user', [CategoryController::class, 'listCategoryByUser']);
            Route::post('/create', [CategoryController::class, 'createCategory']);
            Route::post('/update-thumbnail', [CategoryController::class, 'updateThumbnail']);
        });

        Route::group(['prefix' => 'exercise'], function () {
            Route::get('/list/{categoryId}', [ExerciseController::class, 'listExercise']);
            Route::post('/create', [ExerciseController::class, 'createExercise']);
        });

        Route::group(['prefix' => 'user'], function () {
            Route::get('/me', [UserController::class, 'me']);
            Route::post('/create', [UserController::class, 'createUser']);
            Route::post('/add-history', [UserController::class, 'addToHistory']);
            Route::get('/list-history', [UserController::class, 'listHistory']);
            Route::get('/count-user', [UserController::class, 'countUser']);
            Route::post('/rating', [UserController::class, 'createRating']);
        });
    });

});
