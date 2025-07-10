<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layout.user');
});

Route::get('/dashboard', function(){
    return view('layout.user');
});

Route::get('/login', function(){
    return view('layout.login');
});

Route::get('/resetpass', function(){
    return view('layout.reset_password');
});


Route::get('/add-article', [\App\Http\Controllers\ArticleController::class, 'create'])->name('articles.create');

Route::post('/articles', [\App\Http\Controllers\ArticleController::class, 'store'])->name('articles.store');
