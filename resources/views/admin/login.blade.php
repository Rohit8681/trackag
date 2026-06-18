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
            --trackag-ink: #172033;
            --trackag-muted: #64748b;
            --trackag-border: #dbe5ef;
        }

        * {
            box-sizing: border-box;
        }

        body.login-page {
            min-height: 100vh;
            background: #f8fafc;
            color: var(--trackag-ink);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: "Source Sans 3", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .login-box {
            width: 100%;
            max-width: 900px;
            position: relative;
            z-index: 1;
        }

        .login-shell {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            border-radius: 24px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 32px 80px rgba(15, 76, 129, 0.16);
        }

        /* LEFT PANEL - BRAND */
        .login-brand-panel {
            background: linear-gradient(135deg, #0f4c81 0%, #20b486 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 500px;
            color: #ffffff;
        }

        .login-brand-panel img {
            max-width: 280px;
            width: 100%;
            height: auto;
            margin-bottom: 40px;
        }

        /* Hide brand text content */
        .brand-content,
        .brand-footer,
        .brand-kicker,
        .brand-title,
        .brand-copy,
        .brand-metric {
            display: none !important;
        }

        /* RIGHT PANEL - FORM */
        .login-form-panel {
            padding: 50px 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
        }

        .login-card-body {
            width: 100%;
            max-width: 380px;
        }

        /* LOGO SECTION */
        .login-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 28px;
        }

        .login-logo img {
            max-width: 160px;
            max-height: 100px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .fallback-logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--trackag-primary);
            font-size: 30px;
            font-weight: 800;
        }

        .fallback-logo .bi {
            color: var(--trackag-accent);
        }

        /* HEADING */
        .login-heading {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-heading h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            color: var(--trackag-ink);
            line-height: 1.2;
        }

        .login-heading p {
            margin: 12px 0 0;
            color: var(--trackag-muted);
            font-size: 14px;
            line-height: 1.6;
        }

        /* FORM INPUTS */
        .login-form .input-group {
            margin-bottom: 16px !important;
            border: 1.5px solid var(--trackag-border);
            border-radius: 12px;
            overflow: hidden;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .login-form .input-group:focus-within {
            border-color: var(--trackag-accent);
            box-shadow: 0 8px 20px rgba(32, 180, 134, 0.14);
        }

        .login-form .form-control {
            min-height: 56px;
            border: 0 !important;
            color: var(--trackag-ink);
            font-size: 14px;
            background: transparent;
            padding: 0 16px;
        }

        .login-form .form-control::placeholder {
            color: var(--trackag-muted);
        }

        .login-form .form-floating > label {
            color: var(--trackag-muted);
            font-size: 13px;
            padding-left: 16px;
        }

        .login-form .input-group-text {
            min-width: 56px;
            border: 0;
            border-left: 1.5px solid var(--trackag-border);
            background: transparent;
            color: var(--trackag-primary);
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* OPTIONS - REMEMBER & BUTTON */
        .login-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
            gap: 12px;
        }

        .form-check {
            margin: 0;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            border: 1.5px solid #cbd5e1;
            cursor: pointer;
            border-radius: 4px;
        }

        .form-check-input:checked {
            background-color: var(--trackag-accent);
            border-color: var(--trackag-accent);
        }

        .form-check-label {
            margin-left: 8px;
            color: var(--trackag-muted);
            font-size: 14px;
            cursor: pointer;
            font-weight: 500;
        }

        .sign-in-btn {
            min-height: 52px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--trackag-primary), var(--trackag-accent));
            color: #ffffff;
            font-weight: 700;
            font-size: 15px;
            box-shadow: 0 12px 28px rgba(15, 76, 129, 0.2);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .sign-in-btn:hover,
        .sign-in-btn:focus {
            transform: translateY(-2px);
            box-shadow: 0 16px 36px rgba(15, 76, 129, 0.28);
        }

        /* DOWNLOAD SECTION */
        .download-section {
            margin-top: 28px;
            text-align: center;
        }

        .download-btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            min-height: 54px;
            padding: 10px 20px;
            background: #1a1f36;
            color: #ffffff;
            border: 0;
            border-radius: 11px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 10px 24px rgba(26, 31, 54, 0.18);
            transition: all 0.2s ease;
        }

        .download-btn:hover {
            background: #0f1423;
            transform: translateY(-2px);
            box-shadow: 0 14px 32px rgba(26, 31, 54, 0.24);
        }

        .download-label small {
            display: block;
            font-size: 10px;
            opacity: 0.8;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .download-label strong {
            display: block;
            font-size: 15px;
            font-weight: 800;
        }

        /* ALERT */
        .alert {
            border: 0;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 8px 16px rgba(220, 53, 69, 0.14);
        }

        /* RESPONSIVE */
        @media (max-width: 800px) {
            .login-shell {
                grid-template-columns: 1fr;
            }

            .login-brand-panel {
                display: none;
            }

            .login-form-panel {
                padding: 40px 28px;
                min-height: auto;
            }

            .login-card-body {
                max-width: 100%;
            }

            .login-heading h1 {
                font-size: 24px;
            }

            .login-options {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 480px) {
            body.login-page {
                padding: 16px;
            }

            .login-form-panel {
                padding: 32px 20px;
            }

            .login-heading h1 {
                font-size: 22px;
            }

            .login-heading p {
                font-size: 13px;
            }

            .login-form .input-group {
                margin-bottom: 14px !important;
            }

            .login-options {
                width: 100%;
            }

            .sign-in-btn {
                width: 100%;
            }
        }
    </style>
</head>

<body class="login-page">
    <div class="login-box">
        <div class="login-shell">
            <div class="login-brand-panel">
                <img src="{{ asset('img/trackag-logo.png') }}" alt="Trackag Logo">
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
                            <label for="mobile">User Name</label>
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

                    <div class="login-options">
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember"
                                    @if(isset($_COOKIE['email'])) checked @endif />
                                <label class="form-check-label" for="remember">Remember Me</label>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary sign-in-btn">Sign In</button>
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
