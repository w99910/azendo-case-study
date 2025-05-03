<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Support\Facades\Route;
class BrandController
{
    public static function routes()
    {
        Route::post('/brands', [BrandController::class, 'index'])->name('brands');
    }

    public function index()
    {
        $brands = Brand::all();
        return response()->json($brands);
    }
}
