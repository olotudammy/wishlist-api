<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\WishlistController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth:sanctum')->group(function () {
    /* auth */
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');

    /* products */
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    /* wishlist */
    Route::prefix('wishlist')->group(function () {
        Route::get('', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('', [WishlistController::class, 'store'])->name('wishlist.store');
        Route::delete('/{product_id}', [WishlistController::class, 'destroy'])
            ->where('product_id', '[1-9][0-9]*')
            ->name('wishlist.destroy');
    });
});
