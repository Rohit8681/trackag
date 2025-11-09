<?php
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiTripController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\FailedJobController;
use App\Http\Controllers\Api\LocationApiController;
use App\Http\Controllers\APi\PartyController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TenantAuthenticate;

// Existing Auth API routes
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/login_new', [ApiAuthController::class, 'login_new']);
Route::get('locations', [LocationApiController::class, 'index']);
Route::post('/failedJobs', [FailedJobController::class, 'store']);

Route::middleware([TenantAuthenticate::class])->group(function () {
    // return response()->json(['ok' => true]);
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::post('/profile', [ApiAuthController::class, 'profile']);
    Route::post('/change-password', [ApiAuthController::class, 'changePassword']);

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
    Route::post('/expenses-store', [ExpenseController::class, 'store']);
    Route::get('/get-expenses', [ExpenseController::class, 'index']);
    Route::get('/get-party-visits', [PartyController::class, 'index']);
    Route::post('/party-visits-store', [PartyController::class, 'partyVisitsStore']);
    Route::post('/new-party-store', [PartyController::class, 'newPartyStore']);
});
