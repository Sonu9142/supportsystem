<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;

// 🔓 Public Routes
Route::post('/login', [UserController::class, 'login']);
Route::post('/users', [UserController::class, 'store']);
Route::post('tickets', [TicketController::class, 'store']);

// 🔒 Protected Routes
Route::middleware('auth:sanctum')->group(function () {

    // ✅ Admin creates new developer
    // Route::post('/admin/create-developer', [UserController::class, 'createDeveloper'])
        // ->middleware('is_admin');

        Route::post('/admin/create-developer', [UserController::class, 'createDeveloper']);


    // ✅ Admin manually assigns or reassigns ticket
    // Route::put('/admin/tickets/{id}/assign', [TicketController::class, 'assignTicket'])->middleware('is_admin');
    Route::put('/admin/tickets/{id}/assign', [TicketController::class, 'assignTicket']);

    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Tickets API

    Route::get('tickets', [TicketController::class, 'getAllTickets']);
    Route::put('tickets/{id}', [TicketController::class, 'updateTicket']);
    Route::post('tickets/{id}/acknowledge', [TicketController::class, 'acknowledge']);
});
