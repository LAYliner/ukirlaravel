<?php
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Public\BlogController as PublicBlogController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\ContentHighlightController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProjectController as PublicProjectController;

// Root
Route::get('/', [HomeController::class, 'index'])->name('home');

// Content Highlight Route (untuk redirect ke komentar dengan highlight)
Route::get('/content/{contentId}', [ContentHighlightController::class, 'redirect'])
    ->name('content.highlight');

// Public Blog Routes
Route::get('/blog', [PublicBlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [PublicBlogController::class, 'show'])->name('blog.show');

// Public Contact Route
Route::get('/contact', function () {
    return view('public.contact.index');
})->name('contact');

// Public About Route
Route::get('/about', function () {
    return view('public.about.index');
})->name('about');

// Public Project Routes
Route::get('/projects', [PublicProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [PublicProjectController::class, 'show'])->name('projects.show');

// Public Auth Routes
Route::middleware(['guest', 'no.auth.cache'])->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login.show');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendToken'])
        ->middleware('throttle:password_request')
        ->name('password.email');
    Route::post('/forgot-password/resend', [ForgotPasswordController::class, 'resendToken'])
        ->middleware('throttle:password_request')
        ->name('password.resend');
    Route::get('/password/verify', [ResetPasswordController::class, 'showVerifyForm'])->name('password.verify');
    Route::post('/password/verify', [ResetPasswordController::class, 'verify'])
        ->middleware('throttle:token_verify')
        ->name('password.verify.post');
    Route::get('/password/reset', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Email verification routes
    Route::get('/verify-otp', [VerificationController::class, 'showForm'])->name('verification.notice');
    Route::post('/verify-otp', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/resend-otp', [VerificationController::class, 'resend'])->name('verification.resend');
    Route::get('/verification-success', [VerificationController::class, 'success'])->name('verification.success');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    // Comments (Public but requires Auth)
    Route::post('/comments', [\App\Http\Controllers\Public\CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{id}', [\App\Http\Controllers\Public\CommentController::class, 'destroy'])->name('comments.destroy')->middleware('auth');

    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/change-password', [ProfileController::class, 'changePassword'])
            ->middleware('throttle:change-password')
            ->name('change-password');
        Route::get('/comments', [ProfileController::class, 'comments'])->name('comments');
    });

    // Admin & Author Only
    Route::middleware('role:admin,author')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('ckeditor/upload', [AdminDashboardController::class, 'uploadImage'])->name('ckeditor.upload');

        // Blog Resource Routes
        Route::resource('blog', AdminBlogController::class);
        Route::patch('blog/{id}/status', [AdminBlogController::class, 'updateStatus'])
             ->name('blog.update-status');
        Route::patch('blog/{id}/toggle-visibility', [AdminBlogController::class, 'toggleVisibility'])
             ->name('blog.toggle-visibility');

        // Category Resource Routes
        Route::resource('categories', CategoryController::class);

        // Project Resource Routes
        Route::resource('projects', ProjectController::class);
        Route::patch('projects/{id}/status', [ProjectController::class, 'updateStatus'])
            ->name('projects.update-status');

        Route::resource('tags', TagController::class);

        // Custom Project Route: Toggle Visibility
        Route::patch('projects/{id}/toggle-visibility', [ProjectController::class, 'toggleVisibility'])
            ->name('projects.toggle-visibility');

        // Comment Management Routes (Admin Only)
        Route::middleware('role:admin')->group(function () {
            Route::get('comments', [AdminCommentController::class, 'index'])->name('comments.index');
            Route::delete('comments/{id}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');
            Route::patch('comments/{id}/restore', [AdminCommentController::class, 'restore'])->name('comments.restore');
            Route::delete('comments/{id}/force-delete', [AdminCommentController::class, 'forceDelete'])->name('comments.force-delete');

            // User Management Routes (Admin Only)
            Route::get('users', [UserController::class, 'index'])->name('users.index');
            Route::patch('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
            Route::patch('users/{id}/update-role', [UserController::class, 'updateRole'])->name('users.update-role');
            Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
            Route::patch('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
            Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');
        });
    });
});