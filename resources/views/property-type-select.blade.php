<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Property Type</title>
    <style>
        body {
            margin: 0;
            font-family: 'Nunito Sans', Arial, sans-serif;
            background: #f5f7ff;
            color: #1f2937;
        }

        .wrapper {
            max-width: 1180px;
            margin: 22px auto;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .head {
            padding: 20px;
            border-bottom: 1px solid #eceff4;
            font-size: 31px;
            font-weight: 700;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            padding: 20px;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            border: 1px solid #eceff4;
            box-shadow: 0 4px 16px rgba(17, 24, 39, 0.06);
            min-height: 150px;
            padding: 20px 18px;
            text-align: center;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(17, 24, 39, 0.1);
        }

        .icon {
            width: 56px;
            height: 56px;
            margin: 0 auto 12px;
            border-radius: 6px;
            display: grid;
            place-items: center;
            color: #fff;
        }

        .icon svg {
            width: 28px;
            height: 28px;
        }

        .icon.commercial { background: #22c55e; }
        .icon.residential { background: #f59e0b; }

        .title {
            font-size: 31px;
            font-weight: 700;
            margin: 0 0 6px;
            color: #1f2a44;
        }

        .count {
            margin: 0;
            color: #6b7280;
            font-size: 22px;
            font-weight: 600;
        }

        @media (max-width: 900px) {
            .grid { grid-template-columns: 1fr; }
            .head, .title { font-size: 28px; }
            .count { font-size: 20px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="head">Choose Property Type</div>

        <div class="grid">
            <a href="{{ route('admin.section', ['user' => $user, 'section' => 'manage-properties']) }}" class="card">
                <div class="icon commercial">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="4" y="3" width="16" height="18" rx="1"></rect>
                        <path d="M9 21v-4h6v4"></path>
                        <path d="M8 7h.01M12 7h.01M16 7h.01M8 11h.01M12 11h.01M16 11h.01"></path>
                    </svg>
                </div>
                <h3 class="title">COMMERCIAL</h3>
                <p class="count">Total:{{ $commercialCount }} Properties</p>
            </a>

            <a href="{{ route('admin.section', ['user' => $user, 'section' => 'manage-properties']) }}" class="card">
                <div class="icon residential">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 11.5 12 4l9 7.5"></path>
                        <path d="M5 10.5V20h5v-4h4v4h5v-9.5"></path>
                    </svg>
                </div>
                <h3 class="title">RESIDENTIAL</h3>
                <p class="count">Total:{{ $residentialCount }} Properties</p>
            </a>
        </div>
    </div>
</body>
</html>
