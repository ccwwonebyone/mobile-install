<?php

use Illuminate\Support\Facades\Route;

Route::post('install', [\App\Http\Controllers\Install\InstallController::class, 'store']);
Route::get('install', [\App\Http\Controllers\Install\InstallController::class, 'index']);
Route::delete('install/{id}', [\App\Http\Controllers\Install\InstallController::class, 'destroy']);
Route::get('install/{id}', [\App\Http\Controllers\Install\InstallController::class, 'show']);
Route::put('install/{id}', [\App\Http\Controllers\Install\InstallController::class, 'update']);
