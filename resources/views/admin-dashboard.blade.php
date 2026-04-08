<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }} Dashboard</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=nunito-sans:400,500,600,700" rel="stylesheet" />
        <style>
            :root {
                --bg: #f4f7ff;
                --panel: #ffffff;
                --sidebar: #ffffff;
                --line: #dfe6f3;
                --text: #202530;
                --muted: #7d8797;
                --brand: #18a4ea;
                --brand-dark: #0f1e55;
                --success: #1db8a0;
                --danger: #ef476f;
                --shadow: 0 18px 55px rgba(90, 104, 143, 0.16);
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                font-family: 'Nunito Sans', sans-serif;
                color: var(--text);
                background: linear-gradient(180deg, #f7f9ff 0%, #eef3ff 100%);
            }

            .dashboard-shell {
                min-height: 100vh;
                display: grid;
                grid-template-columns: 270px minmax(0, 1fr);
            }

            .sidebar {
                background: var(--sidebar);
                border-right: 1px solid var(--line);
                display: flex;
                flex-direction: column;
                position: sticky;
                top: 0;
                height: 100vh;
                overflow-y: auto;
                z-index: 10;
            }

            .sidebar-header {
                padding: 24px 24px 18px 28px;
                display: flex;
                align-items: center;
                justify-content: flex-start;
            }

            .sidebar-brand {
                display: inline-flex;
                align-items: center;
                gap: 0;
                font-size: 1rem;
                font-weight: 700;
                line-height: 1;
                letter-spacing: -0.05em;
                text-decoration: none;
            }

            .sidebar-brand-logo {
                width: 140px;
                height: auto;
                margin-right: 8px;
            }

            .sidebar-brand span {
                display: inline-flex;
                align-items: center;
                gap: 0 2px;
            }

            .sidebar-brand-mark {
                width: 32px;
                height: 22px;
                margin-right: 6px;
                margin-bottom: 6px;
                color: var(--brand);
                flex-shrink: 0;
            }

            .sidebar-brand-dark {
                color: #11084f;
            }

            .sidebar-brand-blue {
                color: #1fa2e4;
            }

            .sidebar-brand-dotcom {
                color: #2f11f0;
                font-size: 0.39em;
                font-weight: 600;
                margin-left: 2px;
                margin-bottom: 3px;
            }

            .sidebar-nav {
                padding: 8px 14px 18px;
                display: grid;
                gap: 4px;
                overflow-y: auto;
                max-height: calc(100vh - 92px); /* account for header and footers if added */
            }

            .nav-group {
                display: grid;
                gap: 2px;
            }

            .nav-item {
                border: 0;
                background: transparent;
                border-radius: 12px;
                padding: 12px 18px;
                text-align: left;
                font: inherit;
                font-size: 12px;
                color: var(--text);
            }

            .nav-item:not(.active) {
                font-weight: 600;
            }

            .nav-link {
                display: block;
                border-radius: 12px;
                padding: 12px 18px;
                text-align: left;
                font-size: 14px;
                font-weight: 600;
                color: var(--text);
                text-decoration: none;
                position: relative;
            }

            .nav-toggle {
                list-style: none;
                cursor: pointer;
                position: relative;
                user-select: none;
                padding-right: 48px;
            }

            .nav-toggle::-webkit-details-marker {
                display: none;
            }

            .nav-toggle::after {
                content: "";
                position: absolute;
                right: 22px;
                top: 50%;
                width: 8px;
                height: 8px;
                border-right: 1.8px solid #6c7688;
                border-bottom: 1.8px solid #6c7688;
                transform: translateY(-65%) rotate(45deg);
                transition: transform 0.18s ease;
            }

            details[open] > .nav-toggle::after {
                transform: translateY(-35%) rotate(225deg);
            }

            .nav-subitem {
                display: block;
                padding: 6px 18px 6px 34px;
                font-size: 0.86rem;
                line-height: 1.35;
                color: #4f5a6d;
                text-decoration: none;
                border-radius: 10px;
            }

            .nav-subitem:hover {
                background: #f3f7ff;
            }

            .nav-subitem.active {
                background: #eef6ff;
                color: #138fce;
                font-weight: 600;
            }

            .nav-children {
                display: grid;
                gap: 2px;
                padding-top: 0;
            }

            .nav-item.active {
                background: linear-gradient(135deg, #1aa8ee 0%, #1d97db 100%);
                color: #fff;
                box-shadow: 0 12px 28px rgba(24, 164, 234, 0.24);
            }

            details[open] > .nav-item,
            .nav-item.group-active {
                background: linear-gradient(135deg, #1aa8ee 0%, #1d97db 100%);
                color: #fff;
                box-shadow: 0 12px 28px rgba(24, 164, 234, 0.18);
                position: relative;
            }

            details[open] > .nav-item::after,
            .nav-item.group-active::after {
                border-right-color: #ffffff;
                border-bottom-color: #ffffff;
            }

            details[open] > .nav-item::before,
            .nav-item.group-active::before {
                content: "";
                position: absolute;
                left: -8px;
                top: 10px;
                bottom: 10px;
                width: 4px;
                border-radius: 999px;
                background: #0c8fd1;
            }

            .nav-link.active {
                background: linear-gradient(135deg, #1aa8ee 0%, #1d97db 100%);
                color: #fff;
                box-shadow: 0 12px 28px rgba(24, 164, 234, 0.24);
            }

            .nav-link.active::before {
                content: "";
                position: absolute;
                left: -8px;
                top: 10px;
                bottom: 10px;
                width: 4px;
                border-radius: 999px;
                background: #0c8fd1;
            }

            .sidebar-divider {
                height: 1px;
                background: var(--line);
                margin: 16px 0;
            }

            .sidebar-footer {
                margin-top: auto;
                padding: 0 14px 20px;
                display: grid;
                gap: 4px;
            }

            .content {
                display: grid;
                grid-template-rows: auto 1fr;
                min-width: 0;
                min-height: 100vh;
            }

            .topbar {
                background: rgba(255, 255, 255, 0.96);
                border-bottom: 1px solid var(--line);
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 16px;
                padding: 14px 30px;
            }

            .bell {
                position: relative;
                width: 42px;
                height: 42px;
                display: grid;
                place-items: center;
                border-radius: 50%;
                background: #f1f6ff;
                color: var(--brand);
                font-size: 1.1rem;
            }

            .bell-badge {
                position: absolute;
                top: -2px;
                right: -1px;
                min-width: 18px;
                height: 18px;
                border-radius: 999px;
                background: #ff4d6d;
                color: #fff;
                font-size: 0.66rem;
                display: grid;
                place-items: center;
                padding: 0 5px;
            }

            .profile {
                position: relative;
            }

            .profile-toggle {
                display: flex;
                align-items: center;
                gap: 14px;
                cursor: pointer;
                list-style: none;
            }

            .profile-toggle::-webkit-details-marker {
                display: none;
            }

            .avatar {
                width: 52px;
                height: 52px;
                border-radius: 50%;
                display: grid;
                place-items: center;
                background: linear-gradient(135deg, #f8cadf 0%, #b468a2 100%);
                color: #fff;
                font-weight: 700;
                font-size: 1rem;
                box-shadow: 0 10px 20px rgba(180, 104, 162, 0.28);
            }

            .profile-meta strong {
                display: block;
                font-size: 0.94rem;
                font-weight: 600;
            }

            .profile-meta span {
                display: block;
                color: var(--muted);
                font-size: 0.86rem;
                margin-top: 2px;
            }

            .caret {
                width: 34px;
                height: 34px;
                border-radius: 50%;
                border: 1px solid var(--line);
                display: grid;
                place-items: center;
                color: var(--muted);
            }

            .profile-menu {
                position: absolute;
                top: calc(100% + 12px);
                right: 0;
                width: 240px;
                background: #fff;
                border: 1px solid #e6e9f2;
                border-radius: 6px;
                box-shadow: 0 16px 32px rgba(67, 82, 117, 0.18);
                overflow: hidden;
                z-index: 50;
            }

            .profile-card {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 16px;
                border-bottom: 1px solid #eceff5;
            }

            .profile-card .avatar {
                width: 40px;
                height: 40px;
                font-size: 0.88rem;
            }

            .profile-card strong {
                display: block;
                font-size: 0.88rem;
                line-height: 1.2;
            }

            .profile-card span {
                display: block;
                margin-top: 3px;
                font-size: 0.76rem;
                color: #6e7787;
            }

            .profile-menu-link {
                display: block;
                padding: 14px 20px;
                color: #2a2f38;
                text-decoration: none;
                border-bottom: 1px solid #eceff5;
                font-size: 0.88rem;
            }

            .profile-menu-link:last-child {
                border-bottom: 0;
            }

            .profile-menu-link:hover {
                background: #f7faff;
            }

            .main-panel {
                padding: 34px 32px 40px;
                background:
                    radial-gradient(circle at top left, rgba(255, 255, 255, 0.9), transparent 40%),
                    linear-gradient(180deg, #f7f9ff 0%, #eff3ff 100%);
                min-height: 0;
            }

            .main-panel.manage-properties-panel {
                height: calc(100vh - 81px);
                overflow: hidden;
            }

            .headline {
                margin: 0 0 28px;
                font-size: 1.5rem;
                line-height: 1;
                letter-spacing: -0.04em;
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 26px;
                align-items: start;
            }

            .stat-card {
                background: var(--panel);
                border-radius: 18px;
                padding: 12px 12px 14px;
                box-shadow: var(--shadow);
                min-height: 13px;
                display: grid;
                gap: 12px;
            }

            .stat-top {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 10px;
            }

            .stat-label {
                margin: 0;
                max-width: 128px;
                font-size: 0.62rem;
                color: #626b78;
            }

            .stat-value {
                margin: 4px 0 0;
                font-size: 1.6rem;
                line-height: 1;
                letter-spacing: -0.04em;
                font-weight: 400;
            }

            .icon-badge {
                width: 50px;
                height: 50px;
                border-radius: 16px;
                display: grid;
                place-items: center;
                flex-shrink: 0;
            }

            .icon-badge svg {
                width: 24px;
                height: 24px;
            }

            .tone-lavender {
                background: #ecebff;
                color: #8f8df2;
            }

            .tone-gold {
                background: #fff4d9;
                color: #ffc547;
            }

            .tone-mint {
                background: #dcf8ea;
                color: #65d8a7;
            }

            .tone-peach {
                background: #ffe3d9;
                color: #ff9a69;
            }

            .trend {
                display: flex;
                align-items: center;
                gap: 7px;
                font-size: 0.78rem;
                color: #6e7684;
            }

            .trend strong {
                font-weight: 600;
            }

            .trend.up strong,
            .trend.up svg {
                color: var(--success);
                stroke: var(--success);
            }

            .trend.down strong,
            .trend.down svg {
                color: var(--danger);
                stroke: var(--danger);
            }

            .trend svg {
                width: 16px;
                height: 16px;
                fill: none;
                stroke-width: 2.2;
            }

            .success-banner {
                margin: 0 0 20px;
                padding: 14px 18px;
                border-radius: 16px;
                background: rgba(29, 184, 160, 0.12);
                color: #0f8d78;
                font-size: 0.9rem;
            }

            .section-card {
                background: var(--panel);
                border-radius: 24px;
                padding: 28px 30px;
                box-shadow: var(--shadow);
                max-width: 600px;
            }

            .section-card h2 {
                margin: 0 0 10px;
                font-size: 1rem;
                line-height: 1.1;
            }

            .section-card p {
                margin: 0;
                color: #667085;
                line-height: 1.6;
            }

            .scroll-button {
                position: fixed;
                right: 16px;
                bottom: 16px;
                width: 28px;
                height: 28px;
                border: 0;
                border-radius: 4px 4px 0 0;
                background: #f2f2f2;
                color: #8b8b8b;
                box-shadow: 0 8px 18px rgba(67, 82, 117, 0.2);
                display: grid;
                place-items: center;
                cursor: pointer;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.2s ease, transform 0.2s ease;
                z-index: 30;
            }

            .scroll-button.visible {
                opacity: 1;
                pointer-events: auto;
            }

            .scroll-button:hover {
                transform: translateY(-1px);
            }

            .scroll-button svg {
                width: 12px;
                height: 12px;
                fill: currentColor;
            }

            @media (max-width: 1300px) {
                .stats-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (max-width: 920px) {
                .dashboard-shell {
                    grid-template-columns: 1fr;
                }

                .sidebar {
                    border-right: 0;
                    border-bottom: 1px solid var(--line);
                }

                .sidebar-nav,
                .sidebar-footer {
                    padding-bottom: 18px;
                }
            }

            @media (max-width: 640px) {
                .sidebar-header {
                    padding: 18px 18px 10px;
                }

                .sidebar-brand {
                    font-size: 1.55rem;
                }

                .sidebar-brand-mark {
                    width: 26px;
                    height: 18px;
                    margin-bottom: 4px;
                }

                .topbar {
                    justify-content: space-between;
                    padding: 14px 18px;
                    gap: 12px;
                }

                .main-panel {
                    padding: 24px 18px 30px;
                }

                .headline {
                    font-size: 1.95rem;
                    margin-bottom: 20px;
                }

                .stats-grid,
                .sidebar-footer {
                    grid-template-columns: 1fr;
                }

                .stat-card {
                    min-height: auto;
                }
            }

            /* Amenities Section Styles */
            .filter-bar {
                background: #fff;
                border: 1px solid #eef2f7;
                border-radius: 14px;
                padding: 10px 24px;
                display: flex;
                align-items: center;
                gap: 24px;
                margin-bottom: 28px;
                box-shadow: 0 4px 12px rgba(90, 104, 143, 0.03);
            }

            .filter-item {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .filter-label {
                font-size: 14px;
                font-weight: 700;
                color: #202530;
                white-space: nowrap;
            }

            .select-trigger {
                background: #f8faff;
                border: 1px solid #eef2f7;
                padding: 10px 18px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                gap: 50px;
                cursor: pointer;
                min-width: 190px;
                justify-content: space-between;
                transition: all 0.2s;
            }

            .select-trigger:hover {
                border-color: var(--brand);
                background: #fff;
            }

            .select-trigger span {
                font-size: 14px;
                color: #636e81;
                font-weight: 600;
            }

            .reset-btn {
                background: transparent;
                border: 0;
                color: #ef476f;
                font-size: 14px;
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 8px;
                cursor: pointer;
                margin-left: auto;
                padding: 0;
            }

            .reset-btn svg {
                width: 16px;
                height: 16px;
            }

            .data-table-container {
                background: #fff;
                border-radius: 20px;
                box-shadow: var(--shadow);
                overflow: hidden;
            }

            .data-table {
                width: 100%;
                border-collapse: collapse;
            }

            .data-table th {
                text-align: left;
                padding: 20px 24px;
                font-size: 13px;
                font-weight: 700;
                color: #202530;
                background: #fafbfd;
                border-bottom: 1px solid #f1f4f9;
            }

            .data-table td {
                padding: 18px 24px;
                font-size: 14px;
                color: #4b525f;
                border-bottom: 1px solid #f1f4f9;
                vertical-align: middle;
            }

            .data-table tr:hover td {
                background: #fbfcfe;
            }

            .data-table tr:last-child td {
                border-bottom: 0;
            }

            .badge {
                padding: 6px 14px;
                border-radius: 8px;
                font-size: 12px;
                font-weight: 700;
                display: inline-flex;
            }

            .badge-active { background: #e8f9f6; color: #1db8a0; }
            .badge-inactive { background: #fff1f3; color: #ef476f; }
            .badge-completed { background: #e8f9f6; color: #1db8a0; }
            .badge-processing { background: #f0f3ff; color: #8f8df2; }
            .badge-rejected { background: #fff1f3; color: #ef476f; }
            .badge-on-hold { background: #fff9ed; color: #ffc547; }
            .badge-in-transit { background: #fdf2ff; color: #d68df2; }

            .actions {
                display: flex;
                gap: 8px;
            }

            .btn-action {
                width: 34px;
                height: 34px;
                border-radius: 8px;
                border: 1px solid #eef2f7;
                display: grid;
                place-items: center;
                background: #fff;
                color: #636e81;
                cursor: pointer;
                transition: all 0.2s;
            }

            .btn-action:hover {
                background: #f8faff;
                border-color: #d1d9e7;
            }

            .btn-action.btn-delete:hover {
                background: #fff5f6;
                color: #ef476f;
                border-color: #ffcbd4;
            }

            /* Premium Modal Design */
            .modal-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.4);
                backdrop-filter: blur(8px);
                z-index: 2000;
                display: none;
                place-items: center;
                align-items: flex-start;
                padding: 30px 16px 24px;
                overflow-y: auto;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .modal-backdrop.is-open {
                display: grid;
            }

            .modal-backdrop.is-open,
            .modal-backdrop[style*="display: grid"] {
                opacity: 1;
                animation: fadeIn 0.3s ease forwards;
            }

            @keyframes fadeIn {
                from { opacity: 0; backdrop-filter: blur(0px); }
                to { opacity: 1; backdrop-filter: blur(8px); }
            }

            .modal {
                background: #ffffff;
                border-radius: 24px;
                width: 100%;
                max-width: 640px;
                max-height: calc(100vh - 40px);
                box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.1), 0 0 0 1px rgba(15, 23, 42, 0.05);
                position: relative;
                transform: scale(0.95) translateY(20px);
                opacity: 0;
                transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
                overflow: hidden;
                display: flex;
                flex-direction: column;
            }
            .modal-body {
                flex: 1;
                overflow-y: auto;
                padding: 32px;
                background: #ffffff;
            }

            @media (max-width: 900px) {
                .modal {
                    max-width: 92vw;
                }
                .form-row {
                    grid-template-columns: 1fr;
                }
            }

            .modal-backdrop[style*="display: grid"] .modal {
                transform: scale(1) translateY(0);
                opacity: 1;
            }

            .modal-header {
                flex-shrink: 0;
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                padding: 32px 32px 24px;
                border-bottom: 1px solid #f1f5f9;
                background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            }

            .modal-title {
                font-size: 1.25rem;
                font-weight: 700;
                margin: 0 0 6px;
                color: #0f172a;
                letter-spacing: -0.02em;
            }

            .modal-subtitle {
                font-size: 0.9rem;
                color: #64748b;
                margin: 0;
            }

            .modal-close-btn {
                background: #f1f5f9;
                border: 0;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                display: grid;
                place-items: center;
                color: #64748b;
                cursor: pointer;
                transition: all 0.2s;
            }

            .modal-close-btn:hover {
                background: #e2e8f0;
                color: #0f172a;
                transform: rotate(90deg);
            }

            .form-row {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 24px;
            }

            .form-group {
                margin-bottom: 24px;
            }

            .form-group:last-child {
                margin-bottom: 0;
            }

            .form-label {
                display: block;
                font-size: 0.875rem;
                font-weight: 600;
                color: #334155;
                margin-bottom: 8px;
            }

            .form-control {
                width: 100%;
                padding: 12px 16px;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                font-size: 0.95rem;
                color: #0f172a;
                background: #f8fafc;
                transition: all 0.2s ease;
                font-family: inherit;
            }

            .form-control:hover {
                border-color: #cbd5e1;
            }

            .form-control:focus {
                border-color: var(--brand);
                background: #ffffff;
                outline: 0;
                box-shadow: 0 0 0 4px rgba(24, 164, 234, 0.1);
            }

            .form-control.font-mono {
                font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
                font-size: 0.85rem;
                color: #475569;
                resize: vertical;
            }
            
            select.form-control {
                appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 16px center;
                background-size: 16px;
                padding-right: 40px;
            }

            .help-text {
                display: block;
                font-size: 0.8rem;
                color: #64748b;
                margin-top: 8px;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .help-text::before {
                content: "";
                display: block;
                width: 14px;
                height: 14px;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'%3E%3C/circle%3E%3Cpath d='M12 16v-4'%3E%3C/path%3E%3Cpath d='M12 8h.01'%3E%3C/path%3E%3C/svg%3E");
                background-size: cover;
            }

            .modal-footer {
                flex-shrink: 0;
                padding: 24px 32px;
                border-top: 1px solid #f1f5f9;
                display: flex;
                justify-content: flex-end;
                gap: 16px;
                background: #f8fafc;
            }

            .btn-cancel {
                background: #ffffff;
                color: #475569;
                border: 1px solid #e2e8f0;
                padding: 12px 24px;
                border-radius: 12px;
                font-weight: 600;
                font-size: 0.95rem;
                cursor: pointer;
                transition: all 0.2s;
            }

            .btn-cancel:hover {
                background: #f1f5f9;
                color: #0f172a;
            }

            .btn-primary {
                background: linear-gradient(135deg, var(--brand) 0%, #118bc2 100%);
                color: #fff;
                border: 0;
                padding: 12px 28px;
                border-radius: 12px;
                font-weight: 600;
                font-size: 0.95rem;
                cursor: pointer;
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                box-shadow: 0 4px 12px rgba(24, 164, 234, 0.25), inset 0 1px 1px rgba(255, 255, 255, 0.2);
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 16px rgba(24, 164, 234, 0.3), inset 0 1px 1px rgba(255, 255, 255, 0.3);
            }
            
            .btn-primary:active {
                transform: translateY(0);
                box-shadow: 0 2px 8px rgba(24, 164, 234, 0.2);
            }


            /* Breadcrumbs */
            .breadcrumb-wrapper {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 20px;
                font-size: 13px;
            }

            .breadcrumb-item {
                display: flex;
                align-items: center;
                gap: 8px;
                color: #666;
            }

            .breadcrumb-item svg {
                width: 16px;
                height: 16px;
            }

            .breadcrumb-separator {
                color: #ccc;
            }

            /* Table Header Area */
            .amenities-header-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 15px 0;
                margin-bottom: 20px;
                border-top: 1px solid #eee;
            }

            .amenities-title {
                font-size: 18px;
                font-weight: 500;
                color: #555;
                margin: 0;
            }

            .btn-add-primary {
                background: #007bff;
                color: #fff;
                border: 0;
                padding: 8px 16px;
                border-radius: 4px;
                font-size: 13px;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 6px;
                cursor: pointer;
                text-decoration: none;
            }

            /* Table Controls */
            .table-controls {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 20px;
                font-size: 14px;
                color: #666;
            }

            .entries-control {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .search-control {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .control-input {
                padding: 6px 10px;
                border: 1px solid #eee;
                border-radius: 4px;
            }

            .entries-select {
                padding: 4px 8px;
                border: 1px solid #eee;
                border-radius: 4px;
            }

            .chips {
                display: flex;
                flex-wrap: wrap;
                gap: 14px;
                margin-bottom: 32px;
            }

            .chip-btn {
                background: #fff;
                border: 1px solid #eef2f7;
                padding: 10px 22px;
                border-radius: 24px;
                font-size: 14px;
                font-weight: 600;
                color: #636e81;
                cursor: pointer;
                transition: all 0.2s;
            }

            .chip-btn:hover, .chip-btn.is-active {
                background: #f8faff;
                border-color: var(--brand);
                color: var(--brand);
            }

            .modal-help {
                font-size: 13px;
                color: #9ba3af;
                margin-bottom: 32px;
            }

            .btn-apply {
                background: var(--brand);
                color: #fff;
                border: 0;
                padding: 14px;
                border-radius: 12px;
                font-size: 15px;
                font-weight: 700;
                width: 100%;
                cursor: pointer;
                box-shadow: 0 10px 20px rgba(24, 164, 234, 0.24);
                transition: all 0.2s;
            }

            .btn-apply:hover {
                transform: translateY(-2px);
                box-shadow: 0 12px 24px rgba(24, 164, 234, 0.3);
            }

            .pagination-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 20px 24px;
                background: transparent;
                font-size: 14px;
                color: #64748b;
                border-top: 1px solid #f1f5f9;
            }

            .pagination-controls {
                display: flex;
                align-items: center;
                gap: 4px;
            }

            .page-btn {
                min-width: 38px;
                height: 38px;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0 14px;
                border-radius: 8px;
                border: 1px solid #e2e8f0;
                background: #fff;
                color: #475569;
                font-weight: 500;
                font-size: 14px;
                cursor: pointer;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .page-btn:hover:not(:disabled) {
                background: #f8faff;
                border-color: #d1d9e7;
                color: var(--brand);
            }

            .page-btn.active {
                background: #18a4ea;
                border-color: #18a4ea;
                color: #fff;
                box-shadow: 0 4px 10px rgba(24, 164, 234, 0.3);
            }

            .page-btn:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }
        </style>
    </head>
    <body>
        <div class="dashboard-shell">
            <aside class="sidebar">
                <div class="sidebar-header">
                        <img src="{{ asset('images/homewala-logo.png') }}" alt="Homewala logo" class="sidebar-brand-logo" />
                </div>
                <nav class="sidebar-nav" aria-label="Admin menu">
                    <div class="nav-group">
                        <a class="nav-link {{ $currentPage === 'dashboard' ? 'active' : '' }}" href="{{ route('admin.dashboard', $user) }}">Dashboard</a>
                    </div>

                    @foreach ($menuGroups as $group)
                        <details class="nav-group" {{ $currentGroup === $group['key'] ? 'open' : '' }}>
                            <summary class="nav-item nav-toggle {{ $currentGroup === $group['key'] ? 'group-active' : '' }}">{{ $group['label'] }}</summary>
                            <div class="nav-children">
                                @foreach ($group['items'] as $item)
                                    <a
                                        class="nav-subitem {{ $currentPage === $item['slug'] ? 'active' : '' }}"
                                        href="{{ route('admin.section', ['user' => $user, 'section' => $item['slug']]) }}"
                                    >
                                        {{ $item['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </details>
                    @endforeach
                </nav>

                <div class="sidebar-footer">
                    <a class="nav-link" href="{{ route('admin.register') }}">Logout</a>
                </div>
            </aside>

            @php
                $displayName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                $displayName = $displayName !== '' ? $displayName : ($user->name ?? 'Admin User');
                $avatarLetter = strtoupper(substr($displayName, 0, 1));
            @endphp

            <section class="content">
                <header class="topbar">
    <div class="bell" aria-label="Notifications">
        <span>&#128276;</span>
        <span class="bell-badge">6</span>
    </div>

    <details class="profile">
        <summary class="profile-toggle">
            <div class="avatar">{{ $avatarLetter }}</div>

            <div class="profile-meta">
                <strong>{{ $displayName }}</strong>
                <span>{{ $user->email }}</span>
            </div>

            <div class="caret">&#8964;</div>
        </summary>

        <div class="profile-menu">
            <div class="profile-card">
                <div class="avatar">{{ $avatarLetter }}</div>

                <div>
                    <strong>{{ $displayName }}</strong>
                    <span>{{ $user->email }}</span>
                </div>
            </div>

            @foreach (($profileGroups[0]['items'] ?? []) as $item)
                <a class="profile-menu-link" href="{{ route('admin.section', ['user' => $user, 'section' => $item['slug']]) }}">
                    {{ $item['label'] }}
                </a>
            @endforeach

            <a class="profile-menu-link" href="{{ route('admin.register') }}">Logout</a>
        </div>
    </details>
</header>

                <main class="main-panel {{ $currentPage === 'manage-properties' ? 'manage-properties-panel' : '' }}">
                    @if (session('status'))
                        <div class="success-banner">{{ session('status') }}</div>
                    @endif

                    <h1 class="headline">{{ $currentItem['label'] ?? 'Dashboard' }}</h1>

                    @if ($currentPage === 'dashboard')
                        <div class="stats-grid">
                            @foreach ($stats as $stat)
                                @php
                                    $isDown = str_contains($stat['change'], 'Down');
                                @endphp

                                <article class="stat-card">
                                    <div class="stat-top">
                                        <div>
                                            <p class="stat-label">{{ $stat['label'] }}</p>
                                            <h2 class="stat-value">{{ $stat['value'] }}</h2>
                                        </div>

                                        <div class="icon-badge tone-{{ $stat['tone'] }}">
                                            @if ($stat['icon'] === 'users')
                                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                                    <path d="M16 11c1.66 0 3-1.57 3-3.5S17.66 4 16 4s-3 1.57-3 3.5 1.34 3.5 3 3.5Zm-8 0c1.66 0 3-1.57 3-3.5S9.66 4 8 4 5 5.57 5 7.5 6.34 11 8 11Zm0 2c-2.67 0-8 1.34-8 4v1h10v-1c0-2.66-5.33-4-8-4Zm8 0c-.29 0-.62.02-.97.05 1.33.97 1.97 2.2 1.97 3.95v1h7v-1c0-2.66-5.33-4-8-4Z" fill="currentColor"/>
                                                </svg>
                                            @elseif ($stat['icon'] === 'box')
                                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                                    <path d="m12 2 8 4.5v11L12 22 4 17.5v-11L12 2Zm0 2.3L6.3 7.5 12 10.7l5.7-3.2L12 4.3Zm-6 5v7.1l5 2.8v-7.1l-5-2.8Zm7 9.9 5-2.8V9.3l-5 2.8v7.1Z" fill="currentColor"/>
                                                </svg>
                                            @elseif ($stat['icon'] === 'chart')
                                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                                    <path d="M4 19h16M6 17V7m6 10v-5m6 5V4" stroke="currentColor" stroke-linecap="round"/>
                                                    <path d="m9 10 3-3 3 2 4-5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            @elseif ($stat['icon'] === 'clock')
                                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                                    <circle cx="12" cy="12" r="8.5" stroke="currentColor"/>
                                                    <path d="M12 7.5v4.7l3.2 1.9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M12 5.5v1.2M18.5 12h-1.2M12 18.5v-1.2M5.5 12h1.2" stroke="currentColor" stroke-linecap="round"/>
                                                </svg>
                                            @else
                                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                                    <circle cx="12" cy="12" r="8.5" stroke="currentColor"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="trend {{ $isDown ? 'down' : 'up' }}">
                                        @if ($isDown)
                                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="m4 7 7 7 4-4 5 5"/>
                                                <path d="M20 15v-5h-5"/>
                                            </svg>
                                        @else
                                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="m4 17 7-7 4 4 5-5"/>
                                                <path d="M20 9V4h-5"/>
                                            </svg>
                                        @endif

                                        <span>
                                            <strong>{{ strtok($stat['change'], ' ') }}</strong>
                                            {{ substr($stat['change'], strlen(strtok($stat['change'], ' '))) }}
                                        </span>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @elseif ($currentPage === 'amenities')
                        <style>
                            /* ── Amenities Advanced Animations ── */
                            @keyframes pageSlideIn {
                                from { opacity: 0; transform: translateY(20px); }
                                to   { opacity: 1; transform: translateY(0); }
                            }
                            @keyframes amenityRowIn {
                                from { opacity: 0; transform: translateX(-18px) scale(0.98); }
                                to   { opacity: 1; transform: translateX(0) scale(1); }
                            }
                            @keyframes badgePulse {
                                0%, 100% { box-shadow: 0 0 0 0 rgba(52,211,153,0.5); }
                                50%       { box-shadow: 0 0 0 6px rgba(52,211,153,0); }
                            }
                            @keyframes badgePulseRed {
                                0%, 100% { box-shadow: 0 0 0 0 rgba(239,71,111,0.4); }
                                50%       { box-shadow: 0 0 0 6px rgba(239,71,111,0); }
                            }
                            @keyframes spinPlus {
                                from { transform: rotate(0deg) scale(1); }
                                to   { transform: rotate(135deg) scale(1.3); }
                            }
                            @keyframes addBtnGlow {
                                0%   { box-shadow: 0 4px 15px rgba(56,128,255,0.3); }
                                50%  { box-shadow: 0 6px 30px rgba(56,128,255,0.6); }
                                100% { box-shadow: 0 4px 15px rgba(56,128,255,0.3); }
                            }
                            @keyframes tableHeaderSlide {
                                from { opacity: 0; transform: translateY(-8px); }
                                to   { opacity: 1; transform: translateY(0); }
                            }
                            @keyframes editBtnSpin {
                                from { transform: rotate(0deg); }
                                to   { transform: rotate(180deg); }
                            }

                            .amenities-section-wrap {
                                animation: pageSlideIn 0.45s cubic-bezier(.22,1,.36,1) both;
                            }
                            /* Staggered rows */
                            .amenity-row {
                                animation: amenityRowIn 0.4s cubic-bezier(.22,1,.36,1) both;
                                transition: background 0.2s, box-shadow 0.2s;
                            }
                            @for ($i = 0; $i < count($amenities ?? []); $i++)
                                .amenity-row:nth-child({{ $i + 1 }}) { animation-delay: {{ $i * 0.055 }}s; }
                            @endfor
                            .amenity-row:hover {
                                background: linear-gradient(90deg, rgba(56,128,255,0.06) 0%, transparent 100%);
                                box-shadow: inset 3px 0 0 #3880ff;
                            }
                            /* Animated Add button */
                            .btn-amenity-add {
                                position: relative; overflow: hidden;
                                transition: transform 0.2s cubic-bezier(.4,2,.6,1), box-shadow 0.25s ease;
                                animation: addBtnGlow 2.5s ease-in-out infinite;
                            }
                            .btn-amenity-add:hover {
                                transform: translateY(-3px) scale(1.05);
                                animation: none;
                                box-shadow: 0 8px 28px rgba(56,128,255,0.45);
                            }
                            .btn-amenity-add:active { transform: scale(0.96); }
                            .btn-amenity-add .plus-icon {
                                display: inline-block;
                                transition: transform 0.35s cubic-bezier(.4,2,.6,1);
                                font-weight: 700; font-size: 1.2em;
                            }
                            .btn-amenity-add:hover .plus-icon {
                                transform: rotate(135deg) scale(1.2);
                            }
                            .btn-amenity-add::after {
                                content: ''; position: absolute;
                                top: 50%; left: 50%;
                                width: 0; height: 0;
                                background: rgba(255,255,255,0.3);
                                border-radius: 50%;
                                transform: translate(-50%,-50%);
                                transition: width 0.5s ease, height 0.5s ease, opacity 0.5s;
                                opacity: 0;
                            }
                            .btn-amenity-add:active::after {
                                width: 220px; height: 220px; opacity: 1; transition: 0s;
                            }
                            /* Badge animations */
                            .badge-completed, .badge-active {
                                animation: badgePulse 2.5s ease-in-out infinite;
                            }
                            .badge-inactive, .badge-processing {
                                animation: badgePulseRed 2.5s ease-in-out infinite;
                            }
                            /* Action buttons */
                            .btn-edit {
                                transition: transform 0.25s cubic-bezier(.4,2,.6,1), background 0.2s, box-shadow 0.2s;
                            }
                            .btn-edit:hover {
                                transform: rotate(15deg) scale(1.15);
                                box-shadow: 0 4px 12px rgba(56,128,255,0.35);
                            }
                            .btn-delete {
                                transition: transform 0.25s cubic-bezier(.4,2,.6,1), background 0.2s, box-shadow 0.2s;
                            }
                            .btn-delete:hover {
                                transform: scale(1.15);
                                box-shadow: 0 4px 12px rgba(239,71,111,0.35);
                            }
                            /* Table header */
                            .data-table thead tr th {
                                animation: tableHeaderSlide 0.4s cubic-bezier(.22,1,.36,1) both;
                            }
                            .data-table thead tr th:nth-child(1) { animation-delay: 0s; }
                            .data-table thead tr th:nth-child(2) { animation-delay: 0.05s; }
                            .data-table thead tr th:nth-child(3) { animation-delay: 0.10s; }
                            .data-table thead tr th:nth-child(4) { animation-delay: 0.15s; }
                            .data-table thead tr th:nth-child(5) { animation-delay: 0.20s; }
                            /* Search input focus glow */
                            .control-input {
                                transition: border-color 0.3s, box-shadow 0.3s;
                            }
                            .control-input:focus {
                                border-color: #3880ff;
                                box-shadow: 0 0 0 3px rgba(56,128,255,0.15);
                                outline: none;
                            }
                            /* Icon preview bounce */
                            .icon-preview {
                                transition: transform 0.3s cubic-bezier(.4,2,.6,1);
                            }
                            .amenity-row:hover .icon-preview {
                                transform: scale(1.3) rotate(-5deg);
                            }
                        </style>

                        <div class="amenities-section-wrap">
                        <div class="breadcrumb-wrapper">
                            <div class="breadcrumb-item"></div>
                        </div>

                        <div class="amenities-header-row">
                            <h2 class="amenities-title">Amenities</h2>
                            <button class="btn-add-primary btn-amenity-add" onclick="openAddAmenityModal()">
                                <span class="plus-icon">+</span> Add
                            </button>
                        </div>

                        <div class="table-controls">
                            <div class="search-control">
                                Search: <input type="text" id="amenitySearch" class="control-input" placeholder="Search amenities...">
                                <span style="margin-left: 12px; color: #64748b; font-size: 0.85rem;">Show</span>
                                <select id="amenityEntries" class="entries-select" style="min-width: 75px;">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select> 
                                <span style="color: #64748b; font-size: 0.85rem;">entries</span>
                            </div>
                        </div>

                        <div class="data-table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;">Serial Number</th>
                                        <th style="width: 15%;">Icon</th>
                                        <th style="width: 30%;">Name</th>
                                        <th style="width: 15%;">Status</th>
                                        <th style="width: 10%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (($amenities ?? []) as $index => $amenity)
                                        <tr class="amenity-row" data-id="{{ $amenity->id }}" data-serial="{{ $amenity->serial_number }}" data-name="{{ $amenity->name }}" data-status="{{ $amenity->status }}" data-icon="{{ htmlspecialchars($amenity->icon) }}">
                                            <td>{{ $amenity->serial_number }}</td>
                                            <td>
                                                <div class="icon-preview" style="color: var(--brand); opacity: 0.8; width: 24px; height: 24px;">
                                                    {!! $amenity->icon !!}
                                                </div>
                                            </td>
                                            <td style="font-weight: 600;" class="amenity-name-cell">{{ $amenity->name }}</td>
                                            <td>
                                                <span class="badge badge-{{ strtolower(str_replace(' ', '-', $amenity->status)) }}">
                                                    {{ $amenity->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="actions">
                                                    <button class="btn-action btn-edit" title="Edit" onclick="editAmenity({{ $amenity->id }})">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                    </button>
                                                    <form method="POST" action="{{ route('admin.amenities.destroy', ['user' => $user, 'amenity' => $amenity->id]) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this amenity?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action btn-delete" title="Delete">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-footer">
                                <div class="pagination-info" id="amenityPageInfo">Showing 0 to 0 of 0 entries</div>
                                <div class="pagination-controls" id="amenityPagination"></div>
                            </div>
                        </div>

                        <div id="addAmenityModal" class="modal-backdrop" onclick="if(event.target === this) closeAmenityModal()">
                            <div class="modal">
                                <div class="modal-header">
                                    <div>
                                        <h3 class="modal-title" id="amenityModalTitle">Add Property Amenity</h3>
                                        <p class="modal-subtitle" id="amenityModalSubtitle">Create a new amenity to display on property listings.</p>
                                    </div>
                                    <button type="button" class="modal-close-btn" onclick="closeAmenityModal()">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="addAmenityForm" action="{{ route('admin.amenities.store', $user) }}" method="POST">
                                        @csrf
                                        
                                        <input type="hidden" name="_method" id="amenityMethod" value="POST">
                                        <div class="form-row">
                                            <div class="form-group" style="grid-column: span 2;">
                                                <label class="form-label">Name <span style="color: #ef476f;">*</span></label>
                                                <input type="text" name="name" id="amenityName" class="form-control" placeholder="e.g. Swimming Pool" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">SVG Icon Code <span style="color: #ef476f;">*</span></label>
                                            <textarea class="form-control font-mono" name="icon" rows="4" placeholder='<svg viewBox="0 0 24 24">...</svg>' required></textarea>
                                            <span class="help-text">Paste a valid, clean SVG markup for the amenity icon.</span>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group">
                                                <label class="form-label">Status <span style="color: #ef476f;">*</span></label>
                                                <select class="form-control" name="status" id="amenityStatus" required>
                                                    <option value="Active" selected>Active</option>
                                                    <option value="Inactive">Inactive</option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="form-label">Serial Number <span style="color: #ef476f;">*</span></label>
                                                <input type="number" name="serial_number" id="amenitySerial" class="form-control" placeholder="e.g. 1" required>
                                            </div>
                                        </div>

                                        <p style="margin-top: 8px; color: #f53a0b; font-size: 13px;">
                                            The higher the serial number is, the later the brand will be shown.
                                        </p>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn-cancel" type="button" onclick="closeAmenityModal()" style="background: #6c63ff; color: #fff; border: none; padding: 8px 22px; border-radius: 6px; cursor: pointer; font-weight: 500;">Close</button>
                                    <button type="submit" form="addAmenityForm" class="btn-primary" id="saveAmenityBtn" style="background: #3880ff; color: #fff; border: none; padding: 8px 22px; border-radius: 6px; cursor: pointer; font-weight: 500;">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>

                        </div>{{-- end amenities-section-wrap --}}

                    @elseif ($currentPage === 'categories')
                        <style>
                            /* ── Categories Advanced Animations ── */
                            @keyframes catPageIn {
                                from { opacity: 0; transform: translateY(24px) scale(0.99); }
                                to   { opacity: 1; transform: translateY(0) scale(1); }
                            }
                            @keyframes catRowSlide {
                                from { opacity: 0; transform: translateX(18px); }
                                to   { opacity: 1; transform: translateX(0); }
                            }
                            @keyframes catBtnPop {
                                0%   { transform: scale(1); }
                                40%  { transform: scale(1.08); }
                                70%  { transform: scale(0.97); }
                                100% { transform: scale(1); }
                            }
                            @keyframes catGlow {
                                0%,100% { box-shadow: 0 4px 15px rgba(108,99,255,0.3); }
                                50%     { box-shadow: 0 6px 30px rgba(108,99,255,0.6); }
                            }
                            @keyframes catTypeTagIn {
                                from { opacity: 0; transform: scale(0.8); }
                                to   { opacity: 1; transform: scale(1); }
                            }
                            @keyframes catHeaderWave {
                                0%   { background-position: 0% 50%; }
                                50%  { background-position: 100% 50%; }
                                100% { background-position: 0% 50%; }
                            }

                            .categories-section-wrap {
                                animation: catPageIn 0.45s cubic-bezier(.22,1,.36,1) both;
                            }
                            .category-row {
                                animation: catRowSlide 0.38s cubic-bezier(.22,1,.36,1) both;
                                transition: background 0.2s, box-shadow 0.2s;
                            }
                            @for ($i = 0; $i < count($categories ?? []); $i++)
                                .category-row:nth-child({{ $i + 1 }}) { animation-delay: {{ $i * 0.055 }}s; }
                            @endfor
                            .category-row:hover {
                                background: linear-gradient(90deg, rgba(108,99,255,0.06) 0%, transparent 100%);
                                box-shadow: inset 3px 0 0 #6c63ff;
                            }
                            /* Animated Add button */
                            .btn-cat-add {
                                position: relative; overflow: hidden;
                                background: linear-gradient(135deg, #6c63ff, #3880ff) !important;
                                background-size: 200% 200% !important;
                                transition: transform 0.2s cubic-bezier(.4,2,.6,1), box-shadow 0.25s ease;
                                animation: catGlow 2.5s ease-in-out infinite;
                            }
                            .btn-cat-add:hover {
                                transform: translateY(-3px) scale(1.05);
                                animation: none;
                                box-shadow: 0 8px 28px rgba(108,99,255,0.5);
                            }
                            .btn-cat-add:active {
                                animation: catBtnPop 0.35s cubic-bezier(.4,2,.6,1) both;
                            }
                            .btn-cat-add .cat-plus {
                                display: inline-block;
                                transition: transform 0.35s cubic-bezier(.4,2,.6,1);
                                font-weight: 700; font-size: 1.2em;
                            }
                            .btn-cat-add:hover .cat-plus { transform: rotate(135deg) scale(1.2); }
                            .btn-cat-add::after {
                                content: ''; position: absolute;
                                top: 50%; left: 50%;
                                width: 0; height: 0;
                                background: rgba(255,255,255,0.25);
                                border-radius: 50%;
                                transform: translate(-50%,-50%);
                                transition: width 0.5s ease, height 0.5s ease, opacity 0.5s;
                                opacity: 0;
                            }
                            .btn-cat-add:active::after { width: 220px; height: 220px; opacity: 1; transition: 0s; }
                            /* Category type tag */
                            .category-row td:nth-child(2) {
                                animation: catTypeTagIn 0.4s cubic-bezier(.22,1,.36,1) both;
                            }
                            /* Name cell underline on hover */
                            .category-name-cell {
                                position: relative;
                                transition: color 0.2s;
                            }
                            .category-name-cell::after {
                                content: '';
                                position: absolute;
                                bottom: 6px; left: 12px;
                                width: 0; height: 2px;
                                background: #6c63ff;
                                border-radius: 2px;
                                transition: width 0.3s ease;
                            }
                            .category-row:hover .category-name-cell { color: #6c63ff; }
                            .category-row:hover .category-name-cell::after { width: calc(100% - 24px); }
                        </style>

                        <div class="categories-section-wrap">
                        <div class="breadcrumb-wrapper">
                            <div class="breadcrumb-item"></div>
                        </div>

                        <div class="amenities-header-row">
                            <h2 class="amenities-title">Categories</h2>
                            <button class="btn-add-primary btn-cat-add" onclick="openAddCategoryModal()">
                                <span class="cat-plus">+</span> Add
                            </button>
                        </div>

                        <div class="table-controls">
                            <div class="search-control">
                                Search: <input type="text" id="categorySearch" class="control-input" placeholder="Search categories...">
                                <span style="margin-left: 12px; color: #64748b; font-size: 0.9rem;">Show</span>
                                <select id="categoryEntries" class="entries-select" style="min-width: 70px;">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select> 
                                <span style="color: #64748b; font-size: 0.9rem;">entries</span>
                            </div>
                        </div>

                        <div class="data-table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;">Serial Number</th>
                                        <th style="width: 20%;">Type</th>
                                        <th style="width: 25%;">Name</th>
                                        <th style="width: 20%;">Status</th>
                                        <th style="width: 15%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (($categories ?? []) as $index => $category)
                                        <tr class="category-row" data-id="{{ $category->id }}" data-serial="{{ $category->serial_number }}" data-name="{{ $category->name }}" data-type="{{ $category->type }}" data-status="{{ $category->status }}">
                                            <td>{{ $category->serial_number }}</td>
                                            <td>{{ $category->type }}</td>
                                            <td style="font-weight: 600;" class="category-name-cell">{{ $category->name }}</td>
                                            <td>
                                                <span class="badge badge-{{ $category->status == '1' ? 'active' : 'inactive' }}">
                                                    {{ $category->status == '1' ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="actions">
                                                    <button class="btn-action btn-edit" title="Edit" onclick="editCategory({{ $category->id }})">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                    </button>
                                                    <form method="POST" action="{{ route('admin.categories.destroy', ['user' => $user, 'category' => $category->id]) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action btn-delete" title="Delete">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-footer">
                                <div class="pagination-info" id="categoryPageInfo">Showing 0 to 0 of 0 entries</div>
                                <div class="pagination-controls" id="categoryPagination"></div>
                            </div>
                        </div>
                        </div>{{-- end categories-section-wrap --}}

                        <div id="addCategoryModal" class="modal-backdrop" onclick="if(event.target === this) closeCategoryModal()">
                            <div class="modal">
                                <div class="modal-header">
                                    <div>
                                        <h3 class="modal-title" id="categoryModalTitle">Add Property Category</h3>
                                        <p class="modal-subtitle" id="categoryModalSubtitle">Create a new category to organize properties.</p>
                                    </div>
                                    <button type="button" class="modal-close-btn" onclick="closeCategoryModal()">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="addCategoryForm" action="{{ route('admin.categories.store', $user) }}" method="POST">
                                        @csrf
                                        
                                        <input type="hidden" name="_method" id="categoryMethod" value="POST">
                                        
                                        <div class="form-group">
                                            <label class="form-label">Type <span style="color: #ef476f;">*</span></label>
                                            <select class="form-control" name="type" id="categoryType" required>
                                                <option value="" disabled selected>Select a Type</option>
                                                <option value="Residential">Residential</option>
                                                <option value="Commercial">Commercial</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Name <span style="color: #ef476f;">*</span></label>
                                            <input type="text" name="name" id="categoryName" class="form-control" placeholder="Enter category name" required>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group">
                                                <label class="form-label">Status <span style="color: #ef476f;">*</span></label>
                                                <select class="form-control" name="status" id="categoryStatus" required>
                                                    <option value="" disabled selected>Select Category Status</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="form-label">Serial Number <span style="color: #ef476f;">*</span></label>
                                                <input type="number" name="serial_number" id="categorySerial" class="form-control" placeholder="Enter Serial Number" required>
                                                <span class="help-text" style="color: #ff9a69;">The higher the serial number is, the later the category will be shown.</span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn-cancel" type="button" onclick="closeCategoryModal()">Close</button>
                                    <button type="submit" form="addCategoryForm" class="btn-primary" id="saveCategoryBtn">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>

                    @elseif ($currentPage === 'countries')
                        <div class="breadcrumb-wrapper">
                            <div class="breadcrumb-item"></div>
                        </div>

                        <div class="amenities-header-row">
                            <h2 class="amenities-title">Countries</h2>
                            <button class="btn-add-primary btn-animated-add" onclick="openAddCountryModal()">
                                <span class="btn-add-icon">+</span> Add
                            </button>
                        </div>

                        <style>
                            .btn-animated-add {
                                position: relative;
                                overflow: hidden;
                                transition: transform 0.2s cubic-bezier(.4,2,.6,1), box-shadow 0.25s ease;
                            }
                            .btn-animated-add:hover {
                                transform: translateY(-2px) scale(1.04);
                                box-shadow: 0 6px 24px rgba(56,128,255,0.35);
                            }
                            .btn-animated-add:active {
                                transform: translateY(0) scale(0.98);
                            }
                            .btn-animated-add::after {
                                content: '';
                                position: absolute;
                                top: 50%; left: 50%;
                                width: 0; height: 0;
                                background: rgba(255,255,255,0.25);
                                border-radius: 50%;
                                transform: translate(-50%,-50%);
                                transition: width 0.5s ease, height 0.5s ease, opacity 0.5s ease;
                                opacity: 0;
                            }
                            .btn-animated-add:active::after {
                                width: 200px; height: 200px;
                                opacity: 1;
                                transition: 0s;
                            }
                            .btn-add-icon {
                                display: inline-block;
                                transition: transform 0.3s cubic-bezier(.4,2,.6,1);
                            }
                            .btn-animated-add:hover .btn-add-icon {
                                transform: rotate(90deg) scale(1.2);
                            }

                            @keyframes countryRowFadeIn {
                                from { opacity: 0; transform: translateY(12px); }
                                to { opacity: 1; transform: translateY(0); }
                            }
                            .country-row {
                                animation: countryRowFadeIn 0.35s ease-out both;
                            }
                            @for ($i = 0; $i < count($countries ?? []); $i++)
                                .country-row:nth-child({{ $i + 1 }}) { animation-delay: {{ $i * 0.04 }}s; }
                            @endfor
                        </style>

                        <div class="table-controls">
                            <div class="search-control">
                                Search: <input type="text" id="countrySearch" class="control-input" placeholder="Search countries...">
                                <span style="margin-left: 12px; color: #64748b; font-size: 0.85rem;">Show</span>
                                <select id="countryEntries" class="entries-select" style="min-width: 75px;">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select> 
                                <span style="color: #64748b; font-size: 0.85rem;">entries</span>
                            </div>
                        </div>

                        <div class="data-table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width: 40%;">Country Name</th>
                                        <th style="width: 15%;">States</th>
                                        <th style="width: 15%;">Cities</th>
                                        <th style="width: 15%;">Places</th>
                                        <th style="width: 15%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (($countries ?? []) as $country)
                                        <tr class="country-row" data-id="{{ $country->id }}" data-name="{{ $country->name }}" data-states="{{ $country->states_count ?? 0 }}" data-cities="{{ $country->cities_count ?? 0 }}" data-places="{{ $country->property_places_count ?? 0 }}">
                                            <td style="font-weight: 600;">{{ $country->name }}</td>
                                            <td>{{ $country->states_count ?? 0 }}</td>
                                            <td>{{ $country->cities_count ?? 0 }}</td>
                                            <td>{{ $country->property_places_count ?? 0 }}</td>
                                            <td>
                                                <div class="actions">
                                                    <button class="btn-action btn-edit" title="Edit" onclick="editCountry({{ $country->id }})">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                    </button>
                                                    <form method="POST" action="{{ route('admin.countries.destroy', ['user' => $user, 'country' => $country->id]) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this country?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action btn-delete" title="Delete">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-footer">
                                <div class="pagination-info" id="countryPageInfo">Showing 0 to 0 of 0 entries</div>
                                <div class="pagination-controls" id="countryPagination"></div>
                            </div>
                        </div>

                        {{-- Add / Edit Country Modal --}}
                        <div id="addCountryModal" class="modal-backdrop" onclick="if(event.target === this) closeCountryModal()">
                            <div class="modal" style="max-width: 460px;">
                                <div class="modal-header" style="padding: 18px 24px; border-bottom: 1px solid #eee;">
                                    <div>
                                        <h3 class="modal-title" id="countryModalTitle" style="font-size: 1.1rem; font-weight: 600;">Add Country</h3>
                                    </div>
                                    <button type="button" class="modal-close-btn" onclick="closeCountryModal()" style="font-size: 1.3rem; background: none; border: none; cursor: pointer; color: #888;">
                                        &times;
                                    </button>
                                </div>
                                <div class="modal-body" style="padding: 24px;">
                                    <form id="addCountryForm" action="{{ route('admin.countries.store', $user) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" id="countryMethod" value="POST">

                                        <div class="form-group">
                                            <label class="form-label">Name <span style="color: #ef476f;">*</span></label>
                                            <input type="text" name="name" id="countryName" class="form-control" placeholder="Enter country name" required>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer" style="padding: 14px 24px; border-top: 1px solid #eee;">
                                    <button class="btn-cancel" type="button" onclick="closeCountryModal()" style="background: #6c63ff; color: #fff; border: none; padding: 8px 22px; border-radius: 6px; cursor: pointer; font-weight: 500;">Close</button>
                                    <button type="submit" form="addCountryForm" class="btn-primary" id="saveCountryBtn" style="background: #3880ff; color: #fff; border: none; padding: 8px 22px; border-radius: 6px; cursor: pointer; font-weight: 500;">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>

                    @elseif ($currentPage === 'states')
                        <div class="states-section-wrap">
                        <div class="breadcrumb-wrapper">
                            <div class="breadcrumb-item"></div>
                        </div>

                        <div class="amenities-header-row">
                            <h2 class="amenities-title">States</h2>
                            <button class="btn-add-primary btn-state-add" onclick="openAddStateModal()">
                                <span class="plus-icon">+</span> Add
                            </button>
                        </div>

                        <style>
                            /* ── States Advanced Animations ── */
                            @keyframes pageSlideIn {
                                from { opacity: 0; transform: translateY(20px); }
                                to   { opacity: 1; transform: translateY(0); }
                            }
                            @keyframes stateRowIn {
                                from { opacity: 0; transform: translateX(-18px) scale(0.98); }
                                to   { opacity: 1; transform: translateX(0) scale(1); }
                            }
                            @keyframes addBtnGlow {
                                0%   { box-shadow: 0 4px 15px rgba(56,128,255,0.3); }
                                50%  { box-shadow: 0 6px 30px rgba(56,128,255,0.6); }
                                100% { box-shadow: 0 4px 15px rgba(56,128,255,0.3); }
                            }
                            @keyframes tableHeaderSlide {
                                from { opacity: 0; transform: translateY(-8px); }
                                to   { opacity: 1; transform: translateY(0); }
                            }
                            @keyframes editBtnSpin {
                                from { transform: rotate(0deg); }
                                to   { transform: rotate(180deg); }
                            }

                            .states-section-wrap {
                                animation: pageSlideIn 0.45s cubic-bezier(.22,1,.36,1) both;
                            }
                            /* Staggered rows */
                            .state-row {
                                animation: stateRowIn 0.4s cubic-bezier(.22,1,.36,1) both;
                                transition: background 0.2s, box-shadow 0.2s;
                            }
                            @for ($i = 0; $i < count($states ?? []); $i++)
                                .state-row:nth-child({{ $i + 1 }}) { animation-delay: {{ $i * 0.055 }}s; }
                            @endfor
                            .state-row:hover {
                                background: linear-gradient(90deg, rgba(56,128,255,0.06) 0%, transparent 100%);
                                box-shadow: inset 3px 0 0 #3880ff;
                            }
                            /* Animated Add button */
                            .btn-state-add {
                                position: relative; overflow: hidden;
                                transition: transform 0.2s cubic-bezier(.4,2,.6,1), box-shadow 0.25s ease;
                                animation: addBtnGlow 2.5s ease-in-out infinite;
                            }
                            .btn-state-add:hover {
                                transform: translateY(-3px) scale(1.05);
                                animation: none;
                                box-shadow: 0 8px 28px rgba(56,128,255,0.45);
                            }
                            .btn-state-add:active { transform: scale(0.96); }
                            .btn-state-add .plus-icon {
                                display: inline-block;
                                transition: transform 0.35s cubic-bezier(.4,2,.6,1);
                                font-weight: 700; font-size: 1.2em;
                            }
                            .btn-state-add:hover .plus-icon {
                                transform: rotate(135deg) scale(1.2);
                            }
                            .btn-state-add::after {
                                content: ''; position: absolute;
                                top: 50%; left: 50%;
                                width: 0; height: 0;
                                background: rgba(255,255,255,0.3);
                                border-radius: 50%;
                                transform: translate(-50%,-50%);
                                transition: width 0.5s ease, height 0.5s ease, opacity 0.5s;
                                opacity: 0;
                            }
                            .btn-state-add:active::after {
                                width: 220px; height: 220px; opacity: 1; transition: 0s;
                            }
                            /* Action buttons */
                            .btn-edit {
                                transition: transform 0.25s cubic-bezier(.4,2,.6,1), background 0.2s, box-shadow 0.2s;
                            }
                            .btn-edit:hover {
                                transform: rotate(15deg) scale(1.15);
                                box-shadow: 0 4px 12px rgba(56,128,255,0.35);
                            }
                            .btn-delete {
                                transition: transform 0.25s cubic-bezier(.4,2,.6,1), background 0.2s, box-shadow 0.2s;
                            }
                            .btn-delete:hover {
                                transform: scale(1.15);
                                box-shadow: 0 4px 12px rgba(239,71,111,0.35);
                            }
                            /* Table header */
                            .data-table thead tr th {
                                animation: tableHeaderSlide 0.4s cubic-bezier(.22,1,.36,1) both;
                            }
                            .data-table thead tr th:nth-child(1) { animation-delay: 0s; }
                            .data-table thead tr th:nth-child(2) { animation-delay: 0.05s; }
                            /* Search input focus glow */
                            .control-input {
                                transition: border-color 0.3s, box-shadow 0.3s;
                            }
                            .control-input:focus {
                                border-color: #3880ff;
                                box-shadow: 0 0 0 3px rgba(56,128,255,0.15);
                                outline: none;
                            }
                        </style>

                        <div class="table-controls">
                            <div class="search-control">
                                Search: <input type="text" id="stateSearch" class="control-input" placeholder="Search states...">
                                <span style="margin-left: 12px; color: #64748b; font-size: 0.85rem;">Show</span>
                                <select id="stateEntries" class="entries-select" style="min-width: 75px;">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select> 
                                <span style="color: #64748b; font-size: 0.85rem;">entries</span>
                            </div>
                        </div>

                        <div class="data-table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">State Name</th>
                                        <th style="width: 25%;">Country</th>
                                        <th style="width: 15%;">Cities</th>
                                        <th style="width: 15%;">Places</th>
                                        <th style="width: 15%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (($states ?? []) as $state)
                                        <tr class="state-row" data-id="{{ $state->id }}" data-name="{{ $state->name }}" data-country="{{ $state->country_id }}" data-country-name="{{ $state->country->name ?? '' }}">
                                            <td style="font-weight: 600;">{{ $state->name }}</td>
                                            <td>{{ $state->country->name ?? '-' }}</td>
                                            <td>{{ $state->cities_count ?? 0 }}</td>
                                            <td>{{ $state->property_places_count ?? 0 }}</td>
                                            <td>
                                                <div class="actions">
                                                    <button class="btn-action btn-edit" title="Edit" onclick="editState({{ $state->id }})">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                    </button>
                                                    <form method="POST" action="{{ route('admin.states.destroy', ['user' => $user, 'state' => $state->id]) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this state?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action btn-delete" title="Delete">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-footer">
                                <div class="pagination-info" id="statePageInfo">Showing 0 to 0 of 0 entries</div>
                                <div class="pagination-controls" id="statePagination"></div>
                            </div>
                        </div>

                        {{-- Add / Edit State Modal --}}
                        <div id="addStateModal" class="modal-backdrop" onclick="if(event.target === this) closeStateModal()">
                            <div class="modal" style="max-width: 460px;">
                                <div class="modal-header" style="padding: 18px 24px; border-bottom: 1px solid #eee;">
                                    <div>
                                        <h3 class="modal-title" id="stateModalTitle" style="font-size: 1.1rem; font-weight: 600;">Add State</h3>
                                    </div>
                                    <button type="button" class="modal-close-btn" onclick="closeStateModal()" style="font-size: 1.3rem; background: none; border: none; cursor: pointer; color: #888;">
                                        &times;
                                    </button>
                                </div>
                                <div class="modal-body" style="padding: 24px;">
                                    <form id="addStateForm" action="{{ route('admin.states.store', $user) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" id="stateMethod" value="POST">

                                        <div class="form-group">
                                            <label class="form-label">Country <span style="color: #ef476f;">*</span></label>
                                            <select name="country_id" id="stateCountry" class="form-control" required>
                                                <option value="">Select country</option>
                                                @foreach (($countries ?? []) as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Name <span style="color: #ef476f;">*</span></label>
                                            <input type="text" name="name" id="stateName" class="form-control" placeholder="Enter state name" required>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer" style="padding: 14px 24px; border-top: 1px solid #eee;">
                                    <button class="btn-cancel" type="button" onclick="closeStateModal()" style="background: #6c63ff; color: #fff; border: none; padding: 8px 22px; border-radius: 6px; cursor: pointer; font-weight: 500;">Close</button>
                                    <button type="submit" form="addStateForm" class="btn-primary" id="saveStateBtn" style="background: #3880ff; color: #fff; border: none; padding: 8px 22px; border-radius: 6px; cursor: pointer; font-weight: 500;">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>

                        </div>{{-- end states-section-wrap --}}

                    @elseif ($currentPage === 'cities')
                        <div class="cities-section-wrap">
                        <div class="breadcrumb-wrapper">
                            <div class="breadcrumb-item"></div>
                        </div>

                        <div class="amenities-header-row">
                            <h2 class="amenities-title">Cities</h2>
                            <button class="btn-add-primary btn-city-add" onclick="openAddCityModal()">
                                <span class="plus-icon">+</span> Add
                            </button>
                        </div>

                        <style>
                            /* ── Cities Advanced Animations ── */
                            @keyframes pageSlideIn {
                                from { opacity: 0; transform: translateY(20px); }
                                to   { opacity: 1; transform: translateY(0); }
                            }
                            @keyframes cityRowIn {
                                from { opacity: 0; transform: translateX(-18px) scale(0.98); }
                                to   { opacity: 1; transform: translateX(0) scale(1); }
                            }
                            @keyframes addBtnGlow {
                                0%   { box-shadow: 0 4px 15px rgba(56,128,255,0.3); }
                                50%  { box-shadow: 0 6px 30px rgba(56,128,255,0.6); }
                                100% { box-shadow: 0 4px 15px rgba(56,128,255,0.3); }
                            }
                            @keyframes tableHeaderSlide {
                                from { opacity: 0; transform: translateY(-8px); }
                                to   { opacity: 1; transform: translateY(0); }
                            }
                            @keyframes editBtnSpin {
                                from { transform: rotate(0deg); }
                                to   { transform: rotate(180deg); }
                            }

                            .cities-section-wrap {
                                animation: pageSlideIn 0.45s cubic-bezier(.22,1,.36,1) both;
                            }
                            /* Staggered rows */
                            .city-row {
                                animation: cityRowIn 0.4s cubic-bezier(.22,1,.36,1) both;
                                transition: background 0.2s, box-shadow 0.2s;
                            }
                            @for ($i = 0; $i < count($cities ?? []); $i++)
                                .city-row:nth-child({{ $i + 1 }}) { animation-delay: {{ $i * 0.055 }}s; }
                            @endfor
                            .city-row:hover {
                                background: linear-gradient(90deg, rgba(56,128,255,0.06) 0%, transparent 100%);
                                box-shadow: inset 3px 0 0 #3880ff;
                            }
                            /* Animated Add button */
                            .btn-city-add {
                                position: relative; overflow: hidden;
                                transition: transform 0.2s cubic-bezier(.4,2,.6,1), box-shadow 0.25s ease;
                                animation: addBtnGlow 2.5s ease-in-out infinite;
                            }
                            .btn-city-add:hover {
                                transform: translateY(-3px) scale(1.05);
                                animation: none;
                                box-shadow: 0 8px 28px rgba(56,128,255,0.45);
                            }
                            .btn-city-add:active { transform: scale(0.96); }
                            .btn-city-add .plus-icon {
                                display: inline-block;
                                transition: transform 0.35s cubic-bezier(.4,2,.6,1);
                                font-weight: 700; font-size: 1.2em;
                            }
                            .btn-city-add:hover .plus-icon {
                                transform: rotate(135deg) scale(1.2);
                            }
                            .btn-city-add::after {
                                content: ''; position: absolute;
                                top: 50%; left: 50%;
                                width: 0; height: 0;
                                background: rgba(255,255,255,0.3);
                                border-radius: 50%;
                                transform: translate(-50%,-50%);
                                transition: width 0.5s ease, height 0.5s ease, opacity 0.5s;
                                opacity: 0;
                            }
                            .btn-city-add:active::after {
                                width: 220px; height: 220px; opacity: 1; transition: 0s;
                            }
                            /* Action buttons */
                            .btn-edit {
                                transition: transform 0.25s cubic-bezier(.4,2,.6,1), background 0.2s, box-shadow 0.2s;
                            }
                            .btn-edit:hover {
                                transform: rotate(15deg) scale(1.15);
                                box-shadow: 0 4px 12px rgba(56,128,255,0.35);
                            }
                            .btn-delete {
                                transition: transform 0.25s cubic-bezier(.4,2,.6,1), background 0.2s, box-shadow 0.2s;
                            }
                            .btn-delete:hover {
                                transform: scale(1.15);
                                box-shadow: 0 4px 12px rgba(239,71,111,0.35);
                            }
                            /* Table header */
                            .data-table thead tr th {
                                animation: tableHeaderSlide 0.4s cubic-bezier(.22,1,.36,1) both;
                            }
                            .data-table thead tr th:nth-child(1) { animation-delay: 0s; }
                            .data-table thead tr th:nth-child(2) { animation-delay: 0.05s; }
                            /* Search input focus glow */
                            .control-input {
                                transition: border-color 0.3s, box-shadow 0.3s;
                            }
                            .control-input:focus {
                                border-color: #3880ff;
                                box-shadow: 0 0 0 3px rgba(56,128,255,0.15);
                                outline: none;
                            }
                        </style>

                        <div class="table-controls">
                            <div class="search-control">
                                Search: <input type="text" id="citySearch" class="control-input" placeholder="Search cities...">
                                <span style="margin-left: 12px; color: #64748b; font-size: 0.85rem;">Show</span>
                                <select id="cityEntries" class="entries-select" style="min-width: 75px;">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select> 
                                <span style="color: #64748b; font-size: 0.85rem;">entries</span>
                            </div>
                        </div>

                        <div class="data-table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width: 24%;">City Name</th>
                                        <th style="width: 24%;">State</th>
                                        <th style="width: 24%;">Country</th>
                                        <th style="width: 13%;">Places</th>
                                        <th style="width: 15%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (($cities ?? []) as $city)
                                        <tr class="city-row" data-id="{{ $city->id }}" data-name="{{ $city->name }}" data-country="{{ $city->country_id }}" data-state="{{ $city->state_id }}" data-country-name="{{ $city->country->name ?? '' }}" data-state-name="{{ $city->state->name ?? '' }}">
                                            <td style="font-weight: 600;">{{ $city->name }}</td>
                                            <td>{{ $city->state->name ?? '-' }}</td>
                                            <td>{{ $city->country->name ?? '-' }}</td>
                                            <td>{{ $city->property_places_count ?? 0 }}</td>
                                            <td>
                                                <div class="actions">
                                                    <button class="btn-action btn-edit" title="Edit" onclick="editCity({{ $city->id }})">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                    </button>
                                                    <form method="POST" action="{{ route('admin.cities.destroy', ['user' => $user, 'city' => $city->id]) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this city?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action btn-delete" title="Delete">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-footer">
                                <div class="pagination-info" id="cityPageInfo">Showing 0 to 0 of 0 entries</div>
                                <div class="pagination-controls" id="cityPagination"></div>
                            </div>
                        </div>

                        {{-- Add / Edit City Modal --}}
                        <div id="addCityModal" class="modal-backdrop" onclick="if(event.target === this) closeCityModal()">
                            <div class="modal" style="max-width: 460px;">
                                <div class="modal-header" style="padding: 18px 24px; border-bottom: 1px solid #eee;">
                                    <div>
                                        <h3 class="modal-title" id="cityModalTitle" style="font-size: 1.1rem; font-weight: 600;">Add City</h3>
                                    </div>
                                    <button type="button" class="modal-close-btn" onclick="closeCityModal()" style="font-size: 1.3rem; background: none; border: none; cursor: pointer; color: #888;">
                                        &times;
                                    </button>
                                </div>
                                <div class="modal-body" style="padding: 24px;">
                                    <form id="addCityForm" action="{{ route('admin.cities.store', $user) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" id="cityMethod" value="POST">

                                        <div class="form-group">
                                            <label class="form-label">Country <span style="color: #ef476f;">*</span></label>
                                            <select name="country_id" id="cityCountry" class="form-control" required>
                                                <option value="">Select country</option>
                                                @foreach (($countries ?? []) as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">State <span style="color: #ef476f;">*</span></label>
                                            <select name="state_id" id="cityState" class="form-control" required>
                                                <option value="">Select state</option>
                                                @foreach (($states ?? []) as $state)
                                                    <option value="{{ $state->id }}" data-country="{{ $state->country_id ?? '' }}">{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Name <span style="color: #ef476f;">*</span></label>
                                            <input type="text" name="name" id="cityName" class="form-control" placeholder="Enter city name" required>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer" style="padding: 14px 24px; border-top: 1px solid #eee;">
                                    <button class="btn-cancel" type="button" onclick="closeCityModal()" style="background: #6c63ff; color: #fff; border: none; padding: 8px 22px; border-radius: 6px; cursor: pointer; font-weight: 500;">Close</button>
                                    <button type="submit" form="addCityForm" class="btn-primary" id="saveCityBtn" style="background: #3880ff; color: #fff; border: none; padding: 8px 22px; border-radius: 6px; cursor: pointer; font-weight: 500;">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>

                        </div>{{-- end cities-section-wrap --}}

                    @elseif ($currentPage === 'property-places')
                        <div class="property-places-section-wrap">
                        <div class="breadcrumb-wrapper">
                            <div class="breadcrumb-item"></div>
                        </div>

                        <div class="amenities-header-row">
                            <h2 class="amenities-title">Property Places</h2>
                            <button class="btn-add-primary btn-property-place-add" onclick="openAddPropertyPlaceModal()">
                                <span class="plus-icon">+</span> Add
                            </button>
                        </div>

                        <style>
                            /* ── Property Places Advanced Animations ── */
                            @keyframes pageSlideIn {
                                from { opacity: 0; transform: translateY(20px); }
                                to   { opacity: 1; transform: translateY(0); }
                            }
                            @keyframes propertyPlaceRowIn {
                                from { opacity: 0; transform: translateX(-18px) scale(0.98); }
                                to   { opacity: 1; transform: translateX(0) scale(1); }
                            }
                            @keyframes addBtnGlow {
                                0%   { box-shadow: 0 4px 15px rgba(56,128,255,0.3); }
                                50%  { box-shadow: 0 6px 30px rgba(56,128,255,0.6); }
                                100% { box-shadow: 0 4px 15px rgba(56,128,255,0.3); }
                            }
                            @keyframes tableHeaderSlide {
                                from { opacity: 0; transform: translateY(-8px); }
                                to   { opacity: 1; transform: translateY(0); }
                            }
                            @keyframes editBtnSpin {
                                from { transform: rotate(0deg); }
                                to   { transform: rotate(180deg); }
                            }

                            .property-places-section-wrap {
                                animation: pageSlideIn 0.45s cubic-bezier(.22,1,.36,1) both;
                            }
                            /* Staggered rows */
                            .property-place-row {
                                animation: propertyPlaceRowIn 0.4s cubic-bezier(.22,1,.36,1) both;
                                transition: background 0.2s, box-shadow 0.2s;
                            }
                            @for ($i = 0; $i < count($property_places ?? []); $i++)
                                .property-place-row:nth-child({{ $i + 1 }}) { animation-delay: {{ $i * 0.055 }}s; }
                            @endfor
                            .property-place-row:hover {
                                background: linear-gradient(90deg, rgba(56,128,255,0.06) 0%, transparent 100%);
                                box-shadow: inset 3px 0 0 #3880ff;
                            }
                            /* Animated Add button */
                            .btn-property-place-add {
                                position: relative; overflow: hidden;
                                transition: transform 0.2s cubic-bezier(.4,2,.6,1), box-shadow 0.25s ease;
                                animation: addBtnGlow 2.5s ease-in-out infinite;
                            }
                            .btn-property-place-add:hover {
                                transform: translateY(-3px) scale(1.05);
                                animation: none;
                                box-shadow: 0 8px 28px rgba(56,128,255,0.45);
                            }
                            .btn-property-place-add:active { transform: scale(0.96); }
                            .btn-property-place-add .plus-icon {
                                display: inline-block;
                                transition: transform 0.35s cubic-bezier(.4,2,.6,1);
                                font-weight: 700; font-size: 1.2em;
                            }
                            .btn-property-place-add:hover .plus-icon {
                                transform: rotate(135deg) scale(1.2);
                            }
                            .btn-property-place-add::after {
                                content: ''; position: absolute;
                                top: 50%; left: 50%;
                                width: 0; height: 0;
                                background: rgba(255,255,255,0.3);
                                border-radius: 50%;
                                transform: translate(-50%,-50%);
                                transition: width 0.5s ease, height 0.5s ease, opacity 0.5s;
                                opacity: 0;
                            }
                            .btn-property-place-add:active::after {
                                width: 220px; height: 220px; opacity: 1; transition: 0s;
                            }
                            /* Action buttons */
                            .btn-edit {
                                transition: transform 0.25s cubic-bezier(.4,2,.6,1), background 0.2s, box-shadow 0.2s;
                            }
                            .btn-edit:hover {
                                transform: rotate(15deg) scale(1.15);
                                box-shadow: 0 4px 12px rgba(56,128,255,0.35);
                            }
                            .btn-delete {
                                transition: transform 0.25s cubic-bezier(.4,2,.6,1), background 0.2s, box-shadow 0.2s;
                            }
                            .btn-delete:hover {
                                transform: scale(1.15);
                                box-shadow: 0 4px 12px rgba(239,71,111,0.35);
                            }
                            /* Table header */
                            .data-table thead tr th {
                                animation: tableHeaderSlide 0.4s cubic-bezier(.22,1,.36,1) both;
                            }
                            .data-table thead tr th:nth-child(1) { animation-delay: 0s; }
                            .data-table thead tr th:nth-child(2) { animation-delay: 0.05s; }
                            /* Search input focus glow */
                            .control-input {
                                transition: border-color 0.3s, box-shadow 0.3s;
                            }
                            .control-input:focus {
                                border-color: #3880ff;
                                box-shadow: 0 0 0 3px rgba(56,128,255,0.15);
                                outline: none;
                            }
                        </style>

                        <div class="table-controls">
                            <div class="search-control">
                                Search: <input type="text" id="propertyPlaceSearch" class="control-input" placeholder="Search property places...">
                                <span style="margin-left: 12px; color: #64748b; font-size: 0.9rem;">Show</span>
                                <select id="propertyPlaceEntries" class="entries-select" style="min-width: 70px;">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select> 
                                <span style="color: #64748b; font-size: 0.9rem;">entries</span>
                            </div>
                        </div>

                        <div class="data-table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width: 10%; text-align: center;">Image</th>
                                        <th style="width: 25%;">Place Name</th>
                                        <th style="width: 15%;">City</th>
                                        <th style="width: 15%;">State</th>
                                        <th style="width: 15%;">Country</th>
                                        <th style="width: 10%; text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (($property_places ?? []) as $property_place)
                                        <tr class="property-place-row" data-id="{{ $property_place->id }}" data-name="{{ $property_place->name }}" data-city="{{ $property_place->city_id }}" data-state="{{ $property_place->state_id }}" data-country="{{ $property_place->country_id }}">
                                            <td style="text-align: center;">
                                                <div style="display: flex; justify-content: center;">
                                                    @if($property_place->image)
                                                        <img src="{{ str_starts_with($property_place->image, 'http') ? $property_place->image : asset('storage/' . $property_place->image) }}" style="width: 48px; height: 48px; border-radius: 10px; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                                    @else
                                                        <div style="width: 48px; height: 48px; border-radius: 10px; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td style="font-weight: 600; color: #2c3e50;">{{ $property_place->name }}</td>
                                            <td style="color: #64748b;">{{ $property_place->city->name ?? '-' }}</td>
                                            <td style="color: #64748b;">{{ $property_place->state->name ?? '-' }}</td>
                                            <td style="color: #64748b;">{{ $property_place->country->name ?? '-' }}</td>
                                            <td>
                                                <div class="actions" style="justify-content: center;">
                                                    <button class="btn-action btn-edit" title="Edit" onclick="editPropertyPlace({{ $property_place->id }})">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                    </button>
                                                    <form method="POST" action="{{ route('admin.property-places.destroy', ['user' => $user, 'property_place' => $property_place->id]) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this property place?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action btn-delete" title="Delete">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-footer">
                                <div class="pagination-info" id="propertyPlacePageInfo">Showing 0 to 0 of 0 entries</div>
                                <div class="pagination-controls" id="propertyPlacePagination"></div>
                            </div>
                        </div>

                        {{-- Add / Edit Property Place Modal --}}
                        <div id="addPropertyPlaceModal" class="modal-backdrop" onclick="if(event.target === this) closePropertyPlaceModal()">
                            <div class="modal" style="max-width: 460px;">
                                <div class="modal-header" style="padding: 18px 24px; border-bottom: 1px solid #eee;">
                                    <div>
                                        <h3 class="modal-title" id="propertyPlaceModalTitle" style="font-size: 1.1rem; font-weight: 600;">Add Property Place</h3>
                                    </div>
                                    <button type="button" class="modal-close-btn" onclick="closePropertyPlaceModal()" style="font-size: 1.3rem; background: none; border: none; cursor: pointer; color: #888;">
                                        &times;
                                    </button>
                                </div>
                                <div class="modal-body" style="padding: 24px;">
                                    <form id="addPropertyPlaceForm" action="{{ route('admin.property-places.store', $user) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="_method" id="propertyPlaceMethod" value="POST">

                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label class="form-label" style="font-weight: 500; font-size: 0.9rem; color: #64748b; margin-bottom: 8px; display: block;">Place Name <span style="color: #ef476f;">*</span></label>
                                            <input type="text" name="name" id="propertyPlaceName" class="form-control" placeholder="Enter property place name" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;" required>
                                        </div>

                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label class="form-label" style="font-weight: 500; font-size: 0.9rem; color: #64748b; margin-bottom: 8px; display: block;">Country <span style="color: #ef476f;">*</span></label>
                                            <select name="country_id" id="propertyPlaceCountry" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;" required>
                                                <option value="">Select Country</option>
                                                @foreach($countries ?? [] as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label class="form-label" style="font-weight: 500; font-size: 0.9rem; color: #64748b; margin-bottom: 8px; display: block;">State <span style="color: #ef476f;">*</span></label>
                                            <select name="state_id" id="propertyPlaceState" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;" required>
                                                <option value="">Select State</option>
                                                @foreach($states ?? [] as $state)
                                                    <option value="{{ $state->id }}" data-country="{{ $state->country_id ?? '' }}">{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label class="form-label" style="font-weight: 500; font-size: 0.9rem; color: #64748b; margin-bottom: 8px; display: block;">City <span style="color: #ef476f;">*</span></label>
                                            <select name="city_id" id="propertyPlaceCity" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;" required>
                                                <option value="">Select City</option>
                                                @foreach($cities ?? [] as $city)
                                                    <option value="{{ $city->id }}" data-country="{{ $city->country_id ?? '' }}" data-state="{{ $city->state_id ?? '' }}">{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label class="form-label" style="font-weight: 500; font-size: 0.9rem; color: #64748b; margin-bottom: 8px; display: block;">Image</label>
                                            <input type="file" name="image" id="propertyPlaceImage" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 10px;">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer" style="padding: 14px 24px; border-top: 1px solid #eee;">
                                    <button class="btn-cancel" type="button" onclick="closePropertyPlaceModal()" style="background: #6c63ff; color: #fff; border: none; padding: 8px 22px; border-radius: 6px; cursor: pointer; font-weight: 500;">Close</button>
                                    <button type="submit" form="addPropertyPlaceForm" class="btn-primary" id="savePropertyPlaceBtn" style="background: #3880ff; color: #fff; border: none; padding: 8px 22px; border-radius: 6px; cursor: pointer; font-weight: 500;">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>

                        </div>{{-- end property-places-section-wrap --}}

                    @elseif ($currentPage === 'manage-properties')
                        <style>
                            .manage-properties-wrap {
                                background: #fff;
                                border: 1px solid #e5e7eb;
                                border-radius: 8px;
                                overflow: hidden;
                                height: 100%;
                                display: grid;
                                grid-template-rows: auto minmax(0, 1fr) auto;
                            }

                            .manage-properties-wrap .data-table-container {
                                overflow-x: auto;
                                overflow-y: auto;
                            }

                            .manage-properties-wrap .data-table {
                                min-width: 1100px;
                                font-size: 13px;
                            }

                            .manage-properties-wrap .data-table th,
                            .manage-properties-wrap .data-table td {
                                padding-top: 10px;
                                padding-bottom: 10px;
                            }

                            .manage-properties-controls {
                                display: flex;
                                align-items: center;
                                gap: 8px;
                                padding: 8px 10px;
                                border-bottom: 1px solid #e5e7eb;
                                flex-wrap: wrap;
                            }

                            .manage-properties-title {
                                font-size: 18px;
                                font-weight: 700;
                                color: #1f2937;
                                margin-right: auto;
                                line-height: 1.1;
                            }

                            .manage-properties-input,
                            .manage-properties-select {
                                border: 1px solid #d1d5db;
                                border-radius: 4px;
                                padding: 7px 9px;
                                min-width: 92px;
                                height: 34px;
                                font-size: 13px;
                                color: #374151;
                                background: #fff;
                            }
                            .manage-properties-search {
                                min-width: 120px;
                            }

                            /* ── Manage Properties Advanced Animations ── */
                            @keyframes propPageIn {
                                from { opacity: 0; transform: translateY(24px) scale(0.98); }
                                to   { opacity: 1; transform: translateY(0) scale(1); }
                            }
                            @keyframes propRowFadeIn {
                                from { opacity: 0; transform: translateX(-12px); }
                                to   { opacity: 1; transform: translateX(0); }
                            }
                            @keyframes propBtnPulse {
                                0%, 100% { box-shadow: 0 4px 15px rgba(29, 115, 216, 0.3); }
                                50%      { box-shadow: 0 6px 28px rgba(29, 115, 216, 0.6); }
                            }
                            @keyframes propBadgePop {
                                0% { transform: scale(0.9); opacity: 0; }
                                80% { transform: scale(1.05); }
                                100% { transform: scale(1); opacity: 1; }
                            }

                            .manage-properties-wrap {
                                animation: propPageIn 0.55s cubic-bezier(.22,1,.36,1) both;
                            }
                            
                            .manage-property-row {
                                animation: propRowFadeIn 0.4s cubic-bezier(.22,1,.36,1) both;
                                transition: all 0.25s ease;
                            }
                            @for ($i = 0; $i < count($manageProperties ?? []); $i++)
                                .manage-property-row:nth-child({{ $i + 1 }}) { animation-delay: {{ $i * 0.045 }}s; }
                            @endfor

                            .manage-property-row:hover {
                                background: linear-gradient(90deg, rgba(29, 115, 216, 0.04) 0%, transparent 100%);
                                box-shadow: inset 3px 0 0 #1d73d8;
                                transform: scale(1.002) translateX(2px);
                            }

                            .btn-add-property {
                                position: relative;
                                overflow: hidden;
                                animation: propBtnPulse 2.5s ease-in-out infinite;
                                transition: all 0.3s cubic-bezier(.4,2,.6,1) !important;
                                padding: 7px 12px !important;
                                font-size: 13px;
                            }
                            .btn-add-property:hover {
                                transform: translateY(-3px) scale(1.05);
                                box-shadow: 0 8px 24px rgba(29, 115, 216, 0.4);
                            }
                            .btn-add-property:active { transform: scale(0.96); }

                            .status-select {
                                transition: all 0.2s cubic-bezier(.4,2,.6,1);
                                animation: propBadgePop 0.4s cubic-bezier(.4,2,.6,1) both;
                            }
                            .status-select:hover {
                                transform: translateY(-1px);
                                filter: brightness(1.1);
                                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                            }

                            /* Status Select Dropdowns */
                            .status-select {
                                padding: 4px 10px;
                                border-radius: 4px;
                                border: none;
                                color: white;
                                font-weight: 600;
                                font-size: 13px;
                                cursor: pointer;
                                appearance: none;
                                -webkit-appearance: none;
                                min-width: 100px;
                                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'%3E%3C/path%3E%3C/svg%3E");
                                background-repeat: no-repeat;
                                background-position: right 8px center;
                                padding-right: 28px;
                            }
                            .status-select:focus {
                                outline: none;
                                box-shadow: 0 0 0 2px rgba(255,255,255,0.3);
                            }
                            .status-select option {
                                background-color: #fff;
                                color: #333;
                                padding: 8px;
                            }
                            .status-select.bg-green { background-color: #2ecc71; }
                            .status-select.bg-red { background-color: #e74c3c; }
                            .status-select.bg-pending { background-color: #3880ff; }
                            .manage-property-actions {
                                display: inline-flex;
                                align-items: center;
                                gap: 10px;
                                white-space: nowrap;
                                padding: 5px 8px;
                                border: 1px solid #e6ebf3;
                                border-radius: 12px;
                                background: #fff;
                                box-shadow: 0 6px 16px rgba(148, 163, 184, 0.12);
                            }
                            .manage-property-actions .btn-action {
                                width: 30px;
                                min-width: 30px;
                                height: 30px;
                                padding: 0;
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                border-radius: 8px;
                                border: 1px solid #e8edf5;
                                background: #f8fafc;
                                color: #667085;
                            }
                            .manage-property-actions .btn-action svg {
                                width: 14px;
                                height: 14px;
                            }
                            .btn-action.is-disabled {
                                opacity: 1;
                                cursor: not-allowed;
                                pointer-events: none;
                                background: #f8fafc;
                            }

                        </style>

                        <div class="manage-properties-wrap">
                            <div class="manage-properties-controls">
                                <h2 class="manage-properties-title">Properties</h2>
                                <select id="managePropertyTypeFilter" class="manage-properties-select">
                                    <option value="all">All</option>
  
                                    <option value="apartment">Apartment</option>
                                    <option value="plot">Plot</option>
                                    <option value="house">Villa</option>
                                    
                                    @foreach (($manageProperties ?? collect())->pluck('type')->filter()->unique()->values() as $propertyType)
                                        <option value="{{ strtolower($propertyType) }}">{{ ucfirst($propertyType) }}</option>
                                    @endforeach
                                </select>
                                <input type="text" id="managePropertyTitleSearch" class="manage-properties-input manage-properties-search" placeholder="Title">
                                <div class="entries-control" style="margin-left: 0;">
                                    Show 
                                    <select id="managePropertyEntries" class="manage-properties-select" style="min-width: 70px;">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select> 
                                    entries
                                </div>
                                <a href="{{ route('admin.properties.create', $user) }}" class="btn-add-primary btn-add-property" style="margin-left:auto; background: #1d73d8; color: #fff; border-radius: 4px; border: none; text-decoration:none; display:inline-flex; align-items:center;">
                                    +Add Property
                                </a>
                            </div>

                            <div class="data-table-container">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Post by</th>
                                            <th>Type</th>
                                            <th>City</th>
                                            <th>Approval Status</th>
                                            <th>Status</th>
                                            
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="managePropertiesBody">
                                        @forelse (($manageProperties ?? []) as $property)
                                            <tr class="manage-property-row" data-title="{{ strtolower($property->title ?? '') }}" data-type="{{ strtolower($property->type ?? '') }}">
                                                <td style="font-weight:600;">{{ $property->title ?: 'Untitled Property' }}</td>
                                                <td>{{ $property->post_by ?: '-' }}</td>
                                                <td>{{ $property->type ?: '-' }}</td>
                                                <td>{{ $property->city ?: '-' }}</td>
                                                <td>
                                                    <select class="status-select {{ (int)$property->approve_status === 1 ? 'bg-green' : ((int)$property->approve_status === 0 ? 'bg-red' : 'bg-pending') }}" onchange="this.className='status-select ' + (this.value=='1' ? 'bg-green' : (this.value=='0' ? 'bg-red' : 'bg-pending'))">
                                                        <option value="1" {{ (int)$property->approve_status === 1 ? 'selected' : '' }}>Approve</option>
                                                        <option value="2" {{ (int)$property->approve_status === 2 ? 'selected' : '' }}>Pending</option>
                                                        <option value="0" {{ (int)$property->approve_status === 0 ? 'selected' : '' }}>Rejected</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="status-select {{ (int)$property->status === 1 ? 'bg-green' : 'bg-red' }}" onchange="this.className='status-select ' + (this.value=='1' ? 'bg-green' : 'bg-red')">
                                                        <option value="1" {{ (int)$property->status === 1 ? 'selected' : '' }}>Active</option>
                                                        <option value="0" {{ (int)$property->status === 0 ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </td>
                                                
                                                <td>
                                                    <div class="manage-property-actions">
                                                        <button type="button" class="btn-action btn-edit is-disabled" title="Property edit is not wired in this panel yet" aria-label="Edit property unavailable">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                        </button>
                                                        <button type="button" class="btn-action btn-delete is-disabled" title="Property delete is not wired in this panel yet" aria-label="Delete property unavailable">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" style="text-align:center; color:#6b7280;">No properties found in database.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination-footer">
                                <div class="pagination-info" id="managePropertyPageInfo">Showing 0 to 0 of 0 entries</div>
                                <div class="pagination-controls" id="managePropertyPagination"></div>
                            </div>
                        </div>
                        <style>
                            @media (max-width: 920px) {
                                .main-panel.manage-properties-panel {
                                    height: auto;
                                    overflow: visible;
                                }

                                .manage-properties-wrap {
                                    height: auto;
                                }

                                .manage-properties-wrap .data-table-container {
                                    overflow-y: visible;
                                }
                            }
                        </style>

                    @elseif ($currentPage === 'choose-property-type')
                        <style>
                            .choose-type-wrap {
                                background: #fff;
                                border: 1px solid #e5e7eb;
                                border-radius: 8px;
                                overflow: hidden;
                            }

                            .choose-type-grid {
                                display: grid;
                                grid-template-columns: 1fr 1fr;
                                gap: 16px;
                                padding: 16px;
                            }

                            .choose-type-card {
                                background: #fff;
                                border: 1px solid #eceff4;
                                border-radius: 8px;
                                min-height: 120px;
                                padding: 14px 12px;
                                text-align: center;
                                text-decoration: none;
                                color: inherit;
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                justify-content: center;
                                box-shadow: 0 4px 16px rgba(17, 24, 39, 0.06);
                                transition: transform 0.2s ease, box-shadow 0.2s ease;
                            }

                            .choose-type-card:hover {
                                transform: translateY(-2px);
                                box-shadow: 0 8px 20px rgba(17, 24, 39, 0.1);
                            }

                            .choose-type-icon {
                                width: 44px;
                                height: 44px;
                                margin: 0 auto 8px;
                                border-radius: 6px;
                                display: grid;
                                place-items: center;
                                color: #fff;
                            }

                            .choose-type-icon svg {
                                width: 22px;
                                height: 22px;
                            }

                            .choose-type-icon.commercial { background: #22c55e; }
                            .choose-type-icon.residential { background: #f59e0b; }

                            .choose-type-title {
                                font-size: 20px;
                                font-weight: 700;
                                margin: 0 0 4px;
                                color: #1f2a44;
                            }

                            .choose-type-count {
                                margin: 0;
                                color: #6b7280;
                                font-size: 14px;
                                font-weight: 600;
                            }

                            @media (max-width: 900px) {
                                .choose-type-grid { grid-template-columns: 1fr; }
                                .choose-type-title { font-size: 18px; }
                                .choose-type-count { font-size: 13px; }
                            }
                        </style>

                        <div class="choose-type-wrap">
                            <div class="choose-type-grid">
                                <a href="{{ route('admin.properties.create.type', ['user' => $user, 'type' => 'commercial']) }}" class="choose-type-card">
                                    <div class="choose-type-icon commercial">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <rect x="4" y="3" width="16" height="18" rx="1"></rect>
                                            <path d="M9 21v-4h6v4"></path>
                                            <path d="M8 7h.01M12 7h.01M16 7h.01M8 11h.01M12 11h.01M16 11h.01"></path>
                                        </svg>
                                    </div>
                                    <h3 class="choose-type-title">COMMERCIAL</h3>
                                    <p class="choose-type-count">Total:{{ $commercialCount ?? 0 }} Properties</p>
                                </a>

                                <a href="{{ route('admin.properties.create.type', ['user' => $user, 'type' => 'residential']) }}" class="choose-type-card">
                                    <div class="choose-type-icon residential">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M3 11.5 12 4l9 7.5"></path>
                                            <path d="M5 10.5V20h5v-4h4v4h5v-9.5"></path>
                                        </svg>
                                    </div>
                                    <h3 class="choose-type-title">RESIDENTIAL</h3>
                                    <p class="choose-type-count">Total:{{ $residentialCount ?? 0 }} Properties</p>
                                </a>
                                
                        </div>

                    @elseif ($currentPage === 'add-property')
                        <style>
                            .add-property-wrap { background: #fff; border: 1px solid #dde4ee; border-radius: 8px; overflow: hidden; }
                            .add-property-head { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 18px 24px; border-bottom: 1px solid #e9eef5; background: #fff; }
                            .add-property-title { margin: 0; font-size: 18px; font-weight: 700; color: #202938; }
                            .add-property-subtitle { margin: 6px 0 0; color: #66758b; font-size: 12px; }
                            .add-property-back { display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: #1d4ed8; font-weight: 600; padding: 8px 12px; border-radius: 6px; background: #f8fbff; border: 1px solid #dbe7ff; }
                            .add-property-form { padding: 18px 22px 26px; background: #fff; }
                            .property-form-grid { display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); gap: 18px; }
                            .property-form-card { grid-column: span 12; background: #fff; border: 1px solid #e9eef5; border-radius: 6px; padding: 18px; }
                            .property-form-card h3 { margin: 0 0 6px; font-size: 14px; font-weight: 700; color: #18263a; }
                            .property-form-card p { margin: 0 0 16px; color: #718097; font-size: 12px; }
                            .property-field { grid-column: span 12; }
                            .property-field.col-6 { grid-column: span 6; }
                            .property-field.col-4 { grid-column: span 4; }
                            .property-field.col-3 { grid-column: span 3; }
                            .property-field label { display: block; margin-bottom: 7px; color: #1f2f46; font-size: 12px; font-weight: 700; }
                            .property-field input, .property-field select, .property-field textarea { width: 100%; border: 1px solid #dfe5ee; border-radius: 4px; padding: 10px 12px; font-size: 13px; color: #223247; background: #fff; }
                            .property-field textarea { min-height: 100px; resize: vertical; }
                            .property-field .hint { margin-top: 6px; color: #7a889a; font-size: 12px; }
                            .property-check-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; }
                            .property-check { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: 1px solid #dfe7f1; border-radius: 4px; background: #fff; }
                            .property-check input { width: auto; }
                            .property-toggle-row { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
                            .property-chip { display: inline-flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 4px; border: 1px solid #d7e3f2; background: #fff; color: #1f3550; font-weight: 600; }
                            .upload-panel { border: 1px dashed #d5dde8; border-radius: 4px; min-height: 152px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; color: #5f7086; background: #fff; padding: 20px; }
                            .upload-panel.large { min-height: 165px; }
                            .upload-panel .upload-title { font-size: 13px; font-weight: 600; color: #334258; margin-bottom: 6px; }
                            .upload-panel .upload-meta { font-size: 11px; color: #7b8aa0; }
                            .upload-note { margin-top: 8px; font-size: 11px; color: #4385ff; }
                            .map-preview { height: 265px; border: 1px solid #dfe5ee; border-radius: 4px; background: linear-gradient(180deg, rgba(196,239,219,0.75), rgba(181,229,212,0.85)), radial-gradient(circle at 20% 35%, rgba(99,164,120,0.25) 0 13%, transparent 14%), radial-gradient(circle at 63% 40%, rgba(105,155,112,0.22) 0 12%, transparent 13%), linear-gradient(90deg, rgba(97,134,189,0.18) 0 22%, transparent 23% 100%); position: relative; overflow: hidden; }
                            .map-preview::before { content: 'Pin Location on Map'; position: absolute; top: 14px; left: 14px; background: rgba(255,255,255,0.96); border: 1px solid #dce6f3; color: #223247; font-size: 12px; font-weight: 700; padding: 8px 12px; border-radius: 4px; }
                            .map-preview::after { content: ''; position: absolute; inset: 0; background-image: linear-gradient(rgba(255,255,255,0.18) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.18) 1px, transparent 1px); background-size: 44px 44px; mix-blend-mode: soft-light; }
                            .property-rich-toolbar { display: flex; gap: 6px; flex-wrap: wrap; padding: 8px; border: 1px solid #dfe5ee; border-bottom: none; border-radius: 4px 4px 0 0; background: #fafbfd; }
                            .property-rich-toolbar span { min-width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #dbe2ec; border-radius: 4px; font-size: 12px; color: #425066; background: #fff; }
                            .property-rich-editor { border-top-left-radius: 0 !important; border-top-right-radius: 0 !important; min-height: 132px !important; }
                            .faq-item { display: grid; grid-template-columns: 1fr 1fr auto; gap: 12px; margin-bottom: 12px; }
                            .faq-remove-btn, .faq-add-btn, .property-save-btn { border: none; border-radius: 12px; cursor: pointer; font-weight: 700; }
                            .faq-add-btn { padding: 10px 14px; background: #1d73d8; color: #fff; border-radius: 4px; }
                            .faq-remove-btn { padding: 0 18px; background: #f78d98; color: #fff; border-radius: 4px; }
                            .property-form-actions { display: flex; justify-content: center; gap: 12px; margin-top: 22px; }
                            .property-save-btn { padding: 11px 28px; background: #28a745; color: #fff; box-shadow: none; border-radius: 4px; }
                            .property-save-btn.secondary { background: #eef4fb; color: #27425f; box-shadow: none; }
                            @media (max-width: 1100px) { .property-field.col-6, .property-field.col-4, .property-field.col-3 { grid-column: span 12; } .property-check-grid, .faq-item { grid-template-columns: 1fr; } .add-property-head { flex-direction: column; align-items: flex-start; } }
                        </style>

                        <div class="add-property-wrap">
                            <div class="add-property-head">
                                <div>
                                    <h2 class="add-property-title">{{ ucfirst($selectedPropertyType ?? 'property') }} Property</h2>
                                    <p class="add-property-subtitle">Create the full {{ $selectedPropertyType ?? 'property' }} listing in one page with media, pricing, address, amenities, content, and FAQs.</p>
                                </div>
                                <a href="{{ route('admin.section', ['user' => $user, 'section' => 'manage-properties']) }}" class="add-property-back">
                                    <span>&larr;</span>
                                    <span>Back to Manage Properties</span>
                                </a>
                            </div>

                            <form id="addPropertyForm" class="add-property-form" onsubmit="event.preventDefault();">
                                <div class="property-form-grid">
                                    <div class="property-form-card">
                                        <h3>Add Property</h3>
                                        <p>{{ ucfirst($selectedPropertyType ?? 'Property') }} details and media uploads.</p>
                                        <div class="property-form-grid">
                                            <div class="property-field col-6">
                                                <label for="propertyGalleryImages">Gallery Images *</label>
                                                <div class="upload-note">Maximum file size per image: 2MB</div>
                                                <label class="upload-panel large" for="propertyGalleryImages">
                                                    <span class="upload-title">Drop files here to upload</span>
                                                    <span class="upload-meta">Gallery Images 2mb</span>
                                                </label>
                                                <input type="file" id="propertyGalleryImages" name="gallery_images[]" accept="image/*" multiple hidden>
                                            </div>
                                            <div class="property-field col-6">
                                                <label for="propertyFloorPlanImages">Floor Plan Images</label>
                                                <label class="upload-panel large" for="propertyFloorPlanImages">
                                                    <span class="upload-title">Drag &amp; Drop or Click to Upload Floor Plan Images</span>
                                                    <span class="upload-meta">Upload multiple floor plan images</span>
                                                </label>
                                                <input type="file" id="propertyFloorPlanImages" name="floor_plan_images[]" accept=".pdf,.jpg,.jpeg,.png" multiple hidden>
                                            </div>
                                            <div class="property-field">
                                                <label for="propertyDisplayImage">Display Image *</label>
                                                <label class="upload-panel large" for="propertyDisplayImage">
                                                    <span class="upload-title">Drag &amp; Drop or Click to Upload Display Image</span>
                                                    <span class="upload-meta">Display Image 5 mb</span>
                                                </label>
                                                <div class="hint" style="color:#f97316;">Recommended image resolution is 760x320 pixels</div>
                                                <div class="upload-note">Maximum file size: 2MB</div>
                                                <input type="file" id="propertyDisplayImage" name="display_image" accept="image/*" hidden>
                                            </div>
                                            <div class="property-field">
                                                <label for="propertyName">Name of the Property *</label>
                                                <input type="text" id="propertyName" name="property_name" placeholder="Enter Property Name" required>
                                            </div>
                                            <div class="property-field col-6">
                                                <label for="propertyFullAddress">Full Address *</label>
                                                <input type="text" id="propertyFullAddress" name="full_address" placeholder="Enter Address" readonly required>
                                            </div>
                                            <div class="property-field col-6">
                                                <label for="propertyType">Property Type</label>
                                                <select id="propertyType" name="property_type">
                                                    <option value="">Select property type</option>
                                                    <option value="residential" {{ ($selectedPropertyType ?? '') === 'residential' ? 'selected' : '' }}>Residential</option>
                                                    <option value="commercial" {{ ($selectedPropertyType ?? '') === 'commercial' ? 'selected' : '' }}>Commercial</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="property-form-card">
                                        <h3>Location Details</h3>
                                        <p>Address will auto fill after country, state, city, location and pincode are selected.</p>
                                        <div class="property-form-grid">
                                            <div class="property-field col-3">
                                                <label for="propertyCountry">Country *</label>
                                                <select id="propertyCountry" name="country_id" required>
                                                    <option value="">Select country</option>
                                                    @foreach (($countries ?? []) as $country)
                                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="property-field col-3">
                                                <label for="propertyState">State *</label>
                                                <select id="propertyState" name="state_id" required>
                                                    <option value="">Select state</option>
                                                    @foreach (($states ?? []) as $state)
                                                        <option value="{{ $state->id }}" data-country="{{ $state->country_id ?? '' }}">{{ $state->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="property-field col-3">
                                                <label for="propertyCity">City *</label>
                                                <select id="propertyCity" name="city_id" required>
                                                    <option value="">Select city</option>
                                                    @foreach (($cities ?? []) as $city)
                                                        <option value="{{ $city->id }}" data-state="{{ $city->state_id ?? '' }}">{{ $city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="property-field col-3">
                                                <label for="propertyLocation">Location *</label>
                                                <select id="propertyLocation" name="property_place_id" required>
                                                    <option value="">Select location</option>
                                                    @foreach (($propertyPlaces ?? []) as $place)
                                                        <option value="{{ $place->id }}" data-country="{{ $place->country_id ?? '' }}" data-state="{{ $place->state_id ?? '' }}" data-city="{{ $place->city_id ?? '' }}">{{ $place->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="property-field col-3">
                                                <label for="propertyPincode">Pincode *</label>
                                                <input type="text" id="propertyPincode" name="pincode" placeholder="Enter pincode" required>
                                            </div>
                                            <div class="property-field col-3">
                                                <label for="propertyLatitude">Latitude *</label>
                                                <input type="text" id="propertyLatitude" name="latitude" placeholder="e.g. 13.0827" required>
                                            </div>
                                            <div class="property-field col-3">
                                                <label for="propertyLongitude">Longitude *</label>
                                                <input type="text" id="propertyLongitude" name="longitude" placeholder="e.g. 80.2707" required>
                                            </div>
                                            <div class="property-field col-3">
                                                <label for="propertyMapSelected">Map Selected</label>
                                                <input type="text" id="propertyMapSelected" name="map_selected" placeholder="Paste map link or selected marker info">
                                            </div>
                                            <div class="property-field">
                                                <label>Pin Location on Map</label>
                                                <div class="map-preview"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="property-form-card">
                                        <h3>Listing Settings</h3>
                                        <p>Configure pricing, inventory, area, and publish settings.</p>
                                        <div class="property-form-grid">
                                            <div class="property-field col-6">
                                                <label>Amenities *</label>
                                                <div class="property-check-grid">
                                                    @foreach (($amenities ?? []) as $amenity)
                                                        <label class="property-check">
                                                            <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}">
                                                            <span>{{ $amenity->name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="property-field col-6">
                                                <label>Top Picks</label>
                                                <div class="property-toggle-row">
                                                    <label class="property-chip">
                                                        <input type="checkbox" name="top_picks" value="1">
                                                        <span>Mark as Top Picks</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="property-field col-3"><label for="propertyMinPrice">Min Price (Rs) *</label><input type="number" id="propertyMinPrice" name="min_price" placeholder="Minimum price"></div>
                                            <div class="property-field col-3"><label for="propertyMaxPrice">Max Price (Rs) *</label><input type="number" id="propertyMaxPrice" name="max_price" placeholder="Maximum price"><div class="hint">Either one can be filled if only one value is available.</div></div>
                                            <div class="property-field col-3"><label for="propertyAveragePrice">Average Price (Rs)</label><input type="number" id="propertyAveragePrice" name="average_price" placeholder="Enter Average Price"></div>
                                            <div class="property-field col-3"><label for="propertyBhk">No of BHK</label><select id="propertyBhk" name="bhk"><option value="">Select BHK</option><option value="1 BHK">1 BHK</option><option value="2 BHK">2 BHK</option><option value="3 BHK">3 BHK</option><option value="4 BHK">4 BHK</option><option value="5+ BHK">5+ BHK</option></select></div>
                                            <div class="property-field col-3"><label for="propertyConstructionStatus">Construction Status *</label><select id="propertyConstructionStatus" name="construction_status" required><option value="">Select construction status</option><option value="Ready to Move">Ready to Move</option><option value="Under Construction">Under Construction</option><option value="New Launch">New Launch</option></select></div>
                                            <div class="property-field col-3"><label for="propertyPossessionDate">Possession Date</label><input type="date" id="propertyPossessionDate" name="possession_date"></div>
                                            <div class="property-field col-3"><label for="propertyFurnishedStatus">Furnished Status *</label><select id="propertyFurnishedStatus" name="furnished_status" required><option value="">Select furnished status</option><option value="Furnished">Furnished</option><option value="Semi Furnished">Semi Furnished</option><option value="Unfurnished">Unfurnished</option></select></div>
                                            <div class="property-field col-3"><label for="propertyArea">Area (sqft)</label><input type="number" id="propertyArea" name="area" placeholder="Total area"></div>
                                            <div class="property-field col-3"><label for="propertyMinArea">Min Area (sqft)</label><input type="number" id="propertyMinArea" name="min_area" placeholder="Minimum area"></div>
                                            <div class="property-field col-3"><label for="propertyMaxArea">Max Area (sqft)</label><input type="number" id="propertyMaxArea" name="max_area" placeholder="Maximum area"></div>
                                            <div class="property-field col-3"><label for="propertyStatus">Status *</label><select id="propertyStatus" name="status" required><option value="">Select status</option><option value="1">Active</option><option value="0">Inactive</option></select></div>
                                            <div class="property-field col-3"><label for="propertyCategory">Category *</label><select id="propertyCategory" name="category_id" required><option value="">Select category</option>@foreach (($categories ?? []) as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select></div>
                                            <div class="property-field">
                                                <label for="propertyBrochure">Brochure Files *</label>
                                                <label class="upload-panel" for="propertyBrochure">
                                                    <span class="upload-title">Drag &amp; drop files here or click to browse</span>
                                                    <span class="upload-meta">Supported formats: PDF, DOCX, JPG, PNG</span>
                                                </label>
                                                <div class="upload-note">Maximum file size: 10MB</div>
                                                <div class="hint">Upload brochures, floor plans, price lists, or any documents related to this property.</div>
                                                <input type="file" id="propertyBrochure" name="brochure" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required hidden>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="property-form-card">
                                        <h3>Content</h3>
                                        <p>Add the descriptive content used on the listing page.</p>
                                        <div class="property-form-grid">
                                            <div class="property-field"><label for="propertyOverview">Overview *</label><textarea id="propertyOverview" name="overview" required></textarea></div>
                                            <div class="property-field">
                                                <label for="propertyHighlights">Highlights *</label>
                                                <div class="property-rich-toolbar">
                                                    <span>B</span><span>U</span><span>I</span><span>A</span><span>•</span><span>1.</span><span>≡</span><span>+</span><span>&lt;/&gt;</span><span>?</span>
                                                </div>
                                                <textarea id="propertyHighlights" class="property-rich-editor" name="highlights" placeholder="Enter highlights here..." required></textarea>
                                            </div>
                                            <div class="property-field"><label for="propertyAboutProject">About Project *</label><textarea id="propertyAboutProject" name="about_project" required></textarea></div>
                                            <div class="property-field col-6"><label for="propertyReraNumber">RERA Number</label><input type="text" id="propertyReraNumber" name="rera_number" placeholder="Enter RERA number"></div>
                                        </div>
                                    </div>

                                    <div class="property-form-card">
                                        <h3>Frequently Asked Questions</h3>
                                        <p>Add up to 25 FAQs for this project.</p>
                                        <div id="propertyFaqList">
                                            <div class="faq-item">
                                                <div class="property-field"><label>Question 1</label><input type="text" name="faqs[0][question]" placeholder="Enter question"></div>
                                                <div class="property-field"><label>Answer 1</label><input type="text" name="faqs[0][answer]" placeholder="Enter answer"></div>
                                                <button type="button" class="faq-remove-btn" style="visibility:hidden;">Remove</button>
                                            </div>
                                        </div>
                                        <button type="button" class="faq-add-btn" id="addPropertyFaqBtn">+ Add FAQ</button>
                                    </div>
                                </div>

                                <div class="property-form-actions">
                                    <button type="button" class="property-save-btn secondary">Save Draft</button>
                                    <button type="submit" class="property-save-btn">Save Property</button>
                                </div>
                            </form>
                        </div>

                    @elseif ($currentPage === 'registered-users')
                        <style>
                            /* ── Registered Users Advanced Animations ── */
                            @keyframes userPageSlideIn {
                                from { opacity: 0; transform: translateY(20px); }
                                to   { opacity: 1; transform: translateY(0); }
                            }
                            @keyframes userRowSlideIn {
                                from { opacity: 0; transform: translateX(-20px) scale(0.97); }
                                to   { opacity: 1; transform: translateX(0) scale(1); }
                            }
                            @keyframes userStatusPulsed {
                                0%, 100% { box-shadow: 0 0 0 0 rgba(46,204,113,0.5); }
                                50%       { box-shadow: 0 0 0 6px rgba(46,204,113,0); }
                            }
                            @keyframes userStatusPulseInactive {
                                0%, 100% { box-shadow: 0 0 0 0 rgba(231,76,60,0.4); }
                                50%       { box-shadow: 0 0 0 6px rgba(231,76,60,0); }
                            }
                            @keyframes addUserBtnGlow {
                                0%   { box-shadow: 0 4px 15px rgba(56,128,255,0.3), inset 0 0 0 0 rgba(56,128,255,0.1); }
                                50%  { box-shadow: 0 6px 30px rgba(56,128,255,0.6), inset 0 0 0 2px rgba(56,128,255,0.2); }
                                100% { box-shadow: 0 4px 15px rgba(56,128,255,0.3), inset 0 0 0 0 rgba(56,128,255,0.1); }
                            }
                            @keyframes userTableHeaderSlide {
                                from { opacity: 0; transform: translateY(-8px); }
                                to   { opacity: 1; transform: translateY(0); }
                            }
                            @keyframes userActionBtnRipple {
                                0% {
                                    box-shadow: 0 0 0 0 rgba(56,128,255,0.7);
                                }
                                70% {
                                    box-shadow: 0 0 0 10px rgba(56,128,255,0);
                                }
                                100% {
                                    box-shadow: 0 0 0 0 rgba(56,128,255,0);
                                }
                            }
                            @keyframes userRowColorShift {
                                0%   { background: transparent; }
                                50%  { background: rgba(56,128,255,0.03); }
                                100% { background: transparent; }
                            }

                            .users-section-wrap {
                                animation: userPageSlideIn 0.5s cubic-bezier(.22,1,.36,1) both;
                            }
                            
                            /* Header animation */
                            .amenities-header-row {
                                animation: userPageSlideIn 0.5s cubic-bezier(.22,1,.36,1) 0.1s both;
                            }

                            /* Table controls with delayed animation */
                            .table-controls {
                                animation: userPageSlideIn 0.5s cubic-bezier(.22,1,.36,1) 0.15s both;
                            }

                            /* Search input enhanced animations */
                            .control-input {
                                transition: all 0.3s cubic-bezier(.4,2,.6,1);
                                position: relative;
                            }
                            .control-input:focus {
                                border-color: #1d97db;
                                box-shadow: 0 0 0 4px rgba(29,151,219,0.15), inset 0 1px 3px rgba(29,151,219,0.1);
                                outline: none;
                                transform: scale(1.02);
                            }

                            /* Entries select hover effect */
                            .entries-select {
                                transition: all 0.2s ease;
                            }
                            .entries-select:hover {
                                border-color: #1d97db;
                                box-shadow: 0 2px 8px rgba(29,151,219,0.15);
                            }
                            .entries-select:focus {
                                border-color: #1d97db;
                                box-shadow: 0 0 0 3px rgba(29,151,219,0.1);
                            }

                            .filter-control {
                                display: flex;
                                align-items: center;
                                gap: 8px;
                                font-size: 0.86rem;
                                color: #4f5a6d;
                            }
                            .filter-btn {
                                border: 1px solid #d8e0ee;
                                background: #fff;
                                color: #333;
                                border-radius: 6px;
                                padding: 5px 10px;
                                font-size: 0.82rem;
                                cursor: pointer;
                                transition: all 0.25s cubic-bezier(.4,2,.6,1);
                            }
                            .filter-btn:hover {
                                border-color: #1d97db;
                                background: #eaf6ff;
                                transform: translateY(-2px);
                                box-shadow: 0 4px 12px rgba(29,151,219,0.2);
                            }
                            .filter-btn.active {
                                border-color: #1d97db;
                                background: #eaf6ff;
                                color: #1d97db;
                                font-weight: 600;
                                box-shadow: 0 2px 8px rgba(29,151,219,0.25);
                            }

                            /* Staggered user rows animation */
                            .user-row {
                                animation: userRowSlideIn 0.45s cubic-bezier(.22,1,.36,1) both;
                                transition: all 0.3s cubic-bezier(.4,2,.6,1);
                                position: relative;
                            }
                            @for ($i = 0; $i < count($registeredUsers ?? []); $i++)
                                .user-row:nth-child({{ $i + 1 }}) { animation-delay: {{ $i * 0.06 }}s; }
                            @endfor
                            
                            .user-row:hover {
                                background: linear-gradient(90deg, rgba(29,151,219,0.08) 0%, transparent 100%);
                                box-shadow: inset 3px 0 0 #1d97db, 0 2px 8px rgba(0,0,0,0.08);
                                transform: translateX(2px);
                            }

                            /* Data table animation */
                            .data-table-container {
                                animation: userPageSlideIn 0.5s cubic-bezier(.22,1,.36,1) 0.2s both;
                            }

                            /* Table header animation */
                            .data-table thead tr th {
                                animation: userTableHeaderSlide 0.45s cubic-bezier(.22,1,.36,1) both;
                                position: relative;
                                font-weight: 600;
                                background: linear-gradient(180deg, #f8fafc 0%, #f3f6fb 100%);
                            }
                            .data-table thead tr th:nth-child(1) { animation-delay: 0s; }
                            .data-table thead tr th:nth-child(2) { animation-delay: 0.06s; }
                            .data-table thead tr th:nth-child(3) { animation-delay: 0.12s; }
                            .data-table thead tr th:nth-child(4) { animation-delay: 0.18s; }
                            .data-table thead tr th:nth-child(5) { animation-delay: 0.24s; }
                            .data-table thead tr th:nth-child(6) { animation-delay: 0.30s; }

                            /* Action button styles */
                            .btn-action {
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                width: 36px;
                                height: 36px;
                                border-radius: 8px;
                                border: 1px solid #d1d5db;
                                background: #fff;
                                color: #444;
                                cursor: pointer;
                                transition: border-color 0.2s, background 0.2s;
                            }

                            .btn-action:hover {
                                background: #f9fafb;
                                border-color: #9ca3af;
                            }

                            /* Add User button enhanced animation */
                            .btn-add-primary {
                                position: relative;
                                overflow: hidden;
                                transition: all 0.3s cubic-bezier(.4,2,.6,1);
                                animation: addUserBtnGlow 2.5s ease-in-out infinite;
                            }

                            .btn-add-primary:hover {
                                transform: translateY(-3px) scale(1.05);
                                animation: none;
                                box-shadow: 0 8px 28px rgba(56,128,255,0.45) !important;
                            }

                            .btn-add-primary:active {
                                transform: scale(0.97);
                            }

                            .btn-add-primary span {
                                display: inline-block;
                                transition: transform 0.35s cubic-bezier(.4,2,.6,1);
                                font-weight: 700;
                            }

                            .btn-add-primary:hover span {
                                transform: rotate(135deg) scale(1.2);
                            }

                            /* Status select animations */
                            .status-select {
                                min-width: 110px;
                                border-radius: 6px;
                                border: 1px solid #ccc;
                                padding: 6px 10px;
                                color: inherit;
                                font-weight: 600;
                                appearance: none;
                                -webkit-appearance: none;
                                -moz-appearance: none;
                                background-image: linear-gradient(45deg, transparent 50%, #ffffff 50%), linear-gradient(135deg, #ffffff 50%, transparent 50%), linear-gradient(to right, #ccc, #ccc);
                                background-position: calc(100% - 20px) calc(1em + 2px), calc(100% - 15px) calc(1em + 2px), 100% 50%;
                                background-size: 5px 5px, 5px 5px, 1px 1.5em;
                                background-repeat: no-repeat;
                                padding-right: 30px;
                                outline: none;
                                transition: all 0.3s cubic-bezier(.4,2,.6,1);
                                cursor: pointer;
                            }

                            .status-verified, .status-active { 
                                background-color: #39f0ce; 
                                color: #fdfdfd;
                                border-color: #b3e6de;
                                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231db8a0' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'%3E%3C/path%3E%3C/svg%3E");
                                animation: userStatusPulsed 3s ease-in-out infinite;
                            }
                            .status-unverified, .status-inactive { 
                                background-color: #e98d03;
                                color: #ffffff;
                                border-color: #ffcbd4;
                                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23ef476f' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'%3E%3C/path%3E%3C/svg%3E");
                                animation: userStatusPulseInactive 3s ease-in-out infinite;
                            }

                            .status-select:hover {
                                filter: brightness(1.08);
                                transform: scale(1.02);
                            }

                            .status-select:focus {
                                box-shadow: 0 0 0 3px rgba(0,0,0,0.1);
                                transform: scale(1.03);
                            }

                            /* Pagination animations */
                            .pagination-footer {
                                animation: userPageSlideIn 0.5s cubic-bezier(.22,1,.36,1) 0.3s both;
                                transition: all 0.3s ease;
                            }

                            .pagination-info, .pagination-controls {
                                animation: fadeIn 0.6s ease 0.4s both;
                            }

                            @keyframes fadeIn {
                                from { opacity: 0; }
                                to { opacity: 1; }
                            }

                            /* Breadcrumb animation */
                            .breadcrumb-wrapper {
                                animation: userPageSlideIn 0.5s cubic-bezier(.22,1,.36,1) both;
                            }
                        </style>

                        <div class="users-section-wrap">
                            <div class="breadcrumb-wrapper"><div class="breadcrumb-item"></div></div>
                            <div class="amenities-header-row" style="justify-content: space-between;">
                                <h2 class="amenities-title">Registered Users</h2>
                                <button type="button" class="btn-add-primary" onclick="openAddUserModal()" style="background: #3880ff; color: white; border: none; border-radius: 6px; padding: 8px 14px; cursor: pointer; font-weight: 600; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                    <span style="font-size: 1.2em; font-weight: 700;">+</span> Add
                                </button>
                            </div>

                            <div class="table-controls" style="margin-bottom: 24px;">
                                <div class="search-control">
                                    Search: <input type="text" id="userSearch" class="control-input" placeholder="Search users...">
                                    <span style="margin-left: 12px; color: #64748b; font-size: 0.85rem;">Show</span>
                                    <select id="userEntries" class="entries-select" style="min-width: 75px;">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select> 
                                    <span style="color: #64748b; font-size: 0.85rem;">entries</span>
                                </div>
                                <div class="filter-control">
                                    Filter by:
                                    <button class="filter-btn active" id="userFilterAll" onclick="setUserFilterButton('all')">All</button>
                                    <button class="filter-btn" id="userFilterActive" onclick="setUserFilterButton('active')">Active</button>
                                    <button class="filter-btn" id="userFilterInactive" onclick="setUserFilterButton('inactive')">Inactive</button>
                                </div>
                            </div>

                            <div class="data-table-container">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Email</th>
                                            <th>Email Status</th>
                                            <th>Account Status</th>
                                            <th>Enquiry Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($registeredUsers ?? [] as $regUser)
                                            <tr class="user-row" data-id="{{ $regUser->id }}" data-first-name="{{ $regUser->first_name ?? $regUser->name ?? '' }}" data-last-name="{{ $regUser->last_name ?? '' }}" data-email="{{ $regUser->email }}" data-phone="{{ $regUser->phone ?? $regUser->contact_number ?? '' }}" data-status="{{ $regUser->status ?? '' }}" data-email-status="{{ $regUser->email_verified_at ? 'verified' : 'unverified' }}" data-created="{{ $regUser->created_at }}">
                                                <td style="font-weight: 600;">{{ $regUser->first_name ?? $regUser->name }}</td>
                                                <td>{{ $regUser->email }}</td>
                                                <td>
                                                    <form method="POST" action="{{ route('admin.users.updateEmailVerification', ['user' => $user, 'targetUser' => $regUser->id]) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <select name="email_status" class="status-select" onchange="this.form.submit()">
                                                            <option value="verified" {{ $regUser->email_verified_at ? 'selected' : '' }}>Verified</option>
                                                            <option value="unverified" {{ $regUser->email_verified_at ? '' : 'selected' }}>Unverified</option>
                                                        </select>
                                                    </form>
                                                </td>
                                                <td>
                                                    @if (Schema::hasColumn('users', 'status'))
                                                        <form method="POST" action="{{ route('admin.users.updateStatus', ['user' => $user, 'targetUser' => $regUser->id]) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <select name="account_status" class="status-select" onchange="this.form.submit()">
                                                                <option value="1" {{ $regUser->status == 1 ? 'selected' : '' }}>Active</option>
                                                                <option value="0" {{ $regUser->status == 1 ? '' : 'selected' }}>Inactive</option>
                                                            </select>
                                                        </form>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ optional($regUser->created_at)->format('d M Y, H:i') ?? '-' }}</td>
                                                <td>
                                                    <div class="actions">
                                                        <button type="button" class="btn-action btn-edit" title="Edit" onclick="editUserModal({{ $regUser->id }})">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                        </button>
                                                        <form method="POST" action="{{ route('admin.users.destroy', ['user' => $user, 'targetUser' => $regUser->id]) }}" style="display:inline;" onsubmit="return confirm('Remove this user?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn-action btn-delete" title="Delete">
                                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="pagination-footer">
                                    <div class="pagination-info" id="userPageInfo">Showing 0 to 0 of 0 entries</div>
                                    <div class="pagination-controls" id="userPagination"></div>
                                </div>
                            </div>

                            <div id="userModal" class="modal-backdrop" onclick="if(event.target === this) closeUserModal()">
                                <div class="modal">
                                    <div class="modal-header">
                                        <div>
                                            <h3 class="modal-title" id="userModalTitle">Add Registered User</h3>
                                            <p class="modal-subtitle" id="userModalSubtitle">Fill in the details to manage the user account.</p>
                                        </div>
                                        <button type="button" class="modal-close-btn" onclick="closeUserModal()">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="userModalForm" action="{{ route('admin.users.store', $user) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" id="userModalMethod" value="POST">
                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label class="form-label">First Name <span style="color: #ef476f;">*</span></label>
                                                    <input type="text" name="first_name" id="userFirstName" class="form-control" placeholder="John" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Last Name <span style="color: #ef476f;"></span></label>
                                                    <input type="text" name="last_name" id="userLastName" class="form-control" placeholder="Doe" >
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Email Address <span style="color: #ef476f;">*</span></label>
                                                <input type="email" name="email" id="userEmail" class="form-control" placeholder="john.doe@example.com" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Phone Number <span style="color: #ef476f;">*</span></label>
                                                <input type="text" name="phone" id="userPhone" class="form-control" placeholder="9876543210" required>
                                            </div>
                                            <div id="userAccountSettings" style="display: none;">
                                                <div class="form-row">
                                                    <div class="form-group">
                                                        <label class="form-label">Account Status</label>
                                                        <select name="account_status" id="userAccountStatus" class="form-control">
                                                            <option value="1">Active</option>
                                                            <option value="0">Inactive</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Email Verified</label>
                                                        <select name="email_status" id="userEmailStatus" class="form-control">
                                                            <option value="verified">Verified</option>
                                                            <option value="unverified">Unverified</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn-cancel" type="button" onclick="closeUserModal()">Cancel</button>
                                        <button type="submit" form="userModalForm" class="btn-primary" id="saveUserBtn">Save User</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @else
                        <section class="section-card">
                            <h2>{{ $currentItem['label'] }}</h2>
                            <p>This page is now connected and opens when you click the dropdown item in the sidebar. We can build the full {{ strtolower($currentItem['label']) }} management screen here next.</p>
                        </section>
                    @endif
                </main>
            </section>
        </div>

        <button class="scroll-button" type="button" aria-label="Scroll to top" id="scrollButton">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 8.2 6.7 13.5 5.3 12.1 12 5.4l6.7 6.7-1.4 1.4z"/>
            </svg>
        </button>

        <script>
            const scrollButton = document.getElementById('scrollButton');

            const toggleScrollButton = () => {
                scrollButton.classList.toggle('visible', window.scrollY > 120);
            };

            scrollButton.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            window.addEventListener('scroll', toggleScrollButton, { passive: true });
            toggleScrollButton();

            // Amenities Interactions
            const amenitySearch = document.getElementById('amenitySearch');
            const amenityEntries = document.getElementById('amenityEntries');
            const amenityPageInfo = document.getElementById('amenityPageInfo');
            const amenityPagination = document.getElementById('amenityPagination');
            
            if (amenitySearch && amenityEntries) {
                let currentPage = 1;
                let filteredRows = [];
                
                const filterAmenities = () => {
                    const term = amenitySearch.value.toLowerCase();
                    const availableRows = Array.from(document.querySelectorAll('.amenity-row'));
                    
                    filteredRows = availableRows.filter(row => {
                        const name = row.dataset.name ? row.dataset.name.toLowerCase() : '';
                        const countryName = row.dataset.countryName ? row.dataset.countryName.toLowerCase() : '';
                        return name.includes(term) || countryName.includes(term);
                    });
                    
                    availableRows.forEach(row => row.style.display = 'none');
                    
                    const limit = parseInt(amenityEntries.value, 10);
                    const totalPages = Math.ceil(filteredRows.length / limit) || 1;
                    if (currentPage > totalPages) currentPage = totalPages;
                    
                    const startIndex = (currentPage - 1) * limit;
                    const endIndex = startIndex + limit;
                    
                    const paginatedRows = filteredRows.slice(startIndex, endIndex);
                    paginatedRows.forEach((row, idx) => {
                        row.style.display = 'table-row';
                        row.style.animationDelay = `${idx * 0.045}s`;
                    });
                    
                    // Update Info
                    if (amenityPageInfo) {
                        const dispStart = filteredRows.length > 0 ? startIndex + 1 : 0;
                        const dispEnd = Math.min(endIndex, filteredRows.length);
                        amenityPageInfo.textContent = `Showing ${dispStart} to ${dispEnd} of ${filteredRows.length} entries`;
                    }
                    
                    // Update Pagination Controls
                    if (amenityPagination) {
                        amenityPagination.innerHTML = '';
                        
                        const renderBtn = (label, disabled, active, pageNum) => {
                            const btn = document.createElement('button');
                            btn.className = `page-btn ${active ? 'active' : ''}`;
                            btn.textContent = label;
                            btn.disabled = disabled;
                            if (!disabled && !active) {
                                btn.onclick = () => {
                                    currentPage = pageNum;
                                    filterAmenities();
                                };
                            }
                            return btn;
                        }
                        
                        amenityPagination.appendChild(renderBtn('Previous', currentPage === 1, false, currentPage - 1));
                        
                        for (let p = 1; p <= totalPages; p++) {
                            if (p === 1 || p === totalPages || (p >= currentPage - 1 && p <= currentPage + 1)) {
                                amenityPagination.appendChild(renderBtn(p, false, p === currentPage, p));
                            } else if (p === currentPage - 2 || p === currentPage + 2) {
                                const dots = document.createElement('span');
                                dots.style.padding = '0 8px';
                                dots.textContent = '...';
                                amenityPagination.appendChild(dots);
                            }
                        }
                        
                        amenityPagination.appendChild(renderBtn('Next', currentPage === totalPages, false, currentPage + 1));
                    }
                };
                
                amenitySearch.addEventListener('input', () => { currentPage = 1; filterAmenities(); });
                amenityEntries.addEventListener('change', () => { currentPage = 1; filterAmenities(); });
                
                // Trigger once on load
                filterAmenities();
            }

            function closeAmenityModal() {
                const modal = document.getElementById('addAmenityModal');
                modal.classList.remove('is-open');
                modal.style.display = 'none';
            }

            function editAmenity(id) {
                const row = document.querySelector(`.amenity-row[data-id="${id}"]`);
                if (!row) return;

                const name = row.dataset.name;
                const icon = row.dataset.icon;
                const status = row.dataset.status;
                const serial = row.dataset.serial;

                document.getElementById('amenityModalTitle').textContent = 'Edit Property Amenity';
                document.getElementById('amenityModalSubtitle').textContent = 'Update the details for this amenity.';
                
                document.getElementById('amenityName').value = name;
                document.getElementsByName('icon')[0].value = icon;
                document.getElementById('amenityStatus').value = status;
                document.getElementById('amenitySerial').value = serial;

                const form = document.getElementById('addAmenityForm');
                const updateUrlTemplate = "{{ route('admin.amenities.update', ['user' => $user->id, 'amenity' => '__amenity_id__']) }}";
                form.action = updateUrlTemplate.replace('__amenity_id__', id);
                document.getElementById('amenityMethod').value = 'PUT';
                document.getElementById('saveAmenityBtn').textContent = 'Save';
                
                const modal = document.getElementById('addAmenityModal');
                modal.style.display = 'grid';
                modal.classList.add('is-open');
            }

            function openAddAmenityModal() {
                document.getElementById('amenityModalTitle').textContent = 'Add Property Amenity';
                document.getElementById('amenityModalSubtitle').textContent = 'Create a new amenity to display on property listings.';
                
                document.getElementById('amenityName').value = '';
                document.getElementsByName('icon')[0].value = '';
                document.getElementById('amenityStatus').value = 'Active';
                document.getElementById('amenitySerial').value = '';

                const form = document.getElementById('addAmenityForm');
                const storeUrl = "{{ route('admin.amenities.store', ['user' => $user->id]) }}";
                form.action = storeUrl;
                document.getElementById('amenityMethod').value = 'POST';
                document.getElementById('saveAmenityBtn').textContent = 'Save';
                
                const modal = document.getElementById('addAmenityModal');
                modal.style.display = 'grid';
                modal.classList.add('is-open');
            }
            // Categories Interactions
            const categorySearch = document.getElementById('categorySearch');
            const categoryEntries = document.getElementById('categoryEntries');
            const categoryPageInfo = document.getElementById('categoryPageInfo');
            const categoryPagination = document.getElementById('categoryPagination');
            
            if (categorySearch && categoryEntries) {
                let currentPage = 1;
                let filteredRows = [];
                
                const filterCategories = () => {
                    const term = categorySearch.value.toLowerCase();
                    const availableRows = Array.from(document.querySelectorAll('.category-row'));
                    
                    filteredRows = availableRows.filter(row => {
                        const name = row.dataset.name ? row.dataset.name.toLowerCase() : '';
                        const type = row.dataset.type ? row.dataset.type.toLowerCase() : '';
                        return name.includes(term) || type.includes(term);
                    });
                    
                    availableRows.forEach(row => row.style.display = 'none');
                    
                    const limit = parseInt(categoryEntries.value, 10);
                    const totalPages = Math.ceil(filteredRows.length / limit) || 1;
                    if (currentPage > totalPages) currentPage = totalPages;
                    
                    const startIndex = (currentPage - 1) * limit;
                    const endIndex = startIndex + limit;
                    
                    const paginatedRows = filteredRows.slice(startIndex, endIndex);
                    paginatedRows.forEach((row, idx) => {
                        row.style.display = 'table-row';
                        row.style.animationDelay = `${idx * 0.045}s`;
                    });
                    
                    if (categoryPageInfo) {
                        const dispStart = filteredRows.length > 0 ? startIndex + 1 : 0;
                        const dispEnd = Math.min(endIndex, filteredRows.length);
                        categoryPageInfo.textContent = `Showing ${dispStart} to ${dispEnd} of ${filteredRows.length} entries`;
                    }
                    
                    if (categoryPagination) {
                        categoryPagination.innerHTML = '';
                        
                        const renderBtn = (label, disabled, active, pageNum) => {
                            const btn = document.createElement('button');
                            btn.className = `page-btn ${active ? 'active' : ''}`;
                            btn.textContent = label;
                            btn.disabled = disabled;
                            if (!disabled && !active) {
                                btn.onclick = () => {
                                    currentPage = pageNum;
                                    filterCategories();
                                };
                            }
                            return btn;
                        }
                        
                        categoryPagination.appendChild(renderBtn('Previous', currentPage === 1, false, currentPage - 1));
                        
                        for (let p = 1; p <= totalPages; p++) {
                            if (p === 1 || p === totalPages || (p >= currentPage - 1 && p <= currentPage + 1)) {
                                categoryPagination.appendChild(renderBtn(p, false, p === currentPage, p));
                            } else if (p === currentPage - 2 || p === currentPage + 2) {
                                const dots = document.createElement('span');
                                dots.style.padding = '0 8px';
                                dots.textContent = '...';
                                categoryPagination.appendChild(dots);
                            }
                        }
                        
                        categoryPagination.appendChild(renderBtn('Next', currentPage === totalPages, false, currentPage + 1));
                    }
                };
                
                categorySearch.addEventListener('input', () => { currentPage = 1; filterCategories(); });
                categoryEntries.addEventListener('change', () => { currentPage = 1; filterCategories(); });
                
                filterCategories();
            }

            function closeCategoryModal() {
                document.getElementById('addCategoryModal').style.display = 'none';
            }

            function editCategory(id) {
                const row = document.querySelector(`.category-row[data-id="${id}"]`);
                if (!row) return;

                const name = row.dataset.name;
                const type = row.dataset.type;
                const status = row.dataset.status;
                const serial = row.dataset.serial;

                document.getElementById('categoryModalTitle').textContent = 'Edit Property Category';
                document.getElementById('categoryModalSubtitle').textContent = 'Update the details for this category.';
                
                document.getElementById('categoryName').value = name;
                document.getElementById('categoryType').value = type;
                document.getElementById('categoryStatus').value = status;
                document.getElementById('categorySerial').value = serial;

                const form = document.getElementById('addCategoryForm');
                const baseUrl = "{{ route('admin.dashboard', ['user' => $user->id]) }}".split('/dashboard')[0];
                form.action = `${baseUrl}/dashboard/{{ $user->id }}/categories/${id}`;
                document.getElementById('categoryMethod').value = 'PUT';
                
                document.getElementById('addCategoryModal').style.display = 'grid';
            }

            function openAddCategoryModal() {
                document.getElementById('categoryModalTitle').textContent = 'Add Property Category';
                document.getElementById('categoryModalSubtitle').textContent = 'Create a new category to organize properties.';
                
                document.getElementById('categoryName').value = '';
                document.getElementById('categoryType').value = '';
                document.getElementById('categoryStatus').value = '';
                document.getElementById('categorySerial').value = '';

                const form = document.getElementById('addCategoryForm');
                const storeUrl = "{{ route('admin.categories.store', ['user' => $user->id]) }}";
                form.action = storeUrl;
                document.getElementById('categoryMethod').value = 'POST';
                
                document.getElementById('addCategoryModal').style.display = 'grid';
            }

            const syncSelectOptions = (select, matcher) => {
                if (!select) return;

                Array.from(select.options).forEach((option, index) => {
                    if (index === 0) {
                        option.hidden = false;
                        return;
                    }

                    option.hidden = !matcher(option);
                });

                if (select.selectedOptions[0]?.hidden) {
                    select.value = '';
                }
            };

            // Countries Interactions
            const countrySearch = document.getElementById('countrySearch');
            const countryEntries = document.getElementById('countryEntries');
            const countryPageInfo = document.getElementById('countryPageInfo');
            const countryPagination = document.getElementById('countryPagination');

            if (countrySearch && countryEntries) {
                let currentPage = 1;
                let filteredRows = [];

                const filterCountries = () => {
                    const term = countrySearch.value.toLowerCase();
                    const availableRows = Array.from(document.querySelectorAll('.country-row'));

                    filteredRows = availableRows.filter(row => {
                        const name = row.dataset.name ? row.dataset.name.toLowerCase() : '';
                        return name.includes(term);
                    });

                    availableRows.forEach(row => row.style.display = 'none');

                    const limit = parseInt(countryEntries.value, 10);
                    const totalPages = Math.ceil(filteredRows.length / limit) || 1;
                    if (currentPage > totalPages) currentPage = totalPages;

                    const startIndex = (currentPage - 1) * limit;
                    const endIndex = startIndex + limit;

                    const paginatedRows = filteredRows.slice(startIndex, endIndex);
                    paginatedRows.forEach((row, idx) => {
                        row.style.display = 'table-row';
                        row.style.animationDelay = `${idx * 0.045}s`;
                    });

                    if (countryPageInfo) {
                        const dispStart = filteredRows.length > 0 ? startIndex + 1 : 0;
                        const dispEnd = Math.min(endIndex, filteredRows.length);
                        countryPageInfo.textContent = `Showing ${dispStart} to ${dispEnd} of ${filteredRows.length} entries`;
                    }

                    if (countryPagination) {
                        countryPagination.innerHTML = '';

                        const renderBtn = (label, disabled, active, pageNum) => {
                            const btn = document.createElement('button');
                            btn.className = `page-btn ${active ? 'active' : ''}`;
                            btn.textContent = label;
                            btn.disabled = disabled;
                            if (!disabled && !active) {
                                btn.onclick = () => {
                                    currentPage = pageNum;
                                    filterCountries();
                                };
                            }
                            return btn;
                        };

                        countryPagination.appendChild(renderBtn('Previous', currentPage === 1, false, currentPage - 1));

                        for (let p = 1; p <= totalPages; p++) {
                            if (p === 1 || p === totalPages || (p >= currentPage - 1 && p <= currentPage + 1)) {
                                countryPagination.appendChild(renderBtn(p, false, p === currentPage, p));
                            } else if (p === currentPage - 2 || p === currentPage + 2) {
                                const dots = document.createElement('span');
                                dots.style.padding = '0 8px';
                                dots.textContent = '...';
                                countryPagination.appendChild(dots);
                            }
                        }

                        countryPagination.appendChild(renderBtn('Next', currentPage === totalPages, false, currentPage + 1));
                    }
                };

                countrySearch.addEventListener('input', () => { currentPage = 1; filterCountries(); });
                countryEntries.addEventListener('change', () => { currentPage = 1; filterCountries(); });

                filterCountries();
            }

            function closeCountryModal() {
                document.getElementById('addCountryModal').style.display = 'none';
            }

            function editCountry(id) {
                const row = document.querySelector(`.country-row[data-id="${id}"]`);
                if (!row) return;

                const name = row.dataset.name;

                document.getElementById('countryModalTitle').textContent = 'Edit Country';
                document.getElementById('countryName').value = name;

                const form = document.getElementById('addCountryForm');
                const baseUrl = "{{ route('admin.dashboard', ['user' => $user->id]) }}".split('/dashboard')[0];
                form.action = `${baseUrl}/dashboard/{{ $user->id }}/countries/${id}`;
                document.getElementById('countryMethod').value = 'PUT';

                document.getElementById('addCountryModal').style.display = 'grid';
            }

            function openAddCountryModal() {
                document.getElementById('countryModalTitle').textContent = 'Add Country';
                document.getElementById('countryName').value = '';

                const form = document.getElementById('addCountryForm');
                const storeUrl = "{{ route('admin.countries.store', ['user' => $user->id]) }}";
                form.action = storeUrl;
                document.getElementById('countryMethod').value = 'POST';

                document.getElementById('addCountryModal').style.display = 'grid';
            }

            // States Interactions
            const stateSearch = document.getElementById('stateSearch');
            const stateEntries = document.getElementById('stateEntries');
            const statePageInfo = document.getElementById('statePageInfo');
            const statePagination = document.getElementById('statePagination');

            if (stateSearch && stateEntries) {
                let currentPage = 1;
                let filteredRows = [];

                const filterStates = () => {
                    const term = stateSearch.value.toLowerCase();
                    const availableRows = Array.from(document.querySelectorAll('.state-row'));

                    filteredRows = availableRows.filter(row => {
                        const name = row.dataset.name ? row.dataset.name.toLowerCase() : '';
                        return name.includes(term);
                    });

                    availableRows.forEach(row => row.style.display = 'none');

                    const limit = parseInt(stateEntries.value, 10);
                    const totalPages = Math.ceil(filteredRows.length / limit) || 1;
                    if (currentPage > totalPages) currentPage = totalPages;

                    const startIndex = (currentPage - 1) * limit;
                    const endIndex = startIndex + limit;

                    const paginatedRows = filteredRows.slice(startIndex, endIndex);
                    paginatedRows.forEach((row, idx) => {
                        row.style.display = 'table-row';
                        row.style.animationDelay = `${idx * 0.045}s`;
                    });

                    // Update Info
                    const start = filteredRows.length > 0 ? startIndex + 1 : 0;
                    const end = Math.min(endIndex, filteredRows.length);
                    statePageInfo.textContent = `Showing ${start} to ${end} of ${filteredRows.length} entries`;

                    // Update Pagination
                    statePagination.innerHTML = '';

                    const renderBtn = (label, disabled, active, page) => {
                        const btn = document.createElement('button');
                        btn.textContent = label;
                        btn.disabled = disabled;
                        btn.className = active ? 'page-btn active' : 'page-btn';
                        if (!disabled && !active) {
                            btn.onclick = () => { currentPage = page; filterStates(); };
                        }
                        return btn;
                    };

                    statePagination.appendChild(renderBtn('Previous', currentPage === 1, false, currentPage - 1));

                    for (let p = 1; p <= totalPages; p++) {
                        if (p === 1 || p === totalPages || (p >= currentPage - 1 && p <= currentPage + 1)) {
                            statePagination.appendChild(renderBtn(p, false, p === currentPage, p));
                        } else if (p === currentPage - 2 || p === currentPage + 2) {
                            const dots = document.createElement('span');
                            dots.style.padding = '0 8px';
                            dots.textContent = '...';
                            statePagination.appendChild(dots);
                        }
                    }

                    statePagination.appendChild(renderBtn('Next', currentPage === totalPages, false, currentPage + 1));
                };

                stateSearch.addEventListener('input', () => { currentPage = 1; filterStates(); });
                stateEntries.addEventListener('change', () => { currentPage = 1; filterStates(); });

                filterStates();
            }

            function closeStateModal() {
                document.getElementById('addStateModal').style.display = 'none';
            }

            function editState(id) {
                const row = document.querySelector(`.state-row[data-id="${id}"]`);
                if (!row) return;

                const name = row.dataset.name;
                const country = row.dataset.country;

                document.getElementById('stateModalTitle').textContent = 'Edit State';
                document.getElementById('stateName').value = name;
                document.getElementById('stateCountry').value = country;

                const form = document.getElementById('addStateForm');
                const baseUrl = "{{ route('admin.dashboard', ['user' => $user->id]) }}".split('/dashboard')[0];
                form.action = `${baseUrl}/dashboard/{{ $user->id }}/states/${id}`;
                document.getElementById('stateMethod').value = 'PUT';

                document.getElementById('addStateModal').style.display = 'grid';
            }

            function openAddStateModal() {
                document.getElementById('stateModalTitle').textContent = 'Add State';
                document.getElementById('stateName').value = '';
                document.getElementById('stateCountry').value = '';

                const form = document.getElementById('addStateForm');
                const storeUrl = "{{ route('admin.states.store', ['user' => $user->id]) }}";
                form.action = storeUrl;
                document.getElementById('stateMethod').value = 'POST';

                document.getElementById('addStateModal').style.display = 'grid';
            }

            // Cities Interactions
            const citySearch = document.getElementById('citySearch');
            const cityEntries = document.getElementById('cityEntries');
            const cityPageInfo = document.getElementById('cityPageInfo');
            const cityPagination = document.getElementById('cityPagination');

            if (citySearch && cityEntries) {
                let currentPage = 1;
                let filteredRows = [];

                const filterCities = () => {
                    const term = citySearch.value.toLowerCase();
                    const availableRows = Array.from(document.querySelectorAll('.city-row'));

                    filteredRows = availableRows.filter(row => {
                        const name = row.dataset.name ? row.dataset.name.toLowerCase() : '';
                        const stateName = row.dataset.stateName ? row.dataset.stateName.toLowerCase() : '';
                        const countryName = row.dataset.countryName ? row.dataset.countryName.toLowerCase() : '';
                        return name.includes(term) || stateName.includes(term) || countryName.includes(term);
                    });

                    availableRows.forEach(row => row.style.display = 'none');

                    const limit = parseInt(cityEntries.value, 10);
                    const totalPages = Math.ceil(filteredRows.length / limit) || 1;
                    if (currentPage > totalPages) currentPage = totalPages;

                    const startIndex = (currentPage - 1) * limit;
                    const endIndex = startIndex + limit;

                    const paginatedRows = filteredRows.slice(startIndex, endIndex);
                    paginatedRows.forEach((row, idx) => {
                        row.style.display = 'table-row';
                        row.style.animationDelay = `${idx * 0.045}s`;
                    });

                    // Update Info
                    const start = filteredRows.length > 0 ? startIndex + 1 : 0;
                    const end = Math.min(endIndex, filteredRows.length);
                    cityPageInfo.textContent = `Showing ${start} to ${end} of ${filteredRows.length} entries`;

                    // Update Pagination
                    cityPagination.innerHTML = '';

                    const renderBtn = (label, disabled, active, page) => {
                        const btn = document.createElement('button');
                        btn.textContent = label;
                        btn.disabled = disabled;
                        btn.className = active ? 'page-btn active' : 'page-btn';
                        if (!disabled && !active) {
                            btn.onclick = () => { currentPage = page; filterCities(); };
                        }
                        return btn;
                    };

                    cityPagination.appendChild(renderBtn('Previous', currentPage === 1, false, currentPage - 1));

                    for (let p = 1; p <= totalPages; p++) {
                        if (p === 1 || p === totalPages || (p >= currentPage - 1 && p <= currentPage + 1)) {
                            cityPagination.appendChild(renderBtn(p, false, p === currentPage, p));
                        } else if (p === currentPage - 2 || p === currentPage + 2) {
                            const dots = document.createElement('span');
                            dots.style.padding = '0 8px';
                            dots.textContent = '...';
                            cityPagination.appendChild(dots);
                        }
                    }

                    cityPagination.appendChild(renderBtn('Next', currentPage === totalPages, false, currentPage + 1));
                };

                citySearch.addEventListener('input', () => { currentPage = 1; filterCities(); });
                cityEntries.addEventListener('change', () => { currentPage = 1; filterCities(); });

                filterCities();
            }

            function closeCityModal() {
                document.getElementById('addCityModal').style.display = 'none';
            }

            function editCity(id) {
                const row = document.querySelector(`.city-row[data-id="${id}"]`);
                if (!row) return;

                const name = row.dataset.name;
                const country = row.dataset.country;
                const state = row.dataset.state;

                document.getElementById('cityModalTitle').textContent = 'Edit City';
                document.getElementById('cityName').value = name;
                document.getElementById('cityCountry').value = country;
                syncSelectOptions(document.getElementById('cityState'), option => !country || option.dataset.country === country);
                document.getElementById('cityState').value = state;

                const form = document.getElementById('addCityForm');
                const baseUrl = "{{ route('admin.dashboard', ['user' => $user->id]) }}".split('/dashboard')[0];
                form.action = `${baseUrl}/dashboard/{{ $user->id }}/cities/${id}`;
                document.getElementById('cityMethod').value = 'PUT';

                document.getElementById('addCityModal').style.display = 'grid';
            }

            function openAddCityModal() {
                document.getElementById('cityModalTitle').textContent = 'Add City';
                document.getElementById('cityName').value = '';
                document.getElementById('cityCountry').value = '';
                syncSelectOptions(document.getElementById('cityState'), () => true);
                document.getElementById('cityState').value = '';

                const form = document.getElementById('addCityForm');
                const storeUrl = "{{ route('admin.cities.store', ['user' => $user->id]) }}";
                form.action = storeUrl;
                document.getElementById('cityMethod').value = 'POST';

                document.getElementById('addCityModal').style.display = 'grid';
            }

            // Property Places Interactions
            const propertyPlaceSearch = document.getElementById('propertyPlaceSearch');
            const propertyPlaceEntries = document.getElementById('propertyPlaceEntries');
            const propertyPlacePageInfo = document.getElementById('propertyPlacePageInfo');
            const propertyPlacePagination = document.getElementById('propertyPlacePagination');

            if (propertyPlaceSearch && propertyPlaceEntries) {
                let currentPage = 1;
                let filteredRows = [];

                const filterPropertyPlaces = () => {
                    const term = propertyPlaceSearch.value.toLowerCase();
                    const availableRows = Array.from(document.querySelectorAll('.property-place-row'));

                    filteredRows = availableRows.filter(row => {
                        const name = row.dataset.name ? row.dataset.name.toLowerCase() : '';
                        const cells = row.querySelectorAll('td');
                        const cityName = cells[2]?.textContent?.toLowerCase() || '';
                        const stateName = cells[3]?.textContent?.toLowerCase() || '';
                        const countryName = cells[4]?.textContent?.toLowerCase() || '';
                        return name.includes(term) || cityName.includes(term) || stateName.includes(term) || countryName.includes(term);
                    });

                    availableRows.forEach(row => row.style.display = 'none');

                    const limit = parseInt(propertyPlaceEntries.value, 10);
                    const totalPages = Math.ceil(filteredRows.length / limit) || 1;
                    if (currentPage > totalPages) currentPage = totalPages;

                    const startIndex = (currentPage - 1) * limit;
                    const endIndex = startIndex + limit;

                    const paginatedRows = filteredRows.slice(startIndex, endIndex);
                    paginatedRows.forEach((row, idx) => {
                        row.style.display = 'table-row';
                        row.style.animationDelay = `${idx * 0.045}s`;
                    });

                    // Update Info
                    const start = filteredRows.length > 0 ? startIndex + 1 : 0;
                    const end = Math.min(endIndex, filteredRows.length);
                    propertyPlacePageInfo.textContent = `Showing ${start} to ${end} of ${filteredRows.length} entries`;

                    // Update Pagination
                    propertyPlacePagination.innerHTML = '';

                    const renderBtn = (label, disabled, active, page) => {
                        const btn = document.createElement('button');
                        btn.textContent = label;
                        btn.disabled = disabled;
                        btn.className = active ? 'page-btn active' : 'page-btn';
                        if (!disabled && !active) {
                            btn.onclick = () => { currentPage = page; filterPropertyPlaces(); };
                        }
                        return btn;
                    };

                    propertyPlacePagination.appendChild(renderBtn('Previous', currentPage === 1, false, currentPage - 1));

                    for (let p = 1; p <= totalPages; p++) {
                        if (p === 1 || p === totalPages || (p >= currentPage - 1 && p <= currentPage + 1)) {
                            propertyPlacePagination.appendChild(renderBtn(p, false, p === currentPage, p));
                        } else if (p === currentPage - 2 || p === currentPage + 2) {
                            const dots = document.createElement('span');
                            dots.style.padding = '0 8px';
                            dots.textContent = '...';
                            propertyPlacePagination.appendChild(dots);
                        }
                    }

                    propertyPlacePagination.appendChild(renderBtn('Next', currentPage === totalPages, false, currentPage + 1));
                };

                propertyPlaceSearch.addEventListener('input', () => { currentPage = 1; filterPropertyPlaces(); });
                propertyPlaceEntries.addEventListener('change', () => { currentPage = 1; filterPropertyPlaces(); });

                filterPropertyPlaces();
            }

            function closePropertyPlaceModal() {
                document.getElementById('addPropertyPlaceModal').style.display = 'none';
            }

            function editPropertyPlace(id) {
                const row = document.querySelector(`.property-place-row[data-id="${id}"]`);
                if (!row) return;

                const name = row.dataset.name;
                const city = row.dataset.city;
                const state = row.dataset.state;
                const country = row.dataset.country;

                document.getElementById('propertyPlaceModalTitle').textContent = 'Edit Property Place';
                document.getElementById('propertyPlaceName').value = name;
                document.getElementById('propertyPlaceCountry').value = country;
                syncSelectOptions(document.getElementById('propertyPlaceState'), option => !country || option.dataset.country === country);
                document.getElementById('propertyPlaceState').value = state;
                syncSelectOptions(document.getElementById('propertyPlaceCity'), option => {
                    const countryMatch = !country || option.dataset.country === country;
                    const stateMatch = !state || option.dataset.state === state;
                    return countryMatch && stateMatch;
                });
                document.getElementById('propertyPlaceCity').value = city;

                const form = document.getElementById('addPropertyPlaceForm');
                const baseUrl = "{{ route('admin.dashboard', ['user' => $user->id]) }}".split('/dashboard')[0];
                form.action = `${baseUrl}/dashboard/{{ $user->id }}/property-places/${id}`;
                document.getElementById('propertyPlaceMethod').value = 'PUT';

                document.getElementById('addPropertyPlaceModal').style.display = 'grid';
            }

            function openAddPropertyPlaceModal() {
                document.getElementById('propertyPlaceModalTitle').textContent = 'Add Property Place';
                document.getElementById('propertyPlaceName').value = '';
                document.getElementById('propertyPlaceCity').value = '';
                document.getElementById('propertyPlaceState').value = '';
                document.getElementById('propertyPlaceCountry').value = '';
                document.getElementById('propertyPlaceImage').value = '';
                syncSelectOptions(document.getElementById('propertyPlaceState'), () => true);
                syncSelectOptions(document.getElementById('propertyPlaceCity'), () => true);

                const form = document.getElementById('addPropertyPlaceForm');
                const storeUrl = "{{ route('admin.property-places.store', ['user' => $user->id]) }}";
                form.action = storeUrl;
                document.getElementById('propertyPlaceMethod').value = 'POST';

                document.getElementById('addPropertyPlaceModal').style.display = 'grid';
            }

            const cityCountry = document.getElementById('cityCountry');
            const cityState = document.getElementById('cityState');

            if (cityCountry && cityState) {
                const syncCityStates = () => {
                    const countryId = cityCountry.value;
                    syncSelectOptions(cityState, option => !countryId || option.dataset.country === countryId);
                };

                cityCountry.addEventListener('change', syncCityStates);
                syncCityStates();
            }

            const propertyPlaceCountry = document.getElementById('propertyPlaceCountry');
            const propertyPlaceState = document.getElementById('propertyPlaceState');
            const propertyPlaceCity = document.getElementById('propertyPlaceCity');

            if (propertyPlaceCountry && propertyPlaceState && propertyPlaceCity) {
                const syncPropertyPlaceStates = () => {
                    const countryId = propertyPlaceCountry.value;
                    syncSelectOptions(propertyPlaceState, option => !countryId || option.dataset.country === countryId);
                    syncPropertyPlaceCities();
                };

                const syncPropertyPlaceCities = () => {
                    const countryId = propertyPlaceCountry.value;
                    const stateId = propertyPlaceState.value;

                    syncSelectOptions(propertyPlaceCity, option => {
                        const countryMatch = !countryId || option.dataset.country === countryId;
                        const stateMatch = !stateId || option.dataset.state === stateId;
                        return countryMatch && stateMatch;
                    });
                };

                propertyPlaceCountry.addEventListener('change', syncPropertyPlaceStates);
                propertyPlaceState.addEventListener('change', syncPropertyPlaceCities);
                syncPropertyPlaceStates();
            }

            // Manage Properties Interactions
            const managePropertyTypeFilter = document.getElementById('managePropertyTypeFilter');
            const managePropertyTitleSearch = document.getElementById('managePropertyTitleSearch');
            const managePropertyEntries = document.getElementById('managePropertyEntries');
            const managePropertyPageInfo = document.getElementById('managePropertyPageInfo');
            const managePropertyPagination = document.getElementById('managePropertyPagination');

            if (managePropertyTypeFilter && managePropertyTitleSearch && managePropertyEntries) {
                let currentPage = 1;
                let filteredRows = [];

                const filterManageProperties = () => {
                    const selectedType = managePropertyTypeFilter.value;
                    const titleTerm = managePropertyTitleSearch.value.trim().toLowerCase();
                    const availableRows = Array.from(document.querySelectorAll('.manage-property-row'));

                    filteredRows = availableRows.filter((row) => {
                        const rowType = row.dataset.type || '';
                        const rowTitle = row.dataset.title || '';
                        const matchType = selectedType === 'all' || rowType === selectedType;
                        const matchTitle = titleTerm === '' || rowTitle.includes(titleTerm);
                        return matchType && matchTitle;
                    });

                    availableRows.forEach(row => row.style.display = 'none');

                    const limit = parseInt(managePropertyEntries.value, 10);
                    const totalPages = Math.ceil(filteredRows.length / limit) || 1;
                    if (currentPage > totalPages) currentPage = totalPages;

                    const startIndex = (currentPage - 1) * limit;
                    const endIndex = startIndex + limit;

                    const paginatedRows = filteredRows.slice(startIndex, endIndex);
                    paginatedRows.forEach((row, idx) => {
                        row.style.display = 'table-row';
                        row.style.animationDelay = `${idx * 0.045}s`;
                    });

                    if (managePropertyPageInfo) {
                        const start = filteredRows.length > 0 ? startIndex + 1 : 0;
                        const end = Math.min(endIndex, filteredRows.length);
                        managePropertyPageInfo.textContent = `Showing ${start} to ${end} of ${filteredRows.length} entries`;
                    }

                    if (managePropertyPagination) {
                        managePropertyPagination.innerHTML = '';
                        
                        const renderBtn = (label, disabled, active, pageNum) => {
                            const btn = document.createElement('button');
                            btn.className = `page-btn ${active ? 'active' : ''}`;
                            btn.textContent = label;
                            btn.disabled = disabled;
                            if (!disabled && !active) {
                                btn.onclick = () => {
                                    currentPage = pageNum;
                                    filterManageProperties();
                                    document.querySelector('.manage-properties-wrap .data-table-container')?.scrollTo({ top: 0, behavior: 'smooth' });
                                };
                            }
                            return btn;
                        }

                        managePropertyPagination.appendChild(renderBtn('Previous', currentPage === 1, false, currentPage - 1));

                        for (let p = 1; p <= totalPages; p++) {
                            if (p === 1 || p === totalPages || (p >= currentPage - 1 && p <= currentPage + 1)) {
                                managePropertyPagination.appendChild(renderBtn(p, false, p === currentPage, p));
                            } else if (p === currentPage - 2 || p === currentPage + 2) {
                                const dots = document.createElement('span');
                                dots.style.padding = '0 8px';
                                dots.textContent = '...';
                                managePropertyPagination.appendChild(dots);
                            }
                        }

                        managePropertyPagination.appendChild(renderBtn('Next', currentPage === totalPages, false, currentPage + 1));
                    }
                };

                managePropertyTypeFilter.addEventListener('change', () => { currentPage = 1; filterManageProperties(); });
                managePropertyTitleSearch.addEventListener('input', () => { currentPage = 1; filterManageProperties(); });
                managePropertyEntries.addEventListener('change', () => { currentPage = 1; filterManageProperties(); });
                
                filterManageProperties();
            }

            const propertyCountry = document.getElementById('propertyCountry');
            const propertyState = document.getElementById('propertyState');
            const propertyCity = document.getElementById('propertyCity');
            const propertyLocation = document.getElementById('propertyLocation');
            const propertyPincode = document.getElementById('propertyPincode');
            const propertyFullAddress = document.getElementById('propertyFullAddress');
            const addPropertyFaqBtn = document.getElementById('addPropertyFaqBtn');
            const propertyFaqList = document.getElementById('propertyFaqList');

            if (propertyCountry && propertyState && propertyCity && propertyLocation && propertyFullAddress) {
                const toggleOptions = (select, matcher) => {
                    Array.from(select.options).forEach((option, index) => {
                        if (index === 0) {
                            option.hidden = false;
                            return;
                        }
                        option.hidden = !matcher(option);
                    });
                };

                const selectedText = (select) => select.value ? (select.options[select.selectedIndex]?.text || '') : '';

                const updateAddress = () => {
                    const parts = [
                        selectedText(propertyLocation),
                        selectedText(propertyCity),
                        selectedText(propertyState),
                        selectedText(propertyCountry),
                        propertyPincode.value.trim(),
                    ].filter(Boolean);

                    propertyFullAddress.value = parts.join(', ');
                };

                const syncStates = () => {
                    const countryId = propertyCountry.value;
                    toggleOptions(propertyState, option => !countryId || option.dataset.country === countryId);
                    if (propertyState.selectedOptions[0]?.hidden) {
                        propertyState.value = '';
                    }
                    syncCities();
                    syncLocations();
                    updateAddress();
                };

                const syncCities = () => {
                    const stateId = propertyState.value;
                    toggleOptions(propertyCity, option => !stateId || option.dataset.state === stateId);
                    if (propertyCity.selectedOptions[0]?.hidden) {
                        propertyCity.value = '';
                    }
                    syncLocations();
                    updateAddress();
                };

                const syncLocations = () => {
                    const countryId = propertyCountry.value;
                    const stateId = propertyState.value;
                    const cityId = propertyCity.value;

                    toggleOptions(propertyLocation, option => {
                        const countryMatch = !countryId || option.dataset.country === countryId;
                        const stateMatch = !stateId || option.dataset.state === stateId;
                        const cityMatch = !cityId || option.dataset.city === cityId;
                        return countryMatch && stateMatch && cityMatch;
                    });

                    if (propertyLocation.selectedOptions[0]?.hidden) {
                        propertyLocation.value = '';
                    }
                };

                propertyCountry.addEventListener('change', syncStates);
                propertyState.addEventListener('change', syncCities);
                propertyCity.addEventListener('change', () => {
                    syncLocations();
                    updateAddress();
                });
                propertyLocation.addEventListener('change', updateAddress);
                propertyPincode?.addEventListener('input', updateAddress);

                syncStates();
            }

            if (addPropertyFaqBtn && propertyFaqList) {
                let faqCount = propertyFaqList.querySelectorAll('.faq-item').length;

                const bindFaqRemove = (button) => {
                    button.addEventListener('click', () => {
                        button.closest('.faq-item')?.remove();
                    });
                };

                propertyFaqList.querySelectorAll('.faq-remove-btn').forEach(bindFaqRemove);

                addPropertyFaqBtn.addEventListener('click', () => {
                    if (faqCount >= 25) {
                        return;
                    }

                    const index = faqCount;
                    const wrapper = document.createElement('div');
                    wrapper.className = 'faq-item';
                    wrapper.innerHTML = `
                        <div class="property-field">
                            <label>Question ${index + 1}</label>
                            <input type="text" name="faqs[${index}][question]" placeholder="Enter question">
                        </div>
                        <div class="property-field">
                            <label>Answer ${index + 1}</label>
                            <input type="text" name="faqs[${index}][answer]" placeholder="Enter answer">
                        </div>
                        <button type="button" class="faq-remove-btn">Remove</button>
                    `;

                    propertyFaqList.appendChild(wrapper);
                    bindFaqRemove(wrapper.querySelector('.faq-remove-btn'));
                    faqCount += 1;
                });
            }

            // Registered Users Interactions
            const userSearch = document.getElementById('userSearch');
            const userEntries = document.getElementById('userEntries');
            const userPageInfo = document.getElementById('userPageInfo');
            const userPagination = document.getElementById('userPagination');
            let filterUsers = null;

            let userStatusFilter = 'all';
            const setUserFilterButton = (status) => {
                userStatusFilter = status;
                [userFilterAll, userFilterActive, userFilterInactive].forEach(btn => {
                    if (btn) btn.classList.toggle('active', btn.id === `userFilter${status.charAt(0).toUpperCase() + status.slice(1)}`);
                });
                if (typeof filterUsers === 'function') {
                    filterUsers();
                }
            };


            if (userSearch && userEntries) {
                let currentPage = 1;
                let filteredRows = [];

                filterUsers = () => {
                    const term = userSearch ? userSearch.value.toLowerCase() : '';
                    const availableRows = Array.from(document.querySelectorAll('.user-row'));

                    filteredRows = availableRows.filter(row => {
                        const name = row.dataset.name ? row.dataset.name.toLowerCase() : '';
                        const email = row.dataset.email ? row.dataset.email.toLowerCase() : '';
                        const matchSearch = name.includes(term) || email.includes(term);

                        if (!matchSearch) {
                            return false;
                        }

                        const accountStatus = row.dataset.status ? row.dataset.status.toString().toLowerCase() : '';
                        if (userStatusFilter === 'active') {
                            return accountStatus === '1' || accountStatus === 'active';
                        } else if (userStatusFilter === 'inactive') {
                            return accountStatus === '0' || accountStatus === 'inactive';
                        }
                        return true;
                    });

                    availableRows.forEach(row => row.style.display = 'none');

                    const limit = parseInt(userEntries.value, 10);
                    const totalPages = Math.ceil(filteredRows.length / limit) || 1;
                    if (currentPage > totalPages) currentPage = totalPages;

                    const startIndex = (currentPage - 1) * limit;
                    const endIndex = startIndex + limit;

                    const paginatedRows = filteredRows.slice(startIndex, endIndex);
                    paginatedRows.forEach((row, idx) => {
                        row.style.display = 'table-row';
                        row.style.animationDelay = `${idx * 0.045}s`;
                    });

                    const start = filteredRows.length > 0 ? startIndex + 1 : 0;
                    const end = Math.min(endIndex, filteredRows.length);
                    userPageInfo.textContent = `Showing ${start} to ${end} of ${filteredRows.length} entries`;

                    userPagination.innerHTML = '';

                    const renderBtn = (label, disabled, active, page) => {
                        const btn = document.createElement('button');
                        btn.textContent = label;
                        btn.disabled = disabled;
                        btn.className = active ? 'page-btn active' : 'page-btn';
                        if (!disabled && !active) {
                            btn.onclick = () => { currentPage = page; filterUsers(); };
                        }
                        return btn;
                    };

                    userPagination.appendChild(renderBtn('Previous', currentPage === 1, false, currentPage - 1));

                    for (let p = 1; p <= totalPages; p++) {
                        if (p === 1 || p === totalPages || (p >= currentPage - 1 && p <= currentPage + 1)) {
                            userPagination.appendChild(renderBtn(p, false, p === currentPage, p));
                        } else if (p === currentPage - 2 || p === currentPage + 2) {
                            const dots = document.createElement('span');
                            dots.style.padding = '0 8px';
                            dots.textContent = '...';
                            userPagination.appendChild(dots);
                        }
                    }

                    userPagination.appendChild(renderBtn('Next', currentPage === totalPages, false, currentPage + 1));
                };

                userSearch.addEventListener('input', () => { currentPage = 1; filterUsers(); });
                userEntries.addEventListener('change', () => { currentPage = 1; filterUsers(); });

                filterUsers();
            }

            function openAddUserModal() {
                document.getElementById('userModalTitle').textContent = 'Add Registered User';
                document.getElementById('userModalSubtitle').textContent = 'Create a new user account.';
                document.getElementById('userModalMethod').value = 'POST';
                document.getElementById('userModalForm').action = "{{ route('admin.users.store', $user) }}";
                document.getElementById('userFirstName').value = '';
                document.getElementById('userLastName').value = '';
                document.getElementById('userEmail').value = '';
                document.getElementById('userPhone').value = '';
                document.getElementById('userAccountSettings').style.display = 'none';
                document.getElementById('userModal').style.display = 'grid';
            }

            function editUserModal(id) {
                const row = document.querySelector(`.user-row[data-id="${id}"]`);
                if (!row) return;

                document.getElementById('userModalTitle').textContent = 'Edit Registered User';
                document.getElementById('userModalSubtitle').textContent = 'Update details for ' + row.dataset.firstName;
                document.getElementById('userModalMethod').value = 'PUT';
                
                const updateUrlTemplate = "{{ route('admin.users.update', ['user' => $user->id, 'targetUser' => '__ID__']) }}";
                document.getElementById('userModalForm').action = updateUrlTemplate.replace('__ID__', id);
                
                document.getElementById('userFirstName').value = row.dataset.firstName;
                document.getElementById('userLastName').value = row.dataset.lastName;
                document.getElementById('userEmail').value = row.dataset.email;
                document.getElementById('userPhone').value = row.dataset.phone;
                
                document.getElementById('userAccountStatus').value = (row.dataset.status == '1' || row.dataset.status == 'active') ? '1' : '0';
                document.getElementById('userEmailStatus').value = row.dataset.emailStatus;
                
                document.getElementById('userAccountSettings').style.display = 'block';
                document.getElementById('userModal').style.display = 'grid';
            }

            function closeUserModal() {
                document.getElementById('userModal').style.display = 'none';
            }

            // Ensure status select colors remain in place on table actions
            function updateStatusClass(selectEl) {
                if (!selectEl) return;
                if (selectEl.name === 'email_status') {
                    selectEl.classList.toggle('status-verified', selectEl.value === 'verified');
                    selectEl.classList.toggle('status-unverified', selectEl.value === 'unverified');
                } else if (selectEl.name === 'account_status') {
                    selectEl.classList.toggle('status-active', selectEl.value === '1' || selectEl.value === 1);
                    selectEl.classList.toggle('status-inactive', selectEl.value === '0' || selectEl.value === 0);
                }
            }

            document.querySelectorAll('.status-select').forEach(select => {
                updateStatusClass(select);
                select.addEventListener('change', () => updateStatusClass(select));
            });
        </script>
    </body>
</html>

    
