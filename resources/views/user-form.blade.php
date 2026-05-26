{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mode === 'create' ? 'Add User' : 'Edit User' }}</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Nunito Sans', Arial, sans-serif;
            background: #f5f7fc;
            color: #1f2937;
        }

        .panel {
            max-width: 760px;
            margin: 20px auto;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .panel-head {
            padding: 14px 18px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 24px;
            font-weight: 700;
            color: #1f2a44;
        }

        .panel-body {
            padding: 20px 22px 24px;
        }

        .alert {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 6px;
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #991b1b;
            font-size: 14px;
        }

        .alert ul {
            margin: 8px 0 0;
            padding-left: 18px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px 18px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: #243247;
            margin-bottom: 6px;
        }

        .required {
            color: #ef4444; 
            margin-left: 2px;
        }

        .form-group input {
            width: 100%;
            height: 40px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 0 11px;
            font-size: 14px;
            color: #111827;
            background: #fff;
        }

        .form-group input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .actions {
            margin-top: 14px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            display: flex;
            justify-content: center;
        }

        .btn-save {
            border: 0;
            border-radius: 4px;
            background: #22c55e;
            color: #fff;
            padding: 10px 22px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-save:hover { background: #16a34a; }

        .back-link {
            display: inline-block;
            margin-bottom: 10px;
            color: #2563eb;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        @media (max-width: 860px) {
            .panel { margin: 14px; }
            .panel-head { font-size: 22px; }
            .panel-body { padding: 18px; }
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="panel">
        <div class="panel-head">{{ $mode === 'create' ? 'Add User' : 'Edit User' }}</div>

        <div class="panel-body">
            <a href="{{ route('admin.section', ['section' => 'registered-users']) }}" class="back-link">Back to Registered Users</a>

            @if ($errors->any())
                <div class="alert">
                    Please fix the following errors:
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ $mode === 'create' ? route('admin.users.store') : route('admin.users.update', ['targetUser' => $targetUser]) }}">
                @csrf
                @if ($mode === 'edit')
                    @method('PUT')
                @endif

                <div class="grid">
                    <div class="form-group">
                        <label>First Name<span class="required">*</span></label>
                        <input type="text" name="first_name" required value="{{ old('first_name', $mode === 'edit' ? ($targetUser->first_name ?? '') : '') }}">
                    </div>

                    <div class="form-group">
                        <label>Last Name<span class="required">*</span></label>
                        <input type="text" name="last_name" required value="{{ old('last_name', $mode === 'edit' ? ($targetUser->last_name ?? '') : '') }}">
                    </div>

                    <div class="form-group">
                        <label>Email<span class="required">*</span></label>
                        <input type="email" name="email" required value="{{ old('email', $mode === 'edit' ? ($targetUser->email ?? '') : '') }}">
                    </div>

                    <div class="form-group">
                        <label>Phone<span class="required">*</span></label>
                        <input type="tel" name="phone" required value="{{ old('phone', $mode === 'edit' ? ($targetUser->phone ?? $targetUser->contact_number ?? '') : '') }}">
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn-save">Save</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html> --}}
<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mode === 'create' ? 'Add User' : 'Edit User' }}</title>

    <style>

        :root{
            --primary:#2563eb;
            --primary-dark:#1d4ed8;
            --success:#22c55e;
            --success-dark:#16a34a;
            --danger:#ef4444;
            --text:#1e293b;
            --text-light:#64748b;
            --border:#e2e8f0;
            --bg:#f1f5f9;
            --white:#ffffff;
        }

        *{
            box-sizing:border-box;
            margin:0;
            padding:0;
            font-family:'Poppins',sans-serif;
        }

        body{
            background:
                radial-gradient(circle at top left, #dbeafe 0%, transparent 30%),
                radial-gradient(circle at bottom right, #bfdbfe 0%, transparent 30%),
                var(--bg);
            min-height:100vh;
            padding:40px 20px;
            animation:fadeInBody .7s ease;
        }

        .panel{
            max-width:780px;
            margin:auto;
            background:rgba(255,255,255,0.96);
            backdrop-filter:blur(8px);
            border:1px solid rgba(255,255,255,0.5);
            border-radius:24px;
            overflow:hidden;
            box-shadow:
                0 10px 40px rgba(15,23,42,0.08),
                0 4px 12px rgba(15,23,42,0.05);
            animation:panelPop .7s cubic-bezier(.16,1,.3,1);
        }

        .panel-head{
            padding:24px 30px;
            background:linear-gradient(135deg,var(--primary),#60a5fa);
            color:#fff;
            font-size:28px;
            font-weight:800;
            letter-spacing:.3px;
            position:relative;
            overflow:hidden;
        }

        .panel-head::after{
            content:'';
            position:absolute;
            width:200px;
            height:200px;
            background:rgba(255,255,255,0.08);
            border-radius:50%;
            top:-120px;
            right:-60px;
        }

        .panel-body{
            padding:32px;
        }

        .back-link{
            display:inline-flex;
            align-items:center;
            gap:8px;
            margin-bottom:24px;
            color:var(--primary);
            text-decoration:none;
            font-size:14px;
            font-weight:600;
            transition:.3s ease;
        }

        .back-link:hover{
            transform:translateX(-4px);
            color:var(--primary-dark);
        }

        .alert{
            margin-bottom:24px;
            padding:16px 18px;
            border-radius:14px;
            border:1px solid #fecaca;
            background:#fef2f2;
            color:#991b1b;
            font-size:14px;
            animation:shake .4s ease;
        }

        .alert ul{
            margin-top:10px;
            padding-left:20px;
        }

        .grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:22px;
        }

        .form-group{
            position:relative;
        }

        .form-group label{
            display:block;
            margin-bottom:8px;
            font-size:14px;
            font-weight:700;
            color:var(--text);
        }

        .required{
            color:var(--danger);
        }

        .form-group input{
            width:100%;
            height:52px;
            border:1px solid var(--border);
            border-radius:14px;
            padding:0 16px;
            font-size:14px;
            color:var(--text);
            background:#fff;
            transition:all .3s ease;
        }

        .form-group input:hover{
            border-color:#cbd5e1;
        }

        .form-group input:focus{
            outline:none;
            border-color:var(--primary);
            box-shadow:0 0 0 5px rgba(37,99,235,.12);
            transform:translateY(-2px);
        }

        .actions{
            margin-top:34px;
            padding-top:24px;
            border-top:1px solid var(--border);
            display:flex;
            justify-content:center;
        }

        .btn-save{
            border:none;
            border-radius:14px;
            background:linear-gradient(135deg,var(--success),#4ade80);
            color:#fff;
            padding:14px 34px;
            font-size:15px;
            font-weight:700;
            cursor:pointer;
            transition:all .3s ease;
            box-shadow:0 8px 20px rgba(34,197,94,.22);
        }

        .btn-save:hover{
            background:linear-gradient(135deg,var(--success-dark),var(--success));
            transform:translateY(-3px) scale(1.02);
            box-shadow:0 14px 26px rgba(34,197,94,.28);
        }

        .btn-save:active{
            transform:scale(.98);
        }

        /* Animations */

        @keyframes fadeInBody{
            from{
                opacity:0;
            }
            to{
                opacity:1;
            }
        }

        @keyframes panelPop{
            from{
                opacity:0;
                transform:translateY(30px) scale(.96);
            }
            to{
                opacity:1;
                transform:translateY(0) scale(1);
            }
        }

        @keyframes shake{
            0%,100%{
                transform:translateX(0);
            }
            25%{
                transform:translateX(-4px);
            }
            50%{
                transform:translateX(4px);
            }
            75%{
                transform:translateX(-2px);
            }
        }

        /* Responsive */

        @media(max-width:768px){

            body{
                padding:20px 12px;
            }

            .panel-body{
                padding:22px;
            }

            .panel-head{
                font-size:24px;
                padding:22px;
            }

            .grid{
                grid-template-columns:1fr;
            }
        }

    </style>
</head>

<body>

    <div class="panel">

        <div class="panel-head">
            {{ $mode === 'create' ? 'Add User' : 'Edit User' }}
        </div>

        <div class="panel-body">

            <a href="{{ route('admin.section', ['section' => 'registered-users']) }}" class="back-link">
                ← Back to Registered Users
            </a>

            @if ($errors->any())

                <div class="alert">

                    Please fix the following errors:

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif

            <form method="POST"
                action="{{ $mode === 'create'
                    ? route('admin.users.store')
                    : route('admin.users.update', ['targetUser' => $targetUser]) }}">

                @csrf

                @if ($mode === 'edit')
                    @method('PUT')
                @endif

                <div class="grid">

                    <div class="form-group">
                        <label>
                            First Name
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="first_name"
                            required
                            value="{{ old('first_name', $mode === 'edit' ? ($targetUser->first_name ?? '') : '') }}"
                        >
                    </div>

                    <div class="form-group">
                        <label>
                            Last Name
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="last_name"
                            required
                            value="{{ old('last_name', $mode === 'edit' ? ($targetUser->last_name ?? '') : '') }}"
                        >
                    </div>

                    <div class="form-group">
                        <label>
                            Email
                            <span class="required">*</span>
                        </label>

                        <input
                            type="email"
                            name="email"
                            required
                            value="{{ old('email', $mode === 'edit' ? ($targetUser->email ?? '') : '') }}"
                        >
                    </div>

                    <div class="form-group">
                        <label>
                            Phone
                            <span class="required">*</span>
                        </label>

                        <input
                            type="tel"
                            name="phone"
                            required
                            value="{{ old('phone', $mode === 'edit' ? ($targetUser->phone ?? $targetUser->contact_number ?? '') : '') }}"
                        >
                    </div>

                </div>

                <div class="actions">

                    <button type="submit" class="btn-save">
                        Save
                    </button>

                </div>

            </form>

        </div>

    </div>

</body>
</html>