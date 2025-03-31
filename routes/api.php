<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::group([
    'controller' => AuthController::class
], function () {

    Route::post('register', 'register');
    Route::post('login', 'login');

    Route::post('logout', 'logout')->middleware('auth:sanctum');
});

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('categories', CategoryController::class);
});