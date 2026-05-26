@php
    $sliderBanners = $sliderBanners ?? collect();
    $listViewBanners = $listViewBanners ?? collect();
    $bannerHasStatus = $bannerHasStatus ?? false;
    $listBannerImages = $listBannerImages ?? [];

    $bannerSections = [
        [
            'key' => 'homeSide',
            'title' => 'Home Side Banner',
            'description' => 'Upload banners for the home side banner area.',
            'type' => 'home_side',
            'items' => $sliderBanners,
            'empty' => 'No home side banners uploaded yet.',
            'placeholder' => 'https://www.example.com/home-banner-link',
            'libraryImages' => [],
        ],
        [
            'key' => 'chennaiSide',
            'title' => 'Chennai Side Banner',
            'description' => 'Upload banners for the Chennai side banner area.',
            'type' => 'chennai_side',
            'items' => $listViewBanners,
            'empty' => 'No Chennai side banners uploaded yet.',
            'placeholder' => 'https://www.example.com/chennai-banner-link',
            'libraryImages' => $listBannerImages,
        ],
    ];
@endphp

<div class="banner-admin-page">
    <style>
        .banner-admin-page { padding: 22px 0 32px; }
        .banner-admin-head { display: flex; justify-content: space-between; gap: 18px; align-items: flex-start; margin-bottom: 24px; }
        .banner-admin-head h1 { margin: 0; color: #0f172a; font-size: 28px; font-weight: 800; }
        .banner-admin-head p { margin: 6px 0 0; color: #64748b; font-size: 14px; }
        .banner-admin-stack { display: grid; gap: 24px; }
        .banner-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; box-shadow: 0 16px 35px rgba(15, 23, 42, 0.06); overflow: hidden; }
        .banner-card-header { padding: 20px 22px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; gap: 16px; align-items: flex-start; }
        .banner-card-header h2 { margin: 0; color: #10213e; font-size: 19px; font-weight: 800; }
        .banner-card-header p { margin: 6px 0 0; color: #64748b; font-size: 13px; }
        .banner-form { padding: 22px; display: grid; gap: 18px; }
        .banner-form-grid { display: grid; grid-template-columns: 180px minmax(0, 1fr); gap: 18px; align-items: start; }
        .banner-upload-box { display: grid; gap: 10px; }
        .banner-thumb { width: 100%; aspect-ratio: 1 / 1; border: 1px dashed #cbd5e1; border-radius: 14px; background: #f8fafc; overflow: hidden; display: grid; place-items: center; color: #94a3b8; text-align: center; font-weight: 700; font-size: 12px; }
        .banner-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .banner-upload-label { border: 0; border-radius: 10px; background: #1ea4ee; color: #fff; padding: 11px 14px; text-align: center; font-size: 13px; font-weight: 800; cursor: pointer; }
        .banner-fields { display: grid; gap: 14px; }
        .banner-field { display: grid; gap: 7px; }
        .banner-field label { color: #334155; font-size: 13px;  font-weight: 800; }
        .banner-field input[type="url"] { width: 100%; border: 1px solid #cbd5e1; border-radius: 10px; padding: 12px 13px; color: #0f172a; font-size: 14px; }
        .banner-picker { display: grid; gap: 10px; }
        .banner-picker-title { color: #334155; font-size: 13px; font-weight: 800; }
        .banner-picker-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(145px, 1fr)); gap: 12px; }
        .banner-picker-card { border: 1px solid #dbe3ee; border-radius: 12px; background: #fff; overflow: hidden; cursor: pointer; transition: .2s ease; }
        .banner-picker-card input { position: absolute; opacity: 0; pointer-events: none; }
        .banner-picker-card img { width: 100%; height: 86px; object-fit: cover; display: block; background: #f1f5f9; }
        .banner-picker-card span { display: block; padding: 8px 10px; color: #64748b; font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .banner-picker-card:has(input:checked) { border-color: #1ea4ee; box-shadow: 0 0 0 3px rgba(30, 164, 238, .14); }
        .banner-picker-empty { border: 1px dashed #cbd5e1; border-radius: 12px; padding: 14px; color: #64748b; background: #f8fafc; font-size: 13px; }
        .banner-stage { position: relative; min-height: 260px; border: 1px solid #e2e8f0; border-radius: 14px; background: #f8fafc; overflow: hidden; display: grid; place-items: center; color: #94a3b8; text-align: center; padding: 18px; }
        .banner-stage img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain; background: #fff; }
        .banner-link-badge { position: absolute; left: 50%; bottom: 16px; transform: translateX(-50%); background: rgba(15, 23, 42, .92); color: #fff; text-decoration: none; border-radius: 999px; padding: 9px 14px; font-size: 12px; font-weight: 800; }
        .banner-actions { display: flex; justify-content: flex-end; }
        .banner-save { border: 0; border-radius: 12px; background: #1bb1d6; color: #fff; padding: 12px 20px; font-weight: 800; cursor: pointer; }
        .banner-library { display: grid; gap: 12px; padding: 0 22px 22px; }
        .banner-library-card { display: grid; grid-template-columns: 120px minmax(0, 1fr) auto; gap: 16px; align-items: center; border: 1px solid #e2e8f0; border-radius: 14px; padding: 12px; background: #fbfdff; }
        .banner-library-thumb { width: 120px; height: 78px; border-radius: 10px; overflow: hidden; background: #e2e8f0; }
        .banner-library-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .banner-library-meta { display: grid; gap: 5px; min-width: 0; }
        .banner-library-meta strong { color: #0f172a; font-size: 14px; }
        .banner-library-meta span, .banner-library-meta a { color: #64748b; font-size: 13px; word-break: break-word; text-decoration: none; }
        .banner-library-actions { display: flex; gap: 10px; align-items: center; justify-content: flex-end; flex-wrap: wrap; }
        .banner-library-actions form { margin: 0; }
        .banner-select { border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 10px; background: #fff; }
        .banner-delete { border: 0; border-radius: 10px; padding: 10px 13px; background: #ef4444; color: #fff; font-weight: 800; cursor: pointer; }
        .banner-empty { border: 1px dashed #cbd5e1; border-radius: 14px; padding: 18px; color: #64748b; background: #f8fafc; }
        .banner-error { margin-bottom: 18px; padding: 14px 16px; border-radius: 14px; border: 1px solid #fecaca; background: #fff1f2; color: #b91c1c; }
        @media (max-width: 860px) {
            .banner-form-grid, .banner-library-card { grid-template-columns: 1fr; }
            .banner-library-actions, .banner-actions { justify-content: flex-start; }
            .banner-stage { min-height: 220px; }
        }
    </style>

    <div class="banner-admin-head">
        <div>
            <h1>Banner Management</h1>
            <p>Manage separate Home side and Chennai side banners.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="banner-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="banner-admin-stack">
        @foreach ($bannerSections as $section)
            @php
                $previewBanner = $section['items']->first();
                $previewImage = $previewBanner?->image ? \App\Support\MediaPath::url($previewBanner->image) : null;
                $previewLink = old('type') === $section['type'] ? old('link') : ($previewBanner->link ?? '');
            @endphp

            <section class="banner-card">
                <div class="banner-card-header">
                    <div>
                        <h2>{{ $section['title'] }}</h2>
                        <p>{{ $section['description'] }}</p>
                    </div>
                </div>

                <form class="banner-form" method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="{{ $section['type'] }}">
                    <input type="hidden" name="status" value="1">

                    <div class="banner-form-grid">
                        <div class="banner-upload-box">
                            <div class="banner-thumb">
                                <img id="{{ $section['key'] }}Thumb" src="{{ $previewImage }}" alt="{{ $section['title'] }} thumbnail" style="{{ $previewImage ? '' : 'display:none;' }}">
                                <span id="{{ $section['key'] }}ThumbEmpty" style="{{ $previewImage ? 'display:none;' : '' }}">Upload image</span>
                            </div>
                            <label class="banner-upload-label" for="{{ $section['key'] }}Image">Choose Image</label>
                            <input id="{{ $section['key'] }}Image" type="file" name="image" accept="image/*" hidden {{ empty($section['libraryImages']) ? 'required' : '' }}>
                        </div>

                        <div class="banner-fields">
                            <div class="banner-field">
                                <label for="{{ $section['key'] }}Link">View Link</label>
                                <input id="{{ $section['key'] }}Link" type="url" name="link" value="{{ $previewLink }}" placeholder="{{ $section['placeholder'] }}">
                            </div>

                            <div class="banner-stage">
                                <img id="{{ $section['key'] }}Stage" src="{{ $previewImage }}" alt="{{ $section['title'] }} preview" style="{{ $previewImage ? '' : 'display:none;' }}">
                                <span id="{{ $section['key'] }}StageEmpty" style="{{ $previewImage ? 'display:none;' : '' }}">Upload an image to preview this banner.</span>
                                <a id="{{ $section['key'] }}LinkBadge" class="banner-link-badge" href="{{ $previewLink ?: '#' }}" target="_blank" rel="noopener" style="{{ $previewLink ? '' : 'display:none;' }}">View Link</a>
                            </div>

                            @if (!empty($section['libraryImages']))
                                <div class="banner-picker">
                                    <div class="banner-picker-title">Select from images/list_banners</div>
                                    <div class="banner-picker-grid">
                                        @foreach ($section['libraryImages'] as $image)
                                            <label class="banner-picker-card">
                                                <input type="radio" name="selected_image_path" value="{{ $image['path'] }}" data-preview="{{ $image['url'] }}">
                                                <img src="{{ $image['url'] }}" alt="{{ $image['filename'] }}">
                                                <span title="{{ $image['filename'] }}">{{ $image['filename'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @elseif ($section['key'] === 'chennaiSide')
                                <div class="banner-picker-empty">No images found in list_banners.</div>
                            @endif
                        </div>
                    </div>

                    <div class="banner-actions">
                        <button type="submit" class="banner-save">Save {{ $section['title'] }}</button>
                    </div>
                </form>

                <div class="banner-library">
                    @forelse ($section['items'] as $banner)
                        @php
                            $bannerImage = $banner->image ? \App\Support\MediaPath::url($banner->image) : null;
                        @endphp
                        <article class="banner-library-card">
                            <div class="banner-library-thumb">
                                @if ($bannerImage)
                                    <img src="{{ $bannerImage }}" alt="{{ $section['title'] }} {{ $banner->id }}">
                                @endif
                            </div>
                            <div class="banner-library-meta">
                                <strong>{{ $section['title'] }} #{{ $banner->id }}</strong>
                                <span>{{ $banner->updated_at ? \Carbon\Carbon::parse($banner->updated_at)->format('d M Y, h:i A') : 'Recently uploaded' }}</span>
                                @if (!empty($banner->link))
                                    <a href="{{ $banner->link }}" target="_blank" rel="noopener">{{ $banner->link }}</a>
                                @else
                                    <span>No link added.</span>
                                @endif
                            </div>
                            <div class="banner-library-actions">
                                @if ($bannerHasStatus)
                                    <form method="POST" action="{{ route('admin.banners.updateStatus', $banner->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="banner-select" onchange="this.form.submit()">
                                            <option value="1" {{ (int) ($banner->status ?? 1) === 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ (int) ($banner->status ?? 1) === 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.banners.destroy', $banner->id) }}" onsubmit="return confirm('Delete this banner image?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="banner-delete">Delete</button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="banner-empty">{{ $section['empty'] }}</div>
                    @endforelse
                </div>
            </section>
        @endforeach
    </div>

    <script>
        (() => {
            const setupBannerPreview = (key) => {
                const fileInput = document.getElementById(`${key}Image`);
                const thumb = document.getElementById(`${key}Thumb`);
                const thumbEmpty = document.getElementById(`${key}ThumbEmpty`);
                const stage = document.getElementById(`${key}Stage`);
                const stageEmpty = document.getElementById(`${key}StageEmpty`);
                const linkInput = document.getElementById(`${key}Link`);
                const badge = document.getElementById(`${key}LinkBadge`);

                if (!fileInput || !thumb || !stage || !linkInput || !badge) return;

                fileInput.addEventListener('change', (event) => {
                    const [file] = event.target.files || [];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = (readerEvent) => {
                        thumb.src = readerEvent.target.result;
                        stage.src = readerEvent.target.result;
                        thumb.style.display = 'block';
                        stage.style.display = 'block';
                        if (thumbEmpty) thumbEmpty.style.display = 'none';
                        if (stageEmpty) stageEmpty.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                });

                document.querySelectorAll(`input[name="selected_image_path"][data-preview]`).forEach((input) => {
                    input.addEventListener('change', () => {
                        if (!input.checked || !input.closest('form')?.contains(fileInput)) return;

                        thumb.src = input.dataset.preview;
                        stage.src = input.dataset.preview;
                        thumb.style.display = 'block';
                        stage.style.display = 'block';
                        fileInput.value = '';
                        if (thumbEmpty) thumbEmpty.style.display = 'none';
                        if (stageEmpty) stageEmpty.style.display = 'none';
                    });
                });

                const syncLink = () => {
                    const value = linkInput.value.trim();
                    badge.href = value || '#';
                    badge.style.display = value ? 'inline-flex' : 'none';
                };

                linkInput.addEventListener('input', syncLink);
                syncLink();
            };

            setupBannerPreview('homeSide');
            setupBannerPreview('chennaiSide');
        })();
    </script>
</div>
