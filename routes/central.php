<?php

use App\Http\Controllers\DepoController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\TaDaSlabController;
use App\Http\Controllers\VehicleTypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\TehsilController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\TravelModeController;
use App\Http\Controllers\PurposeController;
use App\Http\Controllers\TourTypeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\MonthlyController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

// ---------------- Central Domain Routes ----------------
Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });

    // Admin (central) routes
    Route::prefix('admin')->group(function () {
        // Public routes
        Route::get('login', [AdminController::class, 'create'])->name('admin.login');
        Route::post('login', [AdminController::class, 'store'])->name('auth.login.request');
        Route::get('logout', [AdminController::class, 'destroy'])->name('admin.logout');

        // Protected routes
        Route::middleware(['admin', 'last_seen'])->group(function () {
            Route::resource('users', UserController::class);
            Route::post('/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
            Route::post('/users/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
            Route::get('get-depos', [UserController::class,'getDepos'])->name('admin.get.depos');
            Route::get('get-user-depo-access', [UserController::class, 'getUserDepoAccess']);
            Route::get('get-user-state-access', [UserController::class, 'getUserStateAccess'])->name('admin.get.user-state-access');
            Route::post('save-user-state-access', [UserController::class, 'saveUserStateAccess'])->name('admin.save.user-state-access');

            Route::post('save-depo-access', [UserController::class, 'saveDepoAccess'])->name('admin.save.depo.access');
            Route::post('save-user-slab', [UserController::class, 'saveSlab'])->name('admin.save.user.slab');


            Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
            Route::resource('companies', CompanyController::class);
            Route::patch('companies/{id}/toggle', [CompanyController::class, 'toggle'])->name('companies.toggle');
            Route::resource('states', StateController::class);
            Route::post('/states/toggle-status', [StateController::class, 'toggleStatus'])->name('states.toggle-status');
            Route::resource('districts', DistrictController::class);
            Route::resource('cities', CityController::class);
            Route::resource('tehsils', TehsilController::class);
            Route::resource('roles', RoleController::class);
            Route::resource('/hr/designations', DesignationController::class);
            Route::post('/hr/designations/toggle-status', [DesignationController::class, 'toggleStatus'])->name('designations.toggle-status');

            Route::resource('depos', DepoController::class);
            Route::post('/depos/toggle-status', [DepoController::class, 'toggleStatus'])->name('depos.toggle-status');
            Route::get('ajax/get-districts', [DepoController::class, 'getDistricts'])->name('depos.get-districts');
            Route::get('ajax/get-tehsils', [DepoController::class, 'getTehsils'])->name('depos.get-tehsils');

            Route::resource('holidays', HolidayController::class);
            Route::post('/holidays/toggle-status', [HolidayController::class, 'toggleStatus'])->name('holidays.toggle-status');

            Route::resource('leaves', LeaveController::class);
            Route::post('/leaves/toggle-status', [LeaveController::class, 'toggleStatus'])->name('leaves.toggle-status');

            Route::get('/get-districts/{state_id}', [UserController::class, 'getDistricts'])->name('get.districts');
            Route::get('/get-cities/{district_id}', [UserController::class, 'getCities'])->name('get.cities');
            Route::get('/get-tehsils/{city_id}', [UserController::class, 'getTehsils'])->name('get.tehsils');
            Route::get('/get-pincodes/{city_id}', [UserController::class, 'getPincodes'])->name('get.pincodes');

            Route::resource('vehicle-types', VehicleTypeController::class);
            Route::get('ta-da-slab', [TaDaSlabController::class, 'form'])->name('ta-da-slab.form');
            Route::post('ta-da-slab', [TaDaSlabController::class, 'save'])->name('ta-da-slab.save');



            // Route::prefix('trips')->group(function () {
            Route::resource('travelmode', TravelModeController::class)->names('travelmode');
            Route::resource('tourtype', TourTypeController::class)->names('tourtype');
            Route::resource('purpose', PurposeController::class)->names('purpose');
            Route::resource('trips', TripController::class)->names('trips');
            Route::post('/trips/{trip}/approve', [TripController::class, 'approve'])->name('trips.approve');
            Route::post('/admin/trips/{id}/complete', [TripController::class, 'completeTrip'])->name('trips.complete');
            Route::post('/trips/{trip}/toggle-status', [TripController::class, 'toggleStatus'])->name('trips.status.toggle');
            // });
        });
    });
});