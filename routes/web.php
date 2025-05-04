<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Livewire\ProductDetails;
use App\Livewire\ProductList;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use Inertia\Inertia;

Route::prefix('admin')->group(function () {
    Route::get('/', ProductList::class)->name('products');

});

ProductController::routes();
BrandController::routes();
CategoryController::routes();
ChatController::routes();

Route::get('/admin/products/{id}', ProductDetails::class)->name('admin.products.details');
Route::get('/', fn() => Inertia::render('app'))->name('app');

