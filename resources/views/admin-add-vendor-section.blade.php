<!-- <div class="add-vendor-page">
    <style>
        .add-vendor-page {
            display: grid;
            gap: 22px;
        }

        .add-vendor-shell {
            background: #fff;
            border: 1px solid #e8edf5;
            border-radius: 18px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .add-vendor-head {
            padding: 22px 22px 16px;
            border-bottom: 1px solid #edf2f7;
        }

        .add-vendor-head h2 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 700;
            color: #24364d;
        }

        .add-vendor-body {
            padding: 24px 22px 28px;
        }

        .add-vendor-form {
            display: grid;
            gap: 18px;
        }

        .add-vendor-photo-block {
            display: grid;
            gap: 10px;
            justify-content: start;
        }

        .add-vendor-photo-label {
            font-size: 0.92rem;
            font-weight: 700;
            color: #20304d;
        }

        .add-vendor-photo-preview {
            width: 120px;
            height: 120px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            overflow: hidden;
            display: grid;
            place-items: center;
        }

        .add-vendor-photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .add-vendor-photo-empty {
            text-align: center;
            color: #94a3b8;
            font-size: 0.8rem;
            font-weight: 700;
            line-height: 1.35;
            padding: 10px;
        }

        .add-vendor-choose {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            background: #1d72f3;
            color: #fff;
            padding: 8px 12px;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            width: fit-content;
        }

        .add-vendor-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px 36px;
        }

        .add-vendor-field {
            display: grid;
            gap: 8px;
        }

        .add-vendor-field.full {
            grid-column: 1 / -1;
        }

        .add-vendor-field label {
            font-size: 0.9rem;
            font-weight: 700;
            color: #20304d;
        }

        .add-vendor-field input,
        .add-vendor-field textarea {
            width: 100%;
            border: 1px solid #dbe3ee;
            border-radius: 4px;
            padding: 11px 14px;
            font: inherit;
            color: #0f172a;
            background: #fff;
        }

        .add-vendor-field textarea {
            min-height: 140px;
            resize: vertical;
        }

        .add-vendor-field input:focus,
        .add-vendor-field textarea:focus {
            outline: none;
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.16);
        }

        .add-vendor-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            justify-content: flex-end;
            padding-top: 6px;
        }

        .add-vendor-btn {
            border: 0;
            border-radius: 8px;
            padding: 11px 18px;
            font-weight: 700;
            cursor: pointer;
        }

        .add-vendor-btn.primary {
            background: #1d72f3;
            color: #fff;
        }

        .add-vendor-btn.secondary {
            background: #eef2f7;
            color: #334155;
            text-decoration: none;
        }

        @media (max-width: 780px) {
            .add-vendor-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }
    </style>

    @if ($errors->any())
        <div style="padding: 16px 18px; border-radius: 18px; border: 1px solid #fecaca; background: #fff1f2; color: #b91c1c;">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="add-vendor-shell">
        <div class="add-vendor-head">
            <h2>Add Vendor</h2>
        </div>

        <div class="add-vendor-body">
            <form class="add-vendor-form" method="POST" action="{{ route('admin.vendors.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="add-vendor-photo-block">
                    <div class="add-vendor-photo-label">Photo</div>
                    <div class="add-vendor-photo-preview">
                        <img id="vendorPhotoPreview" src="" alt="Vendor photo preview" style="display:none;">
                        <div id="vendorPhotoPlaceholder" class="add-vendor-photo-empty">
                            NO IMAGE<br>FOUND
                        </div>
                    </div>
                    <label for="vendorPhotoInput" class="add-vendor-choose">Choose Photo</label>
                    <input id="vendorPhotoInput" type="file" name="photo" accept="image/*" hidden>
                </div>

                <div class="add-vendor-grid">
                    <div class="add-vendor-field">
                        <label for="vendorFirstName">First Name*</label>
                        <input id="vendorFirstName" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Enter First Name" required>
                    </div>

                    <div class="add-vendor-field">
                        <label for="vendorLastName">Last Name*</label>
                        <input id="vendorLastName" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Enter Last Name" required>
                    </div>

                    <div class="add-vendor-field">
                        <label for="vendorEmail">Email*</label>
                        <input id="vendorEmail" type="email" name="email" value="{{ old('email') }}" placeholder="Enter Email" required>
                    </div>

                    <div class="add-vendor-field">
                        <label for="vendorPhone">Phone</label>
                        <input id="vendorPhone" type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter Phone">
                    </div>

                    <div class="add-vendor-field full">
                        <label for="vendorDetails">Details</label>
                        <textarea id="vendorDetails" name="details" placeholder="Enter Details">{{ old('details') }}</textarea>
                    </div>
                </div>

                <div class="add-vendor-actions">
                    <a href="{{ route('admin.section', ['section' => 'registered-vendors']) }}" class="add-vendor-btn secondary">Cancel</a>
                    <button type="submit" class="add-vendor-btn primary">Save Vendor</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const photoInput = document.getElementById('vendorPhotoInput');
            const photoPreview = document.getElementById('vendorPhotoPreview');
            const photoPlaceholder = document.getElementById('vendorPhotoPlaceholder');

            if (!photoInput || !photoPreview || !photoPlaceholder) {
                return;
            }

            photoInput.addEventListener('change', (event) => {
                const [file] = event.target.files || [];
                if (!file) {
                    photoPreview.src = '';
                    photoPreview.style.display = 'none';
                    photoPlaceholder.style.display = 'block';
                    return;
                }

                const reader = new FileReader();
                reader.onload = (loadEvent) => {
                    photoPreview.src = loadEvent.target?.result || '';
                    photoPreview.style.display = 'block';
                    photoPlaceholder.style.display = 'none';
                };
                reader.readAsDataURL(file);
            });
        })();
    </script>
