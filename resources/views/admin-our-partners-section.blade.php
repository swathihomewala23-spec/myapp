@php
    $partners = $partners ?? collect();
@endphp

{{-- ─── Google Fonts: Syne (display) + DM Sans (body) ─── --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
/* ════════════════════════════════════════════
   TOKENS  (scoped to .pt- prefix to avoid
   collision with any existing global styles)
════════════════════════════════════════════ */
:root {
    --pt-font-display : 'Syne', sans-serif;
    --pt-font-body    : 'DM Sans', sans-serif;

    --pt-shell-bg     : #ffffff;
    --pt-border       : #e8edf5;
    --pt-border-soft  : #f1f5f9;

    --pt-ink-900      : #0d1b2a;
    --pt-ink-700      : #1e3351;
    --pt-ink-500      : #4b647e;
    --pt-ink-300      : #8fa3b8;

    --pt-accent       : #2855d8;
    --pt-accent-glow  : rgba(40, 85, 216, 0.18);
    --pt-accent-light : #ebf0ff;

    /* status */
    --pt-active-bg    : #e6faf1;
    --pt-active-ink   : #0e7a4a;
    --pt-active-dot   : #22c77a;
    --pt-inactive-bg  : #fff1f2;
    --pt-inactive-ink : #b91c2a;
    --pt-inactive-dot : #fb4b59;

    /* delete */
    --pt-del-bg       : #fff5f5;
    --pt-del-border   : #fecaca;
    --pt-del-ink      : #dc2626;

    --pt-radius-card  : 20px;
    --pt-radius-btn   : 12px;
    --pt-radius-pill  : 999px;
    --pt-shadow-card  : 0 20px 48px rgba(13,27,42,0.07);
    --pt-shadow-btn   : 0 8px 20px rgba(40, 85, 216, 0.22);
}

/* ════════════════════════════════════════════
   KEYFRAMES
════════════════════════════════════════════ */
@keyframes pt-shell-in {
    0%   { opacity:0; transform:translateY(18px); }
    100% { opacity:1; transform:translateY(0); }
}
@keyframes pt-head-in {
    0%   { opacity:0; transform:translateX(-14px); }
    100% { opacity:1; transform:translateX(0); }
}
@keyframes pt-row-in {
    0%   { opacity:0; transform:translateX(-10px); }
    100% { opacity:1; transform:translateX(0); }
}
@keyframes pt-pulse-dot {
    0%,100% { transform:scale(1); opacity:1; }
    50%      { transform:scale(1.6); opacity:0.5; }
}
@keyframes pt-btn-pop {
    0%   { transform:scale(1); }
    40%  { transform:scale(0.90); }
    75%  { transform:scale(1.06); }
    100% { transform:scale(1); }
}
@keyframes pt-slide-down {
    0%   { opacity:0; transform:translateY(-8px); }
    100% { opacity:1; transform:translateY(0); }
}
@keyframes pt-badge-in {
    0%   { opacity:0; transform:scale(0.7); }
    60%  { transform:scale(1.1); }
    100% { opacity:1; transform:scale(1); }
}
@keyframes pt-thumb-in {
    0%   { opacity:0; transform:scale(0.8) rotate(-4deg); }
    100% { opacity:1; transform:scale(1) rotate(0deg); }
}

/* ════════════════════════════════════════════
   PAGE WRAPPER
════════════════════════════════════════════ */
.pt-page {
    font-family: var(--pt-font-body);
    display: grid;
    gap: 24px;
}

/* ════════════════════════════════════════════
   ERROR BANNER
════════════════════════════════════════════ */
.pt-error-banner {
    padding: 14px 20px;
    border-radius: 14px;
    border: 1px solid var(--pt-inactive-dot);
    background: var(--pt-inactive-bg);
    color: var(--pt-inactive-ink);
    font-size: 0.88rem;
    font-weight: 500;
    animation: pt-slide-down 0.35s ease both;
}

/* ════════════════════════════════════════════
   SHELL
════════════════════════════════════════════ */
.pt-shell {
    background: var(--pt-shell-bg);
    border: 1px solid var(--pt-border);
    border-radius: var(--pt-radius-card);
    box-shadow: var(--pt-shadow-card);
    overflow: hidden;
    animation: pt-shell-in 0.55s cubic-bezier(0.22,1,0.36,1) both;
}

/* ─── Header ─── */
.pt-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 24px 26px 18px;
    border-bottom: 1px solid var(--pt-border-soft);
    background: linear-gradient(95deg, #f9fbff 0%, #ffffff 100%);
    animation: pt-head-in 0.45s 0.05s ease both;
}
.pt-head-left { display:flex; align-items:center; gap:14px; }
.pt-head-icon {
    width: 44px; height: 44px;
    border-radius: 14px;
    background: var(--pt-accent-light);
    display: grid; place-items: center;
    color: var(--pt-accent);
    flex-shrink: 0;
}
.pt-head-icon svg { width:22px; height:22px; }
.pt-head-title {
    margin: 0;
    font-family: var(--pt-font-display);
    font-size: 1.12rem;
    font-weight: 800;
    color: var(--pt-ink-900);
    letter-spacing: -0.4px;
    line-height: 1.2;
}
.pt-head-sub {
    margin: 3px 0 0;
    font-size: 0.80rem;
    color: var(--pt-ink-300);
    font-weight: 400;
}

/* Add button */
.pt-add-btn {
    display: inline-flex; align-items: center; gap: 8px;
    border: 0;
    border-radius: var(--pt-radius-btn);
    background: linear-gradient(130deg, #3b6ff5, var(--pt-accent));
    color: #fff;
    padding: 11px 20px;
    font-family: var(--pt-font-display);
    font-weight: 700;
    font-size: 0.88rem;
    letter-spacing: 0.2px;
    cursor: pointer;
    box-shadow: var(--pt-shadow-btn);
    transition: transform 0.22s ease, box-shadow 0.22s ease;
    position: relative;
    overflow: hidden;
}
.pt-add-btn::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.18) 0%, transparent 60%);
    pointer-events: none;
}
.pt-add-btn:hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 14px 30px rgba(40,85,216,0.32);
}
.pt-add-btn:active { animation: pt-btn-pop 0.3s ease; }
.pt-add-btn svg { width:16px; height:16px; }

