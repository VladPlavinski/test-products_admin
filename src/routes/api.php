<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', [ProductController::class, 'index'])->name('api.products.index');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('api.products.show');

Route::get('/categories', [CategoryController::class, 'index'])->name('api.categories.index');
