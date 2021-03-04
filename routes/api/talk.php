<?php

use Illuminate\Support\Facades\Route;

Route::prefix('talk')->group(function () {
//    Route::resource('/', \App\Http\Controllers\Talk\TalkController::class);
    Route::post('/', [\App\Http\Controllers\Talk\TalkController::class, 'store']);
    Route::get('/', [\App\Http\Controllers\Talk\TalkController::class, 'index']);
    Route::get('/{id}', [\App\Http\Controllers\Talk\TalkController::class, 'show']);
    Route::delete('/all', [\App\Http\Controllers\Talk\TalkController::class, 'delCommentAll']);
    Route::delete('/{id}', [\App\Http\Controllers\Talk\TalkController::class, 'destroy']);
    Route::put('{id}', [\App\Http\Controllers\Talk\TalkController::class, 'update']);
    Route::post('comment', [\App\Http\Controllers\Talk\TalkController::class, 'comment']);
    Route::delete('comment/{id}', [\App\Http\Controllers\Talk\TalkController::class, 'delComment']);
    Route::get('{id}/comment', [\App\Http\Controllers\Talk\TalkController::class, 'getComment']);
    Route::put('{id}/stop', [\App\Http\Controllers\Talk\TalkController::class, 'stop']);
    Route::put('{id}/restart', [\App\Http\Controllers\Talk\TalkController::class, 'restart']);
});
