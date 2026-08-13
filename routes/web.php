<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/colecciones', [StorefrontController::class, 'collections'])->name('collections.index');
Route::get('/colecciones/{slug}', [StorefrontController::class, 'collection'])->name('collections.show');
Route::get('/productos/{slug}', [StorefrontController::class, 'product'])->name('products.show');
Route::get('/guia-de-tallas', [StorefrontController::class, 'sizes'])->name('sizes');
Route::get('/nosotros', [StorefrontController::class, 'about'])->name('about');
Route::get('/contacto', [StorefrontController::class, 'contact'])->name('contact');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:6,1')->name('login.store');

    Route::middleware('auth')->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
        Route::resource('categories', CategoryController::class)->except('show');
        Route::delete('products/bulk', [ProductController::class, 'bulkDestroy'])->name('products.bulk-destroy');
        Route::resource('products', ProductController::class)->except('show');
        Route::middleware('owner')->group(function () {
            Route::resource('users', UserController::class)->except('show');
        });
    });
});
