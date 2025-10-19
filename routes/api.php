<?php

use App\Http\Controllers\Api\sensorController;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\SensorController;



Route::get('/door-data', fn() => response()->json([
    'door' => 'closed',
    'lock' => 'engaged',
    'last_updated' => now()
]));

Route::get('/sunblock-data', fn() => response()->json([
    'shade' => 'deployed',
    'light' => 'filtered',
    'last_updated' => now()
]));


Route::apiResource('sensors', SensorController::class);