<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

use App\Models\Article;
use App\Models\Tag;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RecentSearchController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SearchFilterController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeSearchController;
use App\Http\Controllers\CommentReportController;

use App\Mail\VerificationCodeMail;

// Root redirect
Route::get('/', fn() => redirect('/home'));

// ============================================================================
// GUEST ROUTES (Dashboard - No Authentication Required)
// ============================================================================
Route::get('/home', function () {
    $articles = Article::with('tags')
        ->where('status', 'Published')
        ->orderByDesc('views')
        ->get();

    return view('home.home', compact('articles'));
})->name('home');

Route::get('/home/{tag_slug}', function ($tag_slug) {
    $tag = Tag::where('slug', $tag_slug)->first();
    if (!$tag) abort(404);

    $articles = Article::with('tags')
        ->where('status', 'Published')
        ->whereHas('tags', fn($q) => $q->where('tags.id', $tag->id))
        ->orderByDesc('views')
        ->get();

    return view('home.home', compact('articles', 'tag'));
})->name('home.tag');

Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/home-search', [HomeSearchController::class, 'search']);
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// ============================================================================
// AUTHENTICATION ROUTES
// ============================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
    Route::get('/signup', [UserAuthController::class, 'showSignup'])->name('signup');
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/signup', [UserAuthController::class, 'signup']);
    Route::get('/set-display-name', [UserAuthController::class, 'showDisplayName']);
    Route::post('/set-display-name', [UserAuthController::class, 'storeDisplayName']);
    Route::post('/clear-signup-data', [UserAuthController::class, 'clearSignupData'])->name('clear-signup-data');

    // --- Forgot password: show form to enter email ---
    Route::get('/forgot-password', fn() => view('partials.forgot-pass'))->name('password.email');

    // --- Forgot password: handle email, generate+send code via SMTP ---
    Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
    ]);

    $email = $request->input('email');
    $code = strtoupper(substr(bin2hex(random_bytes(4)), 0, 4)) . '-' . random_int(1000, 9999);

    // Store in DATABASE instead of just session
    DB::table('password_resets')->updateOrInsert(
        ['email' => $email],
        [
            'email' => $email,
            'token' => $code,
            'created_at' => now(),
        ]
    );

    // Keep session for the code verification step
    session([
        'email' => $email,
        'verification_code' => $code,
        'verification_expires_at' => now()->addMinutes(10),
    ]);

    try {
        Mail::to($email)->send(new VerificationCodeMail($code));
    } catch (\Throwable $e) {
        report($e);
        return back()->withErrors([
            'email' => 'We could not send the email. Check mail settings and try again.',
        ]);
    }

    return redirect('/code-verify')->with('success', 'We sent a verification code to your email.');
})->name('password.email.send');

    // --- Code verify: show form (your Blade below) ---
    Route::get('/code-verify', fn() => view('partials.code-verify'))->name('password.code');

    // --- Code verify: handle submitted code ---
    Route::post('/code-verify', function (Request $request) {
        $request->validate(['code' => 'required|string']);

        $sessionEmail = session('email');
        $sessionCode = session('verification_code');
        $expiresAt   = session('verification_expires_at');

        if (!$sessionEmail || !$sessionCode || !$expiresAt) {
            return redirect('/forgot-password')->withErrors(['code' => 'Session expired. Please request a new code.']);
        }

        if (now()->greaterThan($expiresAt)) {
            session()->forget(['verification_code', 'verification_expires_at']);
            return redirect('/forgot-password')->withErrors(['code' => 'Code expired. Please request a new one.']);
        }

        if (strtoupper(trim($request->code)) !== strtoupper($sessionCode)) {
            return back()->withErrors(['code' => 'Invalid code. Please try again.']);
        }

        // success -> go to reset form
        return redirect('/reset-password')->with('success', 'Code accepted.');
    })->name('password.code.verify');

    // --- Optional: Resend code ---
    Route::post('/code-resend', function () {
        $email = session('email');
        if (!$email) {
            return redirect('/forgot-password')->withErrors(['email' => 'No email in session. Please start again.']);
        }

        $code = strtoupper(substr(bin2hex(random_bytes(4)), 0, 4)) . '-' . random_int(1000, 9999);

        session([
            'verification_code' => $code,
            'verification_expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($email)->send(new VerificationCodeMail($code));
        } catch (\Throwable $e) {
            report($e);
            return back()->withErrors([
                'code' => 'Could not resend email. Try again later.',
            ]);
        }

        return back()->with('success', 'A new code has been sent.');
    })->name('password.code.resend');

    // Reset password views + submit (your controller)
    Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.request');
    Route::post('/reset-password', [ResetPasswordController::class, 'updatePassword'])->name('password.update');
    Route::get('/resetsuccess', fn() => view('partials.reset-success'))->name('resetsuccess');
});

