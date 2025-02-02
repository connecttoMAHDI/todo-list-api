<?php

use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $res = new class
    {
        use HttpResponses;
    };

    return $res->successResponse(
        'Welcome to Todo List API'
    );
});
