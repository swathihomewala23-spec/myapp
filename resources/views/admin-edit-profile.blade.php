<!-- @extends('layouts.admin')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Edit Profile</h2>
        </div>

        <form action="{{ route('admin.profile.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Image*</label>
                <div class="flex items-center gap-6">
                    @if($user->profile_image)
                        <img src="{{ \App\Support\MediaPath::url($user->profile_image) }}" alt="Profile" class="w-24 h-24 rounded-lg object-cover border">
                    @endif
                    <div>
                        <input type="file" name="profile_image" class="text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-gray-500">Upload square size image for best quality.</p>
                        @error('profile_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Username*</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 @error('username') border-red-500 @enderror">
                    @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email*</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">First Name*</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 @error('first_name') border-red-500 @enderror">
                    @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Last Name*</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 @error('last_name') border-red-500 @enderror">
                    @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">City</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">State</label>
                    <input type="text" name="state" value="{{ old('state', $user->state ?? 'Tamilnadu') }}" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Zip Code</label>
                    <input type="text" name="zip_code" value="{{ old('zip_code', $user->zip_code) }}" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Address</label>
                <textarea name="address" rows="3" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('address', $user->address) }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Describe about you</label>
                <textarea name="about" rows="4" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('about', $user->about) }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition duration-150">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection -->

@extends('layouts.admin')

@section('content')

