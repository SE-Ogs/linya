<?php

use Illuminate\Support\Facades\Route;
use App\Models\Article;

Route::get('/', function () {                    // This route will be deleted
    $articles = Article::with('tags')->latest()->get();  // I just added this so localhost:8000 will display my page
    return view('admin-panel.user-manage');  
});

// Route::get('/', function () {
//     $articles = Article::with('tags')->latest()->get();
//     return view('layout.user', compact('articles'));  // default view value: layout.user --- uncomment this route after testing
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
Route::get('/articles/{slug}', [CommentManageController::class, 'show'])->name('comment.manage.show');

use App\Http\Controllers\UserManagementController;
Route::get('/admin/users', [UserManagementController::class, 'index'])->name('user.management');
// Dummy routes below for testing purposes, update these routes once database is operational
Route::prefix('users')->group(function () {
    Route::get('{id}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::post('{id}/report', [UserManagementController::class, 'report'])->name('users.report');
    Route::patch('{id}/suspend', [UserManagementController::class, 'suspend'])->name('users.suspend');
    Route::delete('{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');
});
// End of Christian J.'s added routes

use App\Http\Controllers\PostManageController;
Route::get('/admin/posts', [PostManageController::class, 'index'])->name('admin.posts');

