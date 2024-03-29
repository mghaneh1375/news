<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\NewsController;

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

Route::prefix('{lang}')->middleware(['setLocale'])->group(function() {

    Route::get('search/{content?}', [NewsController::class, 'search']);

    Route::get('find/{id}', [NewsController::class, 'find']);

    Route::get('findSlug/{slug}', [NewsController::class, 'findBySlug']);

    Route::get('slugList', [NewsController::class, 'slugList']);

    Route::get('topNews', [NewsController::class, 'topNews']);

});
