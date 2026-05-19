<!-- @php
    $sliderBanners = $sliderBanners ?? collect();
    $listViewBanners = $listViewBanners ?? collect();
    $sliderPreviewBanner = $sliderBanners->first();
    $listPreviewBanner = $listViewBanners->first();
    $sliderPreviewImage = $sliderPreviewBanner?->image ? asset('storage/' . ltrim($sliderPreviewBanner->image, '/')) : null;
    $listPreviewImage = $listPreviewBanner?->image ? asset('storage/' . ltrim($listPreviewBanner->image, '/')) : null;
    $sliderPreviewLink = old('type') === 'slider' ? old('link') : ($sliderPreviewBanner->link ?? '');
    $listPreviewLink = old('type') === 'list_view' ? old('link') : ($listPreviewBanner->link ?? '');
@endphp

<div class="banner-admin-page">
    <style>
        .banner-admin-page {
            padding: 18px 0 28px;
        }

        .banner-admin-stack {
            display: grid;
            gap: 28px;
        }

        .banner-admin-card {
            background: #ffffff;
            border: 1px solid #dbe4f0;
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 24px 48px rgba(15, 23, 42, 0.08);
        }

        .banner-admin-card-head {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
            margin-bottom: 18px;
        }

        .banner-admin-card-head h2 {
            margin: 0;
            font-size: 1.1rem;
            color: #10213e;
        }

        .banner-admin-card-head p {
            margin: 6px 0 0;
            color: #60708a;
            font-size: 0.92rem;
        }

        .banner-admin-form {
            display: grid;
            gap: 18px;
        }

        .banner-admin-field {
            display: grid;
            gap: 8px;
        }

        .banner-admin-field label,
        .banner-admin-preview-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #20304d;
        }

        .banner-admin-field input[type="file"],
        .banner-admin-field input[type="url"] {
            width: 100%;
            border: 1px solid #d6deea;
            border-radius: 14px;
            padding: 12px 14px;
            font: inherit;
            color: #10213e;
            background: #fbfdff;
        }

        .banner-admin-field input[type="url"]:focus,
        .banner-admin-field input[type="file"]:focus {
            outline: none;
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.16);
        }

        .banner-admin-top {
            display: grid;
            grid-template-columns: 132px minmax(0, 1fr);
            gap: 18px;
            align-items: start;
        }

        .banner-admin-thumb-wrap {
            display: grid;
            gap: 8px;
        }

        .banner-admin-thumb {
            width: 100%;
            aspect-ratio: 1 / 1;
            border: 1px dashed #c5d2e5;
            border-radius: 18px;
            background: linear-gradient(180deg, #f8fbff 0%, #eef4ff 100%);
            display: grid;
            place-items: center;
            overflow: hidden;
        }

        .banner-admin-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .banner-admin-thumb-empty {
            color: #7b8ba6;
            font-size: 0.82rem;
            text-align: center;
            padding: 12px;
        }

        .banner-admin-preview {
            position: relative;
            min-height: 280px;
            border: 1px solid #d7dfeb;
            border-radius: 18px;
            background: linear-gradient(180deg, #ffffff 0%, #f4f7fb 100%);
            overflow: hidden;
            display: grid;
            place-items: center;
        }

        .banner-admin-preview img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
            background: #fff;
        }

        .banner-admin-preview-empty {
            padding: 28px;
            text-align: center;
            color: #73829a;
            max-width: 420px;
            line-height: 1.6;
        }

        .banner-admin-link-badge {
            position: absolute;
            left: 50%;
            bottom: 18px;
            transform: translateX(-50%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 9px 16px;
            border-radius: 999px;
            background: rgba(17, 24, 39, 0.9);
            color: #fff;
            text-decoration: none;
            font-size: 0.84rem;
            font-weight: 700;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.24);
        }

        .banner-admin-actions {
            display: flex;
            justify-content: flex-start;
        }

        .banner-admin-save {
            border: 0;
            border-radius: 12px;
            background: #5211ccff;
            color: #fff;
            padding: 12px 18px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.2);
        }

        .banner-admin-save:hover {
            background: #1d4ed8;
        }

        .banner-library {
            display: grid;
            gap: 12px;
            margin-top: 22px;
        }

        .banner-library-card {
            display: grid;
            grid-template-columns: 92px minmax(0, 1fr) auto;
            gap: 16px;
            align-items: center;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 12px 14px;
            background: #fafcff;
        }

        .banner-library-thumb {
            width: 92px;
            height: 64px;
            border-radius: 12px;
            overflow: hidden;
            background: #eaf1fb;
            border: 1px solid #d7dfeb;
        }

        .banner-library-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .banner-library-meta {
            display: grid;
            gap: 4px;
            min-width: 0;
        }

        .banner-library-meta strong {
            color: #12233f;
            font-size: 0.95rem;
        }

        .banner-library-meta span,
        .banner-library-meta a {
            color: #5e708d;
            font-size: 0.85rem;
            word-break: break-word;
        }

        .banner-library-meta a {
            text-decoration: none;
        }

        .banner-library-meta a:hover {
            color: #1d4ed8;
        }

        .banner-library-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .banner-library-actions form {
            margin: 0;
        }

        .banner-library-select {
            border: 1px solid #d6deea;
            border-radius: 10px;
            padding: 9px 10px;
            font: inherit;
            color: #10213e;
            background: #fff;
        }

        .banner-library-delete {
            border: 0;
            border-radius: 10px;
            padding: 10px 14px;
            background: #ef4444;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .banner-library-empty {
            border: 1px dashed #cdd8e8;
            border-radius: 18px;
            padding: 18px;
            color: #70829d;
            background: #fbfdff;
        }

        @media (max-width: 860px) {
            .banner-admin-top,
            .banner-library-card {
                grid-template-columns: 1fr;
            }

            .banner-library-actions {
                justify-content: flex-start;
            }

            .banner-admin-preview {
                min-height: 220px;
            }
        }
    </style>

    @if ($errors->any())
        <div style="margin-bottom: 18px; padding: 16px 18px; border-radius: 18px; border: 1px solid #fecaca; background: #fff1f2; color: #b91c1c;">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="banner-admin-stack">
        <section class="banner-admin-card">
            <div class="banner-admin-card-head">
                <div>
                    <h2>Home Banner Images</h2>
                    <p>Add homepage slider creatives with live preview and click-through link.</p>
                </div>
            </div>

            <form class="banner-admin-form" method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="slider">
                <input type="hidden" name="status" value="1">

                <div class="banner-admin-field">
                    <label for="sliderBannerImage">Choose Main Banner Image</label>
                    <input id="sliderBannerImage" type="file" name="image" accept="image/*" required>
                </div>

                <div class="banner-admin-top">
                    <div class="banner-admin-thumb-wrap">
                        <div class="banner-admin-preview-title">Image Preview</div>
                        <div class="banner-admin-thumb">
                            <img id="sliderBannerThumb" src="{{ $sliderPreviewImage }}" alt="Slider banner thumbnail" style="{{ $sliderPreviewImage ? '' : 'display:none;' }}">
                            <div id="sliderBannerThumbEmpty" class="banner-admin-thumb-empty" style="{{ $sliderPreviewImage ? 'display:none;' : '' }}">Banner thumbnail</div>
                        </div>
                    </div>

                    <div class="banner-admin-field">
                        <label for="sliderBannerLink">View Link</label>
                        <input id="sliderBannerLink" type="url" name="link" value="{{ $sliderPreviewLink }}" placeholder="https://www.example.com/property-page">
                    </div>
                </div>

                <div class="banner-admin-preview-title">Image Preview (Slider Model)</div>
                <div class="banner-admin-preview">
                    <img id="sliderBannerStage" src="{{ $sliderPreviewImage }}" alt="Slider banner full preview" style="{{ $sliderPreviewImage ? '' : 'display:none;' }}">
                    <div id="sliderBannerStageEmpty" class="banner-admin-preview-empty" style="{{ $sliderPreviewImage ? 'display:none;' : '' }}">
                        Upload a wide banner image to preview how it will appear in the homepage slider.
                    </div>
                    <a id="sliderBannerLinkBadge" class="banner-admin-link-badge" href="{{ $sliderPreviewLink ?: '#' }}" target="_blank" rel="noopener" style="{{ $sliderPreviewLink ? '' : 'display:none;' }}">View Link</a>
                </div>

                <div class="banner-admin-actions">
                    <button type="submit" class="banner-admin-save">Save Banners</button>
                </div>
            </form>

            <div class="banner-library">
                @forelse ($sliderBanners as $banner)
                    @php
                        $bannerImage = asset('storage/' . ltrim($banner->image, '/'));
                    @endphp
                    <article class="banner-library-card">
                        <div class="banner-library-thumb">
                            <img src="{{ $bannerImage }}" alt="Slider banner {{ $banner->id }}">
                        </div>
                        <div class="banner-library-meta">
                            <strong>Slider Banner #{{ $banner->id }}</strong>
                            <span>{{ $banner->updated_at ? \Carbon\Carbon::parse($banner->updated_at)->format('d M Y, h:i A') : 'Recently uploaded' }}</span>
                            @if (!empty($banner->link))
                                <a href="{{ $banner->link }}" target="_blank" rel="noopener">{{ $banner->link }}</a>
                            @else
                                <span>No view link added.</span>
                            @endif
                        </div>
                        <div class="banner-library-actions">
                            <form method="POST" action="{{ route('admin.banners.updateStatus', $banner->id) }}">
                                @csrf
                                @method('PUT')
                                <select name="status" class="banner-library-select" onchange="this.form.submit()">
                                    <option value="1" {{ (int) ($banner->status ?? 1) === 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ (int) ($banner->status ?? 1) === 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </form>
                            <form method="POST" action="{{ route('admin.banners.destroy', $banner->id) }}" onsubmit="return confirm('Delete this banner image?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="banner-library-delete">Delete</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="banner-library-empty">No slider banners uploaded yet.</div>
                @endforelse
            </div>
        </section>

        <section class="banner-admin-card">
            <div class="banner-admin-card-head">
                <div>
                    <h2>Chennai Page Banner Images</h2>
                </div>
            </div>

            <form class="banner-admin-form" method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="list_view">
                <input type="hidden" name="status" value="1">

                <div class="banner-admin-field">
                    <label for="listViewBannerImage">Upload List View Banner Images</label>
                    <input id="listViewBannerImage" type="file" name="image" accept="image/*" required>
                </div>

                <div class="banner-admin-top">
                    <div class="banner-admin-thumb-wrap">
                        <div class="banner-admin-preview-title">Image Preview</div>
                        <div class="banner-admin-thumb">
                            <img id="listViewBannerThumb" src="{{ $listPreviewImage }}" alt="List view banner thumbnail" style="{{ $listPreviewImage ? '' : 'display:none;' }}">
                            <div id="listViewBannerThumbEmpty" class="banner-admin-thumb-empty" style="{{ $listPreviewImage ? 'display:none;' : '' }}">List view thumbnail</div>
                        </div>
                    </div>

                    <div class="banner-admin-field">
                        <label for="listViewBannerLink">View Link</label>
                        <input id="listViewBannerLink" type="url" name="link" value="{{ $listPreviewLink }}" placeholder="https://www.example.com/listing-page">
                    </div>
                </div>

                <div class="banner-admin-preview-title">List View Banner Preview</div>
                <div class="banner-admin-preview">
                    <img id="listViewBannerStage" src="{{ $listPreviewImage }}" alt="List view banner full preview" style="{{ $listPreviewImage ? '' : 'display:none;' }}">
                    <div id="listViewBannerStageEmpty" class="banner-admin-preview-empty" style="{{ $listPreviewImage ? 'display:none;' : '' }}">
                        Upload a listing banner image to preview how it will appear in list-view sections.
                    </div>
                    <a id="listViewBannerLinkBadge" class="banner-admin-link-badge" href="{{ $listPreviewLink ?: '#' }}" target="_blank" rel="noopener" style="{{ $listPreviewLink ? '' : 'display:none;' }}">View Link</a>
                </div>

                <div class="banner-admin-actions">
                    <button type="submit" class="banner-admin-save">Save List Banners</button>
                </div>
            </form>

            <div class="banner-library">
                @forelse ($listViewBanners as $banner)
                    @php
                        $bannerImage = asset('storage/' . ltrim($banner->image, '/'));
                    @endphp
                    <article class="banner-library-card">
                        <div class="banner-library-thumb">
                            <img src="{{ $bannerImage }}" alt="List view banner {{ $banner->id }}">
                        </div>
                        <div class="banner-library-meta">
                            <strong>List View Banner #{{ $banner->id }}</strong>
                            <span>{{ $banner->updated_at ? \Carbon\Carbon::parse($banner->updated_at)->format('d M Y, h:i A') : 'Recently uploaded' }}</span>
                            @if (!empty($banner->link))
                                <a href="{{ $banner->link }}" target="_blank" rel="noopener">{{ $banner->link }}</a>
                            @else
                                <span>No view link added.</span>
                            @endif
                        </div>
                        <div class="banner-library-actions">
                            <form method="POST" action="{{ route('admin.banners.updateStatus', $banner->id) }}">
                                @csrf
                                @method('PUT')
                                <select name="status" class="banner-library-select" onchange="this.form.submit()">
                                    <option value="1" {{ (int) ($banner->status ?? 1) === 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ (int) ($banner->status ?? 1) === 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </form>
                            <form method="POST" action="{{ route('admin.banners.destroy', $banner->id) }}" onsubmit="return confirm('Delete this banner image?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="banner-library-delete">Delete</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="banner-library-empty">No list view banners uploaded yet.</div>
                @endforelse
            </div>
        </section>
    </div>

    <script>
        (() => {
            const setupBannerPreview = ({ fileInputId, thumbId, thumbEmptyId, stageId, stageEmptyId, linkInputId, badgeId }) => {
                const fileInput = document.getElementById(fileInputId);
                const thumb = document.getElementById(thumbId);
                const thumbEmpty = document.getElementById(thumbEmptyId);
                const stage = document.getElementById(stageId);
                const stageEmpty = document.getElementById(stageEmptyId);
                const linkInput = document.getElementById(linkInputId);
                const badge = document.getElementById(badgeId);

                if (!fileInput || !thumb || !stage || !linkInput || !badge) {
                    return;
                }

                const syncLink = () => {
                    const value = linkInput.value.trim();
                    badge.href = value || '#';
                    badge.style.display = value ? 'inline-flex' : 'none';
                };

                fileInput.addEventListener('change', (event) => {
                    const [file] = event.target.files || [];
                    if (!file) {
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = (loadEvent) => {
                        const result = loadEvent.target?.result;
                        if (!result) {
                            return;
                        }

                        thumb.src = result;
                        stage.src = result;
                        thumb.style.display = 'block';
                        stage.style.display = 'block';
                        if (thumbEmpty) thumbEmpty.style.display = 'none';
                        if (stageEmpty) stageEmpty.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                });

                linkInput.addEventListener('input', syncLink);
                syncLink();
            };

            setupBannerPreview({
                fileInputId: 'sliderBannerImage',
                thumbId: 'sliderBannerThumb',
                thumbEmptyId: 'sliderBannerThumbEmpty',
                stageId: 'sliderBannerStage',
                stageEmptyId: 'sliderBannerStageEmpty',
                linkInputId: 'sliderBannerLink',
                badgeId: 'sliderBannerLinkBadge',
            });

            setupBannerPreview({
                fileInputId: 'listViewBannerImage',
                thumbId: 'listViewBannerThumb',
                thumbEmptyId: 'listViewBannerThumbEmpty',
                stageId: 'listViewBannerStage',
                stageEmptyId: 'listViewBannerStageEmpty',
                linkInputId: 'listViewBannerLink',
                badgeId: 'listViewBannerLinkBadge',
            });
        })();
    </script>
</div> -->

@php
    $sliderBanners = $sliderBanners ?? collect();
    $listViewBanners = $listViewBanners ?? collect();

    $sliderPreviewBanner = $sliderBanners->first();
    $listPreviewBanner = $listViewBanners->first();

    $sliderPreviewImage = $sliderPreviewBanner?->image
        ? asset('storage/' . ltrim($sliderPreviewBanner->image, '/'))
        : null;

    $listPreviewImage = $listPreviewBanner?->image
        ? asset('storage/' . ltrim($listPreviewBanner->image, '/'))
        : null;

    $sliderPreviewLink = old('type') === 'slider'
        ? old('link')
        : ($sliderPreviewBanner->link ?? '');

    $listPreviewLink = old('type') === 'list_view'
        ? old('link')
        : ($listPreviewBanner->link ?? '');
@endphp

<div class="modern-banner-page">

    <!-- Professional Font -->
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
            --danger:#ef4444;
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

        .modern-banner-page{
            min-height:100vh;
            padding:34px 24px;
            position:relative;
            overflow:hidden;
        }

        /* Background Glow */
        .modern-banner-page::before{
            content:"";
            position:absolute;
            width:420px;
            height:420px;
            border-radius:50%;
            background:rgba(56, 119, 153, 0.1);
            top:-150px;
            left:-100px;
            filter:blur(90px);
            animation:floatGlow 8s infinite ease-in-out;
        }

        .modern-banner-page::after{
            content:"";
            position:absolute;
            width:380px;
            height:380px;
            border-radius:50%;
            background:rgba(59,130,246,.08);
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

        .banner-wrapper{
            max-width:1450px;
            margin:auto;
            position:relative;
            z-index:2;
        }

        /* Top Header */
        .page-header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            flex-wrap:wrap;
            gap:20px;
            margin-bottom:30px;

            animation:fadeUp .8s ease;
        }

        .page-header h1{
            font-size:2.4rem;
            font-weight:900;
            color:var(--dark);
            letter-spacing:-1px;
            margin-bottom:8px;
        }

        .page-header p{
            color:var(--text);
            font-size:15px;
        }

        .header-badge{
            background:linear-gradient(135deg,#1ea4ee,#0d8fd6);
            color:#fff;
            padding:14px 22px;
            border-radius:999px;
            font-size:14px;
            font-weight:700;

            box-shadow:0 15px 30px rgba(30,164,238,.25);

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

        /* Layout */
        .banner-stack{
            display:grid;
            gap:34px;
        }

        /* Card */
        .banner-card{
            background:rgba(255,255,255,.82);

            backdrop-filter:blur(18px);

            border:1px solid rgba(255,255,255,.7);

            border-radius:34px;

            overflow:hidden;

            box-shadow:
                0 20px 50px rgba(15,23,42,.07),
                inset 0 1px 0 rgba(255,255,255,.8);

            animation:fadeUp 1s ease;
        }

        .banner-card-header{
            padding:28px 34px;
            border-bottom:1px solid #edf2f7;

            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:20px;
            flex-wrap:wrap;
        }

        .banner-card-header h2{
            font-size:1.45rem;
            font-weight:800;
            color:var(--dark);
            margin-bottom:6px;
        }

        .banner-card-header p{
            color:var(--text);
            font-size:14px;
            line-height:1.7;
        }

        /* Form */
        .banner-form{
            padding:34px;
            display:grid;
            gap:28px;
        }

        .banner-layout{
            display:grid;
            grid-template-columns:300px 1fr;
            gap:32px;
        }

        /* Left Preview */
        .preview-panel{
            display:flex;
            flex-direction:column;
            gap:18px;
        }

        .preview-title{
            font-size:14px;
            font-weight:700;
            color:#1e293b;
        }

        .preview-thumb{
            width:100%;
            height:220px;

            border-radius:28px;

            border:2px dashed #cbd5e1;

            background:linear-gradient(180deg,#f8fbff,#eef4ff);

            overflow:hidden;

            display:flex;
            align-items:center;
            justify-content:center;

            transition:.3s ease;
        }

        .preview-thumb:hover{
            transform:translateY(-3px);
            border-color:var(--primary);
        }

        .preview-thumb img{
            width:100%;
            height:100%;
            object-fit:cover;
            display:block;
        }

        .preview-empty{
            text-align:center;
            color:#94a3b8;
            font-size:14px;
            font-weight:700;
            line-height:1.7;
            padding:20px;
        }

        .upload-label{
            display:inline-flex;
            align-items:center;
            justify-content:center;

            padding:14px 20px;

            border-radius:18px;

            background:linear-gradient(135deg,#1ea4ee,#0d8fd6);

            color:#fff;

            font-size:14px;
            font-weight:700;

            cursor:pointer;

            box-shadow:0 14px 28px rgba(30,164,238,.25);

            transition:.3s ease;
        }

        .upload-label:hover{
            transform:translateY(-2px);
        }

        /* Right */
        .banner-fields{
            display:grid;
            gap:24px;
        }

        .field{
            display:grid;
            gap:10px;
        }

        .field label{
            font-size:14px;
            font-weight:700;
            color:#1e293b;
        }

        .field input{
            width:100%;

            border:1px solid #dbe3ee;

            border-radius:18px;

            padding:15px 18px;

            font-size:15px;

            background:#fff;

            transition:.25s ease;
        }

        .field input:focus{
            outline:none;

            border-color:var(--primary);

            box-shadow:
                0 0 0 4px rgba(30,164,238,.12);

            transform:translateY(-1px);
        }

        /* Large Preview */
        .banner-stage{
            position:relative;

            min-height:320px;

            border-radius:30px;

            overflow:hidden;

            border:1px solid #dbe3ee;

            background:linear-gradient(180deg,#ffffff,#f5f8fc);

            display:flex;
            align-items:center;
            justify-content:center;
        }

        .banner-stage img{
            width:100%;
            height:100%;
            object-fit:contain;
            display:block;
            background:#fff;
        }

        .banner-link{
            position:absolute;
            left:50%;
            bottom:20px;
            transform:translateX(-50%);

            background:rgba(15,23,42,.92);

            color:#fff;

            text-decoration:none;

            padding:12px 18px;

            border-radius:999px;

            font-size:13px;
            font-weight:700;

            box-shadow:0 12px 24px rgba(15,23,42,.24);

            transition:.3s ease;
        }

        .banner-link:hover{
            background:var(--primary);
        }

        /* Actions */
        .form-actions{
            display:flex;
            justify-content:flex-end;
        }

        .save-btn{
            border:none;

            padding:14px 28px;

            border-radius:18px;

            background:linear-gradient(135deg,#1ea4ee,#0d8fd6);

            color:#fff;

            font-size:15px;
            font-weight:700;

            cursor:pointer;

            box-shadow:0 15px 30px rgba(30,164,238,.25);

            transition:.3s ease;
        }

        .save-btn:hover{
            transform:translateY(-3px);
        }

        /* Library */
        .banner-library{
            display:grid;
            gap:18px;
            margin-top:28px;
        }

        .library-card{
            display:grid;
            grid-template-columns:120px 1fr auto;

            gap:20px;

            align-items:center;

            padding:18px;

            border-radius:24px;

            background:#fbfdff;

            border:1px solid #e2e8f0;

            transition:.3s ease;
        }

        .library-card:hover{
            transform:translateY(-4px);

            box-shadow:0 18px 35px rgba(15,23,42,.06);
        }

        .library-thumb{
            width:120px;
            height:80px;

            border-radius:18px;

            overflow:hidden;

            border:1px solid #dbe3ee;

            background:#f1f5f9;
        }

        .library-thumb img{
            width:100%;
            height:100%;
            object-fit:cover;
        }

        .library-meta{
            display:grid;
            gap:6px;
            min-width:0;
        }

        .library-meta strong{
            color:var(--dark);
            font-size:15px;
        }

        .library-meta span,
        .library-meta a{
            color:var(--text);
            font-size:13px;
            word-break:break-word;
            text-decoration:none;
        }

        .library-meta a:hover{
            color:var(--primary);
        }

        .library-actions{
            display:flex;
            align-items:center;
            gap:12px;
            flex-wrap:wrap;
        }

        .library-select{
            border:1px solid #dbe3ee;

            border-radius:14px;

            padding:11px 14px;

            background:#fff;

            font-size:14px;
        }

        .delete-btn{
            border:none;

            padding:12px 16px;

            border-radius:14px;

            background:var(--danger);

            color:#fff;

            font-size:14px;
            font-weight:700;

            cursor:pointer;

            transition:.3s ease;
        }

        .delete-btn:hover{
            transform:translateY(-2px);
        }

        /* Empty */
        .library-empty{
            padding:24px;
            border-radius:22px;

            border:2px dashed #dbe3ee;

            color:#64748b;

            text-align:center;

            background:#fbfdff;
        }

        /* Errors */
        .error-box{
            background:#fff1f2;

            border:1px solid #fecaca;

            color:#b91c1c;

            border-radius:24px;

            padding:18px 20px;

            margin-bottom:24px;
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

            .banner-layout{
                grid-template-columns:1fr;
            }

            .library-card{
                grid-template-columns:1fr;
            }

        }

        @media(max-width:700px){

            .modern-banner-page{
                padding:24px 14px;
            }

            .page-header h1{
                font-size:2rem;
            }

            .banner-form{
                padding:24px;
            }

            .banner-card-header{
                padding:24px;
            }

            .banner-stage{
                min-height:240px;
            }

        }

    </style>

    <div class="banner-wrapper">

        <!-- Header -->
        <div class="page-header">

            <div>
                <h1>Banner Management</h1>
                <p>Manage homepage slider banners and Chennai page promotional creatives professionally.</p>
            </div>

            <div class="header-badge">
                Homewala Banner
            </div>

        </div>

        @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="banner-stack">

            <!-- Slider Banner -->
            <section class="banner-card">

                <div class="banner-card-header">
                    <div>
                        <h2>Home Slider Banner</h2>
                        <p>Upload wide promotional banners with preview and destination links.</p>
                    </div>
                </div>

                <form class="banner-form"
                      method="POST"
                      action="{{ route('admin.banners.store') }}"
                      enctype="multipart/form-data">

                    @csrf

                    <input type="hidden" name="type" value="slider">
                    <input type="hidden" name="status" value="1">

                    <div class="banner-layout">

                        <!-- Preview -->
                        <div class="preview-panel">

                            <div class="preview-title">
                                Banner Thumbnail
                            </div>

                            <div class="preview-thumb">

                                <img id="sliderBannerThumb"
                                     src="{{ $sliderPreviewImage }}"
                                     style="{{ $sliderPreviewImage ? '' : 'display:none;' }}">

                                <div id="sliderBannerThumbEmpty"
                                     class="preview-empty"
                                     style="{{ $sliderPreviewImage ? 'display:none;' : '' }}">

                                    UPLOAD IMAGE<br>
                                    PREVIEW HERE

                                </div>

                            </div>

                            <label for="sliderBannerImage"
                                   class="upload-label">

                                Choose Banner Image

                            </label>

                            <input id="sliderBannerImage"
                                   type="file"
                                   name="image"
                                   accept="image/*"
                                   hidden
                                   required>

                        </div>

                        <!-- Fields -->
                        <div class="banner-fields">

                            <div class="field">

                                <label>Banner View Link</label>

                                <input id="sliderBannerLink"
                                       type="url"
                                       name="link"
                                       value="{{ $sliderPreviewLink }}"
                                       placeholder="https://www.example.com">

                            </div>

                            <div class="banner-stage">

                                <img id="sliderBannerStage"
                                     src="{{ $sliderPreviewImage }}"
                                     style="{{ $sliderPreviewImage ? '' : 'display:none;' }}">

                                <div id="sliderBannerStageEmpty"
                                     class="preview-empty"
                                     style="{{ $sliderPreviewImage ? 'display:none;' : '' }}">

                                    Upload a homepage banner image to preview the slider appearance.

                                </div>

                                <a id="sliderBannerLinkBadge"
                                   class="banner-link"
                                   href="{{ $sliderPreviewLink ?: '#' }}"
                                   target="_blank"
                                   style="{{ $sliderPreviewLink ? '' : 'display:none;' }}">

                                    View Banner Link

                                </a>

                            </div>

                        </div>

                    </div>

                    <div class="form-actions">
                        <button type="submit"
                                class="save-btn">

                            Save Slider Banner

                        </button>
                    </div>

                </form>
                <div class="banner-stack">

            <!-- Slider Banner -->
            <section class="banner-card">

                <div class="banner-card-header">
                    <div>
                        <h2>Chennai Slider Banner</h2>
                        <p>Upload wide promotional banners with preview and destination links.</p>
                    </div>
                </div>

                <form class="banner-form"
                      method="POST"
                      action="{{ route('admin.banners.store') }}"
                      enctype="multipart/form-data">

                    @csrf

                    <input type="hidden" name="type" value="slider">
                    <input type="hidden" name="status" value="1">

                    <div class="banner-layout">

                        <!-- Preview -->
                        <div class="preview-panel">

                            <div class="preview-title">
                                Banner Thumbnail
                            </div>

                            <div class="preview-thumb">

                                <img id="sliderBannerThumb"
                                     src="{{ $sliderPreviewImage }}"
                                     style="{{ $sliderPreviewImage ? '' : 'display:none;' }}">

                                <div id="sliderBannerThumbEmpty"
                                     class="preview-empty"
                                     style="{{ $sliderPreviewImage ? 'display:none;' : '' }}">

                                    UPLOAD IMAGE<br>
                                    PREVIEW HERE

                                </div>

                            </div>

                            <label for="sliderBannerImage"
                                   class="upload-label">

                                Choose Banner Image

                            </label>

                            <input id="sliderBannerImage"
                                   type="file"
                                   name="image"
                                   accept="image/*"
                                   hidden
                                   required>

                        </div>

                        <!-- Fields -->
                        <div class="banner-fields">

                            <div class="field">

                                <label>Banner View Link</label>

                                <input id="sliderBannerLink"
                                       type="url"
                                       name="link"
                                       value="{{ $sliderPreviewLink }}"
                                       placeholder="https://www.example.com">

                            </div>

                            <div class="banner-stage">

                                <img id="sliderBannerStage"
                                     src="{{ $sliderPreviewImage }}"
                                     style="{{ $sliderPreviewImage ? '' : 'display:none;' }}">

                                <div id="sliderBannerStageEmpty"
                                     class="preview-empty"
                                     style="{{ $sliderPreviewImage ? 'display:none;' : '' }}">

                                    Upload a homepage banner image to preview the slider appearance.

                                </div>

                                <a id="sliderBannerLinkBadge"
                                   class="banner-link"
                                   href="{{ $sliderPreviewLink ?: '#' }}"
                                   target="_blank"
                                   style="{{ $sliderPreviewLink ? '' : 'display:none;' }}">

                                    View Banner Link

                                </a>

                            </div>

                        </div>

                    </div>

                    <div class="form-actions">
                        <button type="submit"
                                class="save-btn">

                            Save Slider Banner

                        </button>
                    </div>

                </form>

                <!-- Library -->
                <div class="banner-library">

                    @forelse ($sliderBanners as $banner)

                        @php
                            $bannerImage = asset('storage/' . ltrim($banner->image, '/'));
                        @endphp

                        <div class="library-card">

                            <div class="library-thumb">
                                <img src="{{ $bannerImage }}">
                            </div>

                            <div class="library-meta">

                                <strong>
                                    Slider Banner #{{ $banner->id }}
                                </strong>

                                <span>
                                    {{ $banner->updated_at ? \Carbon\Carbon::parse($banner->updated_at)->format('d M Y, h:i A') : 'Recently uploaded' }}
                                </span>

                                @if (!empty($banner->link))
                                    <a href="{{ $banner->link }}"
                                       target="_blank">

                                        {{ $banner->link }}

                                    </a>
                                @else
                                    <span>No link added.</span>
                                @endif

                            </div>

                            <div class="library-actions">

                                <form method="POST"
                                      action="{{ route('admin.banners.updateStatus', $banner->id) }}">

                                    @csrf
                                    @method('PUT')

                                    <select name="status"
                                            class="library-select"
                                            onchange="this.form.submit()">

                                        <option value="1"
                                            {{ (int)($banner->status ?? 1) === 1 ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="0"
                                            {{ (int)($banner->status ?? 1) === 0 ? 'selected' : '' }}>
                                            Inactive
                                        </option>

                                    </select>

                                </form>

                                <form method="POST"
                                      action="{{ route('admin.banners.destroy', $banner->id) }}"
                                      onsubmit="return confirm('Delete this banner image?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="delete-btn">

                                        Delete

                                    </button>

                                </form>

                            </div>

                        </div>

                    @empty

                        <div class="library-empty">
                            No slider banners uploaded yet.
                        </div>

                    @endforelse

                </div>

            </section>

        </div>

    </div>

<script>

(() => {

    const setupBannerPreview = ({
        fileInputId,
        thumbId,
        thumbEmptyId,
        stageId,
        stageEmptyId,
        linkInputId,
        badgeId
    }) => {

        const fileInput = document.getElementById(fileInputId);
        const thumb = document.getElementById(thumbId);
        const thumbEmpty = document.getElementById(thumbEmptyId);

        const stage = document.getElementById(stageId);
        const stageEmpty = document.getElementById(stageEmptyId);

        const linkInput = document.getElementById(linkInputId);
        const badge = document.getElementById(badgeId);

        if (!fileInput || !thumb || !stage || !linkInput || !badge) {
            return;
        }

        const syncLink = () => {

            const value = linkInput.value.trim();

            badge.href = value || '#';

            badge.style.display = value
                ? 'inline-flex'
                : 'none';

        };

        fileInput.addEventListener('change', (event) => {

            const [file] = event.target.files || [];

            if (!file) return;

            const reader = new FileReader();

            reader.onload = (e) => {

                const result = e.target.result;

                thumb.src = result;
                stage.src = result;

                thumb.style.display = 'block';
                stage.style.display = 'block';

                if (thumbEmpty) {
                    thumbEmpty.style.display = 'none';
                }

                if (stageEmpty) {
                    stageEmpty.style.display = 'none';
                }

            };

            reader.readAsDataURL(file);

        });

        linkInput.addEventListener('input', syncLink);

        syncLink();

    };

    setupBannerPreview({
        fileInputId: 'sliderBannerImage',
        thumbId: 'sliderBannerThumb',
        thumbEmptyId: 'sliderBannerThumbEmpty',
        stageId: 'sliderBannerStage',
        stageEmptyId: 'sliderBannerStageEmpty',
        linkInputId: 'sliderBannerLink',
        badgeId: 'sliderBannerLinkBadge',
    });

})();

</script>

</div>