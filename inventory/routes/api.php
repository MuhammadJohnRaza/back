<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\StockMovementController;
use Illuminate\Support\Facades\Route;


// Public routes — login is rate-limited to 5 attempts per minute per IP
// to prevent brute-force attacks. Exceeding the limit returns HTTP 429.
Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])
        ->name('api.login')
        ->middleware('throttle:5,1');
});

// Protected routes (Sanctum)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Auth endpoints
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->name('me');

    // Categories CRUD
    Route::apiResource('categories', CategoryController::class)->names([
        'index'   => 'api.categories.index',
        'store'   => 'api.categories.store',
        'show'    => 'api.categories.show',
        'update'  => 'api.categories.update',
        'destroy' => 'api.categories.destroy',
    ]);

    // Products CRUD
    Route::apiResource('products', ProductController::class)->names([
        'index'   => 'api.products.index',
        'store'   => 'api.products.store',
        'show'    => 'api.products.show',
        'update'  => 'api.products.update',
        'destroy' => 'api.products.destroy',
    ]);

    // Stock Movements
    Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
    Route::post('/stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');
    Route::get('/stock-movements/{stockMovement}', [StockMovementController::class, 'show'])->name('stock-movements.show');

    // Reports
    Route::get('/reports/low-stock', [ReportController::class, 'lowStock'])->name('reports.low-stock');
    Route::get('/reports/summary', [ReportController::class, 'summary'])->name('reports.summary');
});
