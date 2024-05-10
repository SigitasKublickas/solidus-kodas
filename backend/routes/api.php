<?php
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::apiResource('categories', CategoryController::class);
Route::get('/products/category/{id}', [ProductController::class, 'showAllByPath']);
Route::get('/categories/showChildCategories/{id}', [CategoryController::class, 'showCategoryOrProductsData']);
Route::get('/categories/get/withoutChild', [CategoryController::class, 'getWithoutChild']); // Expected route
Route::apiResource('products', ProductController::class);
Route::get('/api/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});
Route::post('login', function (Request $request) {
    $token = $request->session()->token();

    $token = csrf_token();
    return "Log In";
});

