<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return phpinfo();
//});
//
//Route::get('login', [UserController::class, 'login']);
//Route::middleware('check.auth')->get('test', [UserController::class, 'test']);
Route::get('/', function () {
    redirect()->route('/login');
});
Route::get('login', [\App\Http\Controllers\Auth\AuthController::class, 'loginView']);
Route::get('install', [\App\Http\Controllers\Install\InstallController::class, 'installView']);
Route::get('install_list', [\App\Http\Controllers\Install\InstallController::class, 'installListView']);
Route::get('install_detail', [\App\Http\Controllers\Install\InstallController::class, 'installDetail']);
Route::get('logon', [\App\Http\Controllers\Auth\AuthController::class, 'loginView']);

Route::get('test/{id}/test', [\App\Http\Controllers\UserController::class, 'test']);
