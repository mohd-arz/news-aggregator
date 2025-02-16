<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DropDownController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\PreferencesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function(){
    Route::post('/register','register');
    Route::post('/login','login');
})->middleware('force-json');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout',[AuthController::class,'logout']);


    // News Routes
    Route::controller(NewsController::class)->group(function(){
        Route::get('/news','getAllNews');
        Route::get('/for-you','ForYou');
    });

    //Preferences Routes
    Route::controller(PreferencesController::class)->group(function(){
        Route::get('/preferences','getPreferences');
        Route::post('/preferences','setPreferences');
    });

    //DropDown Routes
    Route::prefix('dropdown')->controller(DropDownController::class)->group(function(){
        Route::get('/categories','getCategories');
        Route::get('/sources','getSources');
        Route::get('/authors','getAuthors');
    });
});
    

