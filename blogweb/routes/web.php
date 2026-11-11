<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('blog'));
Route::get('/blog', [PostController::class, 'index'])->name('blog');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:10,1');

    Route::get('/forgot-password', [PasswordController::class, 'showForgot'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'sendLink'])
        ->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}',
        [PasswordController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'reset'])
        ->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/account/password', [PasswordController::class, 'showChange'])
        ->name('password.change');
    Route::put('/account/password', [PasswordController::class, 'change'])
        ->name('password.change.update');

    Route::get('/my-posts', [PostController::class, 'mine'])
        ->name('posts.mine');
    Route::get('/my-posts/trash', [PostController::class, 'trash'])
        ->name('posts.trash');

    Route::get('/posts/create', [PostController::class, 'create'])
        ->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])
        ->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])
        ->name('posts.edit')->whereNumber('post');
    Route::put('/posts/{post}', [PostController::class, 'update'])
        ->name('posts.update')->whereNumber('post');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])
        ->name('posts.destroy')->whereNumber('post');
    Route::post('/posts/{post}/restore', [PostController::class, 'restore'])
        ->name('posts.restore')->whereNumber('post')->withTrashed();
    Route::delete('/posts/{post}/force', [PostController::class, 'forceDelete'])
        ->name('posts.force')->whereNumber('post')->withTrashed();

    Route::post('/posts/{post:slug}/comments',
        [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');
});

// Đặt sau /posts/create để slug không nuốt mất đường dẫn "create".
Route::get('/posts/{post:slug}', [PostController::class, 'show'])
    ->name('posts.show');

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/posts', [AdminController::class, 'posts'])->name('posts');
    Route::delete('/posts/{post}', [AdminController::class, 'destroyPost'])
        ->name('posts.destroy')->withTrashed();
    Route::get('/comments', [AdminController::class, 'comments'])
        ->name('comments');

    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])
        ->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])
        ->name('categories.update');
    Route::delete('/categories/{category}',
        [CategoryController::class, 'destroy'])->name('categories.destroy');
});
