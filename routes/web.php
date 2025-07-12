<?php

use Illuminate\Support\Facades\Route;
use App\Models\Article;

Route::get('/', function () {
    $articles = Article::with('tags')->latest()->get();
    return view('admin-panel.comment-manage-article');  
});

// Route::get('/', function () {
//     $articles = Article::with('tags')->latest()->get();
//     return view('layout.user', compact('articles'));  // default: layout.user --- uncomment this after testing
// });


Route::get('/dashboard', function(){
    $articles = Article::with('tags')->latest()->get();
    return view('layout.user', compact('articles'));
});

Route::get('/login', function(){
    return view('layout.login-and-signup', ['show' => 'login']);
});

Route::get('/signup', function(){
    return view('layout.login-and-signup', ['show' => 'signup']);
});

Route::get('/add-article', [\App\Http\Controllers\ArticleController::class, 'create'])->name('articles.create');

Route::post('/articles', [\App\Http\Controllers\ArticleController::class, 'store'])->name('articles.store');

// Christian J. added these routes
use App\Http\Controllers\SearchBarController;
Route::get('/comment-manage-searchbar', [SearchBarController::class, 'index'])->name('search');

use App\Http\Controllers\CommentManageController;
Route::get('/admin/comments', [CommentManageController::class, 'index'])->name('admin.comments');

