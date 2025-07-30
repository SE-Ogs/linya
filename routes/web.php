<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentManageController;
use App\Http\Controllers\RecentSearchController;
use App\Http\Controllers\SearchFilterController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserManagementController;
use App\Models\Article;
use App\Models\Tag;
use App\Http\Controllers\DashboardSearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;

// Root redirect
Route::get('/', function () {
    return redirect('/dashboard');
});

// ============================================================================
// GUEST ROUTES (Dashboard - No Authentication Required)
// ============================================================================
Route::get('/dashboard', function () {
    $articles = Article::with('tags')
        ->where('status', 'Published')
        ->orderByDesc('views')
        ->get();

    return view('layout.user', compact('articles'));
})->name('dashboard');

Route::get('/dashboard/{tag_slug}', function ($tag_slug) {
    $tag = Tag::where('slug', $tag_slug)->first();

    if (!$tag) {
        abort(404);
    }

    $articles = Article::with('tags')
        ->where('status', 'Published')
        ->whereHas('tags', function ($query) use ($tag) {
            $query->where('tags.id', $tag->id);
        })
        ->orderByDesc('views')
        ->get();

    return view('layout.user', compact('articles', 'tag'));
})->name('dashboard.tag');

// Articles
Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');

// =========================================================================
// AUTHENTICATION ROUTES
// =========================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
    Route::get('/signup', [UserAuthController::class, 'showSignup'])->name('signup');
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/signup', [UserAuthController::class, 'signup']);
    Route::post('/clear-signup-data', [UserAuthController::class, 'clearSignupData'])->name('clear-signup-data');

    Route::get('/reset-password', function () {
        return view('partials.reset_password');
    })->name('password.request');

    Route::get('/resetpass', function () {
        return view('partials.reset_password');
    });

    Route::get('/resetsuccess', function () {
        return view('partials.reset_success');
    });

    Route::get('/forgot-password', function () {
    return view('partials.forgot_pass');
    });

    Route::get('/code-verify', function () {
    return view('partials.code_verify');
    });
});

// =========================================================================
// AUTHENTICATED ROUTES
// =========================================================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
    Route::get('/set-display-name', [UserAuthController::class, 'showDisplayName']);
    Route::post('/set-display-name', [UserAuthController::class, 'storeDisplayName']);
    Route::get('/settings', [SettingsController::class, 'showSettings'])->name('settings');
    Route::post('/settings', [UserManagementController::class, 'update'])->name('settings.update');
    Route::get('/recent-searches', [RecentSearchController::class, 'index'])->name('recent-searches.index');
    Route::post('/recent-searches', [RecentSearchController::class, 'store'])->name('recent-searches.store');
    Route::delete('/recent-searches', [RecentSearchController::class, 'clear']);
    Route::get('/dashboard-search', [DashboardSearchController::class, 'search']);

    // Comment management
    Route::get('/articles/{slug}', [CommentManageController::class, 'show'])->name('comment.manage.show');

    // Comment routes - All inside auth group
    Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/articles/{article}/comments/ajax', [CommentController::class, 'storeAjax'])->name('comments.store.ajax');
    Route::get('/articles/{article}/comments', [CommentController::class, 'getComments'])->name('comments.get');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::post('/comments/{comment}/dislike', [CommentController::class, 'dislike'])->name('comments.dislike');

    Route::get('/comment-manage-searchbar', [SearchFilterController::class, 'index'])->name('search');

    // ========================================================================
    // ADMIN ROUTES
    // ========================================================================
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Post management
        Route::get('/articles', function (\Illuminate\Http\Request $request) {
            $query = Article::with('tags');

            if ($request->filled('status') && $request->status !== 'All') {
                $query->where('status', $request->status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('summary', 'like', "%{$search}%");
                });
            }

            if ($request->filled('tags')) {
                $tagIds = $request->tags;
                $query->whereHas('tags', function ($q) use ($tagIds) {
                    $q->whereIn('tags.id', $tagIds);
                });
            }

            $articles = $query->get();
            $tags = Tag::all();

            return view('admin-panel.post_management', compact('articles', 'tags'));
        })->name('articles');

        // Article management
        Route::get('/add-article', [ArticleController::class, 'create'])->name('articles.create');
        Route::post('/articles/preview', [ArticleController::class, 'preview'])->name('articles.preview');
        Route::get('/edit-article/{id}', [ArticleController::class, 'edit'])->name('articles.edit');
        Route::put('/edit-article/{id}', [ArticleController::class, 'update'])->name('articles.update');
        Route::patch('/articles/{article}/approve', [ArticleController::class, 'approve'])->name('articles.approve');
        Route::patch('/articles/{id}/reject', [ArticleController::class, 'reject'])->name('articles.reject');
        Route::patch('/articles/{article}/publish', [ArticleController::class, 'publish'])->name('articles.publish');
        Route::delete('/articles/{article}/delete', [ArticleController::class, 'destroy'])->name('articles.delete');

        // Comment management
        Route::get('/comments', [CommentManageController::class, 'index'])->name('comments');

        // User management
        Route::get('/users', [UserManagementController::class, 'index'])->name('index');
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('{id}/edit', [UserManagementController::class, 'edit'])->name('edit');
            Route::patch('{id}', [UserManagementController::class, 'update'])->name('update');
            Route::post('{id}/report', [UserManagementController::class, 'report'])->name('report');
            Route::patch('{id}/suspend', [UserManagementController::class, 'suspend'])->name('suspend');
            Route::delete('{id}', [UserManagementController::class, 'destroy'])->name('destroy');
        });
    });
});
