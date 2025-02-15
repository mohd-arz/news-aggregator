<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function(){
    Route::post('/register','register');
    Route::post('/login','login');
})->middleware('force-json');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout',[AuthController::class,'logout']);
});
    

