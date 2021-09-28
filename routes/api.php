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

Route::middleware('verify.device_headers')->prefix('v1')->group(static function () {
    /**
     * Login / Register
     */
    Route::prefix('auth')->group(static function () {
        Route::post('/', [AuthController::class, 'authenticate']);
        Route::post('confirm', [AuthController::class, 'authConfirm']);
        Route::post('resend-sms', [AuthController::class, 'resendSms']);
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [UserController::class, 'me']);
        Route::post('/me', [UserController::class, 'update']);

        Route::get('/interests', [InterestController::class, 'index']);

        Route::get('/kids', [KidController::class, 'index']);
        Route::post('/kid', [KidController::class, 'store']);
        Route::put('/kid/{id}', [KidController::class, 'update']);
        Route::delete('/kid/{id}', [KidController::class, 'delete']);

    Route::post('/comment',[CommentController::class,'store']);
    Route::put('comment/{id}',[CommentController::class,'edit'])->where(['id' => '[0-9]+']);
    Route::delete('comment/{id}',[CommentController::class,'destroy'])->where(['id' => '[0-9]+']);

        Route::get('/basket', [BasketController::class, 'index']);
        Route::post('/basket', [BasketController::class, 'store']);
        Route::delete('/basket', [BasketController::class, 'delete']);
    });

    Route::get('banners', [BannerController::class, 'index']);
    Route::get('banners/{object}/{id?}', [BannerController::class, 'show'])->where(['id' => '[0-9]+', 'object' => '[a-z]+']);
    Route::get('comments', [CommentController::class, 'index']);
    Route::get('comments/{id?}', [CommentController::class, 'show'])->where(['id' => '[0-9]+']);
    Route::get('news', [NewsController::class, 'index']);
    Route::get('news/{id?}', [NewsController::class, 'show'])->where(['id' => '[0-9]+']);
    Route::get('category', [CategoryController::class, 'index']);
    Route::get('category/{id?}', [CategoryController::class, 'show'])->where(['id' => '[0-9]+']);


});
