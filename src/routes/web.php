<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/products', function () {
    return Inertia::render('Products', []);
})->name('products');

Route::get('/auth', function () {
    return Inertia::render('Products', []);
})->name('products');

Route::get('/product/{id}', function ($id) {
    return Inertia::render('Product', [
        'id' => $id
    ]);
})->name('product');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
