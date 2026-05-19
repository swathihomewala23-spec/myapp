@php
    $interiorDesigns = $interiorDesigns ?? collect();
@endphp

{{-- ─── Google Fonts: Syne (display) + DM Sans (body) ─── --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
/* ════════════════════════════════════════════
   TOKENS
════════════════════════════════════════════ */
:root {
    --id-font-display : 'Syne', sans-serif;
    --id-font-body    : 'DM Sans', sans-serif;

    --id-bg           : #f6f8fc;
    --id-shell-bg     : #ffffff;
    --id-border       : #e8edf5;
    --id-border-soft  : #f1f5f9;

    --id-ink-900      : #0d1b2a;
    --id-ink-700      : #1e3351;
    --id-ink-500      : #4b647e;
    --id-ink-300      : #8fa3b8;

    --id-accent       : #2855d8;
    --id-accent-glow  : rgba(40, 85, 216, 0.18);
    --id-accent-light : #ebf0ff;

    /* status */
    --id-active-bg    : #e6faf1;
    --id-active-ink   : #0e7a4a;
    --id-active-dot   : #22c77a;
    --id-inactive-bg  : #fff1f2;
    --id-inactive-ink : #b91c2a;
    --id-inactive-dot : #fb4b59;

    /* delete */
    --id-del-bg       : #fff5f5;
    --id-del-border   : #fecaca;
    --id-del-ink      : #dc2626;

    --id-radius-card  : 20px;
    --id-radius-btn   : 12px;
    --id-radius-pill  : 999px;
    --id-shadow-card  : 0 20px 48px rgba(13,27,42,0.07);
    --id-shadow-btn   : 0 8px 20px rgba(40, 85, 216, 0.22);
}

/* ════════════════════════════════════════════
   KEYFRAMES
════════════════════════════════════════════ */
@keyframes id-shell-in {
    0%   { opacity:0; transform:translateY(18px); }
    100% { opacity:1; transform:translateY(0); }
}
@keyframes id-head-in {
    0%   { opacity:0; transform:translateX(-14px); }
    100% { opacity:1; transform:translateX(0); }
}
@keyframes id-row-in {
    0%   { opacity:0; transform:translateX(-10px); }
    100% { opacity:1; transform:translateX(0); }
}
@keyframes id-shimmer {
    0%   { background-position: -600px 0; }
    100% { background-position: 600px 0; }
}
@keyframes id-pulse-dot {
    0%,100% { transform:scale(1); opacity:1; }
    50%      { transform:scale(1.6); opacity:0.5; }
}
@keyframes id-btn-pop {
    0%   { transform:scale(1); }
    40%  { transform:scale(0.90); }
    75%  { transform:scale(1.06); }
    100% { transform:scale(1); }
}
@keyframes id-slide-down {
    0%   { opacity:0; transform:translateY(-8px); }
    100% { opacity:1; transform:translateY(0); }
}
@keyframes id-badge-in {
    0%   { opacity:0; transform:scale(0.7); }
    60%  { transform:scale(1.1); }
    100% { opacity:1; transform:scale(1); }
}
@keyframes id-thumb-in {
    0%   { opacity:0; transform:scale(0.8) rotate(-4deg); }
    100% { opacity:1; transform:scale(1) rotate(0deg); }
}

/* ════════════════════════════════════════════
   PAGE WRAPPER
════════════════════════════════════════════ */
.id-page {
    font-family: var(--id-font-body);
    display: grid;
    gap: 24px;
}

/* ════════════════════════════════════════════
   ERROR BANNER
════════════════════════════════════════════ */
.id-error-banner {
    padding: 14px 20px;
    border-radius: 14px;
    border: 1px solid var(--id-inactive-dot);
    background: var(--id-inactive-bg);
    color: var(--id-inactive-ink);
    font-size: 0.88rem;
    font-weight: 500;
    animation: id-slide-down 0.35s ease both;
}

/* ════════════════════════════════════════════
   SHELL (card container)
════════════════════════════════════════════ */
.id-shell {
    background: var(--id-shell-bg);
    border: 1px solid var(--id-border);
    border-radius: var(--id-radius-card);
    box-shadow: var(--id-shadow-card);
    overflow: hidden;
    animation: id-shell-in 0.55s cubic-bezier(0.22,1,0.36,1) both;
}

/* ─── Header ─── */
.id-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 24px 26px 18px;
    border-bottom: 1px solid var(--id-border-soft);
    background: linear-gradient(95deg, #f9fbff 0%, #ffffff 100%);
    animation: id-head-in 0.45s 0.05s ease both;
}

.id-head-left { display:flex; align-items:center; gap:14px; }

.id-head-icon {
    width: 44px; height: 44px;
    border-radius: 14px;
    background: var(--id-accent-light);
    display: grid; place-items: center;
    color: var(--id-accent);
    flex-shrink: 0;
}
.id-head-icon svg { width:22px; height:22px; }

.id-head-title {
    margin: 0;
    font-family: var(--id-font-display);
    font-size: 1.12rem;
    font-weight: 800;
    color: var(--id-ink-900);
    letter-spacing: -0.4px;
    line-height: 1.2;
}
.id-head-sub {
    margin: 3px 0 0;
    font-size: 0.80rem;
    color: var(--id-ink-300);
    font-weight: 400;
}

/* Add button */
.id-add-btn {
    display: inline-flex; align-items: center; gap: 8px;
    border: 0;
    border-radius: var(--id-radius-btn);
    background: linear-gradient(130deg, #3b6ff5, var(--id-accent));
    color: #fff;
    padding: 11px 20px;
    font-family: var(--id-font-display);
    font-weight: 700;
    font-size: 0.88rem;
    letter-spacing: 0.2px;
    cursor: pointer;
    box-shadow: var(--id-shadow-btn);
    transition: transform 0.22s ease, box-shadow 0.22s ease;
    position: relative;
    overflow: hidden;
}
.id-add-btn::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.18) 0%, transparent 60%);
    pointer-events: none;
}
.id-add-btn:hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 14px 30px rgba(40,85,216,0.32);
}
.id-add-btn:active { animation: id-btn-pop 0.3s ease; }
.id-add-btn svg { width:16px; height:16px; }

