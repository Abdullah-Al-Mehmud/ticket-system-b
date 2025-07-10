<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//User Register
Route::post('/register', [AuthController::class, 'register'])->name('register');

//Login System
Route::post('/login', [AuthController::class, 'login'])->name('login');
