<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ColekController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\SchoolController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminLoginController;

Route::middleware('guest')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);

    Route::get('/schools', [SchoolController::class, 'index']);
    Route::get('/schools/{school}', [SchoolController::class, 'show']);

    Route::post('/admin/login', AdminLoginController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class)->except('store', 'destroy');

    Route::put('/users/{user}/password', [UserController::class, 'updatePassword']);

    Route::post('/logout', LogoutController::class);

    Route::post('/users/{user}/colek', ColekController::class);
});

Route::middleware(['auth:sanctum', EnsureUserIsAdmin::class])->group(function () {
    Route::post('/schools', [SchoolController::class, 'store']);
    Route::put('/schools/{school}/', [SchoolController::class, 'update']);
    Route::delete('/schools/{school}', [SchoolController::class, 'destroy']);
    Route::put('/admin/users/{user}', [UserController::class, 'adminUpdate']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});