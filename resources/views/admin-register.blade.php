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
                            <label for="username">Enter your username or email address</label>
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
