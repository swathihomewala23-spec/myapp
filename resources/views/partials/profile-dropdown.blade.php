@php
    $displayName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: ($user->name ?? 'Admin User');
    $avatarLetter = strtoupper(substr($displayName, 0, 1));
@endphp

<div style="display:flex; align-items:center; gap:12px;">
    <details class="profile" style="position:relative;">
        <summary class="profile-toggle" style="cursor:pointer;">
            <div class="avatar">{{ $avatarLetter }}</div>
        </summary>

        <div class="profile-menu" style="right:0; left:auto;">
            <div class="profile-card">
                <div class="avatar">{{ $avatarLetter }}</div>

                <div>
                    <strong>{{ $displayName }}</strong>
                    <span>{{ $user->email }}</span>
                </div>
            </div>

            <a class="profile-menu-link" href="{{ route('admin.section', ['section' => 'edit-profile']) }}">Edit Profile</a>
            <a class="profile-menu-link" href="{{ route('admin.section', ['section' => 'change-password']) }}">Change Password</a>

            <form action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a class="profile-menu-link logout-link" href="#" onclick="event.preventDefault(); this.closest('details').querySelector('form').submit();">Logout</a>
        </div>
    </details>
</div>
