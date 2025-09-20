<?php

use App\Http\Controllers\DepoController;
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
// These routes should ONLY handle central database operations
// No tenancy middleware should be applied here

Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });
    
    // Test route to verify central routes are working
    Route::get('/test-central', function () {
        return response()->json([
            'message' => 'Central route working',
            'domain' => request()->getHost(),
            'tenant' => tenancy()->tenant ? tenancy()->tenant->id : 'No tenant'
        ]);
    });
    
    // Test route to check tenants
    Route::get('/test-tenants', function () {
        $tenants = \App\Models\Tenant::all();
        $domains = \App\Models\Domain::all();
        
        return response()->json([
            'tenants' => $tenants->map(function($tenant) {
                return [
                    'id' => $tenant->id,
                    'db_name' => $tenant->tenancy_db_name,
                    'domains' => $tenant->domains->pluck('domain')
                ];
            }),
            'all_domains' => $domains->pluck('domain'),
            'current_domain' => request()->getHost()
        ]);
    });
    
    // Test route to create a tenant for barclay-carr.test
    Route::get('/create-test-tenant', function () {
        try {
            // Check if tenant already exists
            $existingTenant = \App\Models\Tenant::whereHas('domains', function($query) {
                $query->where('domain', 'barclay-carr.test');
            })->first();
            
            if ($existingTenant) {
                return response()->json([
                    'message' => 'Tenant already exists',
                    'tenant_id' => $existingTenant->id,
                    'db_name' => $existingTenant->tenancy_db_name
                ]);
            }
            
            // Create new tenant
            $tenant = \App\Models\Tenant::create([
                'id' => 'barclay-carr',
                'tenancy_db_name' => 'tenant_barclay_carr'
            ]);
            
            // Create domain
            $tenant->domains()->create([
                'domain' => 'barclay-carr.test'
            ]);
            
            return response()->json([
                'message' => 'Tenant created successfully',
                'tenant_id' => $tenant->id,
                'db_name' => $tenant->tenancy_db_name,
                'domain' => 'barclay-carr.test'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    });
    
    // Route to manually create tenant database
    Route::get('/setup-tenant-db', function () {
        try {
            // Find tenant by domain
            $tenant = \App\Models\Tenant::whereHas('domains', function($query) {
                $query->where('domain', 'barclay-carr.test');
            })->first();
            
            if (!$tenant) {
                return response()->json([
                    'error' => 'Tenant not found for domain barclay-carr.test'
                ]);
            }
            
            // Get database name
            $dbName = $tenant->tenancy_db_name;
            if (!$dbName) {
                $dbName = 'tenant_' . $tenant->id;
                $tenant->update(['tenancy_db_name' => $dbName]);
            }
            
            // Create database manually
            $connection = DB::connection('mysql');
            
            // Create database
            $connection->statement("CREATE DATABASE IF NOT EXISTS `{$dbName}`");
            
            // Test connection to tenant database
            config(['database.connections.tenant.database' => $dbName]);
            DB::purge('tenant');
            DB::reconnect('tenant');
            
            // Test if we can connect to tenant database
            try {
                DB::connection('tenant')->getPdo();
                $dbConnected = true;
            } catch (\Exception $e) {
                $dbConnected = false;
                $dbError = $e->getMessage();
            }
            
            // Run migrations for tenant database
            $migrationOutput = '';
            try {
                Artisan::call('tenants:migrate', [
                    '--tenants' => $tenant->id
                ]);
                $migrationOutput = Artisan::output();
            } catch (\Exception $e) {
                $migrationOutput = 'Migration error: ' . $e->getMessage();
            }
            
            return response()->json([
                'message' => 'Tenant database setup complete',
                'tenant_id' => $tenant->id,
                'database' => $dbName,
                'db_connected' => $dbConnected,
                'db_error' => $dbError ?? null,
                'migration_output' => $migrationOutput
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    });
    
    // Route to check tenant database status
    Route::get('/check-tenant-db', function () {
        try {
            $tenant = \App\Models\Tenant::whereHas('domains', function($query) {
                $query->where('domain', 'barclay-carr.test');
            })->first();
            
            if (!$tenant) {
                return response()->json(['error' => 'Tenant not found']);
            }
            
            $dbName = $tenant->tenancy_db_name;
            
            // Check if database exists
            $connection = DB::connection('mysql');
            $databases = $connection->select("SHOW DATABASES LIKE '{$dbName}'");
            $dbExists = count($databases) > 0;
            
            // Try to connect to tenant database
            $canConnect = false;
            $connectionError = null;
            
            if ($dbExists) {
                try {
                    config(['database.connections.tenant.database' => $dbName]);
                    DB::purge('tenant');
                    DB::reconnect('tenant');
                    DB::connection('tenant')->getPdo();
                    $canConnect = true;
                } catch (\Exception $e) {
                    $connectionError = $e->getMessage();
                }
            }
            
            return response()->json([
                'tenant_id' => $tenant->id,
                'database_name' => $dbName,
                'database_exists' => $dbExists,
                'can_connect' => $canConnect,
                'connection_error' => $connectionError
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    });
    
    // Route to test tenant database connection with custom bootstrapper
    Route::get('/test-tenant-connection', function () {
        try {
            $tenant = \App\Models\Tenant::whereHas('domains', function($query) {
                $query->where('domain', 'barclay-carr.test');
            })->first();
            
            if (!$tenant) {
                return response()->json(['error' => 'Tenant not found']);
            }
            
            // Manually bootstrap tenancy
            $bootstrapper = new \App\Tenancy\CustomDatabaseTenancyBootstrapper();
            $bootstrapper->bootstrap($tenant);
            
            // Test query on tenant database
            $userCount = DB::connection('tenant')->table('users')->count();
            
            return response()->json([
                'message' => 'Tenant connection successful',
                'tenant_id' => $tenant->id,
                'database' => $tenant->getDatabaseName(),
                'user_count' => $userCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    });
    
    // Route to test the new middleware
    Route::get('/test-middleware', function () {
        return response()->json([
            'message' => 'Middleware test route',
            'tenant' => tenancy()->tenant ? tenancy()->tenant->id : 'No tenant',
            'database' => DB::connection('tenant')->getDatabaseName()
        ]);
    });

    // Admin (central) routes
    Route::prefix('admin')->group(function () {
        // Public routes
        Route::get('login', [AdminController::class, 'create'])->name('admin.login');
        Route::post('login', [AdminController::class, 'store'])->name('auth.login.request');
        Route::get('logout', [AdminController::class, 'destroy'])->name('admin.logout');

        // Protected routes
        Route::middleware(['admin', 'last_seen'])->group(function () {
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

            Route::resource('depos', DepoController::class);
            Route::get('ajax/get-districts', [DepoController::class, 'getDistricts'])->name('depos.get-districts');
            Route::get('ajax/get-tehsils', [DepoController::class, 'getTehsils'])->name('depos.get-tehsils');

            Route::get('/get-districts/{state_id}', [UserController::class, 'getDistricts'])->name('get.districts');
            Route::get('/get-cities/{district_id}', [UserController::class, 'getCities'])->name('get.cities');
            Route::get('/get-tehsils/{city_id}', [UserController::class, 'getTehsils'])->name('get.tehsils');
            Route::get('/get-pincodes/{city_id}', [UserController::class, 'getPincodes'])->name('get.pincodes');
        });
    });
});