/* ─── Toolbar ─── */
.pt-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 18px 26px 12px;
    flex-wrap: wrap;
    background: #fafbfe;
    border-bottom: 1px solid var(--pt-border-soft);
    animation: pt-slide-down 0.4s 0.1s ease both;
}
.pt-toolbar-group {
    display: flex; align-items: center; gap: 8px;
    color: var(--pt-ink-500);
    font-size: 0.88rem; font-weight: 600;
}
.pt-toolbar-group label { font-weight: 600; color: var(--pt-ink-700); }

.pt-ctrl {
    border: 1px solid var(--pt-border);
    border-radius: 10px;
    padding: 8px 14px;
    font-family: var(--pt-font-body);
    font-size: 0.88rem;
    color: var(--pt-ink-900);
    background: #fff;
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
}
.pt-ctrl:focus {
    border-color: var(--pt-accent);
    box-shadow: 0 0 0 3px var(--pt-accent-glow);
}
.pt-search-wrap {
    position: relative;
    display: flex; align-items: center;
}
.pt-search-icon {
    position: absolute; left: 11px;
    color: var(--pt-ink-300);
    pointer-events: none;
    width: 15px; height: 15px;
}
.pt-search-wrap .pt-ctrl { padding-left: 34px; min-width: 200px; }

/* ─── Table ─── */
.pt-table-wrap { padding: 0 4px 4px; overflow-x: auto; }

.pt-table { width: 100%; border-collapse: collapse; }
.pt-table thead th {
    text-align: left;
    padding: 14px 16px;
    font-family: var(--pt-font-display);
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--pt-ink-300);
    background: #f8fafd;
    border-bottom: 1px solid var(--pt-border);
    white-space: nowrap;
    position: sticky; top: 0; z-index: 1;
}
.pt-table thead th:first-child { padding-left: 26px; }
.pt-table thead th:last-child  { padding-right: 26px; }
.pt-table tbody td {
    padding: 16px;
    font-size: 0.90rem;
    color: var(--pt-ink-500);
    border-bottom: 1px solid var(--pt-border-soft);
    vertical-align: middle;
    transition: background 0.18s;
}
.pt-table tbody td:first-child { padding-left: 26px; }
.pt-table tbody td:last-child  { padding-right: 26px; }

