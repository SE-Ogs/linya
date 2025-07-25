<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentManageController;
use App\Http\Controllers\RecentSearchController;
use App\Http\Controllers\SearchBarController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserManagementController;
use App\Models\Article;
use App\Models\Tag;
use App\Http\Controllers\DashboardSearchController;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', function () {
    return redirect('/dashboard');
});

// ============================================================================
// GUEST ROUTES (Dashboard - No Authentication Required)
// ============================================================================
// Dashboard routes
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

Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');


// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
    Route::get('/signup', [UserAuthController::class, 'showSignup'])->name('signup');
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/signup', [UserAuthController::class, 'signup']);
    Route::post('/clear-signup-data', [UserAuthController::class, 'clearSignupData'])->name('clear-signup-data');

    // Password reset routes
    Route::get('/reset-password', function () {
        return view('partials.reset_password');
    })->name('password.request');

    Route::get('/resetpass', function () {
        return view('partials.reset_password');
    });

    Route::get('/resetsuccess', function () {
        return view('partials.reset_success');
    });
});

// ============================================================================
// AUTHENTICATED ROUTES (Require Authentication)
// ============================================================================
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');

    // Display name setup
    Route::get('/set-display-name', [UserAuthController::class, 'showDisplayName']);
    Route::post('/set-display-name', [UserAuthController::class, 'storeDisplayName']);

    // User settings
    Route::get('/settings', [SettingsController::class, 'showSettings'])->name('settings');
    Route::post('/settings', [UserManagementController::class, 'update'])->name('settings.update');

    // Recent searches
    Route::get('/recent-searches', [RecentSearchController::class, 'index'])->name('recent-searches.index');
    Route::post('/recent-searches', [RecentSearchController::class, 'store'])->name('recent-searches.store');
    Route::delete('/recent-searches', [RecentSearchController::class, 'clear']);
    Route::get('/dashboard-search', [DashboardSearchController::class, 'search']);

    // Article management
    Route::get('/add-article', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles/preview', [ArticleController::class, 'preview'])->name('articles.preview');
    Route::get('/edit-article/{id}', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/edit-article/{id}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])->name('articles.destroy');

    // Comment management
    Route::get('/articles/{slug}', [CommentManageController::class, 'show'])->name('comment.manage.show');

    // Search functionality
    Route::get('/comment-manage-searchbar', [SearchBarController::class, 'index'])->name('search');

    // ========================================================================
    // ADMIN ROUTES (Additional middleware can be added here if needed)
    // ========================================================================
    Route::prefix('admin')->name('admin.')->group(function () {

        // Admin dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Post management
        Route::get('/posts', function (\Illuminate\Http\Request $request) {
            $query = Article::with('tags');

            // Apply filters
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
        })->name('posts');

        // Comment management
        Route::get('/comments', [CommentManageController::class, 'index'])->name('comments');

        // User management
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('{id}/edit', [UserManagementController::class, 'edit'])->name('edit');
            Route::patch('{id}', [UserManagementController::class, 'update'])->name('update');
            Route::post('{id}/report', [UserManagementController::class, 'report'])->name('report');
            Route::patch('{id}/suspend', [UserManagementController::class, 'suspend'])->name('suspend');
            Route::delete('{id}', [UserManagementController::class, 'destroy'])->name('destroy');
        });
    });
});
