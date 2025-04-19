<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ColekController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\RegisterController;

Route::middleware('guest')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);

    Route::get('/schools', [SchoolController::class, 'index']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class)->except('store');

    Route::put('/users/{user}/password', [UserController::class, 'updatePassword']);

    Route::post('/logout', LogoutController::class);

    Route::post('/users/{user}/colek', ColekController::class);
});

Route::middleware(['auth:sanctum', EnsureUserIsAdmin::class])->group(function () {
    Route::post('/schools', [SchoolController::class, 'store']);
});