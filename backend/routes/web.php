<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

// categories
Route::get('/categories/{id?}', [CategoryController::class, "index"]);
Route::get('/categories/get/withoutChild', [CategoryController::class, 'getWithoutChild']);

//products
Route::apiResource('products', ProductController::class);
Route::get('/products/filter/{id}', [ProductController::class, 'getFiltersAndProducts']);
Route::post('/products/store/xml', [ProductController::class, 'storeXml']);
Route::get('/api/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});
