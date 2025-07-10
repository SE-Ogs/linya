<?php

use Illuminate\Support\Facades\Route;
use App\Models\Article;

Route::get('/', function () {
    $articles = Article::with('tags')->latest()->get();
    return view('layout.user', compact('articles'));
});

Route::get('/dashboard', function(){
    $articles = Article::with('tags')->latest()->get();
    return view('layout.user', compact('articles'));
});

Route::get('/login', function(){
    return view('layout.login');
});

Route::get('/add-article', [\App\Http\Controllers\ArticleController::class, 'create'])->name('articles.create');

Route::post('/articles', [\App\Http\Controllers\ArticleController::class, 'store'])->name('articles.store');
