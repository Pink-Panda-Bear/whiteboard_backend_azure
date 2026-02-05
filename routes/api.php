<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\StrokeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Auth rute (BEZ auth middleware)
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Protected rute (SA auth:sanctum middleware)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

    // Boards
    Route::get('/boards', [BoardController::class, 'index']);
    Route::post('/boards', [BoardController::class, 'store']);
    Route::get('/boards/{board}', [BoardController::class, 'show']);
    Route::delete('/boards/{board}', [BoardController::class, 'destroy']);
    Route::post('/boards/join', [BoardController::class, 'joinByCode']);

    // Strokes
    Route::get('/boards/{board}/strokes', [StrokeController::class, 'index']);
    Route::post('/boards/{board}/strokes', [StrokeController::class, 'store']);
    Route::put('/boards/{board}/strokes/{stroke}', [StrokeController::class, 'update']);
    Route::delete('/boards/{board}/strokes/{stroke}', [StrokeController::class, 'destroy']);

    // Messages
    Route::get('/boards/{board}/messages', [MessageController::class, 'index']);
    Route::post('/boards/{board}/messages', [MessageController::class, 'store']);
});