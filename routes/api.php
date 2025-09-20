<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiTripController;
use App\Http\Controllers\Api\FailedJobController;
use App\Http\Controllers\Api\LocationApiController;
use Illuminate\Support\Facades\Route;

// Existing Auth API routes
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);
Route::get('locations', [LocationApiController::class, 'index']);
Route::post('/failedJobs', [FailedJobController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::post('/profile', [ApiAuthController::class, 'profile']);
    Route::post('/change-password', [ApiAuthController::class, 'changePassword']);

    // ✅ New Trip API routes (appended here)
    Route::get('/trip/customers', [ApiTripController::class, 'fetchCustomer']);
    Route::get('/tourDetails', [ApiTripController::class, 'getTourDetails']);
    Route::get('/trips', [ApiTripController::class, 'index']);
    Route::post('/trips/store', [ApiTripController::class, 'storeTrip']);
    Route::post('/trips/log-point', [ApiTripController::class, 'logPoint']);
    Route::get('/trips/{tripId}/logs', [ApiTripController::class, 'logs']);
    Route::post('/trips/{tripId}/complete', [ApiTripController::class, 'completeTrip']);
    Route::get('/trip/active', [ApiTripController::class, 'lastActive']);
    Route::get('/trip/{tripId}/detail', [ApiTripController::class, 'showTrip']);
    Route::post('/trip/close', [ApiTripController::class, 'close']);
});
