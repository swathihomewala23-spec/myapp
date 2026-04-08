<!DOCTYPE html>
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
            <a href="{{ route('admin.section', ['user' => $user, 'section' => 'registered-users']) }}" class="back-link">Back to Registered Users</a>

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

            <form method="POST" action="{{ $mode === 'create' ? route('admin.users.store', $user) : route('admin.users.update', ['user' => $user, 'targetUser' => $targetUser]) }}">
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
</html>
