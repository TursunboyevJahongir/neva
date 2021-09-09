<?php


use App\Http\Controllers\api\{
    AuthController, BannerController, CommentController, UserController
};

use Illuminate\Http\Request;
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

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('banners',[BannerController::class,'index']);
Route::get('banners/{object}/{id}',[BannerController::class,'show']);
Route::get('comments',[CommentController::class,'index']);
