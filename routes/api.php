<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

// Rutas de autenticación
Route::post('v1/auth/login', [AuthController::class, 'login']);
Route::post('v1/auth/register', [AuthController::class, 'register']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // ...existing protected routes...
});

// Grupo de versión v1 con autenticación
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Ruta de perfil y logout
    Route::get('auth/user', [AuthController::class, 'user']);
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Grupo para endpoints públicos con límite de 60 peticiones por minuto
    Route::middleware('throttle:60,1')->group(function () {
        // Rutas de solo lectura
        Route::get('products', [ProductController::class, 'index']);
        Route::get('products/{product}', [ProductController::class, 'show']);
        Route::get('categories', [CategoryController::class, 'index']);
        Route::get('categories/{category}', [CategoryController::class, 'show']);
        Route::get('categories/{category}/products', [CategoryController::class, 'getProducts']);
    });

    // Grupo para endpoints de escritura con límite más restrictivo
    Route::middleware(['throttle:30,1'])->group(function () {
        // Rutas de escritura para productos
        Route::post('products', [ProductController::class, 'store']);
        Route::put('products/{product}', [ProductController::class, 'update']);
        Route::delete('products/{product}', [ProductController::class, 'destroy']);

        // Rutas de escritura para categorías
        Route::post('categories', [CategoryController::class, 'store']);
        Route::put('categories/{category}', [CategoryController::class, 'update']);
        Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
    });
});

// Redirección para rutas sin versionar
Route::fallback(function () {
    return response()->json([
        'message' => 'Por favor, utiliza la versión v1 de la API: /api/v1/',
        'success' => false
    ], 404);
});
