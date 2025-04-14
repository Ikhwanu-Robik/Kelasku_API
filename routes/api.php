<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\AuthenticatedSessionController;

Route::middleware('guest')->group(function () {
    Route::post('/register', RegisteredUserController::class);
    Route::post('/login', AuthenticatedSessionController::class);
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
