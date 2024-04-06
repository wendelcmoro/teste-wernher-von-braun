<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;

Route::post('login', [ AuthController::class, 'postLogin' ])->middleware('api');

Route::middleware('auth:sanctum')->group(
    function () {
        Route::get('device/{id?}', [ DeviceController::class, 'getDevices' ]);
        Route::post('device', [ DeviceController::class, 'storeDevice' ]);
        Route::put('device', [ DeviceController::class, 'storeDevice' ]);
        Route::delete('device', [ DeviceController::class, 'deleteDevice' ]);
    }
);
