<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FieldMaster | Login Page v1</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE 4 | Login Page v2" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description"
        content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
    <meta name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
        integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('admin/css/adminlte.css') }}" />
</head>

<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <a href="#"
                    class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
                    <h1 class="mb-0"><b>FieldMaster</b>V1</h1>
                </a>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                {{-- //Enter Wrong Password or User Name it will through Error Message --}}

                @if (Session::has('error_message'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> {{ Session::get('error_message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('auth.login.request') }}" method="post">
    @csrf

    <!-- Company Code Field -->
    <div class="input-group mb-1">
        <div class="form-floating">
            <input name="company_id" id="companyCode" type="text"
                class="form-control @error('company_id') is-invalid @enderror"
                placeholder="Company Code" />
            <label for="companyCode">Company Code</label>
            @error('company_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group-text"><span class="bi bi-building"></span></div>
    </div>

    <!-- Email Field -->
<div class="input-group mb-1">
    <div class="form-floating">
        <input name="email" id="loginEmail" type="email"
            class="form-control @error('email') is-invalid @enderror"
            placeholder="Email"
            @if(isset($_COOKIE["email"])) value="{{ $_COOKIE['email'] }}" @endif />
        <label for="loginEmail">Email</label>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="input-group-text"><span class="bi bi-envelope"></span></div>
</div>

<!-- Password Field -->
<div class="input-group mb-1">
    <div class="form-floating">
        <input name="password" id="loginPassword" type="password"
            class="form-control @error('password') is-invalid @enderror"
            placeholder="Password"
            @if(isset($_COOKIE["password"])) value="{{ $_COOKIE['password'] }}" @endif />
        <label for="loginPassword">Password</label>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
</div>

<!-- Remember Me Option -->
<div class="row">
    <div class="col-8 d-inline-flex align-items-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember" name="remember"
                @if(isset($_COOKIE['email'])) checked @endif />
            <label class="form-check-label" for="remember"> Remember Me </label>
        </div>
    </div>
    <div class="col-4">
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Sign In</button>
        </div>
    </div>
</div>

</form>


                {{-- <div class="social-auth-links text-center mb-3 d-grid gap-2">
            <p>- OR -</p>
            <a href="#" class="btn btn-primary">
              <i class="bi bi-facebook me-2"></i> Sign in using Facebook
            </a>
            <a href="#" class="btn btn-danger">
              <i class="bi bi-google me-2"></i> Sign in using Google+
            </a>
          </div> --}}
                {{-- <p class="mb-1"><a href="forgot-password.html">I forgot my password</a></p>
          <p class="mb-0">
            <a href="register.html" class="text-center"> Register a new membership </a>
          </p> --}}
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
        integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        xintegrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        xintegrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <script src="{{ asset('admin/js/adminlte.js') }}"></script>
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
</body>

</html>
