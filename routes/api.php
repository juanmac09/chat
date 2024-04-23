<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::middleware('auth:api')->get('/user/test', [UserController::class, 'test']);
Route::get('/user/token', [UserController::class, 'generateToken']);



