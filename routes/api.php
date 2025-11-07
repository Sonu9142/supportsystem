<?php

use App\Http\Controllers\TicketController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZohoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('/user',function (){
//     return ["username"=>"aslwallets", "password"=>1234];
// });

// Route::apiResource('users', UserController::class);
Route::get('users', [UserController::class, 'index']);
Route::get('users/{id}', [UserController::class, 'show']);
Route::post('users', [UserController::class, 'store']);
Route::put('users/{id}', [UserController::class, 'update']);
Route::delete('users/{id}', [UserController::class, 'destroy']);




// Route::apiResource('categories', CategoryController::class);

Route::get('categories', [CategoryController::class, 'index']);           // List all categories
Route::get('categories/{id}', [CategoryController::class, 'show']);       // Show a single category
Route::post('categories', [CategoryController::class, 'store']);          // Create a new category
Route::put('categories/{id}', [CategoryController::class, 'update']);     // Update an existing category
Route::delete('categories/{id}', [CategoryController::class, 'destroy']); // Delete a category


// Route::apiResource('tickets', TicketController::class);

// Route::get('tickets', [TicketController::class, 'index']);           // List all tickets
// Route::get('tickets/{id}', [TicketController::class, 'show']);       // Show a single ticket
// Route::post('tickets', [TicketController::class, 'store']);          // Create a new ticket
// Route::put('tickets/{id}', [TicketController::class, 'update']);     // Update an existing ticket
// Route::delete('tickets/{id}', [TicketController::class, 'destroy']); // Delete a ticket
// Route::get('tickets/{id}/details', [TicketController::class, 'details']); //tickets details

Route::post('tickets', [TicketController::class, 'store']);
Route::get('tickets', [TicketController::class, 'getAllTickets']);
Route::put('tickets/{id}', [TicketController::class, 'updateTicket']);
Route::post('tickets/{id}/acknowledge', [TicketController::class, 'acknowledge']);





Route::get('/zoho/login', [ZohoController::class, 'redirectToZoho']);
Route::get('/callback.php', [ZohoController::class, 'handleZohoCallback']);
Route::get('/zoho/refresh', [ZohoController::class, 'refreshAccessToken']);