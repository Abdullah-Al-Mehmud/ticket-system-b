<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//User Register
Route::post('/register', [AuthController::class, 'register'])->name('register');

//Login System
Route::post('/login', [AuthController::class, 'login'])->name('login');

//Logout System
Route::middleware(['auth:api'])->post('/logout', [AuthController::class, 'logout'])->name('logout');






Route::prefix('admin')->middleware(['auth:api', 'role:admin'])->group(function () {
    //Admin Dashboard
    Route::get('/test', function () {
        try {
            return response()->json([
                "status" => true,
                "message" => "admin API Successfully Work"
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
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
    //Event Management CRUD
    Route::post('/event', [EventController::class, 'store']);
    Route::put('/event/{id}', [EventController::class, 'update']);
    Route::patch('/event/{id}', [EventController::class, 'patch']);
    Route::delete('/event/{id}', [EventController::class, 'destroy']);
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
