<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/user/update', [UserController::class, 'changeProfileInformation']);
Route::post('/user/update/password', [UserController::class, 'updatePassword']);
Route::post('/user/delete', [UserController::class, 'deleteUser']);
Route::post('/user/logout', [AuthController::class, 'logout']);
Route::get('/products', [ProductController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/cart', [CartController::class, 'store']);
Route::get('/get/cart', [CartController::class, 'index']);
Route::post('/cart/increase', [CartController::class, 'increaseQuantity']);
Route::post('/cart/decrease', [CartController::class, 'decreaseQuantity']);
Route::post('/cart/delete', [CartController::class, 'removeItem']);