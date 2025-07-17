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

Route::get('/user', function (Request $request) {
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
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->middleware(['role:admin', 'permission:view dashboard']);

        // User Manage
        Route::get('/user', [AdminController::class, 'index'])->middleware('permission:manage users');
        Route::get('/user/{id}', [AdminController::class, 'show'])->middleware('permission:manage users');
        Route::post('/users', [AdminController::class, 'store'])->middleware('permission:manage users');
        Route::patch('/users/{id}', [AdminController::class, 'update'])->middleware('permission:manage users');
        Route::delete('/users/{id}', [AdminController::class, 'destroy'])->middleware('permission:manage users');

        // Ticket Manage
        Route::get('/ticket', [TicketController::class, 'index'])->middleware(['role:admin', 'permission:manage tickets']);
        Route::patch('/ticket/{id}', [TicketController::class, 'update'])->middleware(['role:admin', 'permission:manage tickets']);
        Route::delete('/ticket/{id}', [TicketController::class, 'destroy'])->middleware(['role:admin', 'permission:manage tickets']);

        // Category Manage
        Route::post('/categories', [CategoryController::class, 'store'])->middleware('permission:create categories');
        Route::patch('/categories/{id}', [CategoryController::class, 'update'])->middleware('permission:edit categories');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->middleware('permission:delete categories');

        Route::get('/categories', [CategoryController::class, 'index'])->middleware(['role:admin', 'permission:view categories']);
        Route::get('/categories/{id}', [CategoryController::class, 'show'])->middleware(['role:admin', 'permission:view categories']);

        Route::get('/event', [EventController::class, 'index'])->middleware(['role:admin', 'permission:view events']);
    });

    // ✅ Organizer Permissions
    Route::prefix('organizer')->group(function () {
        Route::get('/dashboard', [OrganizerController::class, 'dashboard'])
            ->middleware(['role:organizer', 'permission:view dashboard']);

        Route::post('/event', [EventController::class, 'store'])->middleware('permission:create events');
        Route::get('/events', [EventController::class, 'myEvent'])->middleware(['role:organizer', 'permission:view events']);
        Route::patch('/event/{id}', [EventController::class, 'update'])->middleware('permission:edit events');
        Route::delete('/event/{id}', [EventController::class, 'destroy'])->middleware('permission:delete events');
    });

    // ✅ User Permissions
    Route::prefix('user')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])
            ->middleware(['role:user', 'permission:view dashboard']);

        Route::post('/ticket', [TicketController::class, 'store'])->middleware(['role:user', 'permission:manage tickets']); // Optional check
        Route::get('/tickets', [TicketController::class, 'myTickets'])->middleware(['role:user', 'permission:manage tickets']);
    });
});
