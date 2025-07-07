<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layout.user');
});

Route::get('/dashboard', function(){
    return view('layout.user');
});

Route::get('/add-article', [\App\Http\Controllers\ArticleController::class, 'create'])->name('articles.create');

Route::post('/articles', [\App\Http\Controllers\ArticleController::class, 'store'])->name('articles.store');
