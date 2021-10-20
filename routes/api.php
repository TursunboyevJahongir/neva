<?php


use App\Http\Controllers\api\{AuthController,
    BannerController,
    BasketController,
    BrandController,
    CardController,
    CategoryController,
    CommentController,
    DataController,
    DistrictController,
    FavoriteController,
    InterestController,
    KidController,
    NewsController,
    OrderController,
    ProductController,
    SearchController,
    UserController};
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
        Route::post('/register', [AuthController::class, 'Registration']);
        Route::post('/', [AuthController::class, 'authenticate']);
        Route::post('confirm', [AuthController::class, 'authConfirm']);
        Route::post('resend-sms', [AuthController::class, 'resendSms']);
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [UserController::class, 'me']);
        Route::post('/me', [UserController::class, 'update']);
        Route::post('/me/address', [UserController::class, 'address']);

        Route::get('/interests', [InterestController::class, 'index']);

        Route::get('/kids', [KidController::class, 'index']);
        Route::post('/kids', [KidController::class, 'store']);
        Route::put('/kids/{id}', [KidController::class, 'update']);
        Route::delete('/kids/{id}', [KidController::class, 'delete']);

        Route::post('/comment', [CommentController::class, 'store']);
        Route::put('comment/{id}', [CommentController::class, 'edit'])->where(['id' => '[0-9]+']);
        Route::delete('comment/{id}', [CommentController::class, 'destroy'])->where(['id' => '[0-9]+']);

        Route::get('/basket', [BasketController::class, 'index']);
        Route::post('/basket', [BasketController::class, 'store']);
        Route::delete('/basket', [BasketController::class, 'delete']);

        Route::get('/favorite', [FavoriteController::class, 'index']);
        Route::post('/favorite', [FavoriteController::class, 'store']);

        Route::get('user/search', [SearchController::class, 'userSearch']);
        Route::delete('user/search/{string}', [SearchController::class, 'userSearchDelete']);

        Route::get('order', [OrderController::class, 'index']);
        Route::get('order/{id}', [OrderController::class, 'show']);
        Route::post('order', [OrderController::class, 'store']);
        Route::post('order/product/{id}', [OrderController::class, 'orderProduct']);

        Route::get('/card', [CardController::class, 'index']);
        Route::post('/card', [CardController::class, 'store']);
        Route::post('/card-confirm', [CardController::class, 'confirm']);
        Route::put('/card/{id}', [CardController::class, 'update']);
        Route::delete('/card/{id}', [CardController::class, 'destroy']);

        Route::post('/logout', [AuthController::class, 'logout']);
    });
//    Route::get('region/{id?}', [DistrictController::class, 'index']);

    Route::get('intro', [DataController::class, 'intro']);

    Route::get('banners', [BannerController::class, 'index']);
    Route::get('brands', [BrandController::class, 'index']);
    Route::get('comments', [CommentController::class, 'index']);
    Route::get('comments/{id?}', [CommentController::class, 'show'])->where(['id' => '[0-9]+']);
    Route::get('news', [NewsController::class, 'index']);
    Route::get('news/{id?}', [NewsController::class, 'show'])->where(['id' => '[0-9]+']);

    Route::get('category', [CategoryController::class, 'parents']);
    Route::get('category/{id}/product', [CategoryController::class, 'show']);
    Route::get('product/{id}', [ProductController::class, 'show']);
    Route::get('product/{id}/similar', [ProductController::class, 'similar']);

    Route::get('popular/search', [SearchController::class, 'popular']);
    Route::get('search/{string}', [SearchController::class, 'search']);
    Route::get('search/suggest/{string}', [SearchController::class, 'suggest']);
});
