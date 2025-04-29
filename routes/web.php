<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ProductDetails;
use App\Livewire\ProductList;

Route::get('/', ProductList::class)->name('products');

Route::get('/products/{id}', ProductDetails::class)->name('products.details');

