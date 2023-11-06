<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserNewsController;
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

Route::middleware(['setLocale'])->group(function() {

    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('login', [AuthController::class, 'login'])->name('login');

    Route::post('login', [AuthController::class, 'doLogin'])->name('doLogin');

    Route::middleware(['auth'])->group(function() {

        Route::get('/doLogOut', [AuthController::class, 'logout'])->name('logout');

        Route::get('changePass', ['as' => 'changePass', 'uses' => 'HomeController@changePass']);

        Route::post('doChangePass', ['as' => 'doChangePass', 'uses' => 'HomeController@doChangePass']);

    });


    Route::prefix('{lang}')->middleware(['shareNews'])->group(function (){

        Route::get('/', [UserNewsController::class, 'newsMainPage'])->name('site.news.main');

        Route::get('/main', [UserNewsController::class, 'newsMainPage'])->name('site.news.main');

        Route::get('/list/{kind}/{content?}', [UserNewsController::class, 'newsListPage'])->name('site.news.list');

        Route::get('/show/{slug}', [UserNewsController::class, 'newsShow'])->name('site.news.show');
        
    });

    Route::get('/listGetElemes', [UserNewsController::class, 'newsListElements'])->name('site.news.list.getElements');

    Route::middleware(['auth'])->prefix('admin')
        ->group(base_path('routes/adminRoutes.php'));
        
});
