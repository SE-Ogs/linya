<?php

use Illuminate\Support\Facades\Route;
use App\Models\Article;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\ArticleController;

Route::get('/', function () {
    return redirect("/dashboard");
});

Route::get('/dashboard', function(){
    $articles = Article::with('tags')
        ->where('status', 'published')
        ->orderByDesc('views')
        ->get();
    return view('layout.user', compact('articles'));
});

Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
Route::get('/signup', [UserAuthController::class, 'showSignup'])->name('signup');
Route::post('/login', [UserAuthController::class, 'login']);

Route::get('/resetpass', function(){
    return view('layout.reset_password');
});


Route::get('/add-article', [\App\Http\Controllers\ArticleController::class, 'create'])->name('articles.create');

Route::post('/articles', [\App\Http\Controllers\ArticleController::class, 'store'])->name('articles.store');
Route::get('/articles/{id}', [\App\Http\Controllers\ArticleController::class, 'show'])->name('articles.show');
Route::post('/articles/preview', [\App\Http\Controllers\ArticleController::class, 'preview'])->name('articles.preview');

// Christian J. added these routes
use App\Http\Controllers\SearchBarController;
Route::get('/comment-manage-searchbar', [SearchBarController::class, 'index'])->name('search');

use App\Http\Controllers\CommentManageController;
Route::get('/admin/comments', [CommentManageController::class, 'index'])->name('admin.comments');

use App\Http\Controllers\PostManageController;
Route::get('/admin/posts', [PostManageController::class, 'index'])->name('admin.posts');

