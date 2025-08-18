<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/profile', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth Routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware(['auth:api'])->post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Event Routes
Route::get('/event', [EventController::class, 'index']);
Route::get('/event/{id}', [EventController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);

// Verify Ticket
Route::post('/ticket-verify', [TicketController::class, 'verifyTicket']);
Route::post('/ticket-check', [TicketController::class, 'checkTicket']);


// Mail Routes
Route::post('/send-mail', [MailController::class, 'send'])->name('send.mail');

Route::post('/send-bookingTicket-email', [MailController::class, 'sendBookingEmail']);

// Ticket Download Route
Route::get('/ticket/download/{id}', [TicketController::class, 'download'])->name('ticket.download');

// Protected Routes
Route::middleware(['auth:api'])->group(function () {
    //Admin Only Permissions
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])
        ->middleware('permission:view admin dashboard');

    // User Manage
    Route::get('/user', [AdminController::class, 'index']);
    Route::get('/user/dashboard', [UserController::class, 'dashboard']);
    Route::get('/user/{id}', [AdminController::class, 'show']);
    Route::post('/users', [AdminController::class, 'store']);
    Route::patch('/users/{id}', [AdminController::class, 'update']);
    Route::delete('/users/{id}', [AdminController::class, 'destroy']);

    // Ticket Manage
    Route::get('/ticket', [TicketController::class, 'index']);
    Route::get('/tickets', [TicketController::class, 'myTickets']);
    Route::get('/ticket/{id}', [TicketController::class, 'show']);
    Route::post('/ticket', [TicketController::class, 'store']);
    Route::patch('/ticket/{id}', [TicketController::class, 'update']);
    Route::delete('/ticket/{id}', [TicketController::class, 'destroy']);

    // Category Manage
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::patch('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // Organizer Permissions
    Route::get('organizer/dashboard', [OrganizerController::class, 'dashboard']);

    //Event Manage
    Route::get('/organizer-event', [EventController::class, 'myEvent']);
    Route::post('/event', [EventController::class, 'store']);
    Route::post('/events/assign-organizers', [EventController::class, 'assignOrganizers']);
    Route::patch('/event/{id}', [EventController::class, 'update']);
    Route::delete('/event/{id}', [EventController::class, 'destroy']);

    //Ticket Category CRUD
    Route::get('/ticket-category', [TicketCategoryController::class, 'index']);
    Route::get('/ticket-category/{id}', [TicketCategoryController::class, 'show']);
    Route::post('/ticket-category', [TicketCategoryController::class, 'store']);
    Route::patch('/ticket-category/{id}', [TicketCategoryController::class, 'update']);
    Route::delete('/ticket-category/{id}', [TicketCategoryController::class, 'destroy']);
});