/* Row animations */
.pt-row {
    animation: pt-row-in 0.4s ease both;
    transition: background 0.18s, transform 0.18s;
}
.pt-row:last-child td { border-bottom: none; }
.pt-row:hover td { background: #f7f9ff; }
.pt-row:hover { transform: translateX(2px); }

.pt-row:nth-child(1)  { animation-delay: 0.06s; }
.pt-row:nth-child(2)  { animation-delay: 0.10s; }
.pt-row:nth-child(3)  { animation-delay: 0.14s; }
.pt-row:nth-child(4)  { animation-delay: 0.18s; }
.pt-row:nth-child(5)  { animation-delay: 0.22s; }
.pt-row:nth-child(6)  { animation-delay: 0.26s; }
.pt-row:nth-child(7)  { animation-delay: 0.30s; }
.pt-row:nth-child(8)  { animation-delay: 0.34s; }
.pt-row:nth-child(9)  { animation-delay: 0.38s; }
.pt-row:nth-child(10) { animation-delay: 0.42s; }

/* Checkbox */
.pt-checkbox { width:16px; height:16px; accent-color: var(--pt-accent); cursor:pointer; }

/* Name cell */
.pt-name {
    font-family: var(--pt-font-display);
    font-weight: 700;
    font-size: 0.93rem;
    color: var(--pt-ink-900);
    letter-spacing: -0.2px;
}

/* Projects cell */
.pt-projects {
    font-size: 0.86rem;
    color: var(--pt-ink-500);
    background: var(--pt-accent-light);
    color: var(--pt-accent);
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 600;
    display: inline-block;
    white-space: nowrap;
}
.pt-projects-empty { color: var(--pt-ink-300); font-size: 0.86rem; }

/* Thumbnail */
.pt-thumb {
    width: 52px; height: 52px;
    border-radius: 14px;
    overflow: hidden;
    border: 1.5px solid var(--pt-border);
    background: linear-gradient(145deg, #f0f5ff 0%, #e8efff 100%);
    display: grid; place-items: center;
    animation: pt-thumb-in 0.4s ease both;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    position: relative;
}
.pt-thumb::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, transparent 60%);
    pointer-events: none;
}
.pt-thumb:hover {
    transform: scale(1.12) rotate(-2deg);
    box-shadow: 0 10px 24px rgba(40,85,216,0.18);
}
.pt-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
.pt-thumb-empty { font-size:0.70rem; color:var(--pt-ink-300); text-align:center; padding:6px; line-height:1.3; }

/* ─── Status Badge ─── */
.pt-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: var(--pt-radius-pill);
    font-family: var(--pt-font-display);
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    animation: pt-badge-in 0.4s ease both;
    transition: transform 0.2s, box-shadow 0.2s;
    white-space: nowrap;
}
.pt-badge:hover { transform:scale(1.06); box-shadow:0 4px 12px rgba(0,0,0,0.10); }
.pt-badge-dot { width:7px; height:7px; border-radius:50%; flex-shrink:0; }

.pt-badge.pt-active {
    background: var(--pt-active-bg);
    color: var(--pt-active-ink);
    border: 1px solid rgba(14,122,74,0.15);
    box-shadow: 0 2px 8px rgba(34,199,122,0.15);
}
.pt-badge.pt-active .pt-badge-dot {
    background: var(--pt-active-dot);
    animation: pt-pulse-dot 1.8s ease-in-out infinite;
}
.pt-badge.pt-inactive {
    background: var(--pt-inactive-bg);
    color: var(--pt-inactive-ink);
    border: 1px solid rgba(185,28,42,0.15);
    box-shadow: 0 2px 8px rgba(251,75,89,0.12);
}
.pt-badge.pt-inactive .pt-badge-dot { background: var(--pt-inactive-dot); }

/* Date */
.pt-date { font-size:0.84rem; color:var(--pt-ink-300); white-space:nowrap; }

/* ─── Actions ─── */
.pt-actions { display:flex; align-items:center; gap:8px; }

.pt-action-btn {
    width: 40px; height: 40px;
    border-radius: var(--pt-radius-btn);
    border: 1px solid var(--pt-border);
    background: #ffffff;
    display: grid; place-items: center;
    cursor: pointer;
    transition: transform 0.22s ease, box-shadow 0.22s ease, background 0.22s, border-color 0.22s;
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
}
.pt-action-btn svg { width:17px; height:17px; stroke-width:1.8; transition:transform 0.22s, color 0.22s; }
.pt-action-btn::before {
    content:''; position:absolute; inset:0;
    opacity:0; transition:opacity 0.22s;
}
.pt-action-btn:hover { transform:translateY(-3px); box-shadow:0 8px 20px rgba(0,0,0,0.10); }
.pt-action-btn:active { animation:pt-btn-pop 0.28s ease; }

