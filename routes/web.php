<?php

use Illuminate\Support\Facades\Route;
use App\Models\Article;
use App\Http\Controllers\UserAuthController;

Route::get('/', function () {
    $articles = Article::with('tags')->latest()->get();
    return view('layout.user', compact('articles'));
});

Route::get('/dashboard', function(){
    $articles = Article::with('tags')->latest()->get();
    return view('layout.user', compact('articles'));
});

Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
Route::get('/signup', [UserAuthController::class, 'showSignup'])->name('signup');
Route::post('/login', [UserAuthController::class, 'login']);

Route::get('/add-article', [\App\Http\Controllers\ArticleController::class, 'create'])->name('articles.create');

Route::post('/articles', [\App\Http\Controllers\ArticleController::class, 'store'])->name('articles.store');
