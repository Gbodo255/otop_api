<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    // Auth publiques
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::post('/storeUser', [UserController::class, 'store'])->name('storeUser');

    // Auth protégées
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/forgot-password', [UserController::class, 'forgotPassword'])->name('forgotPassword');
        Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('resetPassword');
        Route::post('/logout', [UserController::class, 'logout'])->name('logout');

        // User management
        Route::post('/showUser', [UserController::class, 'show'])->name('showUser');
        Route::put('/updateUser/{user}', [UserController::class, 'update'])->name('updateUser');
        Route::get('/users', [UserController::class, 'index'])->name('users');
    });
    
});


