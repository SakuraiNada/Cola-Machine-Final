<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');
Route::delete('/products/async/{id}', [ProductController::class, 'destroyAsync'])->name('product.destroyAsync');
Route::patch('/products/{id}', [ProductController::class, 'update'])->name('product.update');

Route::get('/product-list/registration', [ProductController::class, 'create'])->name('product.registration.create');
Route::post('/product-list/registration', [ProductController::class, 'store'])->name('product.registration.store');
Route::get('/product-list', [ProductController::class, 'index'])->name('product.list');
Route::get('products/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');

Route::get('/products/search', [ProductController::class, 'searchForm'])->name('product.search.form');
Route::get('/products', [ProductController::class, 'search'])->name('product.search');
Route::get('/product', [ProductController::class, 'index'])->name('product.index');










