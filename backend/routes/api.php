<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\TrackedItemController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DocumentVersionController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'malaysia-sme-compliance-tracker',
    ]);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
});

Route::middleware('auth')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    Route::get('/items', [TrackedItemController::class, 'index']);
    Route::post('/items', [TrackedItemController::class, 'store']);
    Route::get('/items/{trackedItem}', [TrackedItemController::class, 'show']);
    Route::put('/items/{trackedItem}', [TrackedItemController::class, 'update']);
    Route::delete('/items/{trackedItem}', [TrackedItemController::class, 'destroy']);
    
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    
    Route::post('/items/{trackedItem}/renewal/start', [\App\Http\Controllers\Api\RenewalController::class, 'start']);
    
    Route::get('/items/{trackedItem}/versions', [DocumentVersionController::class, 'index']);
    Route::post('/items/{trackedItem}/documents', [DocumentVersionController::class, 'store']);
    Route::get('/items/{trackedItem}/versions/{version}', [DocumentVersionController::class, 'show']);
    Route::get('/items/{trackedItem}/versions/{version}/download', [DocumentVersionController::class, 'download'])->name('documents.download');
});
