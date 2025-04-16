<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);

    Route::apiResource('schools', SchoolController::class)->only('index');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class)->except('store');

    Route::put('/users/{user}/password', [UserController::class, 'updatePassword']);

    Route::put('/logout', LogoutController::class);
});
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
