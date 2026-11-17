<?php

use App\Http\Controllers\AdminPortalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorPortalController;
use App\Http\Controllers\PublicNewsController;
use App\Http\Controllers\UserPortalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public News & Editorial Portal Routes (Member 2 Scope)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicNewsController::class, 'index'])->name('home');
Route::get('/blogs', [PublicNewsController::class, 'blogs'])->name('blogs.index');
Route::get('/blog/{slug?}', [PublicNewsController::class, 'show'])->name('blogs.show');
Route::get('/author-profile/{username?}', [PublicNewsController::class, 'author'])->name('blogs.author');

Route::get('/about', [PublicNewsController::class, 'about'])->name('pages.about');
Route::get('/contact', [PublicNewsController::class, 'contact'])->name('pages.contact');
Route::post('/contact', [PublicNewsController::class, 'submitContact'])->name('pages.contact.submit');
Route::get('/privacy-policy', [PublicNewsController::class, 'privacyPolicy'])->name('pages.privacy');

Route::post('/newsletter/subscribe', [PublicNewsController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
Route::get('/language/switch/{code}', [PublicNewsController::class, 'switchLanguage'])->name('language.switch');
Route::get('/rss.xml', [PublicNewsController::class, 'rssFeed'])->name('rss.feed');

/*
|--------------------------------------------------------------------------
| Authentication & Role Switching Routes (Member 1 Scope)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showUserLogin'])->name('login');
Route::get('/user/login', [AuthController::class, 'showUserLogin'])->name('user.login');
Route::get('/author/login', [AuthController::class, 'showAuthorLogin'])->name('author.login');
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');

Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::get('/user/register', [AuthController::class, 'showRegister'])->name('user.register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/demo-switch/{role}', [AuthController::class, 'demoSwitch'])->name('demo.switch');

/*
|--------------------------------------------------------------------------
| Authenticated Reader Interactions & User Portal Routes (Member 1 & 3 Scope)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => redirect(auth()->user()->dashboardRoute()))->name('dashboard');

    Route::post('/blog/{post}/comments', [PublicNewsController::class, 'storeComment'])->name('blogs.comments.store');
    Route::post('/blog/{post}/bookmark', [PublicNewsController::class, 'toggleBookmark'])->name('blogs.bookmark.toggle');

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserPortalController::class, 'dashboard'])->name('dashboard');
        Route::put('/profile', [UserPortalController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [UserPortalController::class, 'updatePassword'])->name('password.update');
        Route::post('/request-author', [UserPortalController::class, 'requestAuthorRole'])->name('request-author');
        Route::post('/tickets', [UserPortalController::class, 'storeTicket'])->name('tickets.store');
    });
});

/*
|--------------------------------------------------------------------------
| Author Studio Routes (Member 3 Scope - Accessible to Authors & Admins)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:author,admin'])->prefix('author')->name('author.')->group(function () {
    Route::get('/dashboard', [AuthorPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/posts', [AuthorPortalController::class, 'posts'])->name('posts');
    Route::get('/posts/create', [AuthorPortalController::class, 'create'])->name('posts.create');
    Route::post('/posts', [AuthorPortalController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [AuthorPortalController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [AuthorPortalController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [AuthorPortalController::class, 'destroy'])->name('posts.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Console Routes (Member 4 Scope - Accessible to Admins)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/posts', [AdminPortalController::class, 'posts'])->name('posts');
    Route::patch('/posts/{post}/moderate', [AdminPortalController::class, 'moderatePost'])->name('posts.moderate');
    Route::post('/posts/bulk-action', [AdminPortalController::class, 'bulkAction'])->name('posts.bulk');

    Route::post('/categories', [AdminPortalController::class, 'storeCategory'])->name('categories.store');
    Route::delete('/categories/{category}', [AdminPortalController::class, 'destroyCategory'])->name('categories.destroy');

    Route::post('/tags', [AdminPortalController::class, 'storeTag'])->name('tags.store');
    Route::delete('/tags/{tag}', [AdminPortalController::class, 'destroyTag'])->name('tags.destroy');

    Route::patch('/comments/{comment}/moderate', [AdminPortalController::class, 'moderateComment'])->name('comments.moderate');
    Route::delete('/comments/{comment}', [AdminPortalController::class, 'destroyComment'])->name('comments.destroy');

    Route::patch('/users/{user}/role', [AdminPortalController::class, 'updateUserRole'])->name('users.role');
    Route::patch('/messages/{message}/read', [AdminPortalController::class, 'markMessageRead'])->name('messages.read');
});
