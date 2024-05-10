<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::apiResource('categories', CategoryController::class);
Route::get('/products/category/{id}', [ProductController::class, 'showAllByPath']);
Route::get('/categories/showChildCategories/{id}', [CategoryController::class, 'showCategoryOrProductsData']);
Route::get('/categories/get/withoutChild', [CategoryController::class, 'getWithoutChild']);
Route::apiResource('products', ProductController::class);

Route::get('/api/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});
