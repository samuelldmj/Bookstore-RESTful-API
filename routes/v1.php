<?php


use App\Http\Controllers\Api\v1\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function(){
    Route::post('/register', [AuthController::class, 'register'] );
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});
