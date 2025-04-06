<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\ProtectedAuthMiddleware;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/restaurants', [RestaurantController::class, 'getAll']);
Route::get('/restaurants/list', [RestaurantController::class, 'getAlllist']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/restaurants', [RestaurantController::class, 'store']);
// });

Route::middleware('auth.jwt')->group(function () {
    // auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    
    // restaurant
    Route::get('/restaurants/{id}', [RestaurantController::class, 'getOne']);
    Route::post('/restaurants', [RestaurantController::class, 'createList']);
    Route::put('/restaurants/{id}', [RestaurantController::class, 'update']);
    Route::delete('/restaurants/{id}', [RestaurantController::class, 'destroy']);
});
