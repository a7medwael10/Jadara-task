<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\StatController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/verify-code', 'verifyCode');
    Route::post('/resend-code', 'resendCode');
    Route::middleware('auth:sanctum')->post('/logout', 'logout');
});


Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');

Route::get('/stats', [StatController::class, 'getStats'])->middleware('auth:sanctum');
