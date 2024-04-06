<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;

Route::post('login', [ AuthController::class, 'postLogin' ])->middleware('api');

Route::middleware('auth:sanctum')->group(
    function () {
        // Rotas de Auth
        Route::get('device', [ DeviceController::class, 'getDevices' ]);
    }
);
