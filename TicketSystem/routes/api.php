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






Route::prefix('admin')->middleware(['auth:api', 'role:admin'])->group(function () {
    //Admin Dashboard
    Route::get('/test', function () {
        return response()->json([
            "status" => true,
            "message" => "admin API Successfully Work"
        ]);
    });
});

Route::prefix('organizer')->middleware(['auth:api', 'role:organizer'])->group(function () {
    //organizer Dashboard
    Route::get('/test', function () {
        return response()->json([
            "status" => true,
            "message" => "organizer API Successfully Work"
        ]);
    });
});

Route::prefix('user')->middleware(['auth:api', 'role:user'])->group(function () {
    //user Dashboard
    Route::get('/test', function () {
        return response()->json([
            "status" => true,
            "message" => "User API Successfully Work"
        ]);
    });
});
