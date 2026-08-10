<?php

use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/colecciones', [StorefrontController::class, 'collections'])->name('collections.index');
Route::get('/colecciones/{slug}', [StorefrontController::class, 'collection'])->name('collections.show');
Route::get('/productos/{slug}', [StorefrontController::class, 'product'])->name('products.show');
Route::get('/guia-de-tallas', [StorefrontController::class, 'sizes'])->name('sizes');
Route::get('/nosotros', [StorefrontController::class, 'about'])->name('about');
Route::get('/contacto', [StorefrontController::class, 'contact'])->name('contact');
