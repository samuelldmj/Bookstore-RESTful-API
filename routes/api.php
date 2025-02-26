<?php


use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('/auth')->group(function(){
    Route::post('/register', [AuthController::class, 'register'] );
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('role:admin')->group(function () {
        Route::resource('/books', BookController::class)->except(['create', 'edit']);
    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});


Route::prefix('v1')->group(base_path('routes/v1.php'));