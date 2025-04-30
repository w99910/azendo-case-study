<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ProductDetails;
use App\Livewire\ProductList;

Route::get('/', function () {
    return view('products');
});
