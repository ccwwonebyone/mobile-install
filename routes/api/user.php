<?php

use Illuminate\Support\Facades\Route;

Route::get('user/current', [\App\Http\Controllers\UserController::class, 'getCurrent']);
