<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth',
    'controller' => AuthController::class,
    'middleware' => 'throttle:60,1',
], function () {
    Route::post('register', 'register')
        ->name('auth.register');
    Route::post('login', 'login')
        ->name('auth.login');
    Route::post('logout', 'logout')
        ->name('auth.logout')
        ->middleware('auth:api');
    Route::get('refresh', 'refresh')
        ->name('auth.refresh');
});
