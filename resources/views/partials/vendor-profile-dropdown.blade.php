@php
    $displayName = trim(($vendor->first_name ?? '') . ' ' . ($vendor->last_name ?? '')) ?: ($vendor->name ?? 'Vendor');
    $avatarLetter = strtoupper(substr($displayName, 0, 1));
@endphp

<div class="dropdown-profile-card">
    <div class="avatar-icon">
        @if($vendor->photo)
            <img src="{{ \App\Support\MediaPath::url($vendor->photo) }}" alt="{{ $displayName }}">
        @else
            {{ $avatarLetter }}
        @endif
    </div>
    <div>
        <strong>{{ $displayName }}</strong>
        <span>{{ $vendor->email ?? '' }}</span>
    </div>
</div>

<a href="{{ route('vendor.section', 'edit-profile') }}" class="dropdown-link">Edit Profile</a>
<a href="{{ route('vendor.section', 'change-password') }}" class="dropdown-link">Change Password</a>

<form action="{{ route('vendor.logout') }}" method="POST" style="display: none;">
    @csrf
</form>
<a href="#" class="dropdown-link" onclick="event.preventDefault(); this.closest('.avatar-dropdown').querySelector('form').submit();">Logout</a>
