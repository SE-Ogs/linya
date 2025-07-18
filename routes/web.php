<?php

use App\Http\Controllers\RecentSearchController;
use Illuminate\Support\Facades\Route;
use App\Models\Article;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\ArticleController;

Route::get('/', function () {                    // This route will be deleted
    $articles = Article::with('tags')->latest()->get();  // I just added this so localhost:8000 will display my page
    return view('admin-panel.comment-manage-article');  
});

// Route::get('/', function () {
//     $articles = Article::with('tags')->latest()->get();
//     return view('layout.user', compact('articles'));  // default view value: layout.user --- uncomment this route after testing
// });

// Route::get('/', function () {
//     return redirect("/dashboard");
// });

// Route::get('/dashboard', function(){
//     $articles = Article::with('tags')
//         ->where('status', 'published')
//         ->orderByDesc('views')
//         ->get();
//     return view('layout.user', compact('articles'));
// });

Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
Route::get('/signup', [UserAuthController::class, 'showSignup'])->name('signup');
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');

// Route for recent searches
Route::get('/recent-searches', [RecentSearchController::class, 'index'])->name('recent-searches.index');
Route::post('/recent-searches', [RecentSearchController::class, 'store'])->name('recent-searches.store');
Route::delete('/recent-searches', [RecentSearchController::class, 'clear']);

Route::get('/resetpass', function(){
    return view('partials.reset_password');
});

Route::get('/resetsuccess', function(){
    return view('partials.reset_success');
});

Route::get('/reset-password', function () {
    return view('partials.reset_password'); // or 'partials.reset_password' if that's the folder
})->name('password.request');


Route::get('/add-article', [ArticleController::class, 'create'])->name('articles.create');

Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');
Route::post('/articles/preview', [ArticleController::class, 'preview'])->name('articles.preview');
Route::get('/articles/{id}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
Route::put('/articles/{id}', [ArticleController::class, 'update'])->name('articles.update');
Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])->name('articles.destroy');

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

Route::get('/admin/posts', function (\Illuminate\Http\Request $request) {
    $query = Article::with('tags');

    // Apply filters
    if ($request->filled('status') && $request->status !== 'All') {
        $query->where('status', $request->status);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('summary', 'like', "%{$search}%");
        });
    }

    if ($request->filled('tags')) {
        $tagIds = $request->tags;
        $query->whereHas('tags', function($q) use ($tagIds) {
            $q->whereIn('tags.id', $tagIds);
        });
    }

    $articles = $query->get();
    $tags = \App\Models\Tag::all();

    return view('admin-panel.post_management', compact('articles', 'tags'));
})->name('admin.posts');

