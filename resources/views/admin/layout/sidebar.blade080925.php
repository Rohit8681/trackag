<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!-- Brand -->
    <div class="sidebar-brand px-3 py-4 d-flex align-items-center">
        <a href="{{ url('admin/dashboard') }}" class="brand-link d-flex align-items-center gap-2">
            <img src="{{ asset('admin/images/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow" style="width: 32px; height: 32px;" />
            <span class="brand-text fw-light fs-5">
                {{ auth()->user()->company ? auth()->user()->company->name : 'FieldMaster' }}
            </span>
        </a>
    </div>

    <!-- Sidebar Menu -->
    <div class="sidebar-wrapper">
        <nav class="mt-3">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                
                <!-- Planning -->
                <li class="nav-item {{ request()->is('admin/budget*') || request()->is('admin/monthly*') || request()->is('admin/achievement*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/budget*') || request()->is('admin/monthly*') || request()->is('admin/achievement*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text me-2"></i>
                        <p>Planning <i class="bi bi-chevron-right ms-auto"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li><a href="{{ url('admin/budget') }}" class="nav-link {{ request()->is('admin/budget*') ? 'active' : '' }}"><i class="bi bi-cash-stack me-2"></i> Budget Plan</a></li>
                        <li><a href="{{ url('admin/monthly') }}" class="nav-link {{ request()->is('admin/monthly*') ? 'active' : '' }}"><i class="bi bi-calendar-month me-2"></i> Monthly Plan</a></li>
                        <li><a href="{{ url('admin/achievement') }}" class="nav-link {{ request()->is('admin/achievement*') ? 'active' : '' }}"><i class="bi bi-graph-up-arrow me-2"></i> Plan Vs Achievement</a></li>
                    </ul>
                </li>

                <!-- Party -->
                <li class="nav-item {{ request()->is('admin/party*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/party*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill me-2"></i>
                        <p>Party <i class="bi bi-chevron-right ms-auto"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li><a href="{{ url('admin/party/new') }}" class="nav-link {{ request()->is('admin/party/new*') ? 'active' : '' }}"><i class="bi bi-person-plus me-2"></i> New Party</a></li>
                        <li><a href="{{ url('admin/party/performance') }}" class="nav-link {{ request()->is('admin/party/performance*') ? 'active' : '' }}"><i class="bi bi-bar-chart me-2"></i> Party Performance</a></li>
                        <li><a href="{{ url('admin/party/ledger') }}" class="nav-link {{ request()->is('admin/party/ledger*') ? 'active' : '' }}"><i class="bi bi-journal me-2"></i> Party Ledger</a></li>
                        <li><a href="{{ url('admin/party/visit') }}" class="nav-link {{ request()->is('admin/party/visit*') ? 'active' : '' }}"><i class="bi bi-pin-map me-2"></i> Party Visit</a></li>
                    </ul>
                </li>

                <!-- Order -->
                <li class="nav-item {{ request()->is('admin/order*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/order*') ? 'active' : '' }}">
                        <i class="bi bi-cart-check me-2"></i>
                        <p>Order <i class="bi bi-chevron-right ms-auto"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li><a href="{{ url('admin/order') }}" class="nav-link {{ request()->is('admin/order') ? 'active' : '' }}"><i class="bi bi-bag-check me-2"></i> Order</a></li>
                        <li><a href="{{ url('admin/order/report') }}" class="nav-link {{ request()->is('admin/order/report*') ? 'active' : '' }}"><i class="bi bi-file-earmark-text me-2"></i> Order Report</a></li>
                    </ul>
                </li>

                <!-- Stock -->
                <li class="nav-item {{ request()->is('admin/stock*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/stock*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam me-2"></i>
                        <p>Stock <i class="bi bi-chevron-right ms-auto"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li><a href="{{ url('admin/stock/available') }}" class="nav-link {{ request()->is('admin/stock/available*') ? 'active' : '' }}"><i class="bi bi-stack me-2"></i> Available Stock</a></li>
                        <li><a href="{{ url('admin/stock/ageing') }}" class="nav-link {{ request()->is('admin/stock/ageing*') ? 'active' : '' }}"><i class="bi bi-hourglass-split me-2"></i> Stock Ageing</a></li>
                        <li><a href="{{ url('admin/stock/live') }}" class="nav-link {{ request()->is('admin/stock/live*') ? 'active' : '' }}"><i class="bi bi-broadcast me-2"></i> Live Feed</a></li>
                    </ul>
                </li>

                <!-- Tracking -->
                <li class="nav-item {{ request()->is('admin/tracking*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/tracking*') ? 'active' : '' }}">
                        <i class="bi bi-geo-alt me-2"></i>
                        <p>Tracking <i class="bi bi-chevron-right ms-auto"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li><a href="{{ url('admin/tracking/daily') }}" class="nav-link {{ request()->is('admin/tracking/daily*') ? 'active' : '' }}"><i class="bi bi-clock-history me-2"></i> Daily Track</a></li>
                        <li><a href="{{ url('admin/tracking/map') }}" class="nav-link {{ request()->is('admin/tracking/map*') ? 'active' : '' }}"><i class="bi bi-map me-2"></i> Emp On Map</a></li>
                    </ul>
                </li>

                <!-- Attendance -->
                <li class="nav-item {{ request()->is('admin/attendance*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/attendance*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check me-2"></i>
                        <p>Attendance <i class="bi bi-chevron-right ms-auto"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li><a href="{{ url('admin/attendance/leave') }}" class="nav-link {{ request()->is('admin/attendance/leave*') ? 'active' : '' }}"><i class="bi bi-door-open me-2"></i> Leave</a></li>
                        <li><a href="{{ url('admin/attendance') }}" class="nav-link {{ request()->is('admin/attendance') ? 'active' : '' }}"><i class="bi bi-person-badge me-2"></i> Attendance</a></li>
                        <li><a href="{{ url('admin/attendance/monthly') }}" class="nav-link {{ request()->is('admin/attendance/monthly*') ? 'active' : '' }}"><i class="bi bi-calendar2-week me-2"></i> Monthly Attendance Report</a></li>
                    </ul>
                </li>

                <!-- Expense -->
                <li class="nav-item {{ request()->is('admin/expense*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/expense*') ? 'active' : '' }}">
                        <i class="bi bi-cash me-2"></i>
                        <p>Expense <i class="bi bi-chevron-right ms-auto"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li><a href="{{ url('admin/expense') }}" class="nav-link {{ request()->is('admin/expense') ? 'active' : '' }}"><i class="bi bi-currency-exchange me-2"></i> Expense</a></li>
                        <li><a href="{{ url('admin/expense/generate') }}" class="nav-link {{ request()->is('admin/expense/generate*') ? 'active' : '' }}"><i class="bi bi-plus-circle me-2"></i> Generate Expense</a></li>
                        <li><a href="{{ url('admin/expense/monthly') }}" class="nav-link {{ request()->is('admin/expense/monthly*') ? 'active' : '' }}"><i class="bi bi-calendar2-check me-2"></i> Monthly Expense Report</a></li>
                    </ul>
                </li>

                <!-- TA-DA Report -->
                <li class="nav-item {{ request()->is('admin/tada*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/tada*') ? 'active' : '' }}">
                        <i class="bi bi-receipt me-2"></i>
                        <p>TA-DA Report <i class="bi bi-chevron-right ms-auto"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li><a href="{{ url('admin/tada/report') }}" class="nav-link {{ request()->is('admin/tada/report*') ? 'active' : '' }}"><i class="bi bi-journal-text me-2"></i> TA-DA Report</a></li>
                        <li><a href="{{ url('admin/tada/daily') }}" class="nav-link {{ request()->is('admin/tada/daily*') ? 'active' : '' }}"><i class="bi bi-calendar-event me-2"></i> Daily Demo</a></li>
                    </ul>
                </li>

                <!-- Field Demo -->
                <li class="nav-item {{ request()->is('admin/demo*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/demo*') ? 'active' : '' }}">
                        <i class="bi bi-easel2 me-2"></i>
                        <p>Field Demo <i class="bi bi-chevron-right ms-auto"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li><a href="{{ url('admin/demo/monthly') }}" class="nav-link {{ request()->is('admin/demo/monthly*') ? 'active' : '' }}"><i class="bi bi-clipboard-data me-2"></i> Monthly Demo Report</a></li>
                    </ul>
                </li>
                <!-- Company Management -->
                <li class="nav-item">
                    <a href="{{ url('admin/companies') }}"
                        class="nav-link {{ request()->is('admin/companies*') ? 'active' : '' }}">
                        <i class="bi bi-buildings me-2"></i>
                        <p>Companies</p>
                    </a>
                </li>

                <!-- Masters -->
                <li class="nav-item {{ request()->is('admin/master*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/master*') ? 'active' : '' }}">
                        <i class="bi bi-gear-fill me-2"></i>
                        <p>Masters <i class="bi bi-chevron-right ms-auto"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li><a href="{{ url('admin/master/state') }}" class="nav-link {{ request()->is('admin/master/state*') ? 'active' : '' }}"><i class="bi bi-map me-2"></i> State</a></li>
                        <li><a href="{{ url('admin/master/salesperson') }}" class="nav-link {{ request()->is('admin/master/salesperson*') ? 'active' : '' }}"><i class="bi bi-person-vcard me-2"></i> Sales Person</a></li>
                        <li><a href="{{ url('admin/master/product') }}" class="nav-link {{ request()->is('admin/master/product*') ? 'active' : '' }}"><i class="bi bi-box2-heart me-2"></i> Product Master</a></li>
                        <li><a href="{{ url('admin/master/party') }}" class="nav-link {{ request()->is('admin/master/party*') ? 'active' : '' }}"><i class="bi bi-people me-2"></i> Party Master</a></li>
                        <li><a href="{{ url('admin/master/hr') }}" class="nav-link {{ request()->is('admin/master/hr*') ? 'active' : '' }}"><i class="bi bi-person-workspace me-2"></i> HR</a></li>
                        <li><a href="{{ url('admin/master/depo') }}" class="nav-link {{ request()->is('admin/master/depo*') ? 'active' : '' }}"><i class="bi bi-building me-2"></i> Depo Master</a></li>
                        <li><a href="{{ url('admin/master/price') }}" class="nav-link {{ request()->is('admin/master/price*') ? 'active' : '' }}"><i class="bi bi-currency-rupee me-2"></i> Price List</a></li>
                        <li><a href="{{ url('admin/master/brochure') }}" class="nav-link {{ request()->is('admin/master/brochure*') ? 'active' : '' }}"><i class="bi bi-file-earmark-pdf me-2"></i> Brochure</a></li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>
