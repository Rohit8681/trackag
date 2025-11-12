<nav class="app-header navbar navbar-expand bg-body shadow-sm">
    <div class="container-fluid">
        <!-- Start Navbar -->
        <ul class="navbar-nav">
            <!-- Sidebar Toggle -->
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>

            <!-- Main Links -->
            <li class="nav-item d-none d-md-block">
                <a href="{{ url('admin/dashboard') }}" class="nav-link">Dashboard</a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="#" class="nav-link">Contact</a>
            </li>
        </ul>

        <!-- End Navbar -->
        <ul class="navbar-nav ms-auto align-items-center">
            <!-- Fullscreen Toggle -->
            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit d-none"></i>
                </a>
            </li>

            <!-- User Dropdown -->
            @php
                $user = Auth::user();
                $defaultImage = asset(
                    $user->gender === 'Female'
                        ? 'admin/images/avatar-female.png'
                        : 'admin/images/avatar-male.png'
                );
                $userImage = $user->image ? asset('storage/' . $user->image) : $defaultImage;
            @endphp

            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ $userImage }}"
                         class="user-image rounded-circle shadow" alt="User Image" width="32" height="32" />
                    <span class="d-none d-md-inline">{{ $user->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <!-- User Info -->
                    <li class="user-header text-bg-primary text-center">
                        <img src="{{ $userImage }}"
                             class="rounded-circle shadow mb-2" alt="User Image" width="80" height="80" />
                        <p class="mb-0">{{ $user->name }}</p>
                        <small>Member since {{ $user->created_at->format('M Y') }}</small>
                    </li>

                    <!-- User Body -->
                    <li class="user-body px-3 py-2">
                        <div class="row text-center">
                            <div class="col-12">
                                <a href="#" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                Change Password
                                </a>
                            </div>
                            {{-- <div class="col-4"><a href="#">Sales</a></div>
                            <div class="col-4"><a href="#">Friends</a></div> --}}
                        </div>
                    </li>

                    <!-- Footer -->
                    <li class="user-footer d-flex justify-content-between px-3 py-2">
                        @if($user && $user->hasRole('master_admin'))
                        <a href="{{ route('apk.create') }}" target="_blank" class="btn btn-outline-primary btn-sm">APK upload</a>
                        @endif
                        <a href="#" class="btn btn-outline-primary btn-sm">Profile</a>
                        <a href="{{ url('admin/logout') }}" class="btn btn-outline-danger btn-sm">Sign out</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    
</nav>
@include('admin.users.change-password-modal');
