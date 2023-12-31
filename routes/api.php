<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
Route::get('/products',[ProductController::class,'products']);
Route::get('/products/recommended',[ProductController::class,'recommended']);
Route::get('/products/featured',[ProductController::class,'featured']);
Route::get('/products/{id}',[ProductController::class,'product']);
Route::post('/signup',[UserController::class,'signup']);
Route::post('/signin',[UserController::class,'signin']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/signout',[UserController::class,'signout']);
    Route::get('/user/{id}',[UserController::class,'user']);
    Route::post('/user/{id}/edit',[UserController::class,'edit']);
    Route::get('/usercount',[UserController::class,'getUserCount'])->middleware('admin');
    Route::get('/productcount',[ProductController::class,'getProductCount'])->middleware('admin');
    Route::get('/colors',[ColorController::class,'colors'])->middleware('admin');
    Route::get('/categories',[CategoryController::class,'categories'])->middleware('admin');
    Route::post('/newproduct',[ProductController::class,'newproduct'])->middleware('admin');
    Route::post('/editproduct/{id}',[ProductController::class,'editProduct'])->middleware('admin');
    Route::delete('/deleteproduct/{id}',[ProductController::class,'deleteProduct'])->middleware('admin');
});


