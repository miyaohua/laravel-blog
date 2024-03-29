<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\LinkController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommenController;
use \App\Http\Controllers\PublicController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\FileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\StarController;
use \App\Http\Controllers\TagController;

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
// @author 米耀华

// 登录 注册
Route::controller(AuthController::class)->prefix('auth')->group(function () {
    // 登录
    Route::post('/login', 'login');
    // 注册
    Route::post('/registry', 'registry');
});

// 友情连接
Route::apiResource('link', LinkController::class);

// 分类列表
Route::apiResource('category', CategoryController::class);

// 文章
Route::apiResource('article', ArticleController::class);

// 上传文件
Route::apiResource('file', FileController::class);

// 标签
Route::apiResource('tag', TagController::class);

// 点赞
Route::apiResource('like', LikeController::class);

// 收藏
Route::apiResource('star', StarController::class);

// 评论
Route::apiResource('commen', CommenController::class);

// 预览
Route::apiResource('preview', PreviewController::class);

// 用户接口 验证当前登录人
Route::controller(UserController::class)->prefix('user')->group(function () {
    // 用户修改头像
    Route::post('/changeAvatar', 'changeUserAvatar');
    // 用户修改信息
    Route::post('/changeInfo', 'changeUserInfo');
    // 用户修改密码
    Route::post('/changePassword', 'changeUserPassword');
    // 用户查询文章点赞
    Route::post('/getArticleLike', 'getArticleLike');
    // 用户查询文章收藏
    Route::post('/getArticleStar', 'getArticleStar');
    // 用户主页基本信息(创作、获赞、收藏)
    Route::post('/getUserBaseInfo', 'getUserBaseInfo');
    // 用户获取收藏列表
    Route::post('/getCollectList', 'getCollectList');
    // 用户获取喜欢列表
    Route::post('/getLikeList', 'getLikeList');
    // 用户获取最近浏览列表
    Route::post('/getPreviewList', 'getPreviewList');
});




// 公共接口 不需要登录
Route::controller(PublicController::class)->prefix('public')->group(function () {
    // 获取首页文章
    Route::get('/article', 'article');
    // 获取友情链接
    Route::get('/link', 'link');
    // 获取热门文章
    Route::get('/popular', 'popular');
    // 获取文章内容
    Route::get('/articleContent', 'articleContent');
    // 获取评论内容
    Route::post('/getCommenContent', 'getCommenContent');
    // 获取分类列表
    Route::post('/getCategory', 'getCategory');
    // 根据分类列表获取文章
    Route::post('/getArticleByCategory', 'getArticleByCategory');
    // 根据用户id获取用户主页基本信息(创作、获赞、收藏)
    Route::post('/getUserInfoByUserId', 'getUserInfoByUserId');
});
