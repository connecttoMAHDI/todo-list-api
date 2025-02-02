<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::apiResource('todos', TodoController::class)
        ->middleware('throttle:60,1');
});
