<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Livewire\ProductDetails;
use App\Livewire\ProductList;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;

Route::prefix('admin')->group(function () {
    Route::get('/', ProductList::class)->name('products');

});

ProductController::routes();
BrandController::routes();
CategoryController::routes();
ChatController::routes();

Route::get('/products/{id}', ProductDetails::class)->name('products.details');
Route::get('/', fn() => view('app'))->name('app');

