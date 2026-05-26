<style>
    .user-form-page {
        padding: 30px;
        background: #f8fafc;
        min-height: calc(100vh - 120px);
    }

    .user-form-card {
        max-width: 760px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .user-form-head {
        padding: 16px 20px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
    }

    .user-form-body {
        padding: 22px;
    }

    .user-form-back {
        display: inline-block;
        margin-bottom: 16px;
        color: #2563eb;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
    }

    .user-form-alert {
        margin-bottom: 16px;
        padding: 12px 14px;
        border-radius: 6px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #991b1b;
        font-size: 14px;
    }

    .user-form-alert ul {
        margin: 8px 0 0;
        padding-left: 18px;
    }

    .user-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px 18px;
    }

    .user-form-field label {
        display: block;
        margin-bottom: 7px;
        color: #1f2937;
        font-size: 13px;
        font-weight: 700;
    }

    .user-form-required {
        color: #dc2626;
        margin-left: 2px;
    }

    .user-form-field input {
        width: 100%;
        border: 1px solid #dbe2ed;
        border-radius: 6px;
        padding: 11px 12px;
        font-size: 14px;
        color: #111827;
        background: #fff;
    }

    .user-form-field input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .user-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 22px;
        padding-top: 16px;
        border-top: 1px solid #e5e7eb;
    }

    .user-form-btn {
        border: 0;
        border-radius: 6px;
        padding: 10px 18px;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .user-form-btn.secondary {
        background: #e5e7eb;
        color: #374151;
    }

    .user-form-btn.primary {
        background: #1370c7;
        color: #fff;
    }

    .user-form-btn.primary:hover {
        background: #1370c7;
    }

    @media (max-width: 860px) {
        .user-form-page {
            padding: 16px;
        }

        .user-form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="user-form-page">
    <div class="user-form-card">
        <div class="user-form-head">{{ $mode === 'create' ? 'Add User' : 'Edit User' }}</div>

        <div class="user-form-body">
            <a href="{{ route('admin.section', ['section' => 'registered-users']) }}" class="user-form-back">Back to Registered Users</a>

            @if ($errors->any())
                <div class="user-form-alert">
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

                <div class="user-form-grid">
                    <div class="user-form-field">
                        <label>First Name<span class="user-form-required">*</span></label>
                        <input type="text" name="first_name" required value="{{ old('first_name', $mode === 'edit' ? ($targetUser->first_name ?? '') : '') }}">
                    </div>

                    <div class="user-form-field">
                        <label>Last Name<span class="user-form-required">*</span></label>
                        <input type="text" name="last_name" required value="{{ old('last_name', $mode === 'edit' ? ($targetUser->last_name ?? '') : '') }}">
                    </div>

                    <div class="user-form-field">
                        <label>Email<span class="user-form-required">*</span></label>
                        <input type="email" name="email" required value="{{ old('email', $mode === 'edit' ? ($targetUser->email ?? '') : '') }}">
                    </div>

                    <div class="user-form-field">
                        <label>Phone<span class="user-form-required">*</span></label>
                        <input type="tel" name="phone" required value="{{ old('phone', $mode === 'edit' ? ($targetUser->phone ?? $targetUser->contact_number ?? '') : '') }}">
                    </div>
                </div>

                <div class="user-form-actions">
                    <a href="{{ route('admin.section', ['section' => 'registered-users']) }}" class="user-form-btn secondary">Cancel</a>
                    <button type="submit" class="user-form-btn primary">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>
