<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Homewala</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --card: #ffffff;
            --line: #d6deea;
            --text: #111827;
            --muted: #6b7280;
            --brand: #27a2e5;
            --brand-dark: #1788d4;
            --shadow: 0 28px 70px rgba(53, 82, 120, 0.18);
        }

        * {
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            background:
                radial-gradient(circle at top, rgba(39, 162, 229, 0.12), transparent 32%),
                linear-gradient(180deg, #fbfdff 0%, #f4f8fd 100%);
            color: var(--text);
        }

        .login-shell {
            width: 100%;
            max-width: 540px;
            display: grid;
            gap: 24px;
            justify-items: center;
        }

        .brand-mark img {
            width: 220px;
            max-width: 100%;
            height: auto;
            display: block;
        }

        .login-card {
            width: 100%;
            background: var(--card);
            border-radius: 28px;
            padding: 28px 36px 30px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(214, 222, 234, 0.9);
        }

        .header {
            text-align: center;
            margin-bottom: 22px;
        }

        .welcome {
            margin: 0;
            color: #374151;
            font-size: 1rem;
            font-weight: 400;
        }

        .title {
            margin: 8px 0 0;
            font-size: 2rem;
            font-weight: 500;
            color: #111827;
        }

        .error-msg {
            margin-bottom: 18px;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid #fecaca;
            background: #fff1f2;
            color: #b91c1c;
            font-size: 0.92rem;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .label {
            display: block;
            margin-bottom: 8px;
            color: #202938;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .control {
            width: 100%;
            min-height: 50px;
            border: 1px solid #cfd8e3;
            border-radius: 12px;
            padding: 13px 22px;
            font-size: 0.98rem;
            color: #111827;
            background: #fff;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .control::placeholder {
            color: #a3aab4;
        }

        .control:focus {
            border-color: rgba(39, 162, 229, 0.75);
            box-shadow: 0 0 0 4px rgba(39, 162, 229, 0.12);
        }

        .options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin: 2px 0 18px;
            flex-wrap: wrap;
        }

        .remember {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .remember input {
            width: 15px;
            height: 15px;
            accent-color: var(--brand);
        }

        .forgot {
            color: var(--brand);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .btn {
            width: 100%;
            border: 0;
            border-radius: 12px;
            padding: 15px 20px;
            background: linear-gradient(180deg, var(--brand) 0%, #2496d7 100%);
            color: #fff;
            font-size: 1.02rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 28px rgba(39, 162, 229, 0.25);
            background: linear-gradient(180deg, #32aae8 0%, var(--brand-dark) 100%);
        }

        .btn.loading {
            pointer-events: none;
            opacity: 0.88;
        }

        .btn-content {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 640px) {
            .login-card {
                padding: 24px 20px;
                border-radius: 22px;
            }

            .title {
                font-size: 1.85rem;
            }

            .brand-mark img {
                width: 190px;
            }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <div class="brand-mark">
            <img src="{{ asset('images/homewala-logo.png') }}" alt="Homewala">
        </div>

        <div class="login-card">
            <div class="header">
                <p class="welcome">Welcome to</p>
                <h1 class="title">Admin</h1>
            </div>

            @if ($errors->any())
                <div class="error-msg">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" id="loginForm">
                @csrf

                <div class="form-group">
                    <label class="label" for="username">Username or email address</label>
                    <input
                        id="username"
                        class="control"
                        type="text"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Username or email address"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label class="label" for="password">Password</label>
                    <input
                        id="password"
                        class="control"
                        type="password"
                        name="password"
                        placeholder="Enter password"
                        required
                    >
                </div>

                <div class="options">
                    <label class="remember">
                        <input type="checkbox" name="remember">
                        <span>Keep me logged in</span>
                    </label>
                    <a href="#" class="forgot">Forgot password?</a>
                </div>

                <button type="submit" class="btn" id="submitBtn">
                    <span class="btn-content">
                        <span>Sign In</span>
                    </span>
                </button>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('loginForm');
        const button = document.getElementById('submitBtn');

        form.addEventListener('submit', () => {
            button.classList.add('loading');
            button.innerHTML = '<span class="btn-content"><span class="spinner"></span><span>Signing in...</span></span>';
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
