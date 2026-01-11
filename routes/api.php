<?php
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiTripController;
use App\Http\Controllers\Api\CommanController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\FailedJobController;
use App\Http\Controllers\Api\LocationApiController;
use App\Http\Controllers\Api\PartyController;
use App\Http\Controllers\Api\PartyPaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TenantAuthenticate;

// Existing Auth API routes
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/login_new', [ApiAuthController::class, 'login_new']);
Route::get('locations', [LocationApiController::class, 'index']);
Route::post('/failedJobs', [FailedJobController::class, 'store']);
Route::get('/apk-list', [ApiAuthController::class, 'getApklist']);

Route::middleware([TenantAuthenticate::class])->group(function () {
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
    Route::post('/trip/gps-store', [ApiTripController::class, 'gpsStore']);
    Route::get('/trip/my-trips', [ApiTripController::class, 'getMyTrips']);
    //expense api
    Route::get('/get-expenses', [ExpenseController::class, 'index']);
    Route::post('/expenses-store', [ExpenseController::class, 'storeOrUpdate']);
    Route::delete('/expenses-delete/{id}', [ExpenseController::class, 'destroy']);
    Route::get('/ta-da-report', [ExpenseController::class, 'taDaReport']);

    //party vist api
    Route::get('/get-party-visits', [PartyController::class, 'index']);
    Route::post('/party-visits-store', [PartyController::class, 'partyVisitsStore']);
    Route::post('/party-visit-checkout', [PartyController::class, 'partyVisitCheckout']);

    //new party api
    Route::get('/party-list', [PartyController::class, 'getPartyList']);
    Route::post('/new-party-store', [PartyController::class, 'newPartyStore']);

    Route::get('/states', [LocationApiController::class, 'getStates']);
    Route::get('/districts/{state_id}', [LocationApiController::class, 'getDistricts']);
    Route::get('/tehsils/{district_id}', [LocationApiController::class, 'getTehsils']);

    Route::get('/party-payment-list', [PartyPaymentController::class, 'index']);
    Route::post('/party-payment-store', [PartyPaymentController::class, 'store']);

    Route::get('price-list', [CommanController::class, 'priceList']);
    Route::get('brochures', [CommanController::class, 'brochures']);
    Route::get('messages', [CommanController::class, 'messages']);




});
