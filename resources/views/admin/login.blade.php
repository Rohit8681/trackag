<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Trackag | Login Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE 4 | Login Page v2" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
    <meta name="keywords" content="bootstrap 5, admin dashboard" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('admin/css/adminlte.css') }}" />

    <style>
        /* .login-logo img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0 3px 12px rgba(0,0,0,0.25);
        } */
         .login-logo img {
    width: 180px; /* tamari requirement pramane vadhaaro */
    height: auto;
    object-fit: contain;
    border-radius: 0; /* circle hataavi do */
    box-shadow: none; /* shadow joiye to add karo */
}

        /* NEW: Download Button Stylish */
        .download-section {
            margin-top: 15px;
            text-align: center;
        }

        .download-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            background: #000;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.25s;
        }

        .download-btn:hover {
            background: #333;
            transform: translateY(-2px);
        }

        .download-btn img {
            height: 28px;
        }
    </style>
</head>

<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="card card-outline card-primary">

            <!-- LOGO -->
            <div class="card-header text-center">
                @if(isset($company) && $company->logo)
                    <div class="login-logo mb-2">
                        <img src="{{ asset('storage/'.$company->logo) }}" alt="Company Logo">
                    </div>
                @else
                    <h3 class="text-primary">Trackag</h3>
                @endif
            </div>

            <div class="card-body login-card-body">

                @if (Session::has('error_message'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> {{ Session::get('error_message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('auth.login.request') }}" method="post">
                    @csrf

                    <div class="input-group mb-1">
                        <div class="form-floating">
                            <input name="mobile" id="mobile" type="number"
                                class="form-control @error('mobile') is-invalid @enderror mobile_no"
                                placeholder="Mobile"
                                @if(isset($_COOKIE["mobile"])) value="{{ $_COOKIE['mobile'] }}" @endif />
                            <label for="loginMobile">Mobile</label>
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-group-text"><span class="bi bi-phone-fill"></span></div>
                    </div>

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

                <!-- NEW: APP DOWNLOAD BUTTON -->
                <div class="download-section">
                @if($apk)
                    <a href="{{ asset('storage/' . $apk->file_path) }}" class="download-btn" download style="padding: 6px 16px; height: auto;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: #fff; margin-right: 10px;">
                            <path d="M3 20.5V3.5C3 2.91 3.34 2.39 3.84 2.15L13.69 12L3.84 21.85C3.34 21.61 3 21.09 3 20.5M16.81 15.12L14.4 12.71L4.54 22.57C4.69 22.62 4.84 22.65 5 22.65C5.37 22.65 5.72 22.5 6 22.25L16.81 15.12M17.41 14.72L21.39 12.06C21.77 11.81 22 11.4 22 11C22 10.6 21.77 10.19 21.39 9.94L17.41 7.28L15.1 12L17.41 14.72M16.81 8.88L6 1.75C5.72 1.5 5.37 1.35 5 1.35C4.84 1.35 4.69 1.38 4.54 1.43L14.4 11.29L16.81 8.88Z" />
                        </svg>
                        <div style="text-align: left; line-height: 1;">
                            <div style="font-size: 8px; text-transform: uppercase; font-weight: 400; margin-bottom: 2px;">Get it on</div>
                            <div style="font-size: 16px; font-weight: 700; letter-spacing: -0.3px;">Google Play</div>
                        </div>
                    </a>
                @else
                    <a href="#" class="download-btn" style="padding: 6px 16px; height: auto;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: #fff; margin-right: 10px;">
                            <path d="M3 20.5V3.5C3 2.91 3.34 2.39 3.84 2.15L13.69 12L3.84 21.85C3.34 21.61 3 21.09 3 20.5M16.81 15.12L14.4 12.71L4.54 22.57C4.69 22.62 4.84 22.65 5 22.65C5.37 22.65 5.72 22.5 6 22.25L16.81 15.12M17.41 14.72L21.39 12.06C21.77 11.81 22 11.4 22 11C22 10.6 21.77 10.19 21.39 9.94L17.41 7.28L15.1 12L17.41 14.72M16.81 8.88L6 1.75C5.72 1.5 5.37 1.35 5 1.35C4.84 1.35 4.69 1.38 4.54 1.43L14.4 11.29L16.81 8.88Z" />
                        </svg>
                        <div style="text-align: left; line-height: 1;">
                            <div style="font-size: 8px; text-transform: uppercase; font-weight: 400; margin-bottom: 2px;">Get it on</div>
                            <div style="font-size: 16px; font-weight: 700; letter-spacing: -0.3px;">Google Play</div>
                        </div>
                    </a>
                @endif
            </div>
                <!-- END NEW -->

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('admin/js/adminlte.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileInput = document.querySelector('.mobile_no');
            mobileInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
                if (this.value.length > 10) {
                    this.value = this.value.slice(0, 10);
                }
            });
        });
    </script>

</body>
</html>