/* Edit */
.pt-action-btn.pt-edit svg { color:var(--pt-ink-500); }
.pt-action-btn.pt-edit::before { background:linear-gradient(135deg, rgba(40,85,216,0.07), rgba(59,111,245,0.12)); }
.pt-action-btn.pt-edit:hover::before { opacity:1; }
.pt-action-btn.pt-edit:hover { border-color:rgba(40,85,216,0.3); }
.pt-action-btn.pt-edit:hover svg { color:var(--pt-accent); transform:rotate(-10deg) scale(1.1); }

/* Delete */
.pt-action-btn.pt-delete { border-color:var(--pt-del-border); background:var(--pt-del-bg); }
.pt-action-btn.pt-delete svg { color:#e57373; }
.pt-action-btn.pt-delete::before { background:linear-gradient(135deg, rgba(220,38,38,0.06), rgba(220,38,38,0.14)); }
.pt-action-btn.pt-delete:hover::before { opacity:1; }
.pt-action-btn.pt-delete:hover { border-color:var(--pt-del-ink); }
.pt-action-btn.pt-delete:hover svg { color:var(--pt-del-ink); transform:scale(1.15) translateY(-1px); }

/* ─── Empty state ─── */
.pt-empty { padding:48px 12px; text-align:center; }
.pt-empty-icon { width:64px; height:64px; background:var(--pt-accent-light); border-radius:18px; display:grid; place-items:center; margin:0 auto 16px; color:var(--pt-accent); }
.pt-empty-icon svg { width:30px; height:30px; }
.pt-empty-title { font-family:var(--pt-font-display); font-size:1rem; font-weight:800; color:var(--pt-ink-700); margin:0 0 6px; }
.pt-empty-sub { font-size:0.86rem; color:var(--pt-ink-300); margin:0; }

/* ─── Footer ─── */
.pt-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 16px 26px 22px;
    flex-wrap: wrap;
    background: #fafbfe;
    border-top: 1px solid var(--pt-border-soft);
}
.pt-page-info { font-size:0.84rem; color:var(--pt-ink-300); font-weight:500; }
.pt-page-info strong { color:var(--pt-ink-700); font-weight:700; }

