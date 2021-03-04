<?php

use Illuminate\Support\Facades\Route;

Route::get('qiniu/token', [\App\Http\Controllers\Qiniu\QiniuController::class, 'index']);
Route::get('ding/h5_sign', [\App\Http\Controllers\DingTalk\DingTalkController::class, 'sign']);
