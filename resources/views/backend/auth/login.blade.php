<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Tusuka Wash Admin Panel" />
    <meta name="author" content="Tusuka Wash" />
    <title>Login - Tusuka Wash</title>
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <link href="{{ asset('assets/css/backend/styles.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <style>
        :root {
            --primary: #667eea;
            --primary-dark: #764ba2;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --text-dark: #2d3436;
            --text-muted: #868e96;
            --border: #e9ecef;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: #f4f5fa;
            color: var(--text-dark);
        }

        .auth-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ================= Left Brand Panel ================= */
        .auth-brand {
            flex: 0 0 70%;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 55px;
            background: var(--gradient);
            color: #fff;
            overflow: hidden;
            text-align: center;
        }

        .auth-brand::before {
            content: '';
            position: absolute;
            top: -150px;
            right: -150px;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }

        .auth-brand::after {
            content: '';
            position: absolute;
            bottom: -120px;
            left: -120px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
        }

        .brand-content {
            position: relative;
            z-index: 2;
            max-width: 420px;
        }

        .brand-logo {
            height: 52px;
            width: auto;
            filter: brightness(0) invert(1);
            margin-bottom: 18px;
            animation: fadeDown .7s ease both;
        }

        .brand-badge {
            display: inline-block;
            padding: 6px 18px;
            border-radius: 50px;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .3);
            font-size: .78rem;
            font-weight: 500;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 22px;
            backdrop-filter: blur(4px);
            animation: fadeIn 1s ease .2s both;
        }

        .brand-title {
            font-family: 'Jost', sans-serif;
            font-size: 2.4rem;
            font-weight: 600;
            line-height: 1.25;
            margin-bottom: 14px;
            animation: fadeUp .7s ease .25s both;
        }

        .brand-subtitle {
            font-size: .95rem;
            font-weight: 300;
            opacity: .9;
            line-height: 1.7;
            margin-bottom: 34px;
            animation: fadeUp .7s ease .4s both;
        }

        .brand-illustration {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            object-fit: cover;
            border: 8px solid rgba(255, 255, 255, .2);
            box-shadow: 0 25px 60px rgba(0, 0, 0, .25);
            animation: floaty 5s ease-in-out infinite, fadeIn 1s ease .5s both;
            background: rgba(255, 255, 255, .1);
        }

        .brand-features {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px 26px;
            margin-top: 36px;
            animation: fadeUp .7s ease .55s both;
        }

        .brand-features li {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .85rem;
            font-weight: 400;
            opacity: .95;
        }

        .brand-features i {
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, .2);
            font-size: .65rem;
        }

        /* Floating soap bubbles */
        .bubble {
            position: absolute;
            bottom: -80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .25);
            animation: rise linear infinite;
            z-index: 1;
        }

        .bubble:nth-child(1) {
            left: 8%;
            width: 26px;
            height: 26px;
            animation-duration: 11s;
            animation-delay: 0s;
        }

        .bubble:nth-child(2) {
            left: 20%;
            width: 14px;
            height: 14px;
            animation-duration: 14s;
            animation-delay: 2s;
        }

        .bubble:nth-child(3) {
            left: 32%;
            width: 34px;
            height: 34px;
            animation-duration: 12s;
            animation-delay: 4s;
        }

        .bubble:nth-child(4) {
            left: 48%;
            width: 18px;
            height: 18px;
            animation-duration: 16s;
            animation-delay: 1s;
        }

        .bubble:nth-child(5) {
            left: 62%;
            width: 28px;
            height: 28px;
            animation-duration: 13s;
            animation-delay: 3s;
        }

        .bubble:nth-child(6) {
            left: 74%;
            width: 16px;
            height: 16px;
            animation-duration: 15s;
            animation-delay: 5s;
        }

        .bubble:nth-child(7) {
            left: 86%;
            width: 24px;
            height: 24px;
            animation-duration: 10s;
            animation-delay: 2.5s;
        }

        @keyframes rise {
            0% {
                transform: translateY(0) translateX(0) scale(1);
                opacity: 0;
            }

            8% {
                opacity: 1;
            }

            50% {
                transform: translateY(-55vh) translateX(22px) scale(1.05);
            }

            100% {
                transform: translateY(-110vh) translateX(-15px) scale(.9);
                opacity: 0;
            }
        }

        /* ================= Right Form Panel ================= */
        .auth-form-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 50px 30px;
            background: #fff;
            position: relative;
        }

        .mobile-brand {
            display: none;
            background: var(--gradient);
            padding: 26px 20px;
            text-align: center;
            width: 100%;
            margin-bottom: 30px;
            border-radius: 0 0 26px 26px;
        }

        .mobile-brand img {
            height: 40px;
            filter: brightness(0) invert(1);
        }

        .form-card {
            width: 100%;
            max-width: 400px;
            animation: fadeUp .7s ease both;
        }

        .form-header {
            margin-bottom: 34px;
            text-align: center;
        }

        .form-header h2 {
            font-family: 'Jost', sans-serif;
            font-size: 1.9rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .form-header p {
            color: var(--text-muted);
            font-size: .9rem;
            font-weight: 300;
        }

        /* Alerts */
        .alert {
            border-radius: 14px;
            border: none;
            padding: 14px 18px;
            margin-bottom: 22px;
            font-size: .87rem;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            animation: shake .5s ease;
        }

        .alert i {
            margin-top: 2px;
        }

        .alert ul {
            margin: 0;
            padding-left: 18px;
        }

        .alert-danger {
            background: rgba(220, 53, 69, .08);
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }

        .alert-success {
            background: rgba(40, 167, 69, .08);
            color: #28a745;
            border-left: 4px solid #28a745;
        }

        /* Inputs */
        .field-group {
            margin-bottom: 22px;
        }

        .field-label {
            display: block;
            font-size: .82rem;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .field-label span {
            color: #dc3545;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap>i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            font-size: .95rem;
            transition: color .3s ease;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            border: 2px solid var(--border);
            border-radius: 14px;
            padding: 14px 48px 14px 48px;
            font-size: .95rem;
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            transition: all .3s ease;
            outline: none;
        }

        .form-control::placeholder {
            color: #adb5bd;
            font-weight: 300;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, .15);
        }

        .input-wrap:focus-within>i {
            color: var(--primary);
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #adb5bd;
            cursor: pointer;
            transition: color .3s ease;
            padding: 4px;
        }

        .toggle-password:hover {
            color: var(--primary);
        }

        /* Options row */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
        }

        .form-check-input {
            width: 17px;
            height: 17px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .form-check-label {
            font-size: .85rem;
            color: var(--text-muted);
            cursor: pointer;
        }

        /* Submit button */
        .btn-login {
            position: relative;
            width: 100%;
            border: none;
            border-radius: 14px;
            padding: 15px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            color: #fff;
            background: var(--gradient);
            background-size: 200% auto;
            cursor: pointer;
            transition: all .35s ease;
            box-shadow: 0 8px 20px rgba(102, 126, 234, .35);
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -80%;
            width: 45%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .35), transparent);
            transform: skewX(-25deg);
            transition: left .6s ease;
        }

        .btn-login:hover {
            background-position: right center;
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(102, 126, 234, .45);
        }

        .btn-login:hover::before {
            left: 130%;
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .btn-login:disabled {
            opacity: .8;
            cursor: not-allowed;
            transform: none;
        }

        .btn-login .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, .4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .6s linear infinite;
            vertical-align: -3px;
            margin-right: 8px;
        }

        .btn-login.loading .spinner {
            display: inline-block;
        }

        /* Footer */
        .form-footer {
            text-align: center;
            margin-top: 34px;
            font-size: .8rem;
            color: var(--text-muted);
        }

        .form-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color .3s ease;
        }

        .form-footer a:hover {
            color: var(--primary-dark);
        }

        .copyright {
            margin-top: 26px;
            font-size: .75rem;
            color: #ced4da;
            text-align: center;
        }

        /* Animations */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(26px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeDown {
            from {
                opacity: 0;
                transform: translateY(-22px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-16px);
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            20% {
                transform: translateX(-6px);
            }

            40% {
                transform: translateX(6px);
            }

            60% {
                transform: translateX(-4px);
            }

            80% {
                transform: translateX(4px);
            }
        }

        /* ================= Responsive ================= */
        @media (max-width: 991px) {
            .auth-wrapper {
                flex-direction: column;
            }

            .auth-brand {
                display: none;
            }

            .mobile-brand {
                display: block;
            }

            .auth-form-side {
                padding: 0 24px 40px;
                justify-content: flex-start;
            }

            .form-card {
                max-width: 440px;
            }
        }

        @media (max-width: 480px) {
            .form-header h2 {
                font-size: 1.6rem;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">

        <!-- Left: Brand Panel -->
        <div class="auth-brand">
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>

            <div class="brand-content">
                <img class="brand-logo" src="{{ asset('assets/img/tusuka_logo.png') }}" alt="Tusuka Wash">

                {{-- <div class="brand-badge">Admin Panel</div> --}}

                {{-- <p class="brand-title">Wash Report,<br>Management</p> --}}

                <p class="brand-subtitle">
                    Manage your wash reports and services all in one place with the Tusuka Wash dashboard.
                </p>

                <img class="brand-illustration" src="{{ asset('assets/wwd.png') }}" alt="Wash Tusuka illustration">

                <ul class="brand-features">
                    <li><i class="fas fa-check"></i> Smart Report Management</li>
                    <li><i class="fas fa-check"></i> Real-time Tracking</li>
                    <li><i class="fas fa-check"></i> Secure & Reliable</li>
                </ul>
            </div>
        </div>

        <!-- Right: Login Form -->
        <div class="auth-form-side">
            <div class="mobile-brand">
                <img src="{{ asset('assets/img/tusuka_logo.png') }}" alt="Tusuka Wash">
            </div>

            <div class="form-card">
                <div class="form-header">
                    <h2>Welcome Back!</h2>
                    <p>Sign in to your account to continue</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (Session::has('message'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <p class="mb-0">{{ Session::get('message') }}</p>
                    </div>
                @endif

                <form action="{{ url('login-post') }}" method="post" id="loginForm">
                    @csrf

                    <div class="field-group">
                        <label class="field-label" for="inputEmail">
                            {{ trans('language.label_email') }} <span>*</span>
                        </label>
                        <div class="input-wrap">
                            <i class="fas fa-envelope"></i>
                            <input class="form-control" id="inputEmail" type="email" name="email"
                                placeholder="name@example.com" required autofocus />
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="inputPassword">
                            {{ trans('language.label_password') }} <span>*</span>
                        </label>
                        <div class="input-wrap">
                            <i class="fas fa-lock"></i>
                            <input class="form-control" id="inputPassword" name="password" type="password"
                                placeholder="••••••••" required />
                            <button type="button" class="toggle-password" onclick="togglePassword(this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <div class="form-check">
                            <input class="form-check-input" id="inputRememberPassword" type="checkbox" />
                            <label class="form-check-label" for="inputRememberPassword">
                                {{ trans('language.label_remember') }}
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" id="loginBtn">
                        <span class="spinner"></span>{{ trans('language.login') }}
                    </button>
                </form>

                <div class="copyright">
                    &copy; {{ date('Y') }} Tusuka Wash. All rights reserved.
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/backend/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/7e596160a4.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/backend/scripts.js') }}"></script>

    <script>
        function togglePassword(btn) {
            const passwordInput = document.getElementById('inputPassword');
            const toggleIcon = btn.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Loading state on submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        });

        // Re-enable button when returning via browser back (e.g. validation errors)
        window.addEventListener('pageshow', function() {
            const btn = document.getElementById('loginBtn');
            btn.classList.remove('loading');
            btn.disabled = false;
        });
    </script>
</body>

</html>