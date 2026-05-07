<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/profile', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth Routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware(['auth:api'])->post('/logout', [AuthController::class, 'logout'])->name('logout');

// Email Verification Routes
Route::get('/email/verify/{token}', [EmailVerificationController::class, 'verify']);
Route::post('/email/resend', [EmailVerificationController::class, 'resend']);

// Public Event Routes
Route::get('/event', [EventController::class, 'index']);
Route::get('/event/{id}', [EventController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);

Route::middleware(['auth:api'])->group(function () {
    // Admin Dashboard
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])
        ->middleware('permission:view admin dashboard');

    // User Management CRUD
    Route::get('/user', [AdminController::class, 'index']);
    Route::get('/user/{id}', [AdminController::class, 'show']);
    Route::post('/users', [AdminController::class, 'store']);
    Route::patch('/users/{id}', [AdminController::class, 'update']);
    Route::delete('/users/{id}', [AdminController::class, 'destroy']);

    // Category Management CRUD
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::patch('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // Event Management CRUD
    Route::get('/organizer-event', [EventController::class, 'myEvent']);
    Route::post('/event', [EventController::class, 'store']);
    Route::post('/events/assign-organizers', [EventController::class, 'assignOrganizers']);
    Route::patch('/event/{id}', [EventController::class, 'update']);
    Route::delete('/event/{id}', [EventController::class, 'destroy']);

    // Ticket Category CRUD
    Route::get('/ticket-category', [TicketCategoryController::class, 'index']);
    Route::get('/ticket-category/{id}', [TicketCategoryController::class, 'show']);
    Route::post('/ticket-category', [TicketCategoryController::class, 'store']);
    Route::patch('/ticket-category/{id}', [TicketCategoryController::class, 'update']);
    Route::delete('/ticket-category/{id}', [TicketCategoryController::class, 'destroy']);

    // Ticket Type CRUD
    Route::get('/ticket-type', [TicketTypeController::class, 'index']);
    Route::get('/ticket-type/{id}', [TicketTypeController::class, 'show']);
    Route::post('/ticket-type', [TicketTypeController::class, 'store']);
    Route::patch('/ticket-type/{id}', [TicketTypeController::class, 'update']);
    Route::delete('/ticket-type/{id}', [TicketTypeController::class, 'destroy']);

    // Ticket Management CRUD
    Route::get('/ticket', [TicketController::class, 'index']);
    Route::get('/tickets', [TicketController::class, 'myTickets']);
    Route::get('/ticket/{id}', [TicketController::class, 'show']);
    Route::post('/ticket', [TicketController::class, 'store']);
    Route::patch('/ticket/{id}', [TicketController::class, 'update']);
    Route::delete('/ticket/{id}', [TicketController::class, 'destroy']);
    // Verify Ticket
    Route::post('/ticket-verify', [TicketController::class, 'verifyTicket']);
    Route::post('/ticket-check', [TicketController::class, 'checkTicket']);
    // Ticket Download Route
    Route::get('/ticket/download/{id}', [TicketController::class, 'download'])->name('ticket.download');
    // Booking Ticket Email
    Route::post('/send-bookingTicket-email', [MailController::class, 'sendBookingEmail']);
    // Mail Test Routes
    Route::post('/send-mail', [MailController::class, 'send'])->name('send.mail');

    // Coupon Management CRUD
    Route::get('/coupon', [CouponController::class, 'getCoupon']);
    Route::get('/coupon/{id}', [CouponController::class, 'getSingleCoupon']);
    Route::post('/coupon', [CouponController::class, 'addCoupon']);
    Route::patch('/coupon/{id}', [CouponController::class, 'updateCoupon']);
    Route::delete('/coupon/{id}', [CouponController::class, 'deleteCoupon']);
});

// Public Coupon Validation
Route::post('/coupon/validate', [CouponController::class, 'validateCoupon']);
