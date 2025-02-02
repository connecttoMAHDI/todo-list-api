<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth:api', 'throttle:60,1'],
], function () {
    Route::apiResource('todos', TodoController::class);
});
