<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('check.auth')->group(function () {
    require_once 'api/talk.php';
    require_once 'api/common.php';
    require_once 'api/user.php';
    require_once 'api/install.php';
});

Route::post('login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
