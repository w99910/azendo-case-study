<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController
{
    public static function routes()
    {
        Route::post('/products', [ProductController::class, 'index'])->name('products.index');
        Route::post('/product/{id}', [ProductController::class, 'show'])->name('products.show');
        Route::post('/product/create', [ProductController::class, 'store'])->name('products.store');
        Route::post('/product/update', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::post('/products/for-you', [ProductController::class, 'getProductsForYou'])->name('products.for-you');
        Route::post('/products/popular', [ProductController::class, 'getPopularProducts'])->name('products.popular');
        Route::post('/products/of-the-day', [ProductController::class, 'getProductOfTheDay'])->name('products.of-the-day');
    }

    public function __construct(private ProductService $productService)
    {
    }

    public function index(Request $request)
    {
        $products = $this->productService->searchProducts($request->all());
        return response()->json($products);
    }

    public function show($id)
    {
        $product = $this->productService->getProduct($id);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $product = $this->productService->createProduct($request->all());
        return response()->json($product);
    }


    public function update(Request $request, $id)
    {
        $product = $this->productService->updateProduct($id, $request->all());
        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = $this->productService->deleteProduct($id);
        return response()->json($product);
    }

    public function getProductsForYou()
    {
        $products = $this->productService->getProductsForYou();
        return response()->json($products);
    }

    public function getPopularProducts()
    {
        $products = $this->productService->getPopularProducts();
        return response()->json($products);
    }

    public function getProductOfTheDay()
    {
        $product = $this->productService->getProductOfTheDay();
        return response()->json($product);
    }
}
