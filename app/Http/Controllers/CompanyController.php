<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Designation;
use App\Models\State;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeCreatedException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


use App\Models\Tenant;

class CompanyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $companies = $user->hasRole('master_admin')
            ? Company::all()
            : Company::where('id', $user->company_id)->get();

        return view('admin.companies.index', compact('companies'));
    }

    
    public function create()
    {
        $authUser = auth()->user();

        $roles = $authUser->user_level === 'master_admin'
            ? Role::all()
            : Role::where('company_id', $authUser->company_id)->get();

        $companies = $authUser->user_level === 'master_admin'
            ? Company::all()
            : collect(); 

        $users = User::when($authUser->user_level !== 'master_admin', function ($query) use ($authUser) {
                $query->where('company_id', $authUser->company_id);
            })->get();

        $designations = $authUser->user_level === 'master_admin'
        ? Designation::all()
        : Designation::where('company_id', $authUser->company_id)->get();

        $state = State::where('status',1)->get();
        
        return view('admin.companies.create',[
            'states' => State::all(),
            'designations' => $designations,
            'users' => $users, // ✅ added here
            'roles' => $roles,
            'permissions' => Permission::all(),
            'authUser' => $authUser,
            'companies' => $companies,
            'state' => $state
        ]);
    }
    
    public function store(StoreCompanyRequest $request)
    {
        $validated = $request->validated();
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $subdomain = Str::slug($validated['code'], '-');
        $centralDomain = env('CENTRAL_DOMAIN', 'test'); 
        $fullDomain = $subdomain . '.' . $centralDomain;
        $tenancyDbName = 'tenant_' . Str::slug($subdomain, '_');

        $company = null;
        $tenant = null;

        try {
            DB::statement("CREATE DATABASE IF NOT EXISTS `$tenancyDbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            DB::beginTransaction();
            try {
                $company = Company::create([
                    'name' => $validated['name'],
                    'code' => $validated['code'] ?? null,
                    'owner_name' => $validated['owner_name'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'gst_number' => $validated['gst_number'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'contact_no' => $validated['contact_no'] ?? null,
                    'contact_no2' => $validated['contact_no2'] ?? null,
                    'telephone_no' => $validated['telephone_no'] ?? null,
                    'website' => $validated['website'] ?? null,
                    'state' => !empty($validated['state']) ? implode(',', $validated['state']) : null,
                    'product_name' => $validated['product_name'] ?? null,
                    'subscription_type' => $validated['subscription_type'] ?? null,
                    'tally_configuration' => $validated['tally_configuration'] ?? 0,
                    'logo' => $validated['logo'] ?? null,
                    'subdomain' => $fullDomain,
                    'start_date' => $validated['start_date'] ?? null,
                    'validity_upto' => $validated['validity_upto'] ?? null,
                    'user_assigned' => $validated['user_assigned'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $tenant = Tenant::create([
                    'id' => (string) Str::uuid(),
                    'data' => [
                        'company_id' => $company->id,
                        'database' => $tenancyDbName,
                    ],
                    'tenancy_db_name' => $tenancyDbName,
                ]);

                $tenant->domains()->create(['domain' => $fullDomain]);
                $company->update(['tenant_id' => $tenant->id]);

                DB::commit();
            } catch (\Exception $e) {
                try {
                    DB::rollBack();
                } catch (\Exception $rollbackEx) {
                    Log::warning('Central DB rollback failed: ' . $rollbackEx->getMessage());
                }
                throw $e; 
            }

            tenancy()->initialize($tenant);
            $tenantConnection = config('database.connections.tenant');
            $tenantConnection['database'] = $tenancyDbName;
            config(['database.connections.tenant' => $tenantConnection]);
            DB::purge('tenant');
            DB::reconnect('tenant');

            $exitCode = Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);

            if ($exitCode !== 0) {
                throw new \Exception('Tenant migrations failed: ' . Artisan::output());
            }

            $tenantData = [
                'id' => $tenant->id,
                'data' => $tenant->data,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $existingTenant = DB::connection('tenant')->table('tenants')
                ->where('id', $tenant->id)
                ->first();
            if (!$existingTenant) {
                DB::connection('tenant')->table('tenants')->insert($tenantData);
            }

            $tenantCompanyData = [
                'name' => $company->name,
                'code' => $company->code,
                'owner_name' => $company->owner_name,
                'email' => $company->email,
                'gst_number' => $company->gst_number,
                'address' => $company->address,
                'contact_no' => $company->contact_no,
                'contact_no2' => $company->contact_no2,
                'telephone_no' => $company->telephone_no,
                'website' => $company->website,
                'state' => $company->state,
                'state' => !empty($company->state) ? $company->state : null,
                'product_name' => $company->product_name,
                'subscription_type' => $company->subscription_type,
                'tally_configuration' => $company->tally_configuration,
                'logo' => $company->logo,
                'subdomain' => $fullDomain,
                'start_date' => $validated['start_date'] ?? null,
                'validity_upto' => $validated['validity_upto'] ?? null,
                'user_assigned' => $validated['user_assigned'] ?? null,
                'tenant_id' => $tenant->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $existingTenantCompany = DB::connection('tenant')->table('companies')
                ->where('code', $company->code)
                ->first();
            if (!$existingTenantCompany) {
                DB::connection('tenant')->table('companies')->insert($tenantCompanyData);
            }

            $seedersPath = database_path('seeders');
            $seederFiles = collect(\File::files($seedersPath))
                ->filter(fn($file) => str_ends_with($file->getFilename(), 'Seeder.php'))
                ->map(fn($file) => 'Database\\Seeders\\' . str_replace('.php', '', $file->getFilename()))
                ->values();

            $seederOrder = [
                'StatesSeeder',
                'DistrictsSeeder',
                'CitiesSeeder',
                'TehsilsSeeder',
                'PincodesSeeder',
            ];
            $orderedSeederFiles = collect($seederOrder)
                ->map(fn($class) => 'Database\\Seeders\\' . $class)
                ->filter(fn($class) => $seederFiles->contains($class))
                ->values();
            $remainingSeeders = $seederFiles->diff($orderedSeederFiles)->values();
            $finalSeederList = $orderedSeederFiles->merge($remainingSeeders);

            DB::connection('tenant')->statement('SET FOREIGN_KEY_CHECKS=0;');
            try {
                foreach ($finalSeederList as $seederClass) {
                    if (in_array($seederClass, [
                        'Database\\Seeders\\DatabaseSeeder',
                        'Database\\Seeders\\MultiCompanySeeder',
                        'Database\\Seeders\\TripSeeder',
                    ])) {
                        continue;
                    }
                    Artisan::call('db:seed', [
                        '--class' => $seederClass,
                        '--database' => 'tenant',
                        '--force' => true,
                    ]);
                }
            } catch (\Exception $seederEx) {
                Log::error("Seeder failed: {$seederClass} - " . $seederEx->getMessage());
                throw new \Exception("Seeder failed: {$seederClass} - " . $seederEx->getMessage());
            } finally {
                DB::connection('tenant')->statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['user_password']),
                'mobile' => $validated['contact_no'] ?? null,
                'address' => $validated['address'] ?? null,
                'user_level' => 'company_admin',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $userId = DB::connection('tenant')->table('users')->insertGetId($userData);
            $tenantUserModel = new \App\Models\User(); // tenant user model
            $tenantUserModel->setConnection('tenant');
            $user = $tenantUserModel->find($userId);

            if ($user) {
                $user->assignRole('sub_admin');
            }
            return redirect()->route('companies.index')
                ->with('success', "Company & Admin created successfully. Domain: {$fullDomain}");

        } catch (\Exception $e) {
            Log::error('Company/Tenant creation failed: ' . $e->getMessage());

            // Clean up
            if ($tenant) {
                try { $tenant->delete(); } catch (\Throwable $ex) {
                    Log::warning('Failed to delete tenant: ' . $ex->getMessage());
                }
            }
            if ($company) {
                try { $company->delete(); } catch (\Throwable $ex) {
                    Log::warning('Failed to delete company: ' . $ex->getMessage());
                }
            }
            try { DB::statement("DROP DATABASE IF EXISTS `$tenancyDbName`"); } catch (\Throwable $ex) {
                Log::warning('Failed to drop database: ' . $ex->getMessage());
            }

            // Provide detailed error message
            $errorMsg = $e->getMessage();
            if (str_contains($errorMsg, 'Integrity constraint violation')) {
                $errorMsg = "A database seeder failed due to missing parent data or foreign key mismatch. Ensure parent tables (e.g., tenants) are created and seeded. Error: " . $errorMsg;
            } elseif (str_contains($errorMsg, "Field 'subdomain' doesn't have a default value")) {
                $errorMsg = "The companies table in the tenant database requires a 'subdomain' value. Ensure the DatabaseSeeder or other seeders include the subdomain field. Error: " . $errorMsg;
            } elseif (str_contains($errorMsg, 'no active transaction')) {
                $errorMsg = "A transaction error occurred, likely due to a DDL operation. Error: " . $errorMsg;
            }

            return back()->withInput()
                ->withErrors(['error' => 'Onboarding failed: ' . $errorMsg]);
        }
    }

    public function show(Company $company)
    {
        $this->authorizeCompanyAccess($company);

        return view('admin.companies.show', compact('company'));
    }

    
    public function edit(Company $company)
    {
        $this->authorizeCompanyAccess($company);
        $state = State::where('status',1)->get();

        return view('admin.companies.edit', compact('company','state'));
    }

    
    public function update(StoreCompanyRequest $request, Company $company)
    {
        $this->authorizeCompanyAccess($company);

        $validated = $request->validated();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }
        if (!empty($validated['state']) && is_array($validated['state'])) {
            $validated['state'] = implode(',', $validated['state']); // [4,5,8] => "4,5,8"
        }

        // ✅ Central DB update
        $company->update($validated);
        $tenant = $company->tenant_id;
        $tenantData = Tenant::where('id', $tenant)->first();

        if (!empty($tenantData->tenancy_db_name)) {
            $tenancyDbName = $tenantData->tenancy_db_name;

            $tenantConnection = config('database.connections.tenant');
            $tenantConnection['database'] = $tenancyDbName;
            config(['database.connections.tenant' => $tenantConnection]);
            DB::purge('tenant');
            DB::reconnect('tenant');

            $tenantCompanyData = [
                'name' => $company->name,
                'code' => $company->code,
                'owner_name' => $company->owner_name,
                'email' => $company->email,
                'gst_number' => $company->gst_number,
                'address' => $company->address,
                'contact_no' => $company->contact_no,
                'contact_no2' => $company->contact_no2,
                'telephone_no' => $company->telephone_no,
                'website' => $company->website,
                'state' => $company->state,
                'product_name' => $company->product_name,
                'subscription_type' => $company->subscription_type,
                'tally_configuration' => $company->tally_configuration,
                'logo' => $company->logo,
                'subdomain' => $company->subdomain,
                'start_date' => $validated['start_date'] ?? $company->start_date,
                'validity_upto' => $validated['validity_upto'] ?? $company->validity_upto,
                'user_assigned' => $validated['user_assigned'] ?? $company->user_assigned,
                'updated_at' => now(),
            ];

            DB::connection('tenant')->table('companies')
                ->where('tenant_id', $tenant)
                ->update($tenantCompanyData);

            // ✅ If password provided → update admin user
            if (!empty($validated['user_password'])) {
                $adminUser = DB::connection('tenant')
                    ->table('users')
                    ->where('email', $company->email)
                    ->first();

                if ($adminUser) {
                    DB::connection('tenant')
                        ->table('users')
                        ->where('id', $adminUser->id)
                        ->update([
                            'password' => Hash::make($validated['user_password']),
                            'updated_at' => now(),
                        ]);
                }
            }
        }

        return redirect()->route('companies.index')
            ->with('success', 'Company & Tenant updated successfully.');
    }
    
    public function destroy(Company $company)
    {
        $this->authorizeMaster(); // Only master_admin can delete companies

        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }

   
    public function toggle($id)
    {
        $company = Company::findOrFail($id);
        $this->authorizeCompanyAccess($company);

        $company->is_active = !$company->is_active;
        $company->status = $company->is_active ? 'Active' : 'Inactive';
        $company->save();

        return redirect()->route('companies.index')->with('success', 'Company status updated.');
    }

    
    private function authorizeMaster()
    {
        $user = Auth::user();
        if (!$user->hasRole('master_admin')) {
            abort(403, 'Unauthorized action.');
        }
    }

    
    private function authorizeCompanyAccess(Company $company)
    {
        $user = Auth::user();

        if ($user->hasRole('master_admin')) {
            return; 
        }

        if ($company->id !== $user->company_id) {
            abort(403, 'Unauthorized access to this company.');
        }
    }
}
