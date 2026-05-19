<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login -  Homewala</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-dim: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
        }

        .background-blobs {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            filter: blur(100px);
        }

        .blob {
            position: absolute;
            width: 400px;
            height: 400px;
            background: var(--primary);
            border-radius: 50%;
            opacity: 0.1;
            animation: move 20s infinite alternate;
        }

        .blob.one { top: -100px; left: -100px; }
        .blob.two { bottom: -100px; right: -100px; background: #818cf8; animation-delay: -5s; }

        @keyframes move {
            0% { transform: translate(0, 0); }
            100% { transform: translate(100px, 100px); }
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 48px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            font-size: 32px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .logo i {
            color: var(--primary);
        }

        .subtitle {
            color: var(--text-dim);
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .label {
            display: block;
            color: var(--text-dim);
            font-size: 14px;
            margin-bottom: 8px;
            margin-left: 4px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-dim);
            transition: color 0.3s;
        }

        input {
            width: 100%;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 14px 16px 14px 44px;
            color: #fff;
            font-size: 15px;
            transition: all 0.3s;
            outline: none;
        }

        input:focus {
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.06);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        input:focus + i {
            color: var(--primary);
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-dim);
            font-size: 14px;
            cursor: pointer;
        }

        .remember input {
            width: auto;
            cursor: pointer;
        }

        .forgot {
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .btn {
            width: 100%;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
        }

        .btn:active {
            transform: translateY(0);
        }

        .error-msg {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            padding: 12px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Loading animation */
        .btn.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="background-blobs">
        <div class="blob one"></div>
        <div class="blob two"></div>
    </div>

    <div class="login-card">
        <div class="header">
            <div class="logo">
                <i class="fas fa-cube"></i>
                <span>Admin Panel</span>
            </div>
            <p class="subtitle">Securely sign in to your dashboard</p>
        </div>

        @if ($errors->any())
            <div class="error-msg">
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" id="loginForm">
            @csrf
            <div class="form-group">
                <label class="label">Username</label>
                <div class="input-wrapper">
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="Enter username" required autofocus>
                    <i class="fas fa-user"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="label">Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password" placeholder="••••••••" required>
                    <i class="fas fa-lock"></i>
                </div>
            </div>

            <div class="options">
                <label class="remember">
                    <input type="checkbox" name="remember">
                    Keep me logged in
                </label>
                <a href="#" class="forgot">Forgot password?</a>
            </div>

            <button type="submit" class="btn" id="submitBtn">
                <span>Sign In</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>
    </div>

    <script>
        const form = document.getElementById('loginForm');
        const btn = document.getElementById('submitBtn');

        form.addEventListener('submit', () => {
            btn.classList.add('loading');
            btn.innerHTML = '<div class="spinner"></div><span>Authenticating...</span>';
        });
    </script>
</body>
</html> -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }} Admin</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Nunito+Sans:400,500,600,700" rel="stylesheet" />
        <style>
            :root {
                --brand-blue: #1e9fe4;
                --brand-navy: #09124d;
                --page-bg: #ffffff;
                --text: #111111;
                --muted: #8e8e8e;
                --border: #c4c4c4;
                --border-focus: #5aa8ee;
                --card-shadow: 0 24px 70px rgba(35, 72, 117, 0.14);
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                font-family: 'Poppins', sans-serif;
                color: var(--text);
                background: var(--page-bg);
            }

            .page-shell {
                min-height: 100vh;
                display: grid;
                place-items: center;
                padding: 56px 20px;
            }

            .auth-wrap {
                width: min(100%, 540px);
            }

            .brand {
                margin: 0 0 42px;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-wrap: wrap;
                gap: 0 8px;
                font-size: 2rem;
                font-weight: 700;
                letter-spacing: -0.05em;
                line-height: 1;
            }

            .brand-logo {
                width: 180px;
                height: auto;
                display: block;
            }

            .brand-text {
                display: inline-flex;
                align-items: center;
                gap: 0 2px;
            }

            .brand-dark {
                color: var(--brand-navy);
            }

            .brand-blue {
                color: var(--brand-blue);
            }

            .brand-dotcom {
                color: var(--brand-navy);
                font-size: 0.42em;
                font-weight: 600;
                vertical-align: middle;
                margin-left: 4px;
            }

            .card {
                background: rgba(255, 255, 255, 0.92);
                border-radius: 26px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
                padding: 24px 36px 24px;
                backdrop-filter: blur(8px);
            }

            .card-header {
                margin-bottom: 18px;
                text-align: center;
            }

            .eyebrow {
                margin: 0;
                font-size: 1rem;
                font-weight: 400;
                text-align: center;
                color: #1b1b1b;
            }

            .title {
                margin: 10px 0 0;
                font-size: clamp(4.1rem);
                line-height: 0.9;
                font-weight: 400;
                letter-spacing: -0.06em;
                color: #000000;
            }

            .status {
                margin: 0 0 18px;
                padding: 12px 16px;
                border-radius: 14px;
                background: rgba(34, 197, 94, 0.12);
                color: #166534;
                font-size: 0.95rem;
            }

            .field {
                display: grid;
                align-content: start;
                gap: 10px;
            }

            .field-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 18px;
                align-items: start;
            }

            .auth-form {
                display: grid;
                gap: 18px;
            }

            label {
                display: block;
                font-size: 0.95rem;
                font-weight: 500;
                line-height: 1.4;
                color: #151515;
            }

            input {
                display: block;
                width: 100%;
                height: 48px;
                border: 1px solid var(--border);
                border-radius: 10px;
                padding: 0 22px;
                outline: none;
                font: inherit;
                color: var(--text);
                background: #fff;
                transition: border-color 0.18s ease, box-shadow 0.18s ease;
            }

            input::placeholder {
                color: #ababab;
            }

            input:focus {
                border-color: var(--border-focus);
                box-shadow: 0 0 0 3px rgba(90, 168, 238, 0.12);
            }

            .error-text {
                font-size: 0.82rem;
                line-height: 1.45;
                color: #dc2626;
            }

            .submit {
                width: 100%;
                height: 44px;
                border: 0;
                border-radius: 10px;
                background: linear-gradient(90deg, #1e9fe4 0%, #1d99de 100%);
                color: #fff;
                font: inherit;
                font-size: 1rem;
                font-weight: 500;
                cursor: pointer;
                box-shadow: 0 12px 24px rgba(30, 159, 228, 0.28);
                transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
            }

            .submit:hover {
                filter: brightness(1.02);
                transform: translateY(-1px);
                box-shadow: 0 14px 28px rgba(30, 159, 228, 0.32);
            }

            .submit:active {
                transform: translateY(0);
            }

            @media (max-width: 640px) {
                .page-shell {
                    padding: 30px 14px;
                }

                .brand {
                    font-size: 1.7rem;
                    margin-bottom: 26px;
                }

                .card {
                    padding: 22px 18px 22px;
                    border-radius: 18px;
                }

                .field-grid {
                    grid-template-columns: 1fr;
                    gap: 18px;
                }
            }
        </style>
    </head>
    <body>
        <main class="page-shell">
            <section class="auth-wrap">
                <h1 class="brand" aria-label="Homewala">
                    <img src="{{ asset('images/homewala-logo.png') }}" alt="Homewala logo" class="brand-logo" />
                </h1>

                <div class="card">
                    <div class="card-header">
                        <p class="eyebrow">Welcome to</p>
                        <h1 class="title">Admin</h1>
                    </div>

                    @if (session('status'))
                        <div class="status">{{ session('status') }}</div>
                    @endif

                    <form class="auth-form" method="POST" action="{{ route('admin.register.store') }}">
                        @csrf

                        <div class="field">
                            <label for="username">Username or email address (Optional)</label>
                            <input
                                id="username"
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                placeholder="Username or email address"
                                autocomplete="username"
                                autocapitalize="off"
                                spellcheck="false"
                            >
                            @error('username')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field-grid">
                            <div class="field">
                                <label for="name">User name *</label>
                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="User name"
                                    autocomplete="name"
                                    autocapitalize="words"
                                >
                                @error('name')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="contact_number">Contact Number</label>
                                <input
                                    id="contact_number"
                                    type="text"
                                    name="contact_number"
                                    value="{{ old('contact_number') }}"
                                    placeholder="Contact Number"
                                    autocomplete="tel"
                                >
                                @error('contact_number')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="field">
                            <label for="password">Password *</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Enter password"
                                autocomplete="new-password"
                            >
                            @error('password')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="submit" type="submit">Sign up</button>
                    </form>
                </div>
            </section>
        </main>
    </body>
</html>
