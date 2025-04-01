<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/restaurants', [RestaurantController::class, 'store']);
// });

Route::middleware('auth.jwt')->group(function () {
    // auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    
    // restaurant
    Route::get('/restaurants', [RestaurantController::class, 'getAll']);
    Route::get('/restaurants/list', [RestaurantController::class, 'getAlllist']);
    Route::get('/restaurants/{id}', [RestaurantController::class, 'getOne']);
    Route::post('/restaurants', [RestaurantController::class, 'createList']);
    Route::put('/restaurants/{id}', [RestaurantController::class, 'update']);
    Route::delete('/restaurants/{id}', [RestaurantController::class, 'destroy']);
});
