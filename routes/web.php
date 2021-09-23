<?php

use App\Http\Controllers\admin\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/',[HomeController::class,'index']);
//Auth::routes();
Route::get('/auth/social', [App\Http\Controllers\Auth\LoginController::class ,'show'])->name('social.login');
Route::get('/oauth/{driver}', [App\Http\Controllers\Auth\LoginController::class,'redirectToProvider'])->name('social.oauth');
Route::get('/oauth/{driver}/callback', [App\Http\Controllers\Auth\LoginController::class,'handleProviderCallback'])->name('social.callback');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
