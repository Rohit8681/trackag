<?php

use App\Http\Controllers\ApkUploadController;
use App\Http\Controllers\BrochureController;
use App\Http\Controllers\DepoController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TaDaBillMasterController;
use App\Http\Controllers\TaDaSlabController;
use App\Http\Controllers\VehicleController;
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
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PartyPaymentController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;


Route::get('/logs', function () {
    // Optional: Add a simple password for security
    $accessKey = request('key'); // e.g. /logs?key=1234
    if ($accessKey !== '1234') {
        abort(403, 'Unauthorized access.');
    }

    $path = storage_path('logs/laravel.log');

    if (!File::exists($path)) {
        return "No log file found.";
    }

    // Read the log file
    $logs = File::get($path);

    // Display formatted logs
    return response("<pre style='background:#000;color:#0f0;padding:15px;font-size:13px;'>"
        . e($logs) . "</pre>");
});
// Route::get('/logs', function () {
//     $accessKey = request('key');

//     if ($accessKey !== '1234') {
//         abort(403, 'Unauthorized access.');
//     }

//     $path = storage_path('logs/laravel.log');

//     if (!File::exists($path)) {
//         return "No log file found.";
//     }

//     // Read only last 500 lines (safe & fast)
//     $lines = 500;
//     $output = shell_exec("tail -n $lines " . escapeshellarg($path));

//     return response("<pre style='background:#000;color:#0f0;padding:15px;font-size:13px;font-family: monospace;'>"
//         . e($output) . "</pre>");
// });

// ---------------- Central Domain Routes ----------------
Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });
    Route::get('/sample-download', function () {
        return redirect('https://testing.trackag.in/sample-files/customers_sample.xlsx');
    })->name('customers.sample-download');


    // Admin (central) routes
    Route::prefix('admin')->group(function () {
        Route::get('coming-soon', function () {
            return view('coming-soon');
        })->name('coming-soon');
        // Public routes
        Route::get('login', [AdminController::class, 'create'])->name('admin.login');
        Route::post('login', [AdminController::class, 'store'])->name('auth.login.request');
        Route::get('logout', [AdminController::class, 'destroy'])->name('admin.logout');

        // Protected routes
        Route::middleware(['admin', 'last_seen'])->group(function () {
            Route::get('/upload-apk', [ApkUploadController::class, 'create'])->name('apk.create');
            Route::post('/upload-apk', [ApkUploadController::class, 'store'])->name('apk.store');
            Route::resource('users', UserController::class);
            Route::post('/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
            Route::post('/users/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
            Route::get('get-depos', [UserController::class,'getDepos'])->name('admin.get.depos');
            Route::get('get-user-depo-access', [UserController::class, 'getUserDepoAccess']);
            Route::get('get-user-state-access', [UserController::class, 'getUserStateAccess'])->name('admin.get.user-state-access');
            Route::post('save-user-state-access', [UserController::class, 'saveUserStateAccess'])->name('admin.save.user-state-access');

            Route::post('save-depo-access', [UserController::class, 'saveDepoAccess'])->name('admin.save.depo.access');
            Route::post('save-user-slab', [UserController::class, 'saveSlab'])->name('admin.save.user.slab');
            Route::get('get-user-slab', [UserController::class, 'getUserSlab'])->name('admin.get-user-slab');

            Route::get('update-state',[AdminController::class,'updateState']);
            Route::get('update-permission',[AdminController::class,'updatePermission']);


            Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
            Route::post('change-password', [AdminController::class, 'changePassword'])->name('change-password');
            Route::resource('companies', CompanyController::class);
            Route::patch('companies/{id}/toggle', [CompanyController::class, 'toggle'])->name('companies.toggle');
            Route::resource('states', StateController::class);
            Route::post('/states/toggle-status', [StateController::class, 'toggleStatus'])->name('states.toggle-status');
            Route::resource('districts', DistrictController::class);
            Route::resource('cities', CityController::class);
            Route::resource('tehsils', TehsilController::class);
            Route::resource('roles', RoleController::class);
            Route::resource('permissions', PermissionController::class);
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

            Route::resource('vehicle', VehicleController::class);
            Route::post('/vehicle/toggle-status', [VehicleController::class, 'toggleStatus'])->name('vehicle.toggle-status');

            Route::get('ta-da-bill-master', [TaDaBillMasterController::class, 'index'])->name('ta-da-bill-master.index');
            Route::post('ta-da-bill-master', [TaDaBillMasterController::class, 'update'])->name('ta-da-bill-master.update');
            Route::post('/ta-da-bill-master/toggle-status', [TaDaBillMasterController::class, 'toggleStatus'])->name('ta-da-bill-master.toggle-status');

            Route::get('/hr/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
            Route::post('/hr/attendance/save', [AttendanceController::class,'save'])->name('attendance.save');
            Route::get('/hr/attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');
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
            Route::put('trips/{trip}/update-km', [TripController::class, 'updateKm'])->name('trips.updateKm');
            Route::post('/trips/{trip}/approve', [TripController::class, 'approve'])->name('trips.approve');
            Route::post('/admin/trips/{id}/complete', [TripController::class, 'completeTrip'])->name('trips.complete');
            Route::post('/trips/{trip}/toggle-status', [TripController::class, 'toggleStatus'])->name('trips.status.toggle');
            // });

            Route::resource('budget', BudgetController::class);
            Route::resource('monthly', MonthlyController::class);
            Route::resource('achievement', AchievementController::class);
            Route::resource('party', PartyController::class);
            Route::get('/admin/get-employees-by-state', [PartyController::class, 'getEmployeesByState'])
    ->name('admin.getEmployeesByState');
            Route::get('/admin/get-party-visits', [PartyController::class, 'getPartyVisits'])->name('admin.get-party-visits');
            Route::get('new-party', [PartyController::class, 'newPartyList'])->name('new-party.list');
            Route::post('new-party/status-update', [PartyController::class, 'updateStatus'])->name('new-party.update-status');

            Route::get('party-payment', [PartyPaymentController::class, 'index'])->name('party-payment');
            Route::post('/party-payment/clear-return', [PartyPaymentController::class, 'clearReturn'])
    ->name('party-payment.clear-return');
            
            Route::resource('order', OrderController::class);
            Route::resource('stock', StockController::class);
            Route::resource('tracking', TrackingController::class);
            Route::resource('expense', ExpenseController::class);
            Route::patch('expense/{id}/approve', [ExpenseController::class, 'approve'])->name('expense.approve');
            Route::patch('expense/{id}/reject', [ExpenseController::class, 'reject'])->name('expense.reject');
            Route::get('expense-report', [ExpenseController::class, 'expenseReport'])->name('expense.report');
            Route::get('/expense-pdf-list', [ExpenseController::class, 'expensePdfList'])
    ->name('expense.pdf.list');
            Route::post('expense/bulk-approve', [ExpenseController::class, 'bulkApprove'])
            ->name('expense.bulk.approve');
            Route::get('/expense-report/pdf', [ExpenseController::class, 'exportPDF'])->name('expense.report.pdf');
            Route::get('/expense-report/excel', [ExpenseController::class, 'exportExcel'])->name('expense.report.excel');
            Route::resource('brochure', BrochureController::class);
            Route::resource('price', PriceController::class);
            Route::resource('products', ProductController::class);

             Route::resource('customers', CustomerController::class);
             Route::resource('messages', MessageController::class);
             

            Route::patch('/customers/{id}/toggle', [CustomerController::class, 'toggleStatus'])->name('customers.toggle');
            Route::post('/customers/import', [CustomerController::class, 'import'])->name('customers.import');
            
        });
    });
});