<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsTagController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\EnSeoController;
use Illuminate\Support\Facades\Route;


//news.list
Route::post('seoTesterContent', [SeoController::class, 'seoTesterContent'])->name('seoTesterContent');
Route::post('EnSeoTesterContent', [EnSeoController::class, 'EnSeoTesterContent'])->name('EnSeoTesterContent');


Route::prefix('tags')->group(function() {
    
    Route::get('/list', [NewsTagController::class, 'list'])->name('tags.list');

    Route::get('/new', [NewsTagController::class, 'new'])->name('tags.new');

    Route::get('/{newsTags}', [NewsTagController::class, 'edit'])->name('tags.edit');

    Route::post('/store', [NewsTagController::class, 'store'])->name('tags.store');
    
    Route::post('/update/{newsTags}', [NewsTagController::class, 'update'])->name('tags.update');

});

Route::prefix('news')->group(function(){
    Route::get('/list', [NewsController::class, 'newsList'])->name('news.list');
    Route::get('/new', [NewsController::class, 'newsNewPage'])->name('news.new');
    Route::get('/edit/{id}', [NewsController::class, 'editNewsPage'])->name('news.edit');

    Route::get('/tagSearch', [NewsController::class, 'newsTagSearch'])->name('news.tagSearch');

    Route::post('/uploadPic',[NewsController::class, 'uploadNewsPic'])->name('news.uploadDescPic');
    Route::post('/store',[NewsController::class, 'storeNews'])->name('news.store');
    Route::post('/storeVideo',[NewsController::class, 'storeNewsVideo'])->name('news.store.video');

    Route::post('/addToTopNews', [NewsController::class, 'addToTopNews'])->name('news.addToTopNews');
    Route::post('/removeAllTops', [NewsController::class, 'removeAllTopNews'])->name('news.removeAllTopNews');
    Route::delete('/delete', [NewsController::class, 'deleteNews'])->name('news.delete');
    Route::delete('/delete/video', [NewsController::class, 'deleteVideoNews'])->name('news.delete.video');
});
