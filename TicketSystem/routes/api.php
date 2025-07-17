<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/profile', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ✅ Auth Routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware(['auth:api'])->post('/logout', [AuthController::class, 'logout'])->name('logout');

// ✅ Public Event Routes
Route::get('/event', [EventController::class, 'index']);
Route::get('/event/{id}', [EventController::class, 'show']);

// ✅ Protected Routes
Route::middleware(['auth:api'])->group(function () {

    // ✅ Admin Only Permissions

    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])
        ->middleware('permission:view admin dashboard');

    // User Manage
    Route::get('/user', [AdminController::class, 'index'])->middleware('permission:manage users admin');
    Route::get('/user/{id}', [AdminController::class, 'show'])->middleware('permission:manage users admin');
    Route::post('/users', [AdminController::class, 'store'])->middleware('permission:manage users admin');
    Route::patch('/users/{id}', [AdminController::class, 'update'])->middleware('permission:manage users admin');
    Route::delete('/users/{id}', [AdminController::class, 'destroy'])->middleware('permission:manage users admin');

    // Ticket Manage
    Route::get('/ticket', [TicketController::class, 'index'])->middleware('permission:manage tickets admin');
    Route::patch('/ticket/{id}', [TicketController::class, 'update'])->middleware('permission:manage tickets admin');
    Route::delete('/ticket/{id}', [TicketController::class, 'destroy'])->middleware('permission:manage tickets admin');

    // Category Manage
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('permission:create categories admin');
    Route::patch('/categories/{id}', [CategoryController::class, 'update'])->middleware('permission:edit categories admin');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->middleware('permission:delete categories admin');

    Route::get('/categories', [CategoryController::class, 'index'])->middleware('permission:view categories admin');
    Route::get('/categories/{id}', [CategoryController::class, 'show'])->middleware('permission:delete categories admin');

    Route::get('/event', [EventController::class, 'index'])->middleware('permission:view events admin');


    // ✅ Organizer Permissions

    Route::get('organizer/dashboard', [OrganizerController::class, 'dashboard'])
        ->middleware('permission:view organizer dashboard');


    Route::post('/event', [EventController::class, 'store'])->middleware('permission:create events organizer');
    Route::get('/events', [EventController::class, 'myEvent'])->middleware('permission:view events organizer');
    Route::patch('/event/{id}', [EventController::class, 'update'])->middleware('permission:edit events organizer');
    Route::delete('/event/{id}', [EventController::class, 'destroy'])->middleware('permission:delete events organizer');


    // ✅ User Permissions

    Route::get('user/dashboard', [UserController::class, 'dashboard'])
        ->middleware('permission:view user dashboard',);

    Route::post('/ticket', [TicketController::class, 'store'])->middleware('permission:create ticket user');
    Route::get('/tickets', [TicketController::class, 'myTickets'])->middleware('permission:View ticket user');
});
