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
        :root {
            --trackag-primary: #0f4c81;
            --trackag-accent: #20b486;
            --trackag-ink: #142333;
            --trackag-muted: #64748b;
            --trackag-border: #d3dde9;
            --trackag-surface: #f8fafc;
        }

        * {
            box-sizing: border-box;
        }

        body.login-page {
            min-height: 100vh;
            background: radial-gradient(circle at top right, rgba(32, 180, 134, 0.18), transparent 28%),
                radial-gradient(circle at bottom left, rgba(15, 76, 129, 0.14), transparent 24%),
                linear-gradient(180deg, #eef4fb 0%, #e2ebf6 100%);
            color: var(--trackag-ink);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: "Source Sans 3", Inter, system-ui, sans-serif;
        }

        .login-box {
            width: min(520px, 100%);
            position: relative;
            z-index: 1;
        }

        .login-shell {
            border-radius: 28px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 35px 80px rgba(15, 76, 129, 0.15);
        }

        .login-brand-panel {
            padding: 32px 28px;
            background: linear-gradient(140deg, rgba(15, 76, 129, 1), rgba(32, 180, 134, 0.92));
            color: #ffffff;
        }

        .brand-content {
            max-width: 100%;
        }

        .brand-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .brand-title {
            margin: 0;
            font-size: clamp(28px, 4vw, 38px);
            font-weight: 800;
            line-height: 1.08;
        }

        .brand-copy {
            margin: 18px 0 0;
            color: rgba(255, 255, 255, 0.86);
            font-size: 15px;
            line-height: 1.7;
        }

        .login-form-panel {
            padding: 36px 32px 28px;
            background: var(--trackag-surface);
        }

        .login-card-body {
            width: 100%;
        }

        .login-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
        }

        .login-logo img {
            max-width: 170px;
            max-height: 100px;
            width: auto;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 16px 30px rgba(15, 76, 129, 0.14));
        }

        .fallback-logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            color: var(--trackag-primary);
            font-size: 30px;
            font-weight: 800;
        }

        .fallback-logo .bi {
            color: var(--trackag-accent);
        }

        .login-heading {
            margin-bottom: 28px;
            text-align: center;
        }

        .login-heading h1 {
            margin: 0;
            color: var(--trackag-ink);
            font-size: 26px;
            font-weight: 800;
            line-height: 1.15;
        }

        .login-heading p {
            margin: 10px 0 0;
            color: var(--trackag-muted);
            font-size: 14px;
        }

        .login-form .input-group {
            margin-bottom: 14px !important;
            border: 1px solid var(--trackag-border);
            border-radius: 16px;
            overflow: hidden;
            background: #ffffff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .login-form .input-group:focus-within {
            border-color: rgba(32, 180, 134, 0.85);
            box-shadow: 0 12px 24px rgba(32, 180, 134, 0.11);
            transform: translateY(-1px);
        }

        .login-form .form-control {
            min-height: 58px;
            border: 0;
            color: var(--trackag-ink);
            font-size: 15px;
            box-shadow: none;
            background: transparent;
        }

        .login-form .form-floating > label {
            color: var(--trackag-muted);
        }

        .login-form .input-group-text {
            min-width: 58px;
            justify-content: center;
            border: 0;
            border-left: 1px solid var(--trackag-border);
            background: #ffffff;
            color: var(--trackag-primary);
            font-size: 19px;
        }

        .login-options {
            align-items: center;
            margin-top: 10px;
            row-gap: 14px;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            margin-top: 0.08rem;
            border-color: #cbd5e1;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--trackag-accent);
            border-color: var(--trackag-accent);
        }

        .form-check-label {
            color: #475569;
            font-size: 15px;
            cursor: pointer;
        }

        .sign-in-btn {
            min-height: 52px;
            border: 0;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--trackag-primary), var(--trackag-accent));
            font-weight: 700;
            box-shadow: 0 16px 30px rgba(15, 76, 129, 0.18);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .sign-in-btn:hover,
        .sign-in-btn:focus {
            transform: translateY(-1px);
            box-shadow: 0 20px 38px rgba(15, 76, 129, 0.22);
        }

        .download-section {
            margin-top: 26px;
            text-align: center;
        }

        .download-btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            min-height: 56px;
            padding: 12px 18px;
            background: #101828;
            color: #fff;
            border: 0;
            border-radius: 16px;
            text-decoration: none;
            font-weight: 700;
            box-shadow: 0 12px 26px rgba(16, 24, 40, 0.22);
            transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
        }

        .download-btn:hover {
            background: #0f172a;
            transform: translateY(-1px);
            box-shadow: 0 18px 34px rgba(16, 24, 40, 0.28);
        }

        .download-label small {
            display: block;
            margin-bottom: 4px;
            color: rgba(255, 255, 255, 0.75);
            font-size: 9px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .download-label strong {
            display: block;
            color: #fff;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0;
        }

        .alert {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 12px 24px rgba(220, 53, 69, 0.12);
        }

        @media (max-width: 520px) {
            body.login-page {
                padding: 16px;
            }

            .login-form-panel {
                padding: 28px 20px 22px;
            }

            .login-heading h1 {
                font-size: 24px;
            }

            .login-options .col-8,
            .login-options .col-4 {
                width: 100%;
                display: block;
            }

            .login-options .col-4 {
                margin-top: 12px;
            }

            .download-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body class="login-page">
    <div class="login-box">
        <div class="login-shell">
            <div class="login-brand-panel">
                <div class="brand-content">
                    <div class="brand-kicker">
                        <span class="bi bi-shield-check"></span>
                        Trackag Admin
                    </div>
                    <h2 class="brand-title">Smarter field operations, one secure login away.</h2>
                    <p class="brand-copy">
                        Manage teams, visits, orders, expenses and live tracking from a clean business dashboard.
                    </p>
                </div>
                <div class="brand-footer">
                    <div class="brand-metric">
                        <span class="bi bi-geo-alt"></span>
                        <div>
                            <strong>Live activity visibility</strong>
                            <span>Built for daily sales and field teams</span>
                        </div>
                    </div>
                    <div class="brand-metric">
                        <span class="bi bi-phone"></span>
                        <div>
                            <strong>Mobile-first workflow</strong>
                            <span>Web admin and Android app connected</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="login-form-panel">
                <div class="login-card-body">
                    @if(isset($company) && $company->logo)
                        <div class="login-logo">
                            <img src="{{ asset('storage/'.$company->logo) }}" alt="Company Logo">
                        </div>
                    @else
                        <div class="login-logo">
                            <div class="fallback-logo">
                                <span class="bi bi-compass"></span>
                                <span>Trackag</span>
                            </div>
                        </div>
                    @endif

                    <div class="login-heading">
                        <h1>Welcome back</h1>
                        <p>Sign in to continue to your admin dashboard.</p>
                    </div>

                @if (Session::has('error_message'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> {{ Session::get('error_message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('auth.login.request') }}" method="post" class="login-form">
                    @csrf

                    <div class="input-group mb-1">
                        <div class="form-floating">
                            <input name="mobile" id="mobile" type="number"
                                class="form-control @error('mobile') is-invalid @enderror mobile_no"
                                placeholder="Mobile"
                                @if(isset($_COOKIE["mobile"])) value="{{ $_COOKIE['mobile'] }}" @endif />
                            <label for="mobile">Mobile</label>
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

                    <div class="row login-options">
                        <div class="col-8 d-inline-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember"
                                    @if(isset($_COOKIE['email'])) checked @endif />
                                <label class="form-check-label" for="remember"> Remember Me </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary sign-in-btn">Sign In</button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- NEW: APP DOWNLOAD BUTTON -->
                <div class="download-section">
                @if($apk)
                    <a href="{{ asset('storage/' . $apk->file_path) }}" class="download-btn" download>
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24">
                            <path d="M3 20.5V3.5C3 2.91 3.34 2.39 3.84 2.15L13.69 12L3.84 21.85C3.34 21.61 3 21.09 3 20.5M16.81 15.12L14.4 12.71L4.54 22.57C4.69 22.62 4.84 22.65 5 22.65C5.37 22.65 5.72 22.5 6 22.25L16.81 15.12M17.41 14.72L21.39 12.06C21.77 11.81 22 11.4 22 11C22 10.6 21.77 10.19 21.39 9.94L17.41 7.28L15.1 12L17.41 14.72M16.81 8.88L6 1.75C5.72 1.5 5.37 1.35 5 1.35C4.84 1.35 4.69 1.38 4.54 1.43L14.4 11.29L16.81 8.88Z" />
                        </svg>
                        <div class="download-label">
                            <small>Get it on</small>
                            <strong>Google Play</strong>
                        </div>
                    </a>
                @else
                    <a href="#" class="download-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24">
                            <path d="M3 20.5V3.5C3 2.91 3.34 2.39 3.84 2.15L13.69 12L3.84 21.85C3.34 21.61 3 21.09 3 20.5M16.81 15.12L14.4 12.71L4.54 22.57C4.69 22.62 4.84 22.65 5 22.65C5.37 22.65 5.72 22.5 6 22.25L16.81 15.12M17.41 14.72L21.39 12.06C21.77 11.81 22 11.4 22 11C22 10.6 21.77 10.19 21.39 9.94L17.41 7.28L15.1 12L17.41 14.72M16.81 8.88L6 1.75C5.72 1.5 5.37 1.35 5 1.35C4.84 1.35 4.69 1.38 4.54 1.43L14.4 11.29L16.81 8.88Z" />
                        </svg>
                        <div class="download-label">
                            <small>Get it on</small>
                            <strong>Google Play</strong>
                        </div>
                    </a>
                @endif
                    </div>
                <!-- END NEW -->
                </div>
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
