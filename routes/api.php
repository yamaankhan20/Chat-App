<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\PusherController;
use Illuminate\Support\Facades\Broadcast;

Route::prefix('chat')->group(function () {
    Route::get('/get-online-users', [UserController::class,'online_user']);
    Route::post('/send-message', [MessageController::class,'store']);
    Route::get('/get-messages', [MessageController::class,'index']);
});

Route::post('/pusher/auth', [PusherController::class, 'authenticate']);
Broadcast::routes(['middleware' => ['auth:sanctum']]);
