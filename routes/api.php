<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\ItemController;
use App\Http\Controllers\api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('login', 'login');
    Route::get('logout', 'logout');
    Route::post('signup', 'signup');
});


Route::middleware('auth:api')->group(function () {
    Route::controller(ProfileController::class)->prefix('user/profile')->group(function () {
        Route::get('/', 'profile');
        Route::post('update', 'update');
    });

    Route::controller(CategoryController::class)->prefix('category')->group(function () {
        Route::get('/', 'list');
        Route::post('add','create');
    });

    Route::controller(ItemController::class)->prefix('item')->group(function () {
        Route::get('/', 'list');
        Route::post('add', 'create');
    });
});
