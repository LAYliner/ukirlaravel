<?php
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Public\BlogController as PublicBlogController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProjectController as PublicProjectController;

// Root
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public Blog Routes
Route::get('/blog', [PublicBlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [PublicBlogController::class, 'show'])->name('blog.show');

// Public Project Routes
Route::get('/projects', [PublicProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [PublicProjectController::class, 'show'])->name('projects.show');

// Public Auth Routes
Route::middleware(['guest', 'no.auth.cache'])->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login.show');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    // Comments (Public but requires Auth)
    Route::post('/comments', [\App\Http\Controllers\Public\CommentController::class, 'store'])->name('comments.store');

    // Admin & Author Only
    Route::middleware('role:admin,author')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Blog Resource Routes
        Route::resource('blog', AdminBlogController::class);

        // Category Resource Routes
        Route::resource('categories', CategoryController::class);

        // Project Resource Routes
        Route::resource('projects', ProjectController::class);
        Route::patch('projects/{id}/status', [ProjectController::class, 'updateStatus'])
     ->name('projects.update-status');

        // Custom Project Route: Toggle Visibility
        Route::patch('projects/{id}/toggle-visibility', [ProjectController::class, 'toggleVisibility'])
             ->name('projects.toggle-visibility');
    });
});