.pt-pagination { display:flex; align-items:center; gap:6px; flex-wrap:wrap; }
.pt-page-btn {
    min-width:36px; height:36px;
    border:1px solid var(--pt-border); border-radius:10px;
    background:#fff; color:var(--pt-ink-500);
    font-family:var(--pt-font-body); font-size:0.84rem; font-weight:600;
    cursor:pointer; display:grid; place-items:center;
    padding:0 10px; transition:all 0.2s ease;
}
.pt-page-btn:hover:not(:disabled) { background:var(--pt-accent-light); border-color:var(--pt-accent); color:var(--pt-accent); transform:translateY(-2px); }
.pt-page-btn.active { background:var(--pt-accent); border-color:var(--pt-accent); color:#fff; box-shadow:var(--pt-shadow-btn); }
.pt-page-btn:disabled { opacity:0.35; cursor:not-allowed; }

/* ════════════════════════════════════════════
   MODAL
════════════════════════════════════════════ */
.pt-modal-backdrop {
    display: none;
    position: fixed; inset: 0;
    background: rgba(13,27,42,0.50);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 9000;
    place-items: center;
    padding: 20px;
}
.pt-modal-backdrop.open { display:grid; animation:pt-shell-in 0.3s ease both; }

.pt-modal {
    background: #fff;
    border-radius: 22px;
    box-shadow: 0 32px 80px rgba(13,27,42,0.18);
    width: 100%; max-width: 600px;
    overflow: hidden;
    animation: pt-shell-in 0.35s cubic-bezier(0.22,1,0.36,1) both;
}

.pt-modal-head {
    display:flex; align-items:flex-start; justify-content:space-between; gap:16px;
    padding:26px 28px 20px;
    border-bottom:1px solid var(--pt-border-soft);
    background:linear-gradient(100deg, #f7faff 0%, #fff 100%);
}
.pt-modal-title {
    font-family:var(--pt-font-display); font-size:1.08rem; font-weight:800;
    color:var(--pt-ink-900); margin:0 0 4px; letter-spacing:-0.3px;
}
.pt-modal-sub { font-size:0.82rem; color:var(--pt-ink-300); margin:0; }

.pt-modal-close {
    width:36px; height:36px; border-radius:10px;
    border:1px solid var(--pt-border); background:#fff;
    display:grid; place-items:center;
    cursor:pointer; color:var(--pt-ink-300);
    transition:all 0.2s; flex-shrink:0;
}
.pt-modal-close:hover { background:var(--pt-del-bg); border-color:var(--pt-del-border); color:var(--pt-del-ink); transform:rotate(90deg); }
.pt-modal-close svg { width:18px; height:18px; }

.pt-modal-body { padding:26px 28px; display:grid; gap:18px; }
.pt-modal-row  { display:grid; grid-template-columns:1fr 1fr; gap:18px; }

.pt-field { display:grid; gap:7px; }
.pt-field label {
    font-family:var(--pt-font-display); font-size:0.80rem; font-weight:700;
    letter-spacing:0.06em; text-transform:uppercase; color:var(--pt-ink-500);
}
.pt-field input, .pt-field select {
    border:1.5px solid var(--pt-border); border-radius:12px;
    padding:11px 14px; font-family:var(--pt-font-body); font-size:0.92rem;
    color:var(--pt-ink-900); background:#fafcff; outline:none;
    transition:border-color 0.2s, box-shadow 0.2s;
}
.pt-field input:focus, .pt-field select:focus {
    border-color:var(--pt-accent);
    box-shadow:0 0 0 3px var(--pt-accent-glow);
    background:#fff;
}
.pt-field input[type="file"] { padding:9px 14px; cursor:pointer; font-size:0.84rem; }

/* Upload preview */
.pt-upload-row { display:flex; align-items:flex-start; gap:16px; }
.pt-preview-box {
    width:90px; height:90px; border-radius:16px;
    overflow:hidden; border:2px dashed var(--pt-border);
    background:linear-gradient(145deg, #f0f5ff 0%, #e8efff 100%);
    display:grid; place-items:center; flex-shrink:0;
    transition:border-color 0.2s;
}
.pt-preview-box img { width:100%; height:100%; object-fit:cover; }
.pt-preview-note { font-size:0.80rem; color:var(--pt-ink-300); line-height:1.5; padding-top:4px; }

.pt-modal-foot {
    display:flex; align-items:center; justify-content:flex-end; gap:10px;
    padding:18px 28px 24px;
    border-top:1px solid var(--pt-border-soft);
    background:#fafbfe;
}
.pt-btn-cancel {
    padding:10px 20px; border:1px solid var(--pt-border); border-radius:10px;
    background:#fff; font-family:var(--pt-font-body); font-size:0.90rem;
    font-weight:600; color:var(--pt-ink-500); cursor:pointer; transition:all 0.2s;
}
.pt-btn-cancel:hover { background:#f1f5f9; border-color:#c8d4e0; color:var(--pt-ink-700); }
.pt-btn-save {
    padding:10px 24px; border:0; border-radius:10px;
    background:linear-gradient(130deg, #3b6ff5, var(--pt-accent));
    color:#fff; font-family:var(--pt-font-display); font-size:0.90rem;
    font-weight:700; cursor:pointer; box-shadow:var(--pt-shadow-btn);
    transition:transform 0.2s, box-shadow 0.2s;
}
.pt-btn-save:hover { transform:translateY(-2px); box-shadow:0 12px 28px rgba(40,85,216,0.30); }
.pt-btn-save:active { animation:pt-btn-pop 0.28s ease; }

/* ─── Responsive ─── */
@media (max-width: 640px) {
    .pt-modal-row  { grid-template-columns:1fr; }
    .pt-table      { min-width:700px; }
    .pt-table-wrap { overflow-x:auto; }
    .pt-head       { flex-wrap:wrap; }
    .pt-toolbar    { flex-direction:column; align-items:flex-start; }
}
</style>

{{-- ─── Error Banner ─── --}}
@if ($errors->any())
<div class="pt-error-banner">
    @foreach ($errors->all() as $error)
        <div>⚠ {{ $error }}</div>
    @endforeach
</div>
@endif

{{-- ─── Page ─── --}}
<div class="pt-page">
<div class="pt-shell">

    {{-- Head --}}
    <div class="pt-head">
        <div class="pt-head-left">
            <div class="pt-head-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4 6v-1a4 4 0 00-4-4H8a4 4 0 00-4 4v1m8 0a4 4 0 014-4h1a4 4 0 014 4v1M12 7a4 4 0 100-8 4 4 0 000 8z"/>
                </svg>
            </div>
            <div>
                <h2 class="pt-head-title">Our Partners</h2>
                <p class="pt-head-sub">Manage partner listings and branding</p>
            </div>
        </div>
        <button type="button" class="pt-add-btn" onclick="ptOpenModal()">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Partner
        </button>
    </div>

    {{-- Toolbar --}}
    <div class="pt-toolbar">
        <div class="pt-toolbar-group">
            <span>Show</span>
            <select class="pt-ctrl" id="ptEntries">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            <span>entries</span>
        </div>
        <div class="pt-toolbar-group">
            <label for="ptSearch">Search:</label>
            <div class="pt-search-wrap">
                <svg class="pt-search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                </svg>
                <input type="text" id="ptSearch" class="pt-ctrl" placeholder="Search partners…">
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="pt-table-wrap">
        <table class="pt-table">
            <thead>
                <tr>
                    <th style="width:48px;"><input type="checkbox" class="pt-checkbox" id="ptSelectAll"></th>
                    <th>Partner Name</th>
                    <th>Projects</th>
                    <th>Logo</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="ptTableBody">
                @forelse ($partners as $partner)
                    @php
                        $ptImg  = $partner->image ? \App\Support\MediaPath::url($partner->image) : null;
                        $ptDate = $partner->created_at
                                    ? \Carbon\Carbon::parse($partner->created_at)->format('d M Y, h:i A')
                                    : '—';
                        $ptActive = (int) $partner->status === 1;
                    @endphp
                    <tr
                        class="pt-row"
                        data-name="{{ strtolower($partner->name ?? '') }}"
                        data-projects="{{ strtolower($partner->projects ?? '') }}"
                        data-id="{{ $partner->id }}"
                        data-image="{{ $ptImg ?? '' }}"
                        data-status="{{ (int) $partner->status }}"
                    >
                        <td><input type="checkbox" class="pt-checkbox"></td>
                        <td class="pt-name">{{ $partner->name }}</td>
                        <td>
                            @if($partner->projects)
                                <span class="pt-projects">{{ $partner->projects }}</span>
                            @else
                                <span class="pt-projects-empty">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="pt-thumb">
                                @if ($ptImg)
                                    <img src="{{ $ptImg }}" alt="{{ $partner->name }}">
                                @else
                                    <div class="pt-thumb-empty">No<br>logo</div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="pt-badge {{ $ptActive ? 'pt-active' : 'pt-inactive' }}">
                                <span class="pt-badge-dot"></span>
                                {{ $ptActive ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="pt-date">{{ $ptDate }}</td>
                        <td>
                            <div class="pt-actions">
                                {{-- Edit --}}
                                <button
                                    type="button"
                                    class="pt-action-btn pt-edit"
                                    onclick="ptEditModal(this)"
                                    data-id="{{ $partner->id }}"
                                    data-name="{{ $partner->name }}"
                                    data-projects="{{ $partner->projects }}"
                                    data-status="{{ (int) $partner->status }}"
                                    data-image="{{ $ptImg ?? '' }}"
                                    title="Edit partner"
                                >
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 019.172 18H6v-3.172A4 4 0 017.464 11.464L9 10z"/>
                                    </svg>
                                </button>

                                {{-- Delete --}}
                                <form
                                    method="POST"
                                    action="{{ route('admin.partners.destroy', $partner->id) }}"
                                    onsubmit="return confirm('Permanently delete this partner?')"
                                    style="margin:0; display:contents;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="pt-action-btn pt-delete" title="Delete partner">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 7h12M9 7V4h6v3m-7 4v6m4-6v6m4-6v6M5 7l1 13a2 2 0 002 2h8a2 2 0 002-2l1-13"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="pt-empty">
                                <div class="pt-empty-icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4 6v-1a4 4 0 00-4-4H8a4 4 0 00-4 4v1m8 0a4 4 0 014-4h1a4 4 0 014 4v1M12 7a4 4 0 100-8 4 4 0 000 8z"/>
                                    </svg>
                                </div>
                                <p class="pt-empty-title">No partners yet</p>
                                <p class="pt-empty-sub">Click "Add Partner" to add your first listing.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <div class="pt-footer">
        <div class="pt-page-info" id="ptPageInfo">Showing 0 to 0 of 0 entries</div>
        <div class="pt-pagination" id="ptPagination"></div>
    </div>

</div><!-- /pt-shell -->
</div><!-- /pt-page -->

{{-- ─────────────────── MODAL ─────────────────── --}}
<div id="ptModal" class="pt-modal-backdrop" onclick="if(event.target===this)ptCloseModal()">
    <div class="pt-modal">
        <div class="pt-modal-head">
            <div>
                <h3 class="pt-modal-title" id="ptModalTitle">Add Partner</h3>
                <p class="pt-modal-sub"  id="ptModalSub">Create a new partner entry for the homepage and listings.</p>
            </div>
            <button type="button" class="pt-modal-close" onclick="ptCloseModal()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="pt-modal-body">
            <form id="ptForm" method="POST" action="{{ route('admin.partners.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="ptFormMethod" value="POST">

                {{-- Name + Projects --}}
                <div class="pt-modal-row">
                    <div class="pt-field">
                        <label for="ptName">Partner Name</label>
                        <input type="text" id="ptName" name="name" value="{{ old('name') }}" placeholder="e.g. Acme Corp" required>
                    </div>
                    <div class="pt-field">
                        <label for="ptProjects">Projects</label>
                        <input type="text" id="ptProjects" name="projects" value="{{ old('projects') }}" placeholder="e.g. 12 Projects">
                    </div>
                </div>

                {{-- Status + File --}}
                <div class="pt-modal-row">
                    <div class="pt-field">
                        <label for="ptStatus">Status</label>
                        <select id="ptStatus" name="status" required>
                            <option value="1" {{ old('status','1')==='1' ? 'selected':'' }}>Active</option>
                            <option value="0" {{ old('status')==='0'   ? 'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="pt-field">
                        <label for="ptImageInput">Logo / Image</label>
                        <input type="file" id="ptImageInput" name="image" accept="image/*">
                    </div>
                </div>

                {{-- Preview --}}
                <div class="pt-upload-row">
                    <div class="pt-preview-box">
                        <img id="ptImgPreview" src="" alt="Preview" style="display:none;">
                        <div id="ptImgPlaceholder" class="pt-thumb-empty">Preview</div>
                    </div>
                    <p class="pt-preview-note">Upload a square logo or brand image.<br>JPG or PNG, up to 2 MB.</p>
                </div>
            </form>
        </div>

        <div class="pt-modal-foot">
            <button type="button" class="pt-btn-cancel" onclick="ptCloseModal()">Cancel</button>
            <button type="submit" form="ptForm" class="pt-btn-save">Save Partner</button>
        </div>
    </div>
</div>

<script>
(() => {
    /* ── DOM refs ── */
    const modal          = document.getElementById('ptModal');
    const form           = document.getElementById('ptForm');
    const formMethod     = document.getElementById('ptFormMethod');
    const modalTitle     = document.getElementById('ptModalTitle');
    const modalSub       = document.getElementById('ptModalSub');
    const nameInput      = document.getElementById('ptName');
    const projectsInput  = document.getElementById('ptProjects');
    const statusInput    = document.getElementById('ptStatus');
    const imgInput       = document.getElementById('ptImageInput');
    const imgPreview     = document.getElementById('ptImgPreview');
    const imgPlaceholder = document.getElementById('ptImgPlaceholder');
    const searchInput    = document.getElementById('ptSearch');
    const entriesSelect  = document.getElementById('ptEntries');
    const pageInfo       = document.getElementById('ptPageInfo');
    const pagination     = document.getElementById('ptPagination');

    let currentPage  = 1;
    let filteredRows = [];

    /* ── Preview helper ── */
    const setPreview = (src) => {
        if (src) {
            imgPreview.src = src;
            imgPreview.style.display  = 'block';
            imgPlaceholder.style.display = 'none';
        } else {
            imgPreview.src = '';
            imgPreview.style.display  = 'none';
            imgPlaceholder.style.display = 'block';
        }
    };

    /* ── Open: Add ── */
    window.ptOpenModal = () => {
        modalTitle.textContent = 'Add Partner';
        modalSub.textContent   = 'Create a new partner entry for the homepage and listings.';
        form.action            = "{{ route('admin.partners.store') }}";
        formMethod.value       = 'POST';
        form.reset();
        statusInput.value = '1';
        setPreview('');
        modal.classList.add('open');
    };

    /* ── Open: Edit ── */
    window.ptEditModal = (btn) => {
        modalTitle.textContent  = 'Edit Partner';
        modalSub.textContent    = 'Update partner details and branding image.';
        form.action             = "{{ url('/dashboard/our-partners') }}/" + btn.dataset.id;
        formMethod.value        = 'PUT';
        nameInput.value         = btn.dataset.name     || '';
        projectsInput.value     = btn.dataset.projects || '';
        statusInput.value       = btn.dataset.status   || '1';
        imgInput.value          = '';
        setPreview(btn.dataset.image || '');
        modal.classList.add('open');
    };

    /* ── Close ── */
    window.ptCloseModal = () => modal.classList.remove('open');
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') ptCloseModal(); });

    /* ── File → preview ── */
    imgInput?.addEventListener('change', (e) => {
        const file = e.target.files?.[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (ev) => setPreview(ev.target?.result || '');
        reader.readAsDataURL(file);
    });

    /* ── Select-all ── */
    document.getElementById('ptSelectAll')?.addEventListener('change', function () {
        document.querySelectorAll('#ptTableBody .pt-checkbox').forEach(cb => cb.checked = this.checked);
    });

    /* ── Filtering + pagination ── */
    const allRows = () => Array.from(document.querySelectorAll('#ptTableBody .pt-row'));

    const buildPagination = (totalPages) => {
        pagination.innerHTML = '';
        const btn = (label, page, disabled = false, active = false) => {
            const b = document.createElement('button');
            b.type        = 'button';
            b.textContent = label;
            b.className   = 'pt-page-btn' + (active ? ' active' : '');
            b.disabled    = disabled;
            if (!disabled && !active) b.addEventListener('click', () => { currentPage = page; filter(); });
            return b;
        };
        pagination.appendChild(btn('← Prev', currentPage - 1, currentPage === 1));
        for (let p = 1; p <= totalPages; p++) {
            if (p === 1 || p === totalPages || Math.abs(p - currentPage) <= 1) {
                pagination.appendChild(btn(p, p, false, p === currentPage));
            } else if (Math.abs(p - currentPage) === 2) {
                const dots = document.createElement('span');
                dots.textContent = '…';
                dots.style.cssText = 'color:var(--pt-ink-300);padding:0 4px;font-size:0.84rem;';
                pagination.appendChild(dots);
            }
        }
        pagination.appendChild(btn('Next →', currentPage + 1, currentPage === totalPages));
    };

    const filter = () => {
        const term  = (searchInput?.value || '').trim().toLowerCase();
        const limit = parseInt(entriesSelect?.value || '10', 10);
        const rows  = allRows();

        filteredRows = rows.filter(r =>
            (r.dataset.name     || '').includes(term) ||
            (r.dataset.projects || '').includes(term)
        );
        rows.forEach(r => r.style.display = 'none');

        const totalPages = Math.max(1, Math.ceil(filteredRows.length / limit));
        if (currentPage > totalPages) currentPage = totalPages;

        const start   = (currentPage - 1) * limit;
        const visible = filteredRows.slice(start, start + limit);
        visible.forEach((r, i) => {
            r.style.display = 'table-row';
            r.style.animationDelay = `${i * 0.045}s`;
        });

        const from = filteredRows.length ? start + 1 : 0;
        const to   = Math.min(start + limit, filteredRows.length);
        pageInfo.innerHTML = `Showing <strong>${from}</strong> to <strong>${to}</strong> of <strong>${filteredRows.length}</strong> entries`;
        buildPagination(totalPages);
    };

    searchInput?.addEventListener('input',  () => { currentPage = 1; filter(); });
    entriesSelect?.addEventListener('change', () => { currentPage = 1; filter(); });
    filter();

    /* ── Reopen on validation error ── */
    @if ($errors->any() && (old('name') || old('projects')))
        if ("{{ old('_method', 'POST') }}" === 'PUT') {
            modalTitle.textContent = 'Edit Partner';
            modalSub.textContent   = 'Update partner details and branding image.';
        }
        modal.classList.add('open');
    @endif
})();
</script>