</div> -->
<div class="vendor-page">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>

        :root{
            --primary:#1ea4ee;
            --primary-dark:#0d8fd6;
            --dark:#0f172a;
            --text:#64748b;
            --border:#e2e8f0;
            --bg:#f8fbff;
            --white:#ffffff;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Inter',sans-serif;
        }

        body{
            background:var(--bg);
        }

        .vendor-page{
            min-height:100vh;
            padding:40px 24px;
            position:relative;
            overflow:hidden;
        }

        /* Animated background glow */
        .vendor-page::before{
            content:"";
            position:absolute;
            width:420px;
            height:420px;
            border-radius:50%;
            background:rgba(30,164,238,.12);
            top:-140px;
            left:-120px;
            filter:blur(90px);
            animation:floatGlow 8s infinite ease-in-out;
        }

        .vendor-page::after{
            content:"";
            position:absolute;
            width:380px;
            height:380px;
            border-radius:50%;
            background:rgba(59,130,246,.10);
            bottom:-140px;
            right:-100px;
            filter:blur(90px);
            animation:floatGlow 10s infinite ease-in-out;
        }

        @keyframes floatGlow{
            0%,100%{
                transform:scale(1);
            }
            50%{
                transform:scale(1.15);
            }
        }

        .vendor-container{
            max-width:1200px;
            margin:auto;
            position:relative;
            z-index:2;
        }

        /* Top Header */
        .vendor-header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            flex-wrap:wrap;
            gap:20px;
            margin-bottom:28px;

            animation:fadeUp .8s ease;
        }

        .vendor-title-wrap h1{
            font-size:2.2rem;
            font-weight:900;
            color:var(--dark);
            letter-spacing:-1px;
            margin-bottom:10px;
        }

        .vendor-title-wrap p{
            color:var(--text);
            font-size:15px;
        }

        .vendor-badge{
            background:linear-gradient(135deg,#1ea4ee,#0d8fd6);
            color:#fff;
            padding:14px 22px;
            border-radius:999px;
            font-size:14px;
            font-weight:700;
            box-shadow:0 14px 30px rgba(30,164,238,.22);

            animation:floatBadge 4s infinite ease-in-out;
        }

        @keyframes floatBadge{
            0%,100%{
                transform:translateY(0px);
            }
            50%{
                transform:translateY(-5px);
            }
        }

        /* Main Card */
        .vendor-card{
            background:rgba(255,255,255,.82);
            backdrop-filter:blur(18px);

            border:1px solid rgba(255,255,255,.7);

            border-radius:34px;

            box-shadow:
                0 20px 45px rgba(15,23,42,.07),
                inset 0 1px 0 rgba(255,255,255,.7);

            overflow:hidden;

            animation:fadeUp 1s ease;
        }

        /* Card Header */
        .vendor-card-header{
            padding:28px 34px;
            border-bottom:1px solid #edf2f7;

            display:flex;
            justify-content:space-between;
            align-items:center;
            flex-wrap:wrap;
            gap:14px;
        }

        .vendor-card-header h2{
            font-size:1.4rem;
            font-weight:800;
            color:var(--dark);
        }

        .vendor-card-header span{
            color:var(--text);
            font-size:14px;
        }

        /* Form */
        .vendor-form{
            padding:34px;
            display:grid;
            gap:34px;
        }

        .vendor-layout{
            display:grid;
            grid-template-columns:280px 1fr;
            gap:36px;
        }

        /* Left Photo Section */
        .photo-section{
            display:flex;
            flex-direction:column;
            align-items:center;
            gap:18px;
        }

        .photo-box{
            width:220px;
            height:220px;
            border-radius:30px;
            overflow:hidden;
            background:#f8fafc;
            border:2px dashed #cbd5e1;

            display:flex;
            align-items:center;
            justify-content:center;

            position:relative;

            transition:.3s ease;
        }

        .photo-box:hover{
            transform:translateY(-4px);
            border-color:var(--primary);
        }

        .photo-box img{
            width:100%;
            height:100%;
            object-fit:cover;
            display:none;
        }

        .photo-placeholder{
            text-align:center;
            color:#94a3b8;
            font-size:14px;
            font-weight:700;
            line-height:1.7;
        }

        .upload-btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;

            background:linear-gradient(135deg,#1ea4ee,#0d8fd6);
            color:#fff;

            border-radius:16px;

            padding:14px 20px;

            font-size:14px;
            font-weight:700;

            cursor:pointer;

            transition:.3s ease;

            box-shadow:0 12px 24px rgba(30,164,238,.25);
        }

        .upload-btn:hover{
            transform:translateY(-2px);
        }

        /* Right Side */
        .vendor-fields{
            display:grid;
            gap:24px;
        }

        .field-grid{
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:22px;
        }

        .field{
            display:grid;
            gap:10px;
        }

        .field.full{
            grid-column:1 / -1;
        }

        .field label{
            font-size:14px;
            font-weight:700;
            color:#1e293b;
        }

        .field input,
        .field textarea{
            width:100%;
            border:1px solid #dbe3ee;
            border-radius:18px;

            padding:15px 18px;

            font-size:15px;
            color:#0f172a;

            background:#fff;

            transition:.25s ease;
        }

        .field textarea{
            min-height:170px;
            resize:vertical;
        }

        .field input:focus,
        .field textarea:focus{
            outline:none;

            border-color:var(--primary);

            box-shadow:
                0 0 0 4px rgba(30,164,238,.12);

            transform:translateY(-1px);
        }

        /* Buttons */
        .vendor-actions{
            display:flex;
            justify-content:flex-end;
            gap:16px;
            padding-top:8px;
        }

        .vendor-btn{
            border:none;
            padding:14px 26px;
            border-radius:18px;

            font-size:15px;
            font-weight:700;

            cursor:pointer;

            transition:.3s ease;

            text-decoration:none;
        }

        .vendor-btn.cancel{
            background:#eef2f7;
            color:#334155;
        }

        .vendor-btn.cancel:hover{
            background:#e2e8f0;
            transform:translateY(-2px);
        }

        .vendor-btn.save{
            background:linear-gradient(135deg,#1ea4ee,#0d8fd6);
            color:#fff;

            box-shadow:0 15px 30px rgba(30,164,238,.24);
        }

        .vendor-btn.save:hover{
            transform:translateY(-3px);
        }

        /* Error Box */
        .error-box{
            background:#fff1f2;
            border:1px solid #fecaca;
            color:#b91c1c;

            border-radius:22px;

            padding:18px 20px;

            margin-bottom:24px;

            animation:fadeUp .8s ease;
        }

        .error-box div{
            margin-bottom:6px;
        }

        /* Animations */
        @keyframes fadeUp{
            from{
                opacity:0;
                transform:translateY(40px);
            }
            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        /* Responsive */
        @media(max-width:980px){

            .vendor-layout{
                grid-template-columns:1fr;
            }

            .photo-section{
                align-items:flex-start;
            }

        }

        @media(max-width:700px){

            .field-grid{
                grid-template-columns:1fr;
            }

            .vendor-form{
                padding:24px;
            }

            .vendor-card-header{
                padding:24px;
            }

            .vendor-title-wrap h1{
                font-size:1.8rem;
            }

            .photo-box{
                width:180px;
                height:180px;
            }

        }

    </style>

    <div class="vendor-container">

        <!-- Header -->
        <div class="vendor-header">

            <div class="vendor-title-wrap">
                <h1>Add Vendor</h1>
                <p>Create and manage professional vendor accounts easily.</p>
            </div>

            <div class="vendor-badge">
                Homewala Vendor Panel
            </div>

        </div>

        <!-- Errors -->
        @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Card -->
        <div class="vendor-card">

            <div class="vendor-card-header">
                <div>
                    <h2>Vendor Information</h2>
                    <span>Fill all required vendor details carefully.</span>
                </div>
            </div>

            <form class="vendor-form"
                  method="POST"
                  action="{{ route('admin.vendors.store') }}"
                  enctype="multipart/form-data">

                @csrf

                <div class="vendor-layout">

                    <!-- Photo -->
                    <div class="photo-section">

                        <div class="photo-box">

                            <img id="vendorPhotoPreview"
                                 src=""
                                 alt="Vendor Preview">

                            <div id="vendorPhotoPlaceholder"
                                 class="photo-placeholder">

                                NO IMAGE<br>
                                SELECT PHOTO

                            </div>

                        </div>

                        <label for="vendorPhotoInput"
                               class="upload-btn">

                            Choose Photo

                        </label>

                        <input id="vendorPhotoInput"
                               type="file"
                               name="photo"
                               accept="image/*"
                               hidden>

                    </div>

                    <!-- Fields -->
                    <div class="vendor-fields">

                        <div class="field-grid">

                            <div class="field">
                                <label>First Name *</label>

                                <input type="text"
                                       name="first_name"
                                       value="{{ old('first_name') }}"
                                       placeholder="Enter First Name"
                                       required>
                            </div>

                            <div class="field">
                                <label>Last Name *</label>

                                <input type="text"
                                       name="last_name"
                                       value="{{ old('last_name') }}"
                                       placeholder="Enter Last Name"
                                       required>
                            </div>

                            <div class="field">
                                <label>Email Address *</label>

                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="Enter Email Address"
                                       required>
                            </div>

                            <div class="field">
                                <label>Phone Number</label>

                                <input type="text"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       placeholder="Enter Phone Number">
                            </div>

                            <div class="field full">
                                <label>Vendor Details</label>

                                <textarea name="details"
                                          placeholder="Write vendor details here...">{{ old('details') }}</textarea>
                            </div>

                        </div>

                        <!-- Buttons -->
                        <div class="vendor-actions">

                            <a href="{{ route('admin.section', ['section' => 'registered-vendors']) }}"
                               class="vendor-btn cancel">

                                Cancel

                            </a>

                            <button type="submit"
                                    class="vendor-btn save">

                                Save Vendor

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <script>

        (() => {

            const photoInput = document.getElementById('vendorPhotoInput');
            const photoPreview = document.getElementById('vendorPhotoPreview');
            const photoPlaceholder = document.getElementById('vendorPhotoPlaceholder');

            if (!photoInput || !photoPreview || !photoPlaceholder) {
                return;
            }

            photoInput.addEventListener('change', (event) => {

                const [file] = event.target.files || [];

                if (!file) {

                    photoPreview.src = '';
                    photoPreview.style.display = 'none';
                    photoPlaceholder.style.display = 'block';

                    return;
                }

                const reader = new FileReader();

                reader.onload = (e) => {

                    photoPreview.src = e.target.result;
                    photoPreview.style.display = 'block';
                    photoPlaceholder.style.display = 'none';

                };

                reader.readAsDataURL(file);

            });

        })();

    </script>

</div>