// ============================================================================
// AUTHENTICATED ROUTES (unchanged)
// ============================================================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
    Route::get('/settings', [SettingsController::class, 'showSettings'])->name('settings');
    Route::post('/settings', [UserManagementController::class, 'update'])->name('settings.update');
    Route::get('/recent-searches', [RecentSearchController::class, 'index'])->name('recent-searches.index');
    Route::post('/recent-searches', [RecentSearchController::class, 'store'])->name('recent-searches.store');
    Route::delete('/recent-searches', [RecentSearchController::class, 'clear']);

    Route::get('/articles/{slug}', [CommentController::class, 'show'])->name('comment.manage.show');

    Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/articles/{article}/comments/ajax', [CommentController::class, 'storeAjax'])->name('comments.store.ajax');
    Route::get('/articles/{article}/comments', [CommentController::class, 'getComments'])->name('comments.get');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::post('/comments/{comment}/dislike', [CommentController::class, 'dislike'])->name('comments.dislike');

    Route::get('/comment-manage-searchbar', [SearchFilterController::class, 'index'])->name('search');

    Route::post('/comments/{comment}/report', [CommentReportController::class, 'store'])->name('comments.report');
    Route::get('/comment-reports/reasons', [CommentReportController::class, 'getReasons'])->name('comment-reports.reasons');

    // Writer/Admin shared…
    function writerAndAdminSharedRoutes() {
        Route::get('/articles', function (Request $request) {
            $query = Article::with('tags');

            if ($request->filled('status') && $request->status !== 'All') {
                $query->where('status', $request->status);
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(fn($q) => $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%"));
            }
            if ($request->filled('tags')) {
                $tagIds = $request->tags;
                $query->whereHas('tags', fn($q) => $q->whereIn('tags.id', $tagIds));
            }

            $articles = $query->get();
            $tags = Tag::all();

            return view('admin-panel.article-management', compact('articles', 'tags'));
        })->name('articles');

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/add-article', [ArticleController::class, 'create'])->name('articles.create');
        Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
        Route::post('/articles/preview', [ArticleController::class, 'preview'])->name('articles.preview');
        Route::post('/articles/back-to-editor', [ArticleController::class, 'backToEditor'])->name('articles.back-to-editor');
    }

    Route::middleware('writer')->prefix('writer')->name('writer.')->group(function () {
        writerAndAdminSharedRoutes();
    });

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        writerAndAdminSharedRoutes();

        Route::get('/edit-article/{id}', [ArticleController::class, 'edit'])->name('articles.edit');
        Route::put('/edit-article/{id}', [ArticleController::class, 'update'])->name('articles.update');
        Route::patch('/articles/{article}/approve', [ArticleController::class, 'approve'])->name('articles.approve');
        Route::patch('/articles/{id}/reject', [ArticleController::class, 'reject'])->name('articles.reject');
        Route::patch('/articles/{article}/publish', [ArticleController::class, 'publish'])->name('articles.publish');
        Route::delete('/articles/{article}/delete', [ArticleController::class, 'destroy'])->name('articles.delete');

        Route::get('/comments', [CommentController::class, 'index'])->name('comments');

        Route::get('/comment-reports', [CommentReportController::class, 'index'])->name('admin.comment-reports.index');
        Route::patch('/comment-reports/{report}', [CommentReportController::class, 'update'])->name('admin.comment-reports.update');

        Route::post('admin/users/{id}/ban', [UserManagementController::class, 'ban'])->name('users.ban');

        Route::get('/users', [UserManagementController::class, 'index'])->name('index');
        Route::prefix('users')->name('users.')->group(function () {
            Route::patch('{id}/admin-update', [UserManagementController::class, 'adminUpdate'])->name('admin-update');
            Route::post('{id}/report', [UserManagementController::class, 'report'])->name('report');
            Route::patch('{id}/ban', [UserManagementController::class, 'ban'])->name('ban');
            Route::patch('{id}/unban', [UserManagementController::class, 'unban'])->name('unban');
            Route::patch('{id}/role', [UserManagementController::class, 'setRole'])->name('set-role');
            Route::delete('{id}', [UserManagementController::class, 'destroy'])->name('destroy');
            Route::get('{id}/reports', [UserManagementController::class, 'getUserReports'])->name('reports');
            Route::get('{id}/ban-history', [UserManagementController::class, 'getBanHistory'])->name('ban-history');
        });
    });
});
