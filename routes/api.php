<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;

// 🔓 Public Routes
Route::post('/login', [UserController::class, 'login']);
Route::post('/users', [UserController::class, 'store']);

// 🔒 Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Tickets API
    Route::post('tickets', [TicketController::class, 'store']);
    Route::get('tickets', [TicketController::class, 'getAllTickets']);
    Route::put('tickets/{id}', [TicketController::class, 'updateTicket']);
    Route::post('tickets/{id}/acknowledge', [TicketController::class, 'acknowledge']);
});
