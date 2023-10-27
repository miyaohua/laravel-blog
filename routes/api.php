<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\LinkController;
use \App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// 登录 注册
Route::controller(AuthController::class)->prefix('auth')->group(function(){
    // 登录
    Route::post('/login','login');
    // 注册
    Route::post('/registry','registry');
});

// 友情连接
Route::apiResource('link',LinkController::class);

// 分类列表
Route::apiResource('category',CategoryController::class);
