<?php

use Illuminate\Support\Facades\Route;
use App\Models\Article;
use App\Http\Controllers\UserAuthController;

Route::get('/', function () {
    return redirect("/dashboard");
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

// Christian J. added these routes
use App\Http\Controllers\SearchBarController;
Route::get('/comment-manage-searchbar', [SearchBarController::class, 'index'])->name('search');

use App\Http\Controllers\CommentManageController;
Route::get('/admin/comments', [CommentManageController::class, 'index'])->name('admin.comments');

use App\Http\Controllers\PostManageController;
Route::get('/admin/posts', [PostManageController::class, 'index'])->name('admin.posts');

