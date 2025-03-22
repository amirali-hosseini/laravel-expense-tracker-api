<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group([
    'controller' => AuthController::class
], function () {

    Route::post('register', 'register');
    Route::post('login', 'login');

    Route::post('logout', 'logout')->middleware('auth:sanctum');
});