/* ─── Toolbar ─── */
.id-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 18px 26px 12px;
    flex-wrap: wrap;
    background: #fafbfe;
    border-bottom: 1px solid var(--id-border-soft);
    animation: id-slide-down 0.4s 0.1s ease both;
}
.id-toolbar-group {
    display: flex; align-items: center; gap: 8px;
    color: var(--id-ink-500);
    font-size: 0.88rem; font-weight: 600;
}
.id-toolbar-group label { font-weight: 600; color: var(--id-ink-700); }

.id-ctrl {
    border: 1px solid var(--id-border);
    border-radius: 10px;
    padding: 8px 14px;
    font-family: var(--id-font-body);
    font-size: 0.88rem;
    color: var(--id-ink-900);
    background: #fff;
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
}
.id-ctrl:focus {
    border-color: var(--id-accent);
    box-shadow: 0 0 0 3px var(--id-accent-glow);
}
.id-search-wrap {
    position: relative;
    display: flex; align-items: center;
}
.id-search-icon {
    position: absolute; left: 11px;
    color: var(--id-ink-300);
    pointer-events: none;
    width: 15px; height: 15px;
}
.id-search-wrap .id-ctrl {
    padding-left: 34px;
    min-width: 200px;
}

/* ─── Table ─── */
.id-table-wrap {
    padding: 0 4px 4px;
    overflow-x: auto;
}

.id-table {
    width: 100%;
    border-collapse: collapse;
}
.id-table thead th {
    text-align: left;
    padding: 14px 16px;
    font-family: var(--id-font-display);
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--id-ink-300);
    background: #f8fafd;
    border-bottom: 1px solid var(--id-border);
    white-space: nowrap;
    position: sticky; top: 0; z-index: 1;
}
.id-table thead th:first-child { border-radius: 0 0 0 0; padding-left: 26px; }
.id-table thead th:last-child  { padding-right: 26px; }

.id-table tbody td {
    padding: 16px;
    font-size: 0.90rem;
    color: var(--id-ink-500);
    border-bottom: 1px solid var(--id-border-soft);
    vertical-align: middle;
    transition: background 0.18s;
}
.id-table tbody td:first-child { padding-left: 26px; }
.id-table tbody td:last-child  { padding-right: 26px; }

/* Row animations + hover */
.id-row {
    animation: id-row-in 0.4s ease both;
    transition: background 0.18s, transform 0.18s;
}
.id-row:last-child td { border-bottom: none; }
.id-row:hover td { background: #f7f9ff; }
.id-row:hover { transform: translateX(2px); }

/* Stagger via CSS (up to 20 rows) */
.id-row:nth-child(1)  { animation-delay: 0.06s; }
.id-row:nth-child(2)  { animation-delay: 0.10s; }
.id-row:nth-child(3)  { animation-delay: 0.14s; }
.id-row:nth-child(4)  { animation-delay: 0.18s; }
.id-row:nth-child(5)  { animation-delay: 0.22s; }
.id-row:nth-child(6)  { animation-delay: 0.26s; }
.id-row:nth-child(7)  { animation-delay: 0.30s; }
.id-row:nth-child(8)  { animation-delay: 0.34s; }
.id-row:nth-child(9)  { animation-delay: 0.38s; }
.id-row:nth-child(10) { animation-delay: 0.42s; }

/* Checkbox */
.id-checkbox {
    width: 16px; height: 16px;
    accent-color: var(--id-accent);
    cursor: pointer;
}

/* Name cell */
.id-name {
    font-family: var(--id-font-display);
    font-weight: 700;
    font-size: 0.93rem;
    color: var(--id-ink-900);
    letter-spacing: -0.2px;
}

/* Thumbnail */
.id-thumb {
    width: 52px; height: 52px;
    border-radius: 14px;
    overflow: hidden;
    border: 1.5px solid var(--id-border);
    background: linear-gradient(145deg, #f0f5ff 0%, #e8efff 100%);
    display: grid; place-items: center;
    animation: id-thumb-in 0.4s ease both;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    position: relative;
}
.id-thumb::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, transparent 60%);
    pointer-events: none;
}
.id-thumb:hover {
    transform: scale(1.12) rotate(-2deg);
    box-shadow: 0 10px 24px rgba(40,85,216,0.18);
}
.id-thumb img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
}
.id-thumb-empty {
    font-size: 0.70rem;
    color: var(--id-ink-300);
    text-align: center;
    padding: 6px;
    line-height: 1.3;
}

/* ─── Status Badge ─── */
.id-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: var(--id-radius-pill);
    font-family: var(--id-font-display);
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    animation: id-badge-in 0.4s ease both;
    transition: transform 0.2s, box-shadow 0.2s;
    white-space: nowrap;
}
.id-badge:hover {
    transform: scale(1.06);
    box-shadow: 0 4px 12px rgba(0,0,0,0.10);
}
.id-badge-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* Active */
.id-badge.id-active {
    background: var(--id-active-bg);
    color: var(--id-active-ink);
    border: 1px solid rgba(14,122,74,0.15);
    box-shadow: 0 2px 8px rgba(34,199,122,0.15);
}
.id-badge.id-active .id-badge-dot {
    background: var(--id-active-dot);
    animation: id-pulse-dot 1.8s ease-in-out infinite;
}

/* Inactive */
.id-badge.id-inactive {
    background: var(--id-inactive-bg);
    color: var(--id-inactive-ink);
    border: 1px solid rgba(185,28,42,0.15);
    box-shadow: 0 2px 8px rgba(251,75,89,0.12);
}
.id-badge.id-inactive .id-badge-dot {
    background: var(--id-inactive-dot);
}

/* ─── Date cell ─── */
.id-date {
    font-size: 0.84rem;
    color: var(--id-ink-300);
    white-space: nowrap;
}

/* ─── Actions ─── */
.id-actions { display:flex; align-items:center; gap:8px; }

.id-action-btn {
    width: 40px; height: 40px;
    border-radius: var(--id-radius-btn);
    border: 1px solid var(--id-border);
    background: #ffffff;
    display: grid; place-items: center;
    cursor: pointer;
    transition: transform 0.22s ease, box-shadow 0.22s ease, background 0.22s, border-color 0.22s;
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
}
.id-action-btn svg { width:17px; height:17px; stroke-width:1.8; transition: transform 0.22s, color 0.22s; }
.id-action-btn::before {
    content: '';
    position: absolute; inset: 0;
    opacity: 0; transition: opacity 0.22s;
}
.id-action-btn:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.10); }
.id-action-btn:active { animation: id-btn-pop 0.28s ease; }

/* Edit */
.id-action-btn.id-edit svg { color: var(--id-ink-500); }
.id-action-btn.id-edit::before { background: linear-gradient(135deg, rgba(40,85,216,0.07), rgba(59,111,245,0.12)); }
.id-action-btn.id-edit:hover::before { opacity:1; }
.id-action-btn.id-edit:hover { border-color: rgba(40,85,216,0.3); }
.id-action-btn.id-edit:hover svg { color: var(--id-accent); transform: rotate(-10deg) scale(1.1); }

/* Delete */
.id-action-btn.id-delete { border-color: var(--id-del-border); background: var(--id-del-bg); }
.id-action-btn.id-delete svg { color: #e57373; }
.id-action-btn.id-delete::before { background: linear-gradient(135deg, rgba(220,38,38,0.06), rgba(220,38,38,0.14)); }
.id-action-btn.id-delete:hover::before { opacity:1; }
.id-action-btn.id-delete:hover { border-color: var(--id-del-ink); }
.id-action-btn.id-delete:hover svg { color: var(--id-del-ink); transform: scale(1.15) translateY(-1px); }

/* ─── Empty state ─── */
.id-empty {
    padding: 48px 12px;
    text-align: center;
}
.id-empty-icon {
    width: 64px; height: 64px;
    background: var(--id-accent-light);
    border-radius: 18px;
    display: grid; place-items: center;
    margin: 0 auto 16px;
    color: var(--id-accent);
}
.id-empty-icon svg { width:30px; height:30px; }
.id-empty-title {
    font-family: var(--id-font-display);
    font-size: 1rem;
    font-weight: 800;
    color: var(--id-ink-700);
    margin: 0 0 6px;
}
.id-empty-sub { font-size: 0.86rem; color: var(--id-ink-300); margin:0; }

/* ─── Footer ─── */
.id-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 16px 26px 22px;
    flex-wrap: wrap;
    background: #fafbfe;
    border-top: 1px solid var(--id-border-soft);
}
.id-page-info {
    font-size: 0.84rem;
    color: var(--id-ink-300);
    font-weight: 500;
}
.id-page-info strong { color: var(--id-ink-700); font-weight: 700; }

.id-pagination { display:flex; align-items:center; gap:6px; flex-wrap:wrap; }
.id-page-btn {
    min-width: 36px; height: 36px;
    border: 1px solid var(--id-border);
    border-radius: 10px;
    background: #fff;
    color: var(--id-ink-500);
    font-family: var(--id-font-body);
    font-size: 0.84rem;
    font-weight: 600;
    cursor: pointer;
    display: grid; place-items: center;
    padding: 0 10px;
    transition: all 0.2s ease;
}
.id-page-btn:hover:not(:disabled) {
    background: var(--id-accent-light);
    border-color: var(--id-accent);
    color: var(--id-accent);
    transform: translateY(-2px);
}
.id-page-btn.active {
    background: var(--id-accent);
    border-color: var(--id-accent);
    color: #fff;
    box-shadow: var(--id-shadow-btn);
}
.id-page-btn:disabled { opacity: 0.35; cursor: not-allowed; }

/* ════════════════════════════════════════════
   MODAL
════════════════════════════════════════════ */
.id-modal-backdrop {
    display: none;
    position: fixed; inset: 0;
    background: rgba(13,27,42,0.50);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 9000;
    place-items: center;
    padding: 20px;
}
.id-modal-backdrop.open { display: grid; animation: id-shell-in 0.3s ease both; }

.id-modal {
    background: #fff;
    border-radius: 22px;
    box-shadow: 0 32px 80px rgba(13,27,42,0.18);
    width: 100%; max-width: 580px;
    overflow: hidden;
    animation: id-shell-in 0.35s cubic-bezier(0.22,1,0.36,1) both;
}

.id-modal-head {
    display: flex; align-items: flex-start; justify-content: space-between; gap: 16px;
    padding: 26px 28px 20px;
    border-bottom: 1px solid var(--id-border-soft);
    background: linear-gradient(100deg, #f7faff 0%, #fff 100%);
}
.id-modal-title {
    font-family: var(--id-font-display);
    font-size: 1.08rem;
    font-weight: 800;
    color: var(--id-ink-900);
    margin: 0 0 4px;
    letter-spacing: -0.3px;
}
.id-modal-sub { font-size: 0.82rem; color: var(--id-ink-300); margin: 0; }

.id-modal-close {
    width: 36px; height: 36px;
    border-radius: 10px;
    border: 1px solid var(--id-border);
    background: #fff;
    display: grid; place-items: center;
    cursor: pointer;
    color: var(--id-ink-300);
    transition: all 0.2s;
    flex-shrink: 0;
}
.id-modal-close:hover { background: var(--id-del-bg); border-color: var(--id-del-border); color: var(--id-del-ink); transform: rotate(90deg); }
.id-modal-close svg { width:18px; height:18px; }

.id-modal-body { padding: 26px 28px; display:grid; gap:18px; }

.id-modal-row { display:grid; grid-template-columns:1fr 1fr; gap:18px; }

.id-field { display:grid; gap:7px; }
.id-field label {
    font-family: var(--id-font-display);
    font-size: 0.80rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--id-ink-500);
}
.id-field input, .id-field select {
    border: 1.5px solid var(--id-border);
    border-radius: 12px;
    padding: 11px 14px;
    font-family: var(--id-font-body);
    font-size: 0.92rem;
    color: var(--id-ink-900);
    background: #fafcff;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.id-field input:focus, .id-field select:focus {
    border-color: var(--id-accent);
    box-shadow: 0 0 0 3px var(--id-accent-glow);
    background: #fff;
}
.id-field input[type="file"] {
    padding: 9px 14px;
    cursor: pointer;
    font-size: 0.84rem;
}

/* Upload preview */
.id-upload-row { display:flex; align-items:flex-start; gap:16px; }
.id-preview-box {
    width: 90px; height: 90px;
    border-radius: 16px;
    overflow: hidden;
    border: 2px dashed var(--id-border);
    background: linear-gradient(145deg, #f0f5ff 0%, #e8efff 100%);
    display: grid; place-items: center;
    flex-shrink: 0;
    transition: border-color 0.2s;
}
.id-preview-box:has(img[style*="block"]) { border-style: solid; border-color: var(--id-accent); }
.id-preview-box img { width:100%; height:100%; object-fit:cover; }
.id-preview-note { font-size: 0.80rem; color: var(--id-ink-300); line-height: 1.5; padding-top:4px; }

.id-modal-foot {
    display: flex; align-items: center; justify-content: flex-end; gap: 10px;
    padding: 18px 28px 24px;
    border-top: 1px solid var(--id-border-soft);
    background: #fafbfe;
}
.id-btn-cancel {
    padding: 10px 20px;
    border: 1px solid var(--id-border);
    border-radius: 10px;
    background: #fff;
    font-family: var(--id-font-body);
    font-size: 0.90rem;
    font-weight: 600;
    color: var(--id-ink-500);
    cursor: pointer;
    transition: all 0.2s;
}
.id-btn-cancel:hover { background: #f1f5f9; border-color: #c8d4e0; color: var(--id-ink-700); }
.id-btn-save {
    padding: 10px 24px;
    border: 0;
    border-radius: 10px;
    background: linear-gradient(130deg, #3b6ff5, var(--id-accent));
    color: #fff;
    font-family: var(--id-font-display);
    font-size: 0.90rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: var(--id-shadow-btn);
    transition: transform 0.2s, box-shadow 0.2s;
}
.id-btn-save:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(40,85,216,0.30); }
.id-btn-save:active { animation: id-btn-pop 0.28s ease; }

/* ─── Responsive ─── */
@media (max-width: 640px) {
    .id-modal-row { grid-template-columns: 1fr; }
    .id-table { min-width: 640px; }
    .id-table-wrap { overflow-x: auto; }
    .id-head { flex-wrap: wrap; }
    .id-toolbar { flex-direction: column; align-items: flex-start; }
}
</style>

{{-- ───────────────────────────────────────────── --}}
{{-- TEMPLATE                                      --}}
{{-- ───────────────────────────────────────────── --}}

@if ($errors->any())
<div class="id-error-banner">
    @foreach ($errors->all() as $error)
        <div>⚠ {{ $error }}</div>
    @endforeach
</div>
@endif

<div class="id-page">
<div class="id-shell">

    {{-- Head --}}
    <div class="id-head">
        <div class="id-head-left">
            <div class="id-head-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </div>
            <div>
                <h2 class="id-head-title">Interior Designs</h2>
                <p class="id-head-sub">Manage your design catalogue</p>
            </div>
        </div>
        <button type="button" class="id-add-btn" onclick="idOpenModal()">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Design
        </button>
    </div>

    {{-- Toolbar --}}
    <div class="id-toolbar">
        <div class="id-toolbar-group">
            <span>Show</span>
            <select class="id-ctrl" id="idEntries">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            <span>entries</span>
        </div>
        <div class="id-toolbar-group">
            <label for="idSearch">Search:</label>
            <div class="id-search-wrap">
                <svg class="id-search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                </svg>
                <input type="text" id="idSearch" class="id-ctrl" placeholder="Search designs…">
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="id-table-wrap">
        <table class="id-table">
            <thead>
                <tr>
                    <th style="width:48px;"><input type="checkbox" class="id-checkbox" id="idSelectAll"></th>
                    <th>Design Name</th>
                    <th>images</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="idTableBody">
                @forelse ($interiorDesigns as $interior)
                    @php
                        $idImg     = $interior->image ? asset('storage/' . ltrim($interior->image, '/')) : null;
                        $idDate    = $interior->created_at
                                        ? \Carbon\Carbon::parse($interior->created_at)->format('d M Y, h:i A')
                                        : '—';
                        $statusLower = strtolower($interior->status ?? '');
                        $idActive  = $statusLower === 'active' || $statusLower === '1' || $interior->status === 1 || $interior->status === true;
                    @endphp
                    <tr
                        class="id-row"
                        data-name="{{ strtolower($interior->name ?? '') }}"
                        data-id="{{ $interior->id }}"
                        data-image="{{ $idImg ?? '' }}"
                        data-status="{{ $idActive ? '1' : '0' }}"
                    >
                        <td><input type="checkbox" class="id-checkbox"></td>
                        <td class="id-name">{{ $interior->name }}</td>
                        <td>
                            <div class="id-thumb">
                                @if ($idImg)
                                    <img src="{{ $idImg }}" alt="{{ $interior->name }}">
                                @else
                                    <div class="id-thumb-empty">No<br>image</div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="id-badge {{ $idActive ? 'id-active' : 'id-inactive' }}">
                                <span class="id-badge-dot"></span>
                                {{ $idActive ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="id-date">{{ $idDate }}</td>
                        <td>
                            <div class="id-actions">
                                {{-- Edit --}}
                                <button
                                    type="button"
                                    class="id-action-btn id-edit"
                                    onclick="idEditModal(this)"
                                    data-id="{{ $interior->id }}"
                                    data-name="{{ $interior->name }}"
                                    data-status="{{ $idActive ? '1' : '0' }}"
                                    data-image="{{ $idImg ?? '' }}"
                                    title="Edit design"
                                >
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 019.172 18H6v-3.172A4 4 0 017.464 11.464L9 10z"/>
                                    </svg>
                                </button>

                                {{-- Delete --}}
                                <form
                                    method="POST"
                                    action="{{ route('admin.interiors.destroy', $interior->id) }}"
                                    onsubmit="return confirm('Permanently delete this design?')"
                                    style="margin:0; display:contents;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="id-action-btn id-delete" title="Delete design">
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
                        <td colspan="6">
                            <div class="id-empty">
                                <div class="id-empty-icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                </div>
                                <p class="id-empty-title">No designs yet</p>
                                <p class="id-empty-sub">Click "Add Design" to create your first interior design entry.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <div class="id-footer">
        <div class="id-page-info" id="idPageInfo">Showing 0 to 0 of 0 entries</div>
        <div class="id-pagination" id="idPagination"></div>
    </div>

</div><!-- /id-shell -->
</div><!-- /id-page -->

{{-- ─────────────────── MODAL ─────────────────── --}}
<div id="idModal" class="id-modal-backdrop" onclick="if(event.target===this)idCloseModal()">
    <div class="id-modal">
        <div class="id-modal-head">
            <div>
                <h3 class="id-modal-title" id="idModalTitle">Add Interior Design</h3>
                <p class="id-modal-sub" id="idModalSub">Create a new design entry with image and status.</p>
            </div>
            <button type="button" class="id-modal-close" onclick="idCloseModal()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="id-modal-body">
            <form id="idForm" method="POST" action="{{ route('admin.interiors.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="idFormMethod" value="POST">

                {{-- Name + Status --}}
                <div class="id-modal-row">
                    <div class="id-field">
                        <label for="idName">Name</label>
                        <input type="text" id="idName" name="name" value="{{ old('name') }}" placeholder="e.g. Modern Minimalist" required>
                    </div>
                    <div class="id-field">
                        <label for="idStatus">Status</label>
                        <select id="idStatus" name="status" required>
                            <option value="1" {{ old('status','1')==='1' ? 'selected':'' }}>Active</option>
                            <option value="0" {{ old('status')==='0'   ? 'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                {{-- File upload --}}
                <div class="id-field">
                    <label for="idImageInput">Design Image</label>
                    <input type="file" id="idImageInput" name="image" accept="image/*">
                </div>

                {{-- Preview --}}
                <div class="id-upload-row">
                    <div class="id-preview-box">
                        <img id="idImgPreview" src="" alt="Preview" style="display:none;">
                        <div id="idImgPlaceholder" class="id-thumb-empty">Preview</div>
                    </div>
                    <p class="id-preview-note">Upload one image for the listing thumbnail.<br>JPG or PNG, up to 2 MB.</p>
                </div>
            </form>
        </div>

        <div class="id-modal-foot">
            <button type="button" class="id-btn-cancel" onclick="idCloseModal()">Cancel</button>
            <button type="submit" form="idForm" class="id-btn-save">Save Design</button>
        </div>
    </div>
</div>

<script>
(() => {
    /* ── DOM refs ── */
    const modal         = document.getElementById('idModal');
    const form          = document.getElementById('idForm');
    const formMethod    = document.getElementById('idFormMethod');
    const modalTitle    = document.getElementById('idModalTitle');
    const modalSub      = document.getElementById('idModalSub');
    const nameInput     = document.getElementById('idName');
    const statusInput   = document.getElementById('idStatus');
    const imgInput      = document.getElementById('idImageInput');
    const imgPreview    = document.getElementById('idImgPreview');
    const imgPlaceholder= document.getElementById('idImgPlaceholder');
    const searchInput   = document.getElementById('idSearch');
    const entriesSelect = document.getElementById('idEntries');
    const pageInfo      = document.getElementById('idPageInfo');
    const pagination    = document.getElementById('idPagination');

    let currentPage   = 1;
    let filteredRows  = [];

    /* ── Image preview helper ── */
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

    /* ── Modal: open for Add ── */
    window.idOpenModal = () => {
        modalTitle.textContent = 'Add Interior Design';
        modalSub.textContent   = 'Create a new design entry with image and status.';
        form.action            = "{{ route('admin.interiors.store') }}";
        formMethod.value       = 'POST';
        form.reset();
        statusInput.value = '1';
        setPreview('');
        modal.classList.add('open');
    };

    /* ── Modal: open for Edit ── */
    window.idEditModal = (btn) => {
        modalTitle.textContent = 'Edit Interior Design';
        modalSub.textContent   = 'Update the title, image and status.';
        form.action            = "{{ url('/dashboard/interiors') }}/" + btn.dataset.id;
        formMethod.value       = 'PUT';
        nameInput.value        = btn.dataset.name   || '';
        statusInput.value      = btn.dataset.status || '1';
        imgInput.value         = '';
        setPreview(btn.dataset.image || '');
        modal.classList.add('open');
    };

    /* ── Modal: close ── */
    window.idCloseModal = () => modal.classList.remove('open');

    /* ── Esc key closes modal ── */
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') idCloseModal(); });

    /* ── Image file → preview ── */
    imgInput?.addEventListener('change', (e) => {
        const file = e.target.files?.[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (ev) => setPreview(ev.target?.result || '');
        reader.readAsDataURL(file);
    });

    /* ── Select-all checkbox ── */
    document.getElementById('idSelectAll')?.addEventListener('change', function() {
        document.querySelectorAll('#idTableBody .id-checkbox').forEach(cb => cb.checked = this.checked);
    });

    /* ── Table filtering + pagination ── */
    const allRows = () => Array.from(document.querySelectorAll('#idTableBody .id-row'));

    const buildPagination = (totalPages) => {
        pagination.innerHTML = '';
        const btn = (label, page, disabled = false, active = false) => {
            const b = document.createElement('button');
            b.type      = 'button';
            b.textContent = label;
            b.className = 'id-page-btn' + (active ? ' active' : '');
            b.disabled  = disabled;
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
                dots.style.cssText = 'color:var(--id-ink-300);padding:0 4px;font-size:0.84rem;';
                pagination.appendChild(dots);
            }
        }
        pagination.appendChild(btn('Next →', currentPage + 1, currentPage === totalPages));
    };

    const filter = () => {
        const term  = (searchInput?.value || '').trim().toLowerCase();
        const limit = parseInt(entriesSelect?.value || '10', 10);
        const rows  = allRows();

        filteredRows = rows.filter(r => (r.dataset.name || '').includes(term));
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

    searchInput?.addEventListener('input', () => { currentPage = 1; filter(); });
    entriesSelect?.addEventListener('change', () => { currentPage = 1; filter(); });
    filter();

    /* ── Reopen modal on validation error ── */
    @if ($errors->any() && old('name'))
        if ("{{ old('_method', 'POST') }}" === 'PUT') {
            modalTitle.textContent = 'Edit Interior Design';
            modalSub.textContent   = 'Update the title, image and status.';
        }
        modal.classList.add('open');
    @endif
})();
</script>