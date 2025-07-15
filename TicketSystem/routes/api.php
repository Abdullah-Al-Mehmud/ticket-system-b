<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
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

Route::get('/event', [EventController::class, 'index']);
Route::get('/event/{id}', [EventController::class, 'show']);

Route::middleware(['auth:api'])->group(function () {
    // Public for all authenticated users

    //Admin Only
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        //Admin Dashboard

        //Ticket Update Mange Admin 
        Route::patch('/ticket/{id}', [TicketController::class, 'update']);
        Route::get('/ticket', [TicketController::class, 'index']);
        Route::delete('/ticket/{id}', [TicketController::class, 'destroy']);

        //Categories CRUD Operation Manage
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::patch('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        Route::get('/categories', [CategoryController::class, 'index']);
    });


    // Organizer only
    Route::prefix('organizer')->middleware(['role:organizer'])->group(function () {
        //organizer Dashboard
        Route::get('/test', function () {
            return response()->json([
                "status" => true,
                "message" => "organizer API Successfully Work"
            ]);
        });
        //Event Management CRUD
        Route::post('/event', [EventController::class, 'store']);
        Route::get('/events', [EventController::class, 'myEvent']);
        Route::patch('/event/{id}', [EventController::class, 'update']);
        Route::delete('/event/{id}', [EventController::class, 'destroy']);
    });

    //User Only
    Route::prefix('user')->middleware(['role:user'])->group(function () {
        //user Dashboard
        Route::get('/test', function () {
            return response()->json([
                "status" => true,
                "message" => "User API Successfully Work"
            ]);
        });
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
    //Tickets Management CRUD
    Route::post('/ticket', [TicketController::class, 'store']);
    Route::get('/tickets', [TicketController::class, 'myTickets']);
});
