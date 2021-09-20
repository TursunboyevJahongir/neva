<?php


use App\Http\Controllers\api\{
    AuthController, BannerController, CategoryController, CommentController, NewsController, UserController
};
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



Route::post('authenticate', [AuthController::class, 'authenticate']);
Route::post('verify', [AuthController::class, 'verify']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', [UserController::class,'index']);
    Route::post('/user', [UserController::class, 'update']);

    Route::post('/comment',[CommentController::class,'store']);
    Route::put('comment/{id}',[CommentController::class,'edit'])->where(['id' => '[0-9]+']);
    Route::delete('comment/{id}',[CommentController::class,'destroy'])->where(['id' => '[0-9]+']);


    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('banners',[BannerController::class,'index']);
Route::get('banners/{object}/{id?}',[BannerController::class,'show'])->where(['id' => '[0-9]+', 'object' => '[a-z]+']);
Route::get('comments',[CommentController::class,'index']);
Route::get('comments/{id?}',[CommentController::class,'show'])->where(['id' => '[0-9]+']);
Route::get('news',[NewsController::class,'index']);
Route::get('news/{id?}',[NewsController::class,'show'])->where(['id' => '[0-9]+']);
Route::get('category',[CategoryController::class,'index']);

