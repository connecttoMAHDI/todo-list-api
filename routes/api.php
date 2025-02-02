<?php

use Illuminate\Support\Facades\Route;

// API v1 routes
Route::group([
    'prefix' => 'v1',
], function () {
    require_once __DIR__.'/v1/auth.php';
    require_once __DIR__.'/v1/todo.php';
});
