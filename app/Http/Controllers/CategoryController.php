<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Route;
class CategoryController
{
    public static function routes()
    {
        Route::post('/categories', [CategoryController::class, 'index'])->name('categories');
    }

    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }
}