<!-- Professional Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>

    *{
        font-family:'Inter',sans-serif;
    }

    body{
        background:
            radial-gradient(circle at top left, rgba(37,99,235,.08), transparent 28%),
            radial-gradient(circle at bottom right, rgba(59,130,246,.08), transparent 28%),
            #f4f7fb;
    }

    .profile-wrapper{
        min-height:100vh;
        padding:40px 20px;
        animation:pageFade .8s ease;
    }

    @keyframes pageFade{
        from{
            opacity:0;
            transform:translateY(20px);
        }
        to{
            opacity:1;
            transform:translateY(0);
        }
    }

    .profile-card{
        max-width:1200px;
        margin:auto;
        background:rgba(255,255,255,.92);
        backdrop-filter:blur(12px);
        border-radius:32px;
        overflow:hidden;
        border:1px solid rgba(255,255,255,.7);
        box-shadow:
            0 25px 60px rgba(15,23,42,.08),
            inset 0 1px 0 rgba(255,255,255,.5);
        animation:cardZoom .7s ease;
    }

    @keyframes cardZoom{
        from{
            opacity:0;
            transform:scale(.96);
        }
        to{
            opacity:1;
            transform:scale(1);
        }
    }

    .profile-header{
        position:relative;
        padding:38px 42px;
        background:linear-gradient(135deg,#2563eb,#1d4ed8,#0f172a);
        overflow:hidden;
    }

    .profile-header::before{
        content:"";
        position:absolute;
        width:320px;
        height:320px;
        border-radius:50%;
        background:rgba(255,255,255,.08);
        top:-120px;
        right:-80px;
    }

    .profile-header h2{
        position:relative;
        z-index:2;
        color:#fff;
        font-size:2rem;
        font-weight:800;
        margin-bottom:8px;
        letter-spacing:-1px;
    }

    .profile-header p{
        position:relative;
        z-index:2;
        color:rgba(255,255,255,.82);
        font-size:15px;
    }

    .profile-body{
        padding:40px;
    }

    .success-alert{
        margin-bottom:25px;
        padding:16px 20px;
        border-radius:18px;
        background:#ecfdf5;
        border:1px solid #bbf7d0;
        color:#166534;
        font-weight:600;
        animation:slideTop .5s ease;
    }

    @keyframes slideTop{
        from{
            opacity:0;
            transform:translateY(-15px);
        }
        to{
            opacity:1;
            transform:translateY(0);
        }
    }

    .profile-layout{
        display:grid;
        grid-template-columns:320px 1fr;
        gap:35px;
    }

    /* LEFT SIDE */

    .profile-sidebar{
        background:#f8fbff;
        border:1px solid #e2e8f0;
        border-radius:28px;
        padding:30px 24px;
        text-align:center;
        height:fit-content;
        animation:slideLeft .8s ease;
    }

    @keyframes slideLeft{
        from{
            opacity:0;
            transform:translateX(-25px);
        }
        to{
            opacity:1;
            transform:translateX(0);
        }
    }

    .profile-image-wrap{
        width:180px;
        height:180px;
        margin:auto;
        position:relative;
    }

    .profile-image{
        width:100%;
        height:100%;
        object-fit:cover;
        border-radius:30px;
        border:5px solid #fff;
        box-shadow:0 20px 40px rgba(37,99,235,.18);
        transition:.4s ease;
    }

    .profile-image:hover{
        transform:scale(1.04);
    }

    .upload-btn{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        margin-top:24px;
        padding:13px 24px;
        border-radius:999px;
        background:linear-gradient(135deg,#2563eb,#1d4ed8);
        color:#fff;
        font-size:14px;
        font-weight:700;
        cursor:pointer;
        transition:.3s ease;
        box-shadow:0 14px 28px rgba(37,99,235,.22);
    }

    .upload-btn:hover{
        transform:translateY(-3px);
        box-shadow:0 20px 35px rgba(37,99,235,.28);
    }

    .upload-note{
        margin-top:14px;
        color:#64748b;
        font-size:13px;
        line-height:1.7;
    }

    /* RIGHT SIDE */

    .profile-form{
        animation:slideRight .8s ease;
    }

    @keyframes slideRight{
        from{
            opacity:0;
            transform:translateX(25px);
        }
        to{
            opacity:1;
            transform:translateX(0);
        }
    }

    .form-grid{
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:22px;
        margin-bottom:22px;
    }

    .form-grid.three{
        grid-template-columns:repeat(3,minmax(0,1fr));
    }

    .form-group{
        display:flex;
        flex-direction:column;
        gap:10px;
    }

    .form-group.full{
        grid-column:1 / -1;
    }

    .form-group label{
        font-size:14px;
        font-weight:700;
        color:#0f172a;
    }

    .form-control{
        width:100%;
        height:56px;
        border:1px solid #dbe4f0;
        border-radius:16px;
        padding:0 18px;
        font-size:15px;
        background:#fff;
        color:#0f172a;
        transition:.3s ease;
    }

    textarea.form-control{
        height:auto;
        min-height:140px;
        padding:16px 18px;
        resize:vertical;
    }

    .form-control:focus{
        outline:none;
        border-color:#3b82f6;
        box-shadow:0 0 0 4px rgba(59,130,246,.12);
        transform:translateY(-1px);
    }

    .form-control:hover{
        border-color:#93c5fd;
    }

    .error-text{
        font-size:13px;
        color:#dc2626;
        font-weight:500;
    }

    .save-btn-wrap{
        display:flex;
        justify-content:flex-end;
        margin-top:10px;
    }

    .save-btn{
        border:none;
        padding:15px 34px;
        border-radius:18px;
        background:linear-gradient(135deg,#2563eb,#1d4ed8);
        color:#fff;
        font-size:15px;
        font-weight:700;
        cursor:pointer;
        transition:.35s ease;
        box-shadow:0 18px 35px rgba(37,99,235,.22);
    }

    .save-btn:hover{
        transform:translateY(-3px);
        box-shadow:0 25px 45px rgba(37,99,235,.3);
    }

    .save-btn:active{
        transform:scale(.98);
    }

    @media(max-width:991px){

        .profile-layout{
            grid-template-columns:1fr;
        }

        .form-grid,
        .form-grid.three{
            grid-template-columns:1fr;
        }

    }

    @media(max-width:600px){

        .profile-wrapper{
            padding:24px 12px;
        }

        .profile-header,
        .profile-body{
            padding:26px 20px;
        }

        .profile-header h2{
            font-size:1.6rem;
        }

        .profile-sidebar{
            padding:24px 18px;
        }

    }

</style>

<div class="profile-wrapper">

    <div class="profile-card">

        <!-- Header -->
        <div class="profile-header">    

            <h2>Edit Profile</h2>

            <p>
                Update your profile information, account settings,
                and personal details professionally.
            </p>

        </div>

        <div class="profile-body">

            @if (session('status'))
                <div class="success-alert">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('admin.profile.update', $user->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="profile-layout">

                    <!-- LEFT SIDE -->
                    <div class="profile-sidebar">

                        <div class="profile-image-wrap">

                            <img
                                id="profilePreview"
                                src="{{ $user->profile_image ? \App\Support\MediaPath::url($user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->first_name ?? 'Admin') . '&background=2563eb&color=fff&size=300' }}"
                                alt="Profile"
                                class="profile-image">

                        </div>

                        <label for="profileImageInput" class="upload-btn">
                            Choose Photo
                        </label>

                        <input
                            type="file"
                            id="profileImageInput"
                            name="profile_image"
                            hidden
                            accept="image/*">

                        <div class="upload-note">
                            Upload high quality square image for best appearance.
                        </div>

                        @error('profile_image')
                            <div class="error-text" style="margin-top:12px;">
                                {{ $message }}
                            </div>
                        @enderror
                        
                    </div>

                    <!-- RIGHT SIDE -->
                    <div class="profile-form">

                        <div class="form-grid">

                            <div class="form-group">

                                <label>Username*</label>

                                <input
                                    type="text"
                                    name="username"
                                    value="{{ old('username', $user->username) }}"
                                    class="form-control">

                                @error('username')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror

                            </div>

                            <div class="form-group">

                                <label>Email*</label>

                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $user->email) }}"
                                    class="form-control">

                                @error('email')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror

                            </div>

                        </div>

                        <div class="form-grid">

                            <div class="form-group">

                                <label>Phone</label>

                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone', $user->phone) }}"
                                    class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Zip Code</label>

                                <input
                                    type="text"
                                    name="zip_code"
                                    value="{{ old('zip_code', $user->zip_code) }}"
                                    class="form-control">

                            </div>

                        </div>

                        <div class="form-grid">

                            <div class="form-group">

                                <label>First Name*</label>

                                <input
                                    type="text"
                                    name="first_name"
                                    value="{{ old('first_name', $user->first_name) }}"
                                    class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Last Name*</label>

                                <input
                                    type="text"
                                    name="last_name"
                                    value="{{ old('last_name', $user->last_name) }}"
                                    class="form-control">

                            </div>

                        </div>

                        <div class="form-grid three">

                            <div class="form-group">

                                <label>City</label>

                                <input
                                    type="text"
                                    name="city"
                                    value="{{ old('city', $user->city) }}"
                                    class="form-control">

                            </div>

                            <div class="form-group">

                                <label>State</label>

                                <input
                                    type="text"
                                    name="state"
                                    value="{{ old('state', $user->state ?? 'Tamilnadu') }}"
                                    class="form-control">

                            </div>

                            <div class="form-group">

                                <label>Zip Code</label>

                                <input
                                    type="text"
                                    name="zip_code"
                                    value="{{ old('zip_code', $user->zip_code) }}"
                                    class="form-control">

                            </div>

                        </div>

                        <div class="form-group full">

                            <label>Address</label>

                            <textarea
                                name="address"
                                class="form-control">{{ old('address', $user->address) }}</textarea>

                        </div>

                        <div class="form-group full" style="margin-top:22px;">

                            <label>Describe About You</label>

                            <textarea
                                name="about"
                                class="form-control">{{ old('about', $user->about) }}</textarea>

                        </div>

                        <div class="save-btn-wrap">

                            <button type="submit" class="save-btn">
                                Save Changes
                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<script>

    const profileImageInput = document.getElementById('profileImageInput');
    const profilePreview = document.getElementById('profilePreview');

    profileImageInput.addEventListener('change', function(event){

        const file = event.target.files[0];

        if(file){

            const reader = new FileReader();

            reader.onload = function(e){

                profilePreview.src = e.target.result;

            }

            reader.readAsDataURL(file);

        }

    });

</script>

@endsection
