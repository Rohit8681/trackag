<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!-- Brand -->
    @php
        $company = DB::table('companies')->first();
        $user = auth()->user();
    @endphp
    <div class="sidebar-brand px-3 py-4 d-flex align-items-center">
        <a href="{{ url('admin/dashboard') }}" class="brand-link d-flex align-items-center gap-2">
            @if($user && $user->hasRole('master_admin'))
                {{-- Master Admin View --}}
                <img src="{{ asset('admin/images/AdminLTELogo.png') }}" 
                    alt="AdminLTE Logo" 
                    class="brand-image opacity-75 shadow" 
                    style="width: 32px; height: 32px;" />
                <span class="brand-text fw-light fs-5">Trackag</span>
            @else
                {{-- Company View --}}
                @if(!empty($company->logo))
                    <img src="{{ asset('storage/' . $company->logo) }}" 
                        alt="{{ $company->name }}" 
                        class="brand-image opacity-75 shadow rounded-circle" 
                        style="width: 32px; height: 32px; object-fit: cover;" />
                @else
                    <img src="{{ asset('admin/images/AdminLTELogo.png') }}" 
                        alt="Default Logo" 
                        class="brand-image opacity-75 shadow" 
                        style="width: 32px; height: 32px;" />
                @endif
                <span class="brand-text fw-light fs-5">{{ $company->name ?? 'Company Name' }}</span>
            @endif
        </a>
    </div>

    <!-- Sidebar Menu -->
    <div class="sidebar-wrapper">
        <nav class="mt-3">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                <!-- Planning -->
                <li class="nav-item {{ request()->is('admin/budget*') || request()->is('admin/monthly*') || request()->is('admin/achievement*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/budget*') || request()->is('admin/monthly*') || request()->is('admin/achievement*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill me-2"></i>
                        <p>
                            Planning
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @canany(['view_budget_plan','create_budget_plan','edit_budget_plan','delete_budget_plan'])
                        <li class="nav-item">
                            <a href="{{ url('admin/budget') }}" class="nav-link {{ request()->is('admin/budget*') ? 'active' : '' }}">
                                <i class="bi bi-cash-stack me-2"></i>
                                <p>Budget Plan</p>
                            </a>
                        </li>
                        @endcanany
                        @canany(['view_monthly_plan','create_monthly_plan','edit_monthly_plan','delete_monthly_plan'])
                        <li class="nav-item">
                            <a href="{{ url('admin/monthly') }}" class="nav-link {{ request()->is('admin/monthly*') ? 'active' : '' }}">
                                <i class="bi bi-calendar-month me-2"></i>
                                <p>Monthly Plan</p>
                            </a>
                        </li>
                        @endcanany
                        @canany(['view_plan_vs_achievement','create_plan_vs_achievement','edit_plan_vs_achievement','delete_plan_vs_achievement'])
                        <li class="nav-item">
                            <a href="{{ url('admin/achievement') }}" class="nav-link {{ request()->is('admin/achievement*') ? 'active' : '' }}">
                                <i class="bi bi-graph-up-arrow me-2"></i>
                                <p>Plan Vs Achievement</p>
                            </a>
                        </li>
                        @endcanany
                    </ul>
                </li>

                <!-- Party -->
                @canany(['view_party_visit','create_party_visit','edit_party_visit','delete_party_visit'])
                <li class="nav-item {{ request()->is('admin/party*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/party*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill me-2"></i>
                        <p>
                            Party
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('admin/party') }}" class="nav-link {{ request()->is('admin/party*') ? 'active' : '' }}">
                                <i class="bi bi-cash-stack me-2"></i>
                                <p>Party Visit</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany

                <!-- Order -->
                @canany(['view_order','create_order','edit_order','delete_order'])
                <li class="nav-item {{ request()->is('admin/order*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/order*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill me-2"></i>
                        <p>
                            Order
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('admin/order') }}" class="nav-link {{ request()->is('admin/order*') ? 'active' : '' }}">
                                <i class="bi bi-cash-stack me-2"></i>
                                <p>Order</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany

                <!-- Stock -->
                @canany(['view_stock','create_stock','edit_stock','delete_stock'])
                <li class="nav-item {{ request()->is('admin/stock*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/stock*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill me-2"></i>
                        <p>
                            Stock
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('admin/stock') }}" class="nav-link {{ request()->is('admin/stock*') ? 'active' : '' }}">
                                <i class="bi bi-cash-stack me-2"></i>
                                <p>Stock</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany


                <!-- Tracking -->
                @canany(['view_tracking','create_tracking','edit_tracking','delete_tracking'])
                <li class="nav-item {{ request()->is('admin/tracking*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/tracking*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill me-2"></i>
                        <p>
                            Tracking
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('admin/tracking') }}" class="nav-link {{ request()->is('admin/tracking*') ? 'active' : '' }}">
                                <i class="bi bi-cash-stack me-2"></i>
                                <p>Tracking</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany

                <!-- Attendance -->
                @canany(['view_attendance','create_attendance','edit_attendance','delete_attendance'])
                <li class="nav-item {{ request()->is('admin/attendance*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/attendance*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill me-2"></i>
                        <p>
                            Attendance
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('coming-soon') }}" class="nav-link {{ request()->is('admin/attendance*') ? 'active' : '' }}">
                                <i class="bi bi-cash-stack me-2"></i>
                                <p>Attendance</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany

                <!-- Expense -->
                @canany(['view_expense','create_expense','edit_expense','delete_expense'])
                <li class="nav-item {{ request()->is('admin/expense*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/expense*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill me-2"></i>
                        <p>
                            Expense
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('admin/expense') }}" class="nav-link {{ request()->is('admin/expense*') ? 'active' : '' }}">
                                <i class="bi bi-cash-stack me-2"></i>
                                <p>Expense</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany

                <!-- User Management -->
                @canany(['view_users','create_users','edit_users','delete_users','view_roles','create_roles','edit_roles','delete_roles','view_permissions','create_permissions','edit_permissions','delete_permissions'])
                <li class="nav-item {{ request()->is('admin/users*') || request()->is('admin/roles*') || request()->is('admin/permissions*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/users*') || request()->is('admin/roles*') || request()->is('admin/permissions*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill me-2"></i>
                        <p>User Management <i class="bi bi-chevron-right ms-auto"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @canany(['view_users','create_users','edit_users','delete_users'])
                        <li class="nav-item">
                            <a href="{{ url('admin/users') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                <i class="bi bi-person-fill me-2"></i>
                                <p>Manage Users</p>
                            </a>
                        </li>
                        @endcanany
                         @canany(['view_roles','create_roles','edit_roles','delete_roles'])
                        <li class="nav-item">
                            <a href="{{ url('admin/roles') }}" class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                                <i class="bi bi-shield-lock me-2"></i>
                                <p>Manage Roles</p>
                            </a>
                        </li>
                        @endcanany
                        @if(auth()->user() && auth()->user()->hasRole('master_admin'))
                        <li class="nav-item">
                            <a href="{{ url('admin/permissions') }}" class="nav-link {{ request()->is('admin/permissions*') ? 'active' : '' }}">
                                <i class="bi bi-key me-2"></i>
                                <p>Manage Permissions</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endcanany

                <!-- Trip Management -->
                @canany(['view_all_trip','create_all_trip','edit_all_trip','delete_all_trip','view_trip_types','create_trip_types','edit_trip_types','delete_trip_types','view_travel_modes','create_travel_modes','edit_travel_modes','delete_travel_modes','view_trip_purposes','create_trip_purposes','edit_trip_purposes','delete_trip_purposes'])
                <li class="nav-item {{ request()->is('admin/trip-types*') || request()->is('admin/travel-modes*') || request()->is('admin/purposes*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/trip-types*') || request()->is('admin/travel-modes*') || request()->is('admin/purposes*') ? 'active' : '' }}">
                        <i class="bi bi-truck-front me-2"></i>
                        <p>Trip Management <i class="bi bi-chevron-right ms-auto"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @canany(['view_all_trip','create_all_trip','edit_all_trip','delete_all_trip'])
                        <li class="nav-item">
                            <a href="{{ url('admin/trips') }}" class="nav-link {{ request()->is('admin/trips*') ? 'active' : '' }}">
                                <i class="bi bi-truck me-2"></i>
                                <p>All Trips</p>
                            </a>
                        </li>
                        @endcanany
                        @canany(['view_trip_types','create_trip_types','edit_trip_types','delete_trip_types'])
                        <li class="nav-item">
                            <a href="{{ url('admin/tourtype') }}" class="nav-link {{ request()->is('admin/tourtype*') ? 'active' : '' }}">
                                <i class="bi bi-tag me-2"></i>
                                <p>Trip Types</p>
                            </a>
                        </li>
                        @endcanany
                        @canany(['view_travel_modes','create_travel_modes','edit_travel_modes','delete_travel_modes'])
                        <li class="nav-item">
                            <a href="{{ url('admin/travelmode') }}" class="nav-link {{ request()->is('admin/travelmode*') ? 'active' : '' }}">
                                <i class="bi bi-signpost me-2"></i>
                                <p>Travel Modes</p>
                            </a>
                        </li>
                        @endcanany
                        @canany(['view_trip_purposes','create_trip_purposes','edit_trip_purposes','delete_trip_purposes'])
                        <li class="nav-item">
                            <a href="{{ url('admin/purpose') }}" class="nav-link {{ request()->is('admin/purpose*') ? 'active' : '' }}">
                                <i class="bi bi-bullseye me-2"></i>
                                <p>Trip Purposes</p>
                            </a>
                        </li>
                        @endcanany
                    </ul>
                </li>
                @endcanany

                <!-- HR Module -->
                @canany(['view_designations','create_designations','edit_designations','delete_designations','view_attendance','create_attendance','edit_attendance','delete_attendance'])
                <li class="nav-item {{ request()->is('admin/hr/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/hr/*') ? 'active' : '' }}">
                        <i class="bi bi-person-workspace me-2"></i>
                        <p>HR Module <i class="bi bi-chevron-right ms-auto"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @canany(['view_designations','create_designations','edit_designations','delete_designations'])
                        <li class="nav-item">
                            <a href="{{ url('admin/hr/designations') }}" class="nav-link {{ request()->is('admin/hr/designations*') ? 'active' : '' }}">
                                <i class="bi bi-person-vcard me-2"></i>
                                <p>Designations</p>
                            </a>
                        </li>
                        @endcanany
                        @canany(['view_attendance','create_attendance','edit_attendance','delete_attendance'])
                        <li class="nav-item">
                            <a href="{{ url('admin/hr/attendance') }}" class="nav-link {{ request()->is('admin/hr/attendance*') ? 'active' : '' }}">
                                <i class="bi bi-clock-history me-2"></i>
                                <p>Attendance</p>
                            </a>
                        </li>
                        @endcanany
                    </ul>
                </li>
                @endcanany

                <!-- Master Management -->
                @canany(['view_states','create_states','edit_states','delete_states','view_districts','create_districts','edit_districts','delete_districts','view_talukas','create_talukas','edit_talukas','delete_talukas','view_vehicle_types','create_vehicle_types','edit_vehicle_types','delete_vehicle_types','view_depo_master','create_depo_master','edit_depo_master','delete_depo_master','view_holiday_master','create_holiday_master','edit_holiday_master','delete_holiday_master','view_leave_master','create_leave_master','edit_leave_master','delete_leave_master'])
                <li class="nav-item {{ request()->is('admin/states*') || request()->is('admin/districts*') || request()->is('admin/tehsils*') || request()->is('admin/vehicle-types*') || request()->is('admin/depos*') || request()->is('admin/holidays*') || request()->is('admin/leaves*') || request()->is('admin/ta-da-bill-master*') || request()->is('admin/vehicle*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/states*') || request()->is('admin/districts*') || request()->is('admin/tehsils*') || request()->is('admin/vehicle-types*') || request()->is('admin/depos*') || request()->is('admin/holidays*') || request()->is('admin/leaves*') || request()->is('admin/ta-da-bill-master*') || request()->is('admin/vehicle*') ? 'active' : '' }}">
                        <i class="bi bi-geo-alt-fill me-2"></i>
                        <p>Master<i class="bi bi-chevron-right ms-auto"></i></p>
                    </a>
                    <ul class="nav nav-treeview">

                        {{-- State Menu --}}
                        <li class="nav-item {{ request()->is('admin/states*') || request()->is('admin/districts*') || request()->is('admin/tehsils*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('admin/states*') || request()->is('admin/districts*') || request()->is('admin/tehsils*') ? 'active' : '' }}">
                                <i class="bi bi-flag me-2"></i>
                                <p>State<i class="bi bi-chevron-right ms-auto"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('admin/states') }}" class="nav-link {{ request()->is('admin/states*') ? 'active' : '' }}">
                                        <i class="bi bi-circle me-2"></i>
                                        <p>States</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('admin/districts') }}" class="nav-link {{ request()->is('admin/districts*') ? 'active' : '' }}">
                                        <i class="bi bi-circle me-2"></i>
                                        <p>Districts</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('admin/tehsils') }}" class="nav-link {{ request()->is('admin/tehsils*') ? 'active' : '' }}">
                                        <i class="bi bi-circle me-2"></i>
                                        <p>Talukas</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- <li class="nav-item">
                            <a href="{{ url('admin/vehicle-types') }}" class="nav-link {{ request()->is('admin/vehicle-types*') ? 'active' : '' }}">
                                <i class="bi bi-circle me-2"></i>
                                <p>Vehicle Types</p>
                            </a>
                        </li> --}}

                        {{-- Depo Master --}}
                        <li class="nav-item">
                            <a href="{{ url('admin/depos') }}" class="nav-link {{ request()->is('admin/depos*') ? 'active' : '' }}">
                                <i class="bi bi-person-lines-fill me-2"></i>
                                <p>Depo Master</p>
                            </a>
                        </li>

                        {{-- Holiday Master --}}
                        <li class="nav-item">
                            <a href="{{ url('admin/holidays') }}" class="nav-link {{ request()->is('admin/holidays*') ? 'active' : '' }}">
                                <i class="bi bi-calendar-event me-2"></i>
                                <p>Holiday Master</p>
                            </a>
                        </li>

                        {{-- Leave Master --}}
                        <li class="nav-item">
                            <a href="{{ url('admin/leaves') }}" class="nav-link {{ request()->is('admin/leaves*') ? 'active' : '' }}">
                                <i class="bi bi-calendar-check me-2"></i>
                                <p>Leave Master</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/ta-da-slab') }}"
                                class="nav-link {{ request()->is('admin/ta-da-slab*') ? 'active' : '' }}">
                                <i class="bi bi-circle me-2"></i>
                                <p>TA-DA</p>
                            </a>
                        </li>

                        {{-- TA-DA Bill Master --}}
                        <li class="nav-item">
                            <a href="{{ url('admin/ta-da-bill-master') }}" class="nav-link {{ request()->is('admin/ta-da-bill-master*') ? 'active' : '' }}">
                                <i class="bi bi-file-earmark-text me-2"></i>
                                <p>TA-DA Bill Master</p>
                            </a>
                        </li>

                        {{-- Vehicle Master --}}
                        <li class="nav-item">
                            <a href="{{ url('admin/vehicle') }}" class="nav-link {{ request()->is('admin/vehicle*') ? 'active' : '' }}">
                                <i class="bi bi-car-front me-2"></i>
                                <p>Vehicle Master</p>
                            </a>
                        </li>

                    </ul>
                </li>
                @endcanany

                <li class="nav-item">
                    <a href="{{ url('admin/companies') }}"
                        class="nav-link {{ request()->is('admin/companies*') ? 'active' : '' }}">
                        <i class="bi bi-buildings me-2"></i>
                        <p>Companies</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/customers') }}"
                        class="nav-link {{ request()->is('admin/customers*') ? 'active' : '' }}">
                        <i class="bi bi-person-lines-fill me-2"></i>
                        <p>Party (Customers)</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
