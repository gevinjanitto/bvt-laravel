<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/tour-packages', [SiteController::class, 'tours'])->name('tours');
Route::get('/tour-packages/{slug}', [SiteController::class, 'tourShow'])->name('tours.show');
Route::get('/car-rental', [SiteController::class, 'cars'])->name('cars');
Route::get('/car-rental/{slug}', [SiteController::class, 'carShow'])->name('cars.show');
Route::get('/activities', [SiteController::class, 'activities'])->name('activities');
Route::get('/activities/{slug}', [SiteController::class, 'activityShow'])->name('activities.show');
Route::get('/about', [SiteController::class, 'about'])->name('about');
Route::get('/articles', [SiteController::class, 'articles'])->name('articles');
Route::get('/articles/{slug}', [SiteController::class, 'articleShow'])->name('articles.show');
Route::get('/policies/{type}', [SiteController::class, 'policy'])->name('policy');
Route::post('/booking', [SiteController::class, 'booking'])->name('booking');
Route::post('/newsletter', [SiteController::class, 'newsletter'])->name('newsletter');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('bookings', [AdminController::class, 'bookings'])->name('bookings');
        Route::patch('bookings/{booking}', [AdminController::class, 'updateBooking'])->name('bookings.update');
        Route::delete('bookings/{booking}', [AdminController::class, 'destroyBooking'])->name('bookings.destroy');
        Route::get('settings', [AdminController::class, 'settings'])->name('settings');
        Route::put('settings', [AdminController::class, 'updateSettings'])->name('settings.update');
        Route::get('content', [AdminController::class, 'content'])->name('content');
        Route::put('content', [AdminController::class, 'updateContent'])->name('content.update');
<<<<<<< HEAD
=======
        Route::post('content/reset', [AdminController::class, 'resetContent'])->name('content.reset');
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
        Route::get('account', [AuthController::class, 'account'])->name('account');
        Route::put('account', [AuthController::class, 'updateAccount'])->name('account.update');
        Route::put('preferences', [AuthController::class, 'updatePreferences'])->name('preferences.update');
        Route::post('upload', [AdminController::class, 'upload'])->name('upload');

        Route::get('{resource}', [ResourceController::class, 'index'])->name('resource.index')->whereIn('resource', ['tours', 'cars', 'activities', 'articles']);
        Route::get('{resource}/create', [ResourceController::class, 'create'])->name('resource.create');
        Route::post('{resource}', [ResourceController::class, 'store'])->name('resource.store');
        Route::get('{resource}/{id}/edit', [ResourceController::class, 'edit'])->name('resource.edit');
        Route::put('{resource}/{id}', [ResourceController::class, 'update'])->name('resource.update');
        Route::delete('{resource}/{id}', [ResourceController::class, 'destroy'])->name('resource.destroy');
    });
});
