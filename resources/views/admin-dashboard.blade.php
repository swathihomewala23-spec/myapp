<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }} Dashboard</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=nunito-sans:400,500,600,700" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            :root {
                --bg: #f4f7ff;
                --panel: #ffffff;
                --sidebar: #FFFFFF;
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
                font-size: 0.5rem;
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

            .nav-item, .nav-link {
                border: 0;
                background: transparent;
                border-radius: 12px;
                padding: 12px 18px;
                text-align: left;
                font: inherit;
                font-size: 14px;
                color: var(--text);
                text-decoration: none;
                position: relative;
                display: flex;
                align-items: center;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .nav-item:not(.active), .nav-link:not(.active) {
                font-weight: 600;
            }

            .nav-icon {
                margin-right: 12px;
                font-size: 16px;
                color: #7d8797;
                transition: transform 0.3s ease, color 0.3s ease;
            }

            .nav-text {
                transition: transform 0.3s ease;
            }

            .nav-item:hover, .nav-link:hover {
                background: #f3f7ff;
                color: var(--brand);
                transform: translateX(4px);
            }

            .nav-item:hover .nav-icon, .nav-link:hover .nav-icon {
                transform: scale(1.15) rotate(5deg);
                color: var(--brand);
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
                display: flex;
                align-items: center;
                padding: 10px 18px 10px 34px;
                font-size: 13px;
                line-height: 1.35;
                color: #4f5a6d;
                text-decoration: none;
                border-radius: 10px;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .nav-subitem i {
                margin-right: 8px;
                font-size: 12px;
                transition: transform 0.3s ease;
            }

            .nav-subitem:hover {
                background: #f3f7ff;
                color: var(--brand);
                transform: translateX(4px);
            }
            
            .nav-subitem:hover i {
                transform: scale(1.1) translateX(2px);
            }

            .nav-subitem.active {
                background: #eef6ff;
                color: #138fce;
                font-weight: 600;
                transform: translateX(4px);
            }

            .nav-children {
                display: grid;
                gap: 2px;
                padding-top: 0;
            }

            .nav-item.active, .nav-link.active {
                background: linear-gradient(135deg, #1aa8ee 0%, #1d97db 100%);
                color: #fff;
                box-shadow: 0 12px 28px rgba(24, 164, 234, 0.24);
                transform: translateX(4px);
            }

            .nav-item.active .nav-icon, .nav-link.active .nav-icon {
                color: #fff;
                transform: scale(1.1);
            }

            details[open] > .nav-item,
            .nav-item.group-active {
                background: linear-gradient(135deg, #1aa8ee 0%, #1d97db 100%);
                color: #fff;
                box-shadow: 0 12px 28px rgba(24, 164, 234, 0.18);
                position: relative;
            }
            
            details[open] > .nav-item .nav-icon,
            .nav-item.group-active .nav-icon {
                color: #fff;
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
                display: flex;
                align-items: center;
                padding: 14px 20px;
                color: #2a2f38;
                text-decoration: none;
                border-bottom: 1px solid #eceff5;
                font-size: 0.88rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .profile-menu-link i {
                margin-right: 10px;
                font-size: 14px;
                color: #7d8797;
                transition: transform 0.3s ease, color 0.3s ease;
            }

            .profile-menu-link:last-child {
                border-bottom: 0;
            }

            .profile-menu-link:hover {
                background: #f7faff;
                color: var(--brand);
                transform: translateX(4px);
            }

            .profile-menu-link:hover i {
                color: var(--brand);
                transform: scale(1.1) translateX(2px);
            }

            .logout-link:hover, .logout-link:hover i {
                color: #dc2626 !important;
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
                text-decoration: none;
                color: inherit;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
                border: 1px solid transparent;
            }

            .stat-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 25px 60px rgba(90, 104, 143, 0.2);
                border-color: var(--brand);
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
                padding-right: 16px;
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
                        <a class="nav-link {{ $currentPage === 'dashboard' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-chart-pie nav-icon"></i><span class="nav-text">Dashboard</span>
                        </a>
                    </div>

                    @foreach ($menuGroups as $group)
                        @php
                            $groupIcon = 'fa-folder';
                            $labelLower = strtolower($group['label']);
                            if(str_contains($labelLower, 'property')) $groupIcon = 'fa-building';
                            elseif(str_contains($labelLower, 'enquir')) $groupIcon = 'fa-envelope';
                            elseif(str_contains($labelLower, 'master')) $groupIcon = 'fa-database';
                            elseif(str_contains($labelLower, 'vendor')) $groupIcon = 'fa-users';
                        @endphp
                        <details class="nav-group" {{ $currentGroup === $group['key'] ? 'open' : '' }}>
                            <summary class="nav-item nav-toggle {{ $currentGroup === $group['key'] ? 'group-active' : '' }}">
                                <span class="nav-text">{{ $group['label'] }}</span>
                            </summary>
                            <div class="nav-children">
                                @foreach ($group['items'] as $item)
                                    @continue($item['slug'] === 'add-vendor')
                                    @php
                                        $subIcon = 'fa-angle-right';
                                        $slug = strtolower($item['slug']);
                                        if (str_contains($slug, 'add')) $subIcon = 'fa-plus';
                                        elseif (str_contains($slug, 'manage') || str_contains($slug, 'list')) $subIcon = 'fa-list-ul';
                                        elseif (str_contains($slug, 'enquir')) $subIcon = 'fa-envelope-open-text';
                                        elseif (str_contains($slug, 'location') || str_contains($slug, 'city') || str_contains($slug, 'state') || str_contains($slug, 'countr')) $subIcon = 'fa-map-marker-alt';
                                        elseif (str_contains($slug, 'vendor') || str_contains($slug, 'user') || str_contains($slug, 'member')) $subIcon = 'fa-users';
                                        elseif (str_contains($slug, 'categor') || str_contains($slug, 'amenit') || str_contains($slug, 'type')) $subIcon = 'fa-tags';
                                        elseif (str_contains($slug, 'setting') || str_contains($slug, 'config')) $subIcon = 'fa-cog';
                                    @endphp
                                    <a
                                        class="nav-subitem {{ ($currentPage === $item['slug'] || (in_array($currentPage, ['add-vendor', 'vendor-detail', 'vendor-edit', 'vendor-password'], true) && $item['slug'] === 'registered-vendors') || (in_array($currentPage, ['add-user', 'edit-user'], true) && $item['slug'] === 'registered-users')) ? 'active' : '' }}"
                                        href="{{ route('admin.section', [ 'section' => $item['slug']]) }}"
                                    >
                                        {{ $item['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </details>
                    @endforeach
                </nav>

                <div class="sidebar-footer">
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
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
                @php
                    $profIcon = 'fa-user-cog';
                    if (str_contains(strtolower($item['slug']), 'password')) $profIcon = 'fa-key';
                    if (str_contains(strtolower($item['slug']), 'profile')) $profIcon = 'fa-user-edit';
                @endphp
                <a class="profile-menu-link" href="{{ route('admin.section', ['section' => $item['slug']]) }}">
                    {{ $item['label'] }}
                </a>
            @endforeach

            <form action="{{ route('admin.logout') }}" method="POST" id="logout-form" style="display: none;">
                @csrf
            </form>
            <a class="profile-menu-link logout-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
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
                                <a href="{{ route('admin.section', ['section' => $stat['slug']]) }}" class="stat-card">
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
                                            @elseif ($stat['icon'] === 'prop-enquiry')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                                </svg>
                                            @elseif ($stat['icon'] === 'interior')
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <path d="M3 9h18"></path>
                                                    <path d="M9 21V9"></path>
                                                </svg>
                                            @else
                                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                                    <circle cx="12" cy="12" r="8.5" stroke="currentColor"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="trend up">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M12 3l2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 17.3 6.4 20.2l1.1-6.2L3 9.6l6.2-.9L12 3Z"/>
                                        </svg>

                                        <span>
                                            <strong>{{ $stat['change'] }}</strong>
                                            based on total
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @elseif ($currentPage === 'member-requests')
                        <style>
                            @keyframes memberReqIn {
                                from { opacity: 0; transform: translateY(20px); }
                                to { opacity: 1; transform: translateY(0); }
                            }
                            .member-requests-wrap {
                                animation: memberReqIn 0.5s cubic-bezier(.22,1,.36,1) both;
                            }
                            .status-badge {
                                padding: 6px 12px;
                                border-radius: 6px;
                                font-size: 0.75rem;
                                font-weight: 700;
                                text-transform: uppercase;
                            }
                            .status-pending { background: #fff7ed; color: #c2410c; }
                            .status-approved { background: #f0fdf4; color: #15803d; }
                            .status-rejected { background: #fef2f2; color: #b91c1c; }
                        </style>

                        <div class="member-requests-wrap">
                            <div class="breadcrumb-wrapper">
                                <div class="breadcrumb-item"></div>
                            </div>
                            <div class="amenities-header-row">
                                <h2 class="amenities-title">Member Requests</h2>
                            </div>
                            <div class="data-table-container">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>SI.No</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Reason</th>  
                                            <th>Status</th>
                                            <th>Date Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $si = ($memberRequests->currentPage() - 1) * $memberRequests->perPage() + 1; @endphp
                                        @forelse($memberRequests as $req)
                                            <tr class="user-row">
                                                <td>{{ $si++ }}</td>
                                                <td style="font-weight: 600;">{{ $req->first_name }}</td>
                                                <td>{{ $req->last_name }}</td>
                                                <td>{{ $req->email }}</td>
                                                <td style="color: var(--brand); font-weight: 500;">{{ $req->phone }}</td>
                                                <td>
                                                    <div style="max-width: 250px; font-size: 0.85rem; color: #64748b;" title="{{ $req->reason }}">
                                                        {{ \Illuminate\Support\Str::limit($req->reason, 60) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $status = strtolower($req->status ?? 'pending');
                                                        $statusClass = 'status-pending';
                                                        if($status === 'approved') $statusClass = 'status-approved';
                                                        if($status === 'rejected') $statusClass = 'status-rejected';
                                                    @endphp
                                                    <span class="status-badge {{ $statusClass }}">
                                                        {{ ucfirst($status) }}
                                                    </span>
                                                </td>
                                                <td style="font-size: 0.85rem; color: #64748b;">
                                                    {{ \Carbon\Carbon::parse($req->created_at)->format('d M Y, H:i') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" style="text-align: center; padding: 40px; color: #64748b;">No member requests found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="pagination-footer">
                                    <div class="pagination-info">
                                        Showing {{ $memberRequests->firstItem() ?? 0 }} to {{ $memberRequests->lastItem() ?? 0 }} of {{ $memberRequests->total() }} entries
                                    </div>
                                    <div class="pagination-controls">
                                        {{ $memberRequests->links('pagination::simple-bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    @elseif ($currentPage === 'edit-profile')
                        <div class="edit-profile-wrap" style="padding: 24px 30px; background: #f8fafc; min-height: 100vh;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
                                <div>
                                    <h2 style="font-size: 1.5rem; font-weight: 800; margin: 0 0 10px; color: #0f172a;">Update Profile</h2>
                                </div>
                            </div>

                            <style>
                                .edit-profile-panel { background: #fff; border-radius: 24px; padding: 30px; box-shadow: 0 30px 60px rgba(15,23,42,0.08); border: 1px solid #e2e8f0; }
                                .profile-grid { display: grid; gap: 24px; grid-template-columns: 1fr; }
                                .profile-grid-row { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 18px; }
                                @media (max-width: 980px) { .profile-grid-row { grid-template-columns: 1fr; } }
                                .profile-control { display: grid; gap: 10px; }
                                .profile-control label { font-size: 0.95rem; font-weight: 700; color: #0f172a; }
                                .profile-control input, .profile-control textarea, .profile-control select { width: 100%; min-height: 46px; border: 1px solid #d8e2ef; border-radius: 14px; padding: 12px 14px; font-size: 0.95rem; color: #1e293b; background: #fcfdff; outline: none; transition: border-color .2s, box-shadow .2s; }
                                .profile-control input:focus, .profile-control textarea:focus, .profile-control select:focus { border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56,189,248,.16); }
                                .profile-control textarea { min-height: 140px; resize: vertical; }
                                .profile-image-card { display: grid; gap: 18px; padding: 24px; border-radius: 24px; background: #f6f7f8ff; border: 1px solid #e2e8f0; align-items: center; }
                                .profile-image-preview { width: 140px; height: 140px; border-radius: 24px; background: #fff; overflow: hidden; border: 1px solid #e2e8f0; display: grid; place-items: center; }
                                .profile-image-preview img { width: 100%; height: 100%; object-fit: cover; display: block; }
                                .btn-upload-profile { display: inline-flex; align-items: center; justify-content: center; border-radius: 999px; border: 1px solid #cbd5e1; background: #fff; color: #0f172a; font-weight: 700; padding: 12px 22px; cursor: pointer; transition: all .18s ease; }
                                .btn-upload-profile:hover { background: #eef4ff; border-color: #93c5fd; }
                                .btn-save-profile { border: 0; border-radius: 999px; background: #1d4ed8; color: #fff; padding: 14px 28px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: transform .18s ease; }
                                .btn-save-profile:hover { transform: translateY(-1px); }
                                .form-note { color: #64748b; font-size: 0.92rem; }
                            </style>

                            <div class="edit-profile-panel">
                                @if (session('status'))
                                    <div style="margin-bottom: 22px; padding: 18px 22px; border-radius: 18px; background: #ecfdf5; color: #166534; border: 1px solid #d1fae5;">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="profile-grid">
                                        <div class="profile-image-card">
                                            <div style="display: grid; gap: 12px;">
                                                <strong style="font-size: 1rem; color: #0f172a;">Profile Image</strong>
                                                <span class="form-note">Upload square size image for best quality.</span>
                                            </div>
                                            @php
                                                $profileImage = $user->profile_image
                                                    ? \App\Support\MediaPath::url($user->profile_image)
                                                    : 'https://via.placeholder.com/140x140?text=Upload';
                                            @endphp
                                            <div class="profile-image-preview">
                                                <img id="profilePreview" src="{{ $profileImage }}" alt="Profile image preview">
                                            </div>
                                            <label for="profileImageInput" class="btn-upload-profile">Choose Image</label>
                                            <input id="profileImageInput" type="file" name="profile_image" accept="image/*" style="display:none;" onchange="previewProfileImage(event)">
                                        </div>

                                        <div style="display: grid; gap: 24px;">
                                            <div class="profile-grid-row">
                                                <div class="profile-control">
                                                    <label for="username">Username*</label>
                                                    <input id="username" type="text" name="username" value="{{ old('username', $user->username) }}" required>
                                                </div>
                                                <div class="profile-control">
                                                    <label for="email">Email*</label>
                                                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                                                </div>
                                                <div class="profile-control">
                                                    <label for="phone">Phone</label>
                                                    <input id="phone" type="text" name="phone" value="{{ old('phone', $user->contact_number ?? $user->phone) }}">
                                                </div>
                                            </div>

                                            <div class="profile-grid-row">
                                                <div class="profile-control">
                                                    <label for="first_name">First Name*</label>
                                                    <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $user->first_name ?? $user->name) }}" required>
                                                </div>
                                                <div class="profile-control">
                                                    <label for="last_name">Last Name*</label>
                                                    <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                                                </div>
                                                <div class="profile-control">
                                                    <label for="country">Country</label>
                                                    <input id="country" type="text" name="country" value="{{ old('country', $user->country) }}">
                                                </div>
                                            </div>

                                            <div class="profile-grid-row">
                                                <div class="profile-control">
                                                    <label for="city">City</label>
                                                    <input id="city" type="text" name="city" value="{{ old('city', $user->city) }}">
                                                </div>
                                                <div class="profile-control">
                                                    <label for="state">State</label>
                                                    <input id="state" type="text" name="state" value="{{ old('state', $user->state) }}">
                                                </div>
                                                <div class="profile-control">
                                                    <label for="zip_code">Zip Code</label>
                                                    <input id="zip_code" type="text" name="zip_code" value="{{ old('zip_code', $user->zip_code) }}">
                                                </div>
                                            </div>

                                            <div class="profile-control">
                                                <label for="address">Address</label>
                                                <input id="address" type="text" name="address" value="{{ old('address', $user->address) }}">
                                            </div>

                                            <div class="profile-control">
                                                <label for="about">Describe about you</label>
                                                <textarea id="about" name="about">{{ old('about', $user->about) }}</textarea>
                                            </div>

                                            <div style="display:flex; justify-content:flex-end;">
                                                <button type="submit" class="btn-save-profile">Save Profile</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <script>
                                function previewProfileImage(event) {
                                    const input = event.target;
                                    if (!input.files || !input.files[0]) return;
                                    const reader = new FileReader();
                                    reader.onload = function (e) {
                                        document.getElementById('profilePreview').src = e.target.result;
                                    };
                                    reader.readAsDataURL(input.files[0]);
                                }
                            </script>
                        </div>

                    @elseif ($currentPage === 'change-password')
                        <div class="change-password-wrap" style="padding: 24px 30px; background: #f8fafc; min-height: 100vh;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
                                <div>
                                    <h2 style="font-size: 1.5rem; font-weight: 100; margin: 0 0 10px; color: #0f172a;">Change Password</h2>
                                </div>
                            </div>

                            <style>
                                .password-panel { background: #fff; border-radius: 24px; padding: 40px; box-shadow: 0 30px 60px rgba(15,23,42,0.08); border: 1px solid #e2e8f0; max-width: 600px; }
                                .password-form { display: grid; gap: 24px; }
                                .password-control { display: grid; gap: 10px; }
                                .password-control label { font-size: 0.95rem; font-weight: 700; color: #0f172a; }
                                .password-control input { width: 100%; min-height: 50px; border: 1px solid #d8e2ef; border-radius: 14px; padding: 12px 16px; font-size: 0.95rem; color: #1e293b; background: #fcfdff; outline: none; transition: border-color .2s, box-shadow .2s; }
                                .password-control input:focus { border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56,189,248,.16); }
                                .btn-update-password { border: 0; border-radius: 999px; background: #6711b8ff; color: #fff; padding: 14px 32px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: transform .18s ease, background .2s ease; }
                                .btn-update-password:hover { transform: translateY(-1px); background: #4612d4ff; }
                                .password-helper { color: #64748b; font-size: 0.92rem; }
                                .error-message { color: #dc2626; font-size: 0.92rem; margin-top: 4px; }
                                .success-message { color: #16a34a; padding: 12px 16px; background: #dcfce7; border: 1px solid #86efac; border-radius: 12px; margin-bottom: 20px; }
                            </style>

                            <div class="password-panel">
                                @if (session('status'))
                                    <div class="success-message">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                @if ($errors->any())
                                    <div style="margin-bottom: 20px; padding: 12px 16px; background: #fee2e2; border: 1px solid #fecaca; border-radius: 12px; color: #dc2626;">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('admin.password.update') }}" class="password-form">
                                    @csrf
                                    @method('PUT')

                                    <div class="password-control">
                                        <label for="current_password">Current Password<span style="color: #dc2626;">*</span></label>
                                        <input 
                                            id="current_password" 
                                            type="password" 
                                            name="current_password" 
                                            required 
                                            placeholder="Enter your current password"
                                            class="{{ $errors->has('current_password') ? 'input-error' : '' }}"
                                        >
                                        @error('current_password')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="password-control">
                                        <label for="password">New Password<span style="color: #dc2626;">*</span></label>
                                        <input 
                                            id="password" 
                                            type="password" 
                                            name="password" 
                                            required 
                                            placeholder="Enter your new password"
                                            class="{{ $errors->has('password') ? 'input-error' : '' }}"
                                        >
                                        <div class="password-helper">Must be at least 8 characters long</div>
                                        @error('password')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="password-control">
                                        <label for="password_confirmation">Confirm New Password<span style="color: #dc2626;">*</span></label>
                                        <input 
                                            id="password_confirmation" 
                                            type="password" 
                                            name="password_confirmation" 
                                            required 
                                            placeholder="Confirm your new password"
                                            class="{{ $errors->has('password_confirmation') ? 'input-error' : '' }}"
                                        >
                                        @error('password_confirmation')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div style="display: flex; justify-content: flex-start; padding-top: 12px;">
                                        <button type="submit" class="btn-update-password">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @elseif ($currentPage === 'banner')
                        @include('admin-banner-section')

                    @elseif ($currentPage === 'our-partners')
                        @include('admin-our-partners-section')

                    @elseif ($currentPage === 'interior')
                        @include('admin-interior-section')

                    @elseif ($currentPage === 'add-vendor')
                        @include('admin-add-vendor-section')

                    @elseif (in_array($currentPage, ['add-user', 'edit-user'], true))
                        @include('admin-user-form-section')

                    @elseif ($currentPage === 'vendor-edit')
                        @php
                            $vendorName = $vendor->display_name ?? trim(($vendor->first_name ?? '') . ' ' . ($vendor->last_name ?? ''));
                            $vendorName = trim((string) $vendorName) !== '' ? $vendorName : 'Unnamed Vendor';
                            $vendorPhoto = $vendor->photo ?? null;
                            $vendorPhotoUrl = $vendorPhoto ? \App\Support\MediaPath::url($vendorPhoto, 'storage/public/vendor_photos') : null;
                        @endphp
                        <style>
                            .vendor-form-page { padding: 30px; background: #f8fafc; min-height: 100vh; }
                            .vendor-form-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06); }
                            .vendor-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
                            .vendor-form-field.full { grid-column: 1 / -1; }
                            .vendor-form-field label { display: block; margin-bottom: 7px; color: #1f2937; font-size: 13px; font-weight: 700; }
                            .vendor-form-field input, .vendor-form-field select, .vendor-form-field textarea { width: 100%; border: 1px solid #dbe2ed; border-radius: 6px; padding: 11px 12px; font-size: 14px; }
                            .vendor-form-field textarea { min-height: 120px; resize: vertical; }
                            .vendor-edit-photo { width: 96px; height: 96px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; overflow: hidden; color: #94a3b8; font-size: 32px; font-weight: 800; }
                            .vendor-edit-photo img { width: 100%; height: 100%; object-fit: cover; }
                            .vendor-form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 22px; }
                            .vendor-form-btn { border: 0; border-radius: 6px; padding: 11px 18px; font-weight: 800; text-decoration: none; cursor: pointer; }
                            .vendor-form-btn.cancel { background: #e5e7eb; color: #374151; }
                            .vendor-form-btn.save { background: #2563eb; color: #fff; }
                            @media (max-width: 800px) { .vendor-form-grid { grid-template-columns: 1fr; } }
                        </style>

                        <div class="vendor-form-page">
                            <div style="display:flex; justify-content:space-between; gap:16px; align-items:center; margin-bottom:18px;">
                                <div>
                                    <h2 style="margin:0; color:#0f172a;">Edit Vendor</h2>
                                    <p style="margin:6px 0 0; color:#64748b;">Update {{ $vendorName }} details.</p>
                                </div>
                                <a class="vendor-form-btn cancel" href="{{ route('admin.vendors.show', ['vendor' => $vendor->id]) }}">Back</a>
                            </div>

                            <form class="vendor-form-card" method="POST" action="{{ route('admin.vendors.update', ['vendor' => $vendor->id]) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div style="display:flex; align-items:center; gap:16px; margin-bottom:22px;">
                                    <div class="vendor-edit-photo">
                                        @if($vendorPhotoUrl)
                                            <img src="{{ $vendorPhotoUrl }}" alt="{{ $vendorName }}">
                                        @else
                                            {{ substr($vendorName, 0, 1) }}
                                        @endif
                                    </div>
                                    <div class="vendor-form-field" style="flex:1;">
                                        <label for="vendorPhoto">Photo</label>
                                        <input id="vendorPhoto" type="file" name="photo" accept="image/*">
                                    </div>
                                </div>

                                <div class="vendor-form-grid">
                                    <div class="vendor-form-field">
                                        <label for="vendorFirstName">First Name *</label>
                                        <input id="vendorFirstName" type="text" name="first_name" value="{{ old('first_name', $vendor->first_name ?? '') }}" required>
                                    </div>
                                    <div class="vendor-form-field">
                                        <label for="vendorLastName">Last Name *</label>
                                        <input id="vendorLastName" type="text" name="last_name" value="{{ old('last_name', $vendor->last_name ?? '') }}" required>
                                    </div>
                                    <div class="vendor-form-field">
                                        <label for="vendorEmail">Email *</label>
                                        <input id="vendorEmail" type="email" name="email" value="{{ old('email', $vendor->email ?? '') }}" required>
                                    </div>
                                    <div class="vendor-form-field">
                                        <label for="vendorPhone">Phone</label>
                                        <input id="vendorPhone" type="text" name="phone" value="{{ old('phone', $vendor->phone ?? '') }}">
                                    </div>
                                    <div class="vendor-form-field">
                                        <label for="vendorUsername">Username</label>
                                        <input id="vendorUsername" type="text" name="username" value="{{ old('username', $vendor->username ?? '') }}">
                                    </div>
                                    <div class="vendor-form-field">
                                        <label for="vendorStatus">Status</label>
                                        <select id="vendorStatus" name="status">
                                            <option value="1" {{ (string) old('status', $vendor->status ?? 1) === '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ (string) old('status', $vendor->status ?? 1) === '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="vendor-form-field full">
                                        <label for="vendorDetails">Details</label>
                                        <textarea id="vendorDetails" name="details">{{ old('details', $vendor->details ?? '') }}</textarea>
                                    </div>
                                </div>

                                <div class="vendor-form-actions">
                                    <a class="vendor-form-btn cancel" href="{{ route('admin.vendors.show', ['vendor' => $vendor->id]) }}">Cancel</a>
                                    <button type="submit" class="vendor-form-btn save">Update Vendor</button>
                                </div>
                            </form>
                        </div>

                    @elseif ($currentPage === 'vendor-password')
                        @php
                            $vendorName = $vendor->display_name ?? trim(($vendor->first_name ?? '') . ' ' . ($vendor->last_name ?? ''));
                            $vendorName = trim((string) $vendorName) !== '' ? $vendorName : 'Unnamed Vendor';
                        @endphp
                        <style>
                            .vendor-password-page { padding: 30px; background: #f8fafc; min-height: 100vh; }
                            .vendor-password-card { max-width: 560px; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06); }
                            .vendor-password-field { margin-bottom: 16px; }
                            .vendor-password-field label { display: block; margin-bottom: 7px; color: #1f2937; font-size: 13px; font-weight: 700; }
                            .vendor-password-field input { width: 100%; border: 1px solid #dbe2ed; border-radius: 6px; padding: 11px 12px; font-size: 14px; }
                            .vendor-password-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
                            .vendor-password-btn { border: 0; border-radius: 6px; padding: 11px 18px; font-weight: 800; text-decoration: none; cursor: pointer; }
                            .vendor-password-btn.cancel { background: #e5e7eb; color: #374151; }
                            .vendor-password-btn.save { background: #2563eb; color: #fff; }
                        </style>

                        <div class="vendor-password-page">
                            <div style="margin-bottom:18px;">
                                <h2 style="margin:0; color:#0f172a;">Change Vendor Password</h2>
                                <p style="margin:6px 0 0; color:#64748b;">Set a new password for {{ $vendorName }}.</p>
                            </div>

                            <form class="vendor-password-card" method="POST" action="{{ route('admin.vendors.password.update', ['vendor' => $vendor->id]) }}">
                                @csrf
                                @method('PUT')

                                <div class="vendor-password-field">
                                    <label for="vendorPassword">New Password *</label>
                                    <input id="vendorPassword" type="password" name="password" required minlength="8">
                                </div>
                                <div class="vendor-password-field">
                                    <label for="vendorPasswordConfirmation">Confirm Password *</label>
                                    <input id="vendorPasswordConfirmation" type="password" name="password_confirmation" required minlength="8">
                                </div>

                                <div class="vendor-password-actions">
                                    <a class="vendor-password-btn cancel" href="{{ route('admin.vendors.show', ['vendor' => $vendor->id]) }}">Cancel</a>
                                    <button type="submit" class="vendor-password-btn save">Update Password</button>
                                </div>
                            </form>
                        </div>

                    @elseif ($currentPage === 'website-settings')
                        <div class="website-settings-wrap" style="padding: 30px; background: #ffffff; min-height: 100vh;">
                            
                            <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 28px; color: #6b7280;">Update Website Information</h2>

                            <style>
                                .website-settings-wrap { background: #f9fafb; }
                                .settings-section { background: #fff; border-radius: 8px; margin-bottom: 30px; border: 1px solid #e5e7eb; overflow: hidden; }
                                .settings-section-header { background: #6c87c2ff; color: #fff; padding: 16px 24px; font-size: 0.95rem; font-weight: 600; }
                                .settings-section-body { padding: 24px; }
                                .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 24px; }
                                .form-row.full { grid-template-columns: 1fr; }
                                .form-row.three { grid-template-columns: repeat(3, 1fr); }
                                @media (max-width: 900px) { .form-row, .form-row.three { grid-template-columns: 1fr; } }
                                
                                .form-group { display: grid; gap: 8px; }
                                .form-group label { font-size: 0.875rem; font-weight: 600; color: #1f2937; }
                                .form-group input[type="text"],
                                .form-group input[type="email"],
                                .form-group textarea,
                                .form-group select { 
                                    width: 100%; 
                                    padding: 10px 12px; 
                                    border: 1px solid #d1d5db; 
                                    border-radius: 6px; 
                                    font-size: 0.875rem; 
                                    color: #374151; 
                                    background: #f9fafb; 
                                    font-family: inherit;
                                }
                                .form-group textarea { min-height: 100px; resize: vertical; }
                                .form-group input[type="text"]:focus,
                                .form-group input[type="email"]:focus,
                                .form-group textarea:focus,
                                .form-group select:focus { outline: none; border-color: #2563eb; background: #fff; }
                                
                                .image-section { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 24px; }
                                .image-box { padding: 20px; background: #f3f4f6; border-radius: 8px; text-align: left; }
                                .image-box-label { font-size: 0.875rem; font-weight: 600; color: #1f2937; margin-bottom: 8px; display: block; }
                                .image-box-hint { font-size: 0.8rem; color: #e61313fb; margin-bottom: 16px; display: block; }
                                .image-preview-wrapper { 
                                    width: 140px; 
                                    height: 140px; 
                                    border: 1px solid #d1d5db; 
                                    border-radius: 6px; 
                                    display: flex; 
                                    align-items: center; 
                                    justify-content: center; 
                                    margin-bottom: 12px;
                                    background: #fff;
                                }
                                .image-preview-wrapper img { max-width: 100%; max-height: 100%; object-fit: contain; }
                                .btn-choose-file { 
                                    display: inline-block; 
                                    background: #6c87c2ff; 
                                    color: #fff; 
                                    padding: 8px 16px; 
                                    border-radius: 6px;  
                                    font-size: 0.8rem; 
                                    font-weight: 600; 
                                    cursor: pointer; 
                                    border: none;
                                    text-decoration: none;
                                }
                                .btn-choose-file:hover { background: #1d4ed8; }
                                .btn-update-website { border: 0; border-radius: 999px; background: #6711b8ff; color: #fff; padding: 14px 32px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: transform .18s ease, background .2s ease; }
                                .btn-update-website:hover { background: #5308dfff; border-color: #6c0bdbff; }

                                .toggle-wrapper { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
                                .toggle-switch { appearance: none; width: 48px; height: 28px; background: #d1d5db; border-radius: 999px; cursor: pointer; position: relative; border: none; transition: background 0.3s; }
                                .toggle-switch:checked { background: #10b981; }
                                .toggle-switch::before { content: ''; position: absolute; width: 24px; height: 24px; background: #fff; border-radius: 50%; top: 2px; left: 2px; transition: left 0.3s; }
                                .toggle-switch:checked::before { left: 22px; }
                                
                                .label-text { font-size: 0.875rem; font-weight: 600; color: #1f2937; cursor: pointer; }
                            </style>

                            <!-- Website Information Section -->
                            <div class="settings-section">
                                <div class="settings-section-header">Website Information</div>
                                <div class="settings-section-body">
                                    <div class="image-section">
                                        <div class="image-box">
                                            <span class="image-box-label">Website Logo</span>
                                            <div class="image-preview-wrapper">
                                                @if($settings->website_logo && file_exists(public_path('storage/' . $settings->website_logo)))
                                                    <img src="{{ \App\Support\MediaPath::url($settings->website_logo) }}" alt="Logo" id="logoPreview">
                                                @else
                                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 60'%3E%3Crect fill='%23f3f4f6' width='200' height='60'/%3E%3Ctext x='50%25' y='50%25' font-family='Arial' font-size='14' fill='%239ca3af' text-anchor='middle' dominant-baseline='middle'%3ELogo%3C/text%3E%3C/svg%3E" alt="Logo placeholder">
                                                @endif
                                            </div>  
                                            <button type="button" class="btn-choose-file" onclick="document.getElementById('websiteLogo').click();">Choose Logo</button>
                                            
                                            <input id="websiteLogo" type="file" name="website_logo" accept="image/*" style="display:none;">
                                            
                                            <span class="image-box-hint">Upload PNG or JPG Image. Recommended size: 200x60</span>

                                        </div>

                                        <div class="image-box">
                                            <span class="image-box-label">Website Favicon</span>
                                            <div class="image-preview-wrapper">
                                                @if($settings->website_favicon && file_exists(public_path('storage/' . $settings->website_favicon)))
                                                    <img src="{{ \App\Support\MediaPath::url($settings->website_favicon) }}" alt="Favicon" id="faviconPreview">
                                                @else
                                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect fill='%23f3f4f6' width='32' height='32'/%3E%3Ctext x='50%25' y='50%25' font-family='Arial' font-size='10' fill='%239ca3af' text-anchor='middle' dominant-baseline='middle'%3EIcon%3C/text%3E%3C/svg%3E" alt="Favicon placeholder">
                                                @endif
                                            </div>
                                            <button type="button" class="btn-choose-file" onclick="document.getElementById('websiteFavicon').click();">Choose Favicon</button>

                                             <span class="image-box-hint">Upload square image. Recommended size: 32x32 or 16x16</span>
                                            <input id="websiteFavicon" type="file" name="website_favicon" accept="image/*" style="display:none;">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Website Title*</label>
                                            <input type="text" name="website_title" value="{{ old('website_title', $settings->website_title ?? '') }}" placeholder="Homewala Solutions | Real Estate Property | Buy & Sell">
                                        </div>
                                        <div class="form-group">
                                            <label>Website Description</label>
                                            <textarea name="website_description" placeholder="Homewala - India's leading real estate platform...">{{ old('website_description', $settings->website_description ?? '') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Email Address*</label>
                                            <input type="email" name="email_address" value="{{ old('email_address', $settings->email_address ?? '') }}" placeholder="info@homewala.com">
                                        </div>
                                        <div class="form-group">
                                            <label>Contact Number*</label>
                                            <input type="text" name="contact_number" value="{{ old('contact_number', $settings->contact_number ?? '') }}" placeholder="+91 8925997081">
                                        </div>
                                    </div>

                                    <div class="form-row full">
                                        <div class="form-group">
                                            <label>Address*</label>
                                            <input type="text" name="address" value="{{ old('address', $settings->address ?? '') }}" placeholder="No.78/10, Old State Bank colony, West Tambaram, Chennai-600045">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mail Information Section -->
                            <div class="settings-section">
                                <div class="settings-section-header">Mail Information</div>
                                <div class="settings-section-body">
                                    <div class="toggle-wrapper">
                                        <input type="checkbox" id="enable_smtp" name="enable_smtp" class="toggle-switch" {{ $settings->enable_smtp ? 'checked' : '' }}>
                                        <label for="enable_smtp" class="label-text">Enable SMTP</label>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>SMTP Host</label>
                                            <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings->smtp_host ?? '') }}" placeholder="host@gmail.com">
                                        </div>
                                        <div class="form-group">
                                            <label>SMTP Port</label>
                                            <select name="smtp_port">
                                                <option value="">Select Port</option>
                                                <option value="587" {{ old('smtp_port', $settings->smtp_port) == '587' ? 'selected' : '' }}>587</option>
                                                <option value="465" {{ old('smtp_port', $settings->smtp_port) == '465' ? 'selected' : '' }}>465</option>
                                                <option value="25" {{ old('smtp_port', $settings->smtp_port) == '25' ? 'selected' : '' }}>25</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Encryption</label>
                                            <select name="smtp_encryption">
                                                <option value="">Select Encryption</option>
                                                <option value="TLS" {{ old('smtp_encryption', $settings->smtp_encryption) == 'TLS' ? 'selected' : '' }}>TLS</option>
                                                <option value="SSL" {{ old('smtp_encryption', $settings->smtp_encryption) == 'SSL' ? 'selected' : '' }}>SSL</option>
                                                <option value="NONE" {{ old('smtp_encryption', $settings->smtp_encryption) == 'NONE' ? 'selected' : '' }}>None</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>SMTP Username</label>
                                            <input type="text" name="smtp_username" value="{{ old('smtp_username', $settings->smtp_username ?? '') }}" placeholder="test">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>SMTP Password</label>
                                            <input type="password" name="smtp_password" placeholder="••••">
                                        </div>
                                        <div class="form-group">
                                            <label>From Mail</label>
                                            <input type="email" name="from_mail" value="{{ old('from_mail', $settings->from_mail ?? '') }}" placeholder="test@gmail.com">
                                        </div>
                                    </div>

                                    <div class="form-row full">
                                        <div class="form-group">
                                            <label>From Name</label>
                                            <input type="text" name="from_name" value="{{ old('from_name', $settings->from_name ?? '') }}" placeholder="Home Wala">
                                        </div>
                                    </div>
                                    <div style="display: flex; justify-content: flex-start; padding-top: 12px;">
                                        <button type="submit" class="btn-update-website">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @elseif ($currentPage === 'registered-vendors')
                        <div class="vendors-section" style="padding: 30px; background: #f8fafc; min-height: 100vh;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3px;">
                                <h2 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0;">Registered Vendor</h2>
                                <a href="{{ route('admin.section', ['section' => 'add-vendor']) }}" class="btn-add-member" style="background: #3880ff; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; transition: 0.2s;">Add New Vendor</a>
                            </div>

                            <style>
    *{
        font-family:'Poppins',sans-serif;
    }

    .vendors-section{
        background:linear-gradient(135deg,#f8fbff,#eef4ff);
        min-height:100vh;
        animation:pageFade .7s ease;
    }

    .vendors-header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:28px !important;
        flex-wrap:wrap;
        gap:15px;
    }

    .vendors-title{
        font-size:2rem !important;
        font-weight:800 !important;
        color:#0f172a !important;
        margin:0;
        position:relative;
        letter-spacing:-0.5px;
    }

    .vendors-title::after{
        content:'';
        position:absolute;
        left:0;
        bottom:-10px;
        width:75px;
        height:4px;
        border-radius:20px;
        background:linear-gradient(90deg,#2563eb,#60a5fa);
    }

    .btn-add-member{
        background:linear-gradient(135deg,#2563eb,#3b82f6) !important;
        color:#fff !important;
        text-decoration:none;
        padding:12px 22px !important;
        border-radius:14px !important;
        font-weight:600 !important;
        font-size:0.92rem !important;
        transition:all .35s ease !important;
        box-shadow:0 10px 25px rgba(37,99,235,.18);
    }

    .btn-add-member:hover{
        transform:translateY(-4px);
        box-shadow:0 18px 35px rgba(37,99,235,.25);
    }

    /* GRID */
    .vendor-grid{
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:28px;
    }

    /* CARD */
    .vendor-card{ 
        background:rgba(255, 255, 255, 0.88);
        backdrop-filter:blur(12px);
        border-radius:28px;
        padding:26px 22px;
        text-align:center;
        border:1px solid rgba(255,255,255,0.7);
        box-shadow:
            0 10px 30px rgba(15,23,42,0.05),
            0 4px 12px rgba(15,23,42,0.03);
        transition:all .45s cubic-bezier(.34,1.56,.64,1);
        position:relative;
        overflow:hidden;
        animation:cardFade .7s ease;
    }

    .vendor-card::before{
        content:'';
        position:absolute;
        top:0;
        left:-120%;
        width:100%;
        height:100%;
        background:linear-gradient(
            120deg,
            transparent,
            rgba(255,255,255,.55),
            transparent
        );
        transition:.8s ease;
    }

    .vendor-card:hover::before{
        left:120%;
    }

    .vendor-card:hover{
        transform:translateY(-10px) scale(1.02);
        box-shadow:
            0 22px 40px rgba(37,99,235,0.12),
            0 8px 18px rgba(15,23,42,0.06);
        border-color:#dbeafe;
    }

    .vendor-card-main{
        display:block;
        padding:14px 0 12px;
        text-decoration:none;
        color:inherit;
    }

    /* LOGO */
    .vendor-logo-wrap{
        width:110px;
        height:110px;
        margin:0 auto 22px;
        border-radius:50%;
        background:linear-gradient(135deg,#f8fbff,#dbeafe);
        display:flex;
        align-items:center;
        justify-content:center;
        border:5px solid #fff;
        box-shadow:
            0 10px 25px rgba(37,99,235,.12),
            inset 0 2px 8px rgba(255,255,255,.8);
        overflow:hidden;
        transition:all .4s ease;
    }

    .vendor-card:hover .vendor-logo-wrap{
        transform:scale(1.08) rotate(3deg);
    }

    .vendor-logo-wrap img{
        width:72%;
        height:72%;
        object-fit:cover;
        border-radius:50%;
    }

    /* NAME */
    .vendor-name{
        font-size:1.05rem !important;
        font-weight:700 !important;
        color:#0f172a !important;
        margin-bottom:8px !important;
        transition:.3s ease;
    }

    .vendor-card:hover .vendor-name{
        color:#2563eb !important;
    }

    /* ACTION BUTTONS */
    .vendor-card-actions{
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:10px;
        margin-top:18px;
    }

    .vendor-card-actions form{
        margin:0;
    }

    .vendor-action-btn{
        border:0;
        border-radius:12px;
        padding:10px 12px;
        font-size:12px;
        font-weight:700;
        cursor:pointer;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-height:40px;
        width:100%;
        transition:all .3s ease;
        letter-spacing:.2px;
    }

    .vendor-action-btn:hover{
        transform:translateY(-3px);
        box-shadow:0 10px 18px rgba(0,0,0,.08);
    }

    .vendor-action-btn.login{
        background:#dcfce7;
        color:#166534;
    }

    .vendor-action-btn.edit{
        background:#dbeafe;
        color:#1d4ed8;
    }

    .vendor-action-btn.password{
        background:#fef3c7;
        color:#92400e;
    }

    .vendor-action-btn.delete{
        background:#fee2e2;
        color:#991b1b;
    }

    .vendor-pagination{
        flex-wrap:nowrap;
        gap:20px;
    }

    .vendor-pagination .pagination-info{
        white-space:nowrap;
        flex-shrink:0;
    }

    .vendor-pagination .pagination-controls{
        flex-wrap:nowrap;
        overflow-x:auto;
        overflow-y:hidden;
        margin-left:auto;
        padding-bottom:2px;
    }

    /* EMPTY STATE */
    .empty-vendor{
        grid-column:1/-1;
        text-align:center;
        padding:90px 30px;
        background:#fff;
        border-radius:28px;
        border:2px dashed #cbd5e1;
        animation:fadeIn .6s ease;
    }

    .empty-vendor h3{
        color:#0f172a;
        margin-bottom:10px;
        font-size:1.4rem;
    }

    .empty-vendor p{
        color:#64748b;
        font-size:15px;
    }

    /* PAGINATION */
    .pagination-wrap{
        margin-top:40px;
        display:flex;
        justify-content:center;
    }

    /* ANIMATIONS */
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

    @keyframes cardFade{
        from{
            opacity:0;
            transform:translateY(35px);
        }
        to{
            opacity:1;
            transform:translateY(0);
        }
    }

    @keyframes fadeIn{
        from{
            opacity:0;
            transform:scale(.96);
        }
        to{
            opacity:1;
            transform:scale(1);
        }
    }

    /* RESPONSIVE */
    @media (max-width:1200px){
        .vendor-grid{
            grid-template-columns:repeat(3,1fr);
        }
    }

    @media (max-width:900px){
        .vendor-grid{
            grid-template-columns:repeat(2,1fr);
        }
    }

    @media (max-width:600px){

        .vendors-header{
                flex-direction:column;
            align-items:flex-start;
        }

        .vendor-grid{
            grid-template-columns:1fr;s
        }

        .vendors-title{  
            font-size:1.6rem !important;
        }
    }
</style>

                            <div class="vendor-grid">
                                @forelse($vendors as $vendor)
                                    @php
                                        $vendorName = $vendor->display_name ?? trim(($vendor->first_name ?? '') . ' ' . ($vendor->last_name ?? ''));
                                        $vendorName = trim((string) $vendorName) !== '' ? $vendorName : 'Unnamed Vendor';
                                    @endphp
                                    <div class="vendor-card">
                                        <a class="vendor-card-main" href="{{ route('admin.vendors.show', ['vendor' => $vendor->id]) }}" aria-label="View {{ $vendorName }} details">
                                            <div class="vendor-logo-wrap">
                                                @if($vendor->photo)
                                                    <img src="{{ \App\Support\MediaPath::url($vendor->photo, 'storage/public/vendor_photos') }}" alt="{{ $vendorName }}">
                                                @else
                                                    <div style="font-size: 2rem; font-weight: 800; color: #e2e8f0;">{{ substr($vendorName, 0, 1) }}</div>
                                                @endif
                                            </div>
                                            <h3 class="vendor-name">{{ $vendorName }}</h3>
                                        </a>

                                        {{-- <div class="vendor-card-actions" aria-label="{{ $vendorName }} actions">
                                            <form method="POST" action="{{ route('admin.vendors.secret-login', ['vendor' => $vendor->id]) }}">
                                                @csrf
                                                <button type="submit" class="vendor-action-btn login">Secret Login</button>
                                            </form>
                                            <a class="vendor-action-btn edit" href="{{ route('admin.vendors.edit', ['vendor' => $vendor->id]) }}">Edit</a>
                                            <a class="vendor-action-btn password" href="{{ route('admin.vendors.password', ['vendor' => $vendor->id]) }}">Password</a>
                                            <form method="POST" action="{{ route('admin.vendors.destroy', ['vendor' => $vendor->id]) }}" onsubmit="return confirm('Delete this vendor?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="vendor-action-btn delete">Delete</button>
                                            </form>
                                        </div> --}}
                                    </div>
                                @empty
                                    <div style="grid-column: 1/-1; text-align: center; padding: 100px; color: #64748b;">
                                        <h3>No Vendors Found</h3>
                                        <p>Start adding members to see them here.</p>
                                    </div>
                                @endforelse
                            </div>

                            <div class="custom-pagination-container vendor-pagination" style="margin-top: 40px;">
                                <div class="pagination-info">
                                    Showing <span>{{ $vendors->firstItem() ?? 0 }}</span> to <span>{{ $vendors->lastItem() ?? 0 }}</span> of <span>{{ $vendors->total() }}</span> entries
                                </div>
                                <div class="pagination-controls">
                                    @if ($vendors->onFirstPage())
                                        <span class="page-nav disabled">&larr; Prev</span>
                                    @else
                                        <a href="{{ $vendors->appends(request()->query())->previousPageUrl() }}" class="page-nav">&larr; Prev</a>
                                    @endif

                                    @foreach (range(1, $vendors->lastPage()) as $page)
                                        @if ($page == $vendors->currentPage())
                                            <span class="page-num active">{{ $page }}</span>
                                        @else
                                            <a href="{{ $vendors->appends(request()->query())->url($page) }}" class="page-num">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    @if ($vendors->hasMorePages())
                                        <a href="{{ $vendors->appends(request()->query())->nextPageUrl() }}" class="page-nav">Next &rarr;</a>
                                    @else
                                        <span class="page-nav disabled">Next &rarr;</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @elseif ($currentPage === 'vendor-detail')
                        @php
                            $vendorName = $vendor->display_name ?? trim(($vendor->first_name ?? '') . ' ' . ($vendor->last_name ?? ''));
                            $vendorName = trim((string) $vendorName) !== '' ? $vendorName : 'Unnamed Vendor';
                            $vendorPhoto = $vendor->photo ?? null;
                            $vendorPhotoUrl = $vendorPhoto ? \App\Support\MediaPath::url($vendorPhoto, 'storage/public/vendor_photos') : null;
                            $vendorStatus = (int) ($vendor->status ?? 0) === 1 ? 'Active' : 'Inactive';
                        @endphp
                        <style>
                            .vendor-detail-page { padding: 30px; background: #f8fafc; min-height: 100vh; }
                            .vendor-detail-top { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 18px; }
                            .vendor-detail-back { display: inline-flex; align-items: center; gap: 8px; color: #1d4ed8; background: #eef5ff; border: 1px solid #dbeafe; padding: 9px 14px; border-radius: 6px; font-weight: 700; text-decoration: none; }
                            .vendor-detail-shell { display: grid; grid-template-columns: minmax(260px, 340px) minmax(0, 1fr); gap: 22px; align-items: start; }
                            .vendor-detail-card, .vendor-detail-panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06); }
                            .vendor-detail-card { padding: 24px; text-align: center; }
                            .vendor-detail-photo { width: 120px; height: 120px; margin: 0 auto 18px; border-radius: 50%; background: #f1f5f9; border: 4px solid #fff; box-shadow: 0 8px 22px rgba(15, 23, 42, 0.12); display: flex; align-items: center; justify-content: center; overflow: hidden; color: #94a3b8; font-size: 42px; font-weight: 800; }
                            .vendor-detail-photo img { width: 100%; height: 100%; object-fit: cover; }
                            .vendor-detail-card h2 { margin: 0 0 6px; color: #0f172a; font-size: 24px; font-weight: 800; }
                            .vendor-detail-card p { margin: 0; color: #64748b; font-size: 14px; }
                            .vendor-detail-status { display: inline-flex; margin-top: 16px; padding: 6px 12px; border-radius: 999px; font-size: 12px; font-weight: 800; color: #166534; background: #dcfce7; }
                            .vendor-detail-status.inactive { color: #991b1b; background: #fee2e2; }
                            .vendor-detail-panel { padding: 22px; }
                            .vendor-detail-panel-head { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 16px; }
                            .vendor-detail-panel h3 { margin: 0; color: #111827; font-size: 18px; font-weight: 800; }
                            .vendor-detail-menu { position: relative; }
                            .vendor-detail-menu summary { list-style: none; width: 36px; height: 36px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; color: #475569; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; font-size: 22px; line-height: 1; user-select: none; }
                            .vendor-detail-menu summary::-webkit-details-marker { display: none; }
                            .vendor-detail-menu summary:hover { background: #f8fafc; color: #0f172a; }
                            .vendor-detail-menu-list { position: absolute; right: 0; top: calc(100% + 8px); z-index: 20; min-width: 190px; padding: 8px; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 18px 40px rgba(15, 23, 42, 0.14); }
                            .vendor-detail-menu-list form { margin: 0; }
                            .vendor-detail-menu-item { width: 100%; border: 0; background: transparent; border-radius: 6px; padding: 10px 12px; color: #334155; font-size: 13px; font-weight: 700; text-align: left; text-decoration: none; cursor: pointer; display: block; }
                            .vendor-detail-menu-item:hover { background: #f8fafc; color: #0f172a; }
                            .vendor-detail-menu-item.delete { color: #b91c1c; }
                            .vendor-detail-menu-item.delete:hover { background: #fef2f2; color: #991b1b; }
                            .vendor-detail-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
                            .vendor-detail-item { border: 1px solid #edf2f7; border-radius: 6px; padding: 13px 14px; background: #fbfdff; }
                            .vendor-detail-item span { display: block; margin-bottom: 5px; color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase; }
                            .vendor-detail-item strong { display: block; color: #0f172a; font-size: 14px; overflow-wrap: anywhere; }
                            .vendor-detail-bio { margin-top: 16px; padding: 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #334155; line-height: 1.6; }
                            .vendor-properties { margin-top: 22px; }
                            .vendor-properties table { width: 100%; border-collapse: collapse; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
                            .vendor-properties th, .vendor-properties td { padding: 12px 14px; border-bottom: 1px solid #e5e7eb; text-align: left; font-size: 14px; }
                            .vendor-properties th { background: #f8fafc; color: #475569; font-size: 12px; text-transform: uppercase; }
                            .vendor-properties tr:last-child td { border-bottom: 0; }
                            .vendor-property-actions { display: flex; gap: 8px; align-items: center; }
                            .vendor-property-actions form { margin: 0; }
                            .vendor-property-action { border: 0; border-radius: 6px; padding: 7px 10px; font-size: 12px; font-weight: 700; text-decoration: none; cursor: pointer; }
                            .vendor-property-action.edit { color: #1d4ed8; background: #dbeafe; }
                            .vendor-property-action.delete { color: #991b1b; background: #fee2e2; }
                            @media (max-width: 900px) {
                                .vendor-detail-shell { grid-template-columns: 1fr; }
                                .vendor-detail-grid { grid-template-columns: 1fr; }
                                .vendor-detail-top { align-items: flex-start; flex-direction: column; }
                            }
                        </style>

                        <div class="vendor-detail-page">
                            <div class="vendor-detail-top">
                                <div>
                                    <h2 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0;">{{ $vendorName }}</h2>
                                    <p style="margin: 6px 0 0; color: #64748b;">Vendor database details</p>
                                </div>
                                <a class="vendor-detail-back" href="{{ route('admin.section', ['section' => 'registered-vendors']) }}">Back to Vendors</a>
                            </div>

                            <div class="vendor-detail-shell">
                                <aside class="vendor-detail-card">
                                    <div class="vendor-detail-photo">
                                        @if($vendorPhotoUrl)
                                            <img src="{{ $vendorPhotoUrl }}" alt="{{ $vendorName }}">
                                        @else
                                            {{ substr($vendorName, 0, 1) }}
                                        @endif
                                    </div>
                                    <h2>{{ $vendorName }}</h2>
                                    <p>{{ $vendor->email ?? 'No email' }}</p>
                                    <span class="vendor-detail-status {{ $vendorStatus === 'Inactive' ? 'inactive' : '' }}">{{ $vendorStatus }}</span>
                                </aside>

                                <section class="vendor-detail-panel">
                                    <div class="vendor-detail-panel-head">
                                        <h3>Vendor Information</h3>
                                        <details class="vendor-detail-menu">
                                            <summary aria-label="Vendor actions">&vellip;</summary>
                                            <div class="vendor-detail-menu-list">
                                                <form method="POST" action="{{ route('admin.vendors.secret-login', ['vendor' => $vendor->id]) }}">
                                                    @csrf
                                                    <button type="submit" class="vendor-detail-menu-item">Secret Login</button>
                                                </form>
                                                <a class="vendor-detail-menu-item" href="{{ route('admin.vendors.edit', ['vendor' => $vendor->id]) }}">Edit</a>
                                                <a class="vendor-detail-menu-item" href="{{ route('admin.vendors.password', ['vendor' => $vendor->id]) }}">Change Password</a>
                                                <form method="POST" action="{{ route('admin.vendors.destroy', ['vendor' => $vendor->id]) }}" onsubmit="return confirm('Delete this vendor?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="vendor-detail-menu-item delete">Delete</button>
                                                </form>
                                            </div>
                                        </details>
                                    </div>
                                    <div class="vendor-detail-grid">
                                        <div class="vendor-detail-item"><span>First Name</span><strong>{{ $vendor->first_name ?? '-' }}</strong></div>
                                        <div class="vendor-detail-item"><span>Last Name</span><strong>{{ $vendor->last_name ?? '-' }}</strong></div>
                                        <div class="vendor-detail-item"><span>Email</span><strong>{{ $vendor->email ?? '-' }}</strong></div>
                                        <div class="vendor-detail-item"><span>Phone</span><strong>{{ $vendor->phone ?? '-' }}</strong></div>
                                        <div class="vendor-detail-item"><span>Username</span><strong>{{ $vendor->username ?? '-' }}</strong></div>
                                        <div class="vendor-detail-item"><span>Request Status</span><strong>{{ $vendor->vendor_request ?? '-' }}</strong></div>
                                        {{-- <div class="vendor-detail-item"><span>Amount</span><strong>{{ $vendor->amount ?? '0' }}</strong></div>
                                        <div class="vendor-detail-item"><span>Average Rating</span><strong>{{ $vendor->avg_rating ?? '0' }}</strong></div> --}}
                                        <div class="vendor-detail-item"><span>Created</span><strong>{{ !empty($vendor->created_at) ? \Illuminate\Support\Carbon::parse($vendor->created_at)->format('d M Y, H:i') : '-' }}</strong></div>
                                        <div class="vendor-detail-item"><span>Updated</span><strong>{{ !empty($vendor->updated_at) ? \Illuminate\Support\Carbon::parse($vendor->updated_at)->format('d M Y, H:i') : '-' }}</strong></div>
                                    </div>

                                    <div class="vendor-detail-bio">
                                        {{ $vendor->details ?? 'No vendor details added.' }}
                                    </div>

                                    {{-- <div class="vendor-properties">
                                        <h3>Vendor Properties</h3>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Property</th>
                                                    <th>Type</th>
                                                    <th>Location</th>
                                                    <th>Status</th>
                                                    <th>Approval</th>
                                                    <th>Created</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse(($vendorProperties ?? collect()) as $property)
                                                    <tr>
                                                        <td>{{ $property->property_name ?? 'Untitled Property' }}</td>
                                                        <td>{{ $property->type ?? '-' }}</td>
                                                        <td>{{ $property->property_area ?? $property->city ?? '-' }}</td>
                                                        <td>{{ isset($property->status) ? ((int) $property->status === 1 ? 'Active' : 'Inactive') : '-' }}</td>
                                                        <td>{{ isset($property->approve_status) ? ((int) $property->approve_status === 1 ? 'Approved' : ((int) $property->approve_status === 0 ? 'Rejected' : 'Pending')) : '-' }}</td>
                                                        <td>{{ !empty($property->created_at) ? \Illuminate\Support\Carbon::parse($property->created_at)->format('d M Y') : '-' }}</td>
                                                        <td>
                                                            <div class="vendor-property-actions">
                                                                <a class="vendor-property-action edit" href="{{ route('admin.properties.edit', ['id' => $property->id]) }}">Edit</a>
                                                                <form method="POST" action="{{ route('admin.properties.destroy', ['id' => $property->id]) }}" onsubmit="return confirm('Delete this property?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="vendor-property-action delete">Delete</button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" style="text-align:center; color:#64748b;">No properties found for this vendor.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div> --}}
                                </section>
                            </div>
                        </div>
                 @elseif ($currentPage === 'property-enquiry')

<style>

    /* =========================
       GOOGLE FONT
    ========================== */

    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    .analysis-wrap{
        font-family:'Inter',sans-serif;
        animation:fadeSlideUp .6s ease;
    }

    /* =========================
       ANIMATIONS
    ========================== */

    @keyframes fadeSlideUp{
        from{
            opacity:0;
            transform:translateY(25px);
        }
        to{
            opacity:1;
            transform:translateY(0);
        }
    }

    @keyframes rowFade{
        from{
            opacity:0;
            transform:translateX(-20px);
        }
        to{
            opacity:1;
            transform:translateX(0);
        }
    }

    @keyframes pulseGlow{
        0%,100%{
            box-shadow:0 0 0 rgba(0,0,0,0);
        }
        50%{
            box-shadow:0 0 18px rgba(0,0,0,0.10);
        }
    }

    /* =========================
       CARD
    ========================== */

    .analysis-card{
        background:#ffffff !important;
        border-radius:24px !important;
        padding:28px !important;
        border:1px solid #e2e8f0 !important;
        box-shadow:
            0 10px 30px rgba(15,23,42,0.06),
            0 2px 8px rgba(15,23,42,0.04) !important;
    }

    .analysis-card-header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:28px;
        flex-wrap:wrap;
        gap:14px;
    }

    .analysis-card-header h3{
        font-size:30px !important;
        font-weight:800 !important;
        color:#0f172a !important;
        letter-spacing:-0.5px;
    }

    /* =========================
       TOTAL BADGE
    ========================== */

    .total-badge{
        background:linear-gradient(
            135deg,
            #2563eb,
            #1d4ed8
        );
        color:#fff;
        padding:10px 18px;
        border-radius:999px;
        font-size:13px;
        font-weight:700;
        box-shadow:0 10px 20px rgba(37,99,235,0.22);
        animation:pulseGlow 3s infinite;
    }

    /* =========================
       TABLE
    ========================== */

    .table-container{
        overflow-x:auto;
        border-radius:20px;
    }

    .enquiry-table{
        width:100%;
        border-collapse:collapse;
        overflow:hidden;
    }

    .enquiry-table thead tr{
        background:#f8fafc;
        border-bottom:1px solid #e2e8f0;
    }

    .enquiry-table th{
        padding:18px 16px;
        color:#64748b;
        font-size:12px;
        font-weight:700;
        text-transform:uppercase;
        letter-spacing:.6px;
        text-align:left;
    }

    .enquiry-table td{
        padding:18px 16px;
        font-size:14px;
        border-bottom:1px solid #f1f5f9;
        vertical-align:middle;
    }

    .enquiry-row{
        transition:all .3s ease;
        animation:rowFade .5s ease both;
    }

    @foreach($enquiries as $index => $item)
        .enquiry-row:nth-child({{ $index + 1 }}){
            animation-delay:{{ $index * 0.05 }}s;
        }
    @endforeach

    .enquiry-row:hover{
        background:linear-gradient(
            90deg,
            rgba(37,99,235,0.05),
            rgba(255,255,255,1)
        );
        transform:translateY(-2px);
        box-shadow:0 8px 18px rgba(15,23,42,0.04);
    }

    /* =========================
       CUSTOMER
    ========================== */

    .customer-name{
        font-size:15px;
        font-weight:700;
        color:#0f172a;
    }

    /* =========================
       CONTACT
    ========================== */

    .contact-email{
        color:#64748b;
        font-size:13px;
        margin-bottom:6px;
    }

    .contact-mobile{
        color:#1a82ab;
        font-weight:700;
        font-size:14px;
    }

    /* =========================
       PROPERTY BADGE
    ========================== */

    .property-badge{
        display:inline-flex;
        align-items:center;
        padding:9px 14px;
        border-radius:999px;
        background:#eff6ff;
        color:#18181a;
        font-size:13px;
        font-weight:700;
    }

    /* =========================
       STATUS SELECT
    ========================== */

    .status-select{
        border:none;
        border-radius:12px;
        padding:10px 14px;
        min-width:130px;
        font-size:13px;
        font-weight:700;
        cursor:pointer;
        transition:all .3s ease;
        font-family:'Inter',sans-serif;
        animation:pulseGlow 3s infinite;
    }

    .status-select:hover{
        transform:translateY(-2px);
    }

    .status-select:focus{
        outline:none;
    }

    /* RECEIVED = GREEN */

    .status-received{
        background:#dcfce7;
        color:#15803d;
        border:1px solid #bbf7d0;
        box-shadow:0 4px 14px rgba(34,197,94,0.16);
    }

    /* CLOSED = PURPLE */

    .status-closed{
        background:#f3e8ff;
        color:#7e22ce;
        border:1px solid #d8b4fe;
        box-shadow:0 4px 14px rgba(168,85,247,0.16);
    }

    /* =========================
       PAGINATION
    ========================== */

    .pagination-wrapper{
        margin-top:28px;
        padding-top:20px;
        border-top:1px solid #f1f5f9;
    }

    .custom-pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
        font-family: 'Inter', sans-serif;
        flex-wrap: wrap;
        gap: 16px;
    }

    .pagination-info {
        font-size: 14px;
        color: #64748b;
    }

    .pagination-info span {
        font-weight: 700;
        color: #0f172a;
    }

    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pagination-controls .page-nav,
    .pagination-controls .page-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        padding: 0 16px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .pagination-controls .page-num {
        width: 40px;
        padding: 0;
    }

    .pagination-controls .page-nav:hover:not(.disabled),
    .pagination-controls .page-num:hover:not(.active) {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
        transform: translateY(-1px);
    }

    .pagination-controls .page-num.active {
        background: #2563eb;
        border-color: #2563eb;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    .pagination-controls .disabled {
        color: #94a3b8;
        background: #f8fafc;
        border-color: #e2e8f0;
        cursor: not-allowed;
        opacity: 0.7;
    }

</style>

                <div class="analysis-wrap" style="padding:24px;">

                    <div class="analysis-card">

                        <!-- HEADER -->

                        <div class="analysis-card-header">

                            <h3>
                                Property Enquiries
                            </h3>

                            <span class="total-badge">
                                Total: {{ $enquiries->total() }}
                            </span>

                        </div>

                        <!-- TABLE -->

                        <div class="table-container">

                            <table class="enquiry-table">

                                <thead>

                                    <tr>

                                        <th>Date</th>

                                        <th>Customer</th>

                                        <th>Contact</th>

                                        <th>Property</th>

                                        <th>Status</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($enquiries as $item)

                                        @php
                                            $enquiryStatus = strtolower($item->enquiry_status ?? 'received');
                                        @endphp

                                        <tr class="enquiry-row">

                                            <!-- DATE -->

                                            <td>

                                                <div style="font-weight:600; color:#334155;">
                                                    {{ $item->created_at->format('d M Y') }}
                                                </div>

                                            </td>

                                            <!-- CUSTOMER -->

                                            <td>

                                                <div class="customer-name">
                                                    {{ $item->name }}
                                                </div>

                                            </td>

                                            <!-- CONTACT -->

                                            <td>

                                                <div class="contact-email">
                                                    {{ $item->email }}
                                                </div>

                                                <div class="contact-mobile">
                                                    {{ $item->mobile }}
                                                </div>

                                            </td>

                                            <!-- PROPERTY -->

                                            <td>

                                                <span class="property-badge">

                                                    {{ $item->property_name ?? 'Property ID: '.$item->property_id }}

                                                </span>

                                            </td>

                                            <!-- STATUS -->

                                            <td>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.enquiries.updateStatus', ['enquiry' => $item->id]) }}"
                                                >

                                                    @csrf
                                                    @method('PUT')

                                                    <select
                                                        name="account_status"
                                                        class="status-select {{ $enquiryStatus === 'received' ? 'status-received' : 'status-closed' }}"
                                                        onchange="
                                                            this.className='status-select ' +
                                                            (this.value==='received'
                                                                ? 'status-received'
                                                                : 'status-closed');
                                                            this.form.submit();
                                                        "
                                                    >

                                                        <option
                                                            value="received"
                                                            {{ $enquiryStatus === 'received' ? 'selected' : '' }}
                                                        >
                                                            Received
                                                        </option>

                                                        <option
                                                            value="closed"
                                                            {{ $enquiryStatus === 'closed' ? 'selected' : '' }}
                                                        >
                                                            Closed
                                                        </option>

                                                    </select>

                                                </form>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                        {{-- Premium Pagination --}}
                        @if($enquiries && $enquiries->total() > 0)
                        <div class="custom-pagination-container">
                            <div class="pagination-info">
                                Showing <span>{{ $enquiries->firstItem() }}</span> to <span>{{ $enquiries->lastItem() }}</span> of <span>{{ $enquiries->total() }}</span> entries
                            </div>
                            <div class="pagination-controls">
                                @if ($enquiries->onFirstPage())
                                    <span class="page-nav disabled">&larr; Prev</span>
                                @else
                                    <a href="{{ $enquiries->appends(request()->query())->previousPageUrl() }}" class="page-nav">&larr; Prev</a>
                                @endif

                                @foreach (range(1, $enquiries->lastPage()) as $page)
                                    @if ($page == $enquiries->currentPage())
                                        <span class="page-num active">{{ $page }}</span>
                                    @else
                                        <a href="{{ $enquiries->appends(request()->query())->url($page) }}" class="page-num">{{ $page }}</a>
                                    @endif
                                @endforeach

                                @if ($enquiries->hasMorePages())
                                    <a href="{{ $enquiries->appends(request()->query())->nextPageUrl() }}" class="page-nav">Next &rarr;</a>
                                @else
                                    <span class="page-nav disabled">Next &rarr;</span>
                                @endif
                            </div>
                        </div>
                        @endif

                    </div>

                </div>
                                        </div>
@elseif ($currentPage === 'interior-enquiries')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    .interior-enquiry-wrap{
        font-family: 'Inter', sans-serif;
        animation: enquiryPageFade 0.6s ease;
    }

    @keyframes enquiryPageFade{
        from{
            opacity:0;
            transform:translateY(18px);
        }
        to{
            opacity:1;
            transform:translateY(0);
        }
    }

    @keyframes enquiryRowFade{
        from{
            opacity:0;
            transform:translateX(-20px);
        }
        to{
            opacity:1;
            transform:translateX(0);
        }
    }

    @keyframes badgePulse{
        0%,100%{
            transform:scale(1);
        }
        50%{
            transform:scale(1.04);
        }
    }

    .interior-card{
        background:#ffffff;
        border-radius:24px;
        padding:26px;
        box-shadow:0 12px 35px rgba(15,23,42,0.06);
        border:1px solid #edf2f7;
    }

    .interior-header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:26px;
        flex-wrap:wrap;
        gap:14px;
    }

    .interior-title{
        font-size:1.45rem;
        font-weight:800;
        color:#0f172a;
        margin:0;
        letter-spacing:-0.4px;
    }

    .interior-count{
        background:linear-gradient(135deg,#7c3aed,#9333ea);
        color:#fff;
        padding:10px 18px;
        border-radius:40px;
        font-size:0.85rem;
        font-weight:700;
        box-shadow:0 8px 20px rgba(124,58,237,0.25);
    }

    .interior-table-wrap{
        overflow-x:auto;
        border-radius:18px;
    }

    .interior-table{
        width:100%;
        border-collapse:collapse;
        min-width:950px;
    }

    .interior-table thead tr{
        background:#f8fafc;
    }

    .interior-table th{
        padding:16px 18px;
        text-align:left;
        font-size:0.8rem;
        font-weight:700;
        color:#64748b;
        text-transform:uppercase;
        letter-spacing:0.8px;
        border-bottom:1px solid #e2e8f0;
    }

    .interior-table td{
        padding:18px;
        border-bottom:1px solid #f1f5f9;
        vertical-align:middle;
    }

    .interior-row{
        animation:enquiryRowFade 0.45s ease both;
        transition:all .25s ease;
    }

    @foreach($interiorEnquiries as $index => $item)
        .interior-row:nth-child({{ $index + 1 }}){
            animation-delay:{{ $index * 0.05 }}s;
        }
    @endforeach

    .interior-row:hover{
        background:linear-gradient(90deg,rgba(124,58,237,0.06),transparent);
        transform:translateX(3px);
        box-shadow:inset 4px 0 0 #7c3aed;
    }

    .customer-name{
        font-size:0.95rem;
        font-weight:700;
        color:#0f172a;
    }

    .contact-email{
        font-size:0.84rem;
        color:#64748b;
        margin-bottom:4px;
    }

    .contact-mobile{
        font-size:0.9rem;
        font-weight:700;
        color:#1a82ab;
    }

    .property-name{
        color:#1e293b;
        font-weight:600;
        font-size:0.9rem;
    }

    .date-text{
        color:#64748b;
        font-size:0.88rem;
        font-weight:500;
    }

    .status-select{
        border:none;
        border-radius:12px;
        padding:10px 16px;
        font-size:0.82rem;
        font-weight:700;
        cursor:pointer;
        min-width:130px;
        transition:all .25s ease;
        animation:badgePulse 2.2s infinite;
    }

    .status-select:hover{
        transform:translateY(-2px);
    }

    .status-select:focus{
        outline:none;
    }

    /* Pending */
    .status-pending{
        background:#fff1f2;
        color:#e11d48;
        box-shadow:0 0 0 2px rgba(225,29,72,0.10);
    }

    /* Contacted */
    .status-contacted{
        background:#f3e8ff;
        color:#7e22ce;
        box-shadow:0 0 0 2px rgba(25, 204, 147, 0.1);
    }

    /* Closed */
    .status-closed{
        background:#ecfdf5;
        color:#059669;
        box-shadow:0 0 0 2px rgba(126,34,206,0.10);
    }

    .pagination-wrap{
        margin-top:24px;
    }

    .pagination-wrap nav{
        display:flex;
        justify-content:center;
    }

    .custom-pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
        font-family: 'Inter', sans-serif;
        flex-wrap: wrap;
        gap: 16px;
    }

    .pagination-info {
        font-size: 14px;
        color: #64748b;
    }

    .pagination-info span {
        font-weight: 700;
        color: #0f172a;
    }

    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pagination-controls .page-nav,
    .pagination-controls .page-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        padding: 0 16px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .pagination-controls .page-num {
        width: 40px;
        padding: 0;
    }

    .pagination-controls .page-nav:hover:not(.disabled),
    .pagination-controls .page-num:hover:not(.active) {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
        transform: translateY(-1px);
    }

    .pagination-controls .page-num.active {
        background: #2563eb;
        border-color: #2563eb;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    .pagination-controls .disabled {
        color: #94a3b8;
        background: #f8fafc;
        border-color: #e2e8f0;
        cursor: not-allowed;
        opacity: 0.7;
    }

</style>

<div class="interior-enquiry-wrap">
    <div class="interior-card">

        <div class="interior-header">
            <h3 class="interior-title">Interior Enquiries</h3>

            <span class="interior-count">
                Total: {{ $interiorEnquiries->total() }}
            </span>
        </div>

        <div class="interior-table-wrap">
            <table class="interior-table">

                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Property</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($interiorEnquiries as $item)

                        @php
                            $status = strtolower($item->status ?? 'pending');

                            $statusClass = match($status){
                                'pending' => 'status-pending',
                                'contacted' => 'status-contacted',
                                'closed' => 'status-closed',
                                default => 'status-pending'
                            };
                        @endphp

                        <tr class="interior-row">

                            <td>
                                <div class="date-text">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                </div>
                            </td>

                            <td>
                                <div class="customer-name">
                                    {{ $item->name }}
                                </div>
                            </td>

                            <td>
                                <div class="contact-email">
                                    {{ $item->email }}
                                </div>

                                <div class="contact-mobile">
                                    {{ $item->mobilenumber }}
                                </div>
                            </td>

                            <td>
                                <div class="property-name">
                                    {{ $item->propertyname ?? 'N/A' }}
                                </div>
                            </td>

                            <td>
                                <form method="POST" action="{{ route('admin.interior-enquiries.updateStatus', ['id' => $item->id]) }}">
                                    @csrf
                                    @method('PUT')

                                    <select 
                                        name="account_status"
                                        class="status-select {{ $statusClass }}"
                                        onchange="
                                            this.classList.remove('status-pending','status-contacted','status-closed');

                                            if(this.value === 'pending'){
                                                this.classList.add('status-pending');
                                            }
                                            else if(this.value === 'contacted'){
                                                this.classList.add('status-contacted');
                                            }
                                            else{
                                                this.classList.add('status-closed');
                                            }

                                            this.form.submit();
                                        "
                                    >
                                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>

                                        <option value="contacted" {{ $status === 'contacted' ? 'selected' : '' }}>
                                            Contacted
                                        </option>

                                        <option value="closed" {{ $status === 'closed' ? 'selected' : '' }}>
                                            Closed
                                        </option>

                                    </select>
                                </form>
                            </td>

                        </tr>

                    @endforeach
                </tbody>

            </table>
        </div>

        {{-- Premium Pagination --}}
        @if($interiorEnquiries && $interiorEnquiries->total() > 0)
        <div class="custom-pagination-container">
            <div class="pagination-info">
                Showing <span>{{ $interiorEnquiries->firstItem() }}</span> to <span>{{ $interiorEnquiries->lastItem() }}</span> of <span>{{ $interiorEnquiries->total() }}</span> entries
            </div>
            <div class="pagination-controls">
                @if ($interiorEnquiries->onFirstPage())
                    <span class="page-nav disabled">&larr; Prev</span>
                @else
                    <a href="{{ $interiorEnquiries->appends(request()->query())->previousPageUrl() }}" class="page-nav">&larr; Prev</a>
                @endif

                @foreach (range(1, $interiorEnquiries->lastPage()) as $page)
                    @if ($page == $interiorEnquiries->currentPage())
                        <span class="page-num active">{{ $page }}</span>
                    @else
                        <a href="{{ $interiorEnquiries->appends(request()->query())->url($page) }}" class="page-num">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($interiorEnquiries->hasMorePages())
                    <a href="{{ $interiorEnquiries->appends(request()->query())->nextPageUrl() }}" class="page-nav">Next &rarr;</a>
                @else
                    <span class="page-nav disabled">Next &rarr;</span>
                @endif
            </div>
        </div>
        @endif

    </div>
</div>

                    @elseif ($currentPage === 'call-enquiries' || $currentPage === 'whatsapp-enquiries')
                        <div class="analysis-wrap" style="padding: 24px;">
                            <div class="analysis-card" style="background: #fff; border-radius: 20px; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); border: 1px solid #f0f2f5;">
                                <div class="analysis-card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">{{ ucfirst($interactionType) }} Enquiries</h3>
                                    <span style="background: var(--brand); color: #fff; padding: 6px 16px; border-radius: 30px; font-weight: 600; font-size: 0.85rem;">Total Logs: {{ $interactions->total() }}</span>
                                </div>
                                <div class="table-container" style="overflow-x: auto;">
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <thead>
                                            <tr style="border-bottom: 2px solid #f8fafc; text-align: left; height: 50px;">
                                                <th style="padding: 12px; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Date</th>
                                                <th style="padding: 12px; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Customer Name</th>
                                                <th style="padding: 12px; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Phone Number</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($interactions as $item)
                                                <tr style="border-bottom: 1px solid #f8fafc; transition: background 0.2s;" onmouseover="this.style.background='#fcfdfe'" onmouseout="this.style.background='transparent'">
                                                    <td style="padding: 16px 12px; font-size: 0.95rem; color: #1e293b;">{{ $item->created_at->format('M d, Y') }} <br><small style="color: #94a3b8;">{{ $item->created_at->format('H:i') }}</small></td>
                                                    <td style="padding: 16px 12px; font-size: 0.95rem; font-weight: 600; color: #1e293b;">{{ $item->customer_name ?: 'Unknown' }}</td>
                                                    <td style="padding: 16px 12px; font-size: 0.95rem; font-family: monospace; color: var(--brand);">{{ $item->customer_phone ?: 'N/A' }}</td>
                                                    <td style="padding: 16px 12px; font-size: 0.95rem; color: #64748b; line-height: 1.5;">{{ Str::limit($item->notes, 80) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div style="margin-top: 24px;">{{ $interactions->links() }}</div>
                            </div>
                        </div>

                    @elseif ($currentPage === 'enquiry-analysis')
                        <div class="analysis-dashboard" style="padding: 30px; background: #f8fafc; min-height: 100vh;">
                            <style>
                                .dash-title { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 24px; letter-spacing: -0.02em; }
                                .card-main { background: #fff; border-radius: 24px; padding: 30px; box-shadow: 0 4px 25px rgba(0,0,0,0.02); border: 1px solid #f1f5f9; margin-bottom: 30px; position: relative; }
                                .card-header-flex { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
                                .card-title { font-size: 1.25rem; font-weight: 700; color: #1e293b; }
                                .legend-item { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; color: #64748b; }
                                .legend-dot { width: 12px; height: 12px; border-radius: 50%; }
                                
                                .bottom-grid { display: grid; grid-template-columns: 1fr 1.2fr 1fr; gap: 30px; }
                                .mini-card { background: #fff; border-radius: 20px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); border: 1px solid #f1f5f9; }
                                
                                .form-group { margin-bottom: 18px; }
                                .form-label { display: block; font-size: 0.85rem; font-weight: 700; color: #475569; margin-bottom: 8px; }
                                .form-input { width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; font-size: 0.9rem; transition: all 0.2s; }
                                .form-input:focus { border-color: var(--brand); outline: none; background: #fff; box-shadow: 0 0 0 4px rgba(24, 164, 234, 0.1); }
                                .btn-submit { background: var(--brand); color: #fff; border: none; padding: 14px; border-radius: 12px; width: 100%; font-weight: 700; cursor: pointer; transition: all 0.2s; }
                                .btn-submit:hover { background: #1593d1; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(24, 164, 234, 0.2); }
                            </style>

                            <h2 class="dash-title">Enquiry Dashboard</h2>

                            <div class="card-main">
                                <div class="card-header-flex">
                                    <div class="card-title">Enquiry Trends (Property vs Others)</div>
                                    <div style="display: flex; gap: 20px;">
                                        <div class="legend-item"><span class="legend-dot" style="background: #fb923c;"></span> Property Enquiries</div>
                                        <div class="legend-item"><span class="legend-dot" style="background: #c084fc;"></span> Support Interactions</div>
                                    </div>
                                </div>
                                <div style="height: 400px; width: 100%;">
                                    <canvas id="mainTrendsChart"></canvas>
                                </div>
                            </div>

                            <div class="bottom-grid">
                                <div class="mini-card">
                                    <h4 class="card-title" style="font-size: 1rem; margin-bottom: 20px;">Property Enquiry Mix</h4>
                                    <div style="height: 250px; position: relative;">
                                        <canvas id="donutChart"></canvas>
                                    </div>
                                    <div style="margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                        <div style="text-align: center;">
                                            <span style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">{{ $totalProperty }}</span><br>
                                            <small style="color: #64748b; font-weight: 600;">Total Properties</small>
                                        </div>
                                        <div style="text-align: center;">
                                            <span style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">{{ $totalCalls + $totalWhatsapp }}</span><br>
                                            <small style="color: #64748b; font-weight: 600;">Logs</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mini-card">
                                    <h4 class="card-title" style="font-size: 1rem; margin-bottom: 20px;">Manual Log Entry</h4>
                                    <form action="{{ route('admin.enquiry.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label class="form-label">Type</label>
                                            <select name="type" class="form-input" style="appearance: none;">
                                                <option value="call">Call Enquiry</option>
                                                <option value="whatsapp">WhatsApp Enquiry</option>
                                                
                                            </select>
                                        </div>
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 18px;">
                                            <div>
                                                <label class="form-label">Name</label>
                                                <input type="text" name="customer_name" class="form-input" placeholder="Name">
                                            </div>
                                            <div>
                                                <label class="form-label">Phone</label>
                                                <input type="text" name="customer_phone" class="form-input" placeholder="Phone">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Notes</label>
                                            <textarea name="notes" class="form-input" rows="2" placeholder="Brief details..."></textarea>
                                        </div>
                                        <button type="submit" class="btn-submit">Add Entry</button>
                                    </form>
                                </div>

                                <div class="mini-card">
                                    <h4 class="card-title" style="font-size: 1rem; margin-bottom: 20px;">Daily Growth</h4>
                                    <div style="height: 250px;">
                                        <canvas id="growthChart"></canvas>
                                    </div>
                                    <div style="margin-top: 15px; font-size: 0.85rem; color: #64748b; text-align: center;">
                                        Overall Engagement: <span style="color: var(--success); font-weight: 700;">+24%</span>
                                    </div>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const ctxMain = document.getElementById('mainTrendsChart').getContext('2d');
                                    
                                    const gradProp = ctxMain.createLinearGradient(0, 0, 0, 400);
                                    gradProp.addColorStop(0, 'rgba(251, 146, 60, 0.45)');
                                    gradProp.addColorStop(1, 'rgba(251, 146, 60, 0)');
                                    
                                    const gradSupport = ctxMain.createLinearGradient(0, 0, 0, 400);
                                    gradSupport.addColorStop(0, 'rgba(192, 132, 252, 0.35)');
                                    gradSupport.addColorStop(1, 'rgba(192, 132, 252, 0)');

                                    new Chart(ctxMain, {
                                        type: 'line',
                                        data: {
                                            labels: {!! json_encode($chartLabels) !!},
                                            datasets: [
                                                { label: 'Property Enquiries', data: {!! json_encode($propertyData) !!}, borderColor: '#fb923c', backgroundColor: gradProp, fill: true, tension: 0.4, borderWidth: 3, pointRadius: 0, pointHoverRadius: 6 },
                                                { label: 'Support Enquiries', data: {!! json_encode(array_map(fn($c, $w) => $c + $w, $callData, $whatsappData)) !!}, borderColor: '#c084fc', backgroundColor: gradSupport, fill: true, tension: 0.4, borderWidth: 3, pointRadius: 0, pointHoverRadius: 6 }
                                            ]
                                        },
                                        options: {
                                            responsive: true, maintainAspectRatio: false,
                                            plugins: { legend: { display: false }, tooltip: { cornerRadius: 10, padding: 15, titleFont: { size: 14 } } },
                                            scales: {
                                                y: { beginAtZero: true, grid: { color: '#f1f5f9', borderDash: [5, 5] }, ticks: { color: '#94a3b8', font: { size: 12 } } },
                                                x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 12 } } }
                                            }
                                        }
                                    });

                                    // Donut Chart
                                    new Chart(document.getElementById('donutChart'), {
                                        type: 'doughnut',
                                        data: {
                                            labels: {!! json_encode(array_keys($statusCounts)) !!},
                                            datasets: [{
                                                data: {!! json_encode(array_values($statusCounts)) !!},
                                                backgroundColor: ['#3880ff', '#22c55e', '#fb923c', '#ef476f'],
                                                borderWidth: 0, cutout: '80%', radius: '90%'
                                            }]
                                        },
                                        options: {
                                            responsive: true, maintainAspectRatio: false,
                                            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15, font: { size: 11 } } } }
                                        }
                                    });

                                    // Small Trend Chart
                                    new Chart(document.getElementById('growthChart'), {
                                        type: 'line',
                                        data: {
                                            labels: {!! json_encode($chartLabels) !!},
                                            datasets: [{
                                                data: {!! json_encode(array_map(fn($p, $c, $w) => $p + $c + $w, $propertyData, $callData, $whatsappData)) !!},
                                                borderColor: '#3880ff', borderWidth: 3, fill: false, tension: 0.5, pointRadius: 4, pointBackgroundColor: '#fff', pointBorderWidth: 2
                                            }]
                                        },
                                        options: {
                                            responsive: true, maintainAspectRatio: false,
                                            plugins: { legend: { display: false } },
                                            scales: { y: { display: false }, x: { grid: { display: false }, ticks: { display: false } } }
                                        }
                                    });
                                });
                            </script>
                        </div>
                    @elseif ($currentPage === 'amenities')
                       <style>
    /* ===== PROFESSIONAL MODERN THEME ===== */

    .amenities-section-wrap{
        animation: pageFadeIn .6s ease;
        font-family: 'Inter', 'Poppins', sans-serif;
    }

    @keyframes pageFadeIn{
        from{
            opacity:0;
            transform:translateY(18px);
        }
        to{
            opacity:1;
            transform:translateY(0);
        }
    }

    @keyframes rowFade{
        from{
            opacity:0;
            transform:translateX(-15px);
        }
        to{
            opacity:1;
            transform:translateX(0);
        }
    }

    @keyframes pulseGlow{
        0%{
            box-shadow:0 0 0 rgba(56,128,255,0.4);
        }
        50%{
            box-shadow:0 0 18px rgba(56,128,255,0.35);
        }
        100%{
            box-shadow:0 0 0 rgba(56,128,255,0.4);
        }
    }

    @keyframes floatingBtn{
        0%,100%{
            transform:translateY(0);
        }
        50%{
            transform:translateY(-3px);
        }
    }

    /* ===== MAIN CARD ===== */

    .data-table-container{
        background:#ffffff;
        border-radius:22px;
        overflow:hidden;
        border:1px solid #e2e8f0;
        box-shadow:
            0 10px 35px rgba(15,23,42,0.05),
            0 2px 8px rgba(15,23,42,0.04);
        transition:.3s ease;
    }

    .data-table-container:hover{
        box-shadow:
            0 15px 45px rgba(15,23,42,0.08),
            0 5px 15px rgba(15,23,42,0.06);
    }

    /* ===== HEADER ===== */

    .amenities-header-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:24px;
    }

    .amenities-title{
        font-size:28px;
        font-weight:800;
        color:#0f172a;
        letter-spacing:-0.5px;
        position:relative;
    }

    .amenities-title::after{
        content:'';
        position:absolute;
        left:0;
        bottom:-8px;
        width:55px;
        height:4px;
        border-radius:30px;
        background:linear-gradient(90deg,#3880ff,#6c63ff);
    }

    /* ===== ADD BUTTON ===== */

    .btn-amenity-add{
        background:linear-gradient(135deg,#3880ff,#6c63ff);
        border:none;
        color:#fff;
        border-radius:14px;
        padding:12px 22px;
        font-size:14px;
        font-weight:700;
        display:flex;
        align-items:center;
        gap:10px;
        cursor:pointer;
        transition:all .3s ease;
        animation:floatingBtn 3s ease-in-out infinite;
        box-shadow:0 8px 20px rgba(56,128,255,.25);
    }

    .btn-amenity-add:hover{
        transform:translateY(-4px) scale(1.03);
        animation:none;
        box-shadow:0 12px 28px rgba(56,128,255,.35);
    }

    .plus-icon{
        font-size:20px;
        transition:.3s ease;
    }

    .btn-amenity-add:hover .plus-icon{
        transform:rotate(90deg);
    }

    /* ===== SEARCH SECTION ===== */

    .table-controls{
        margin-bottom:24px;
        background:#fff;
        border-radius:18px;
        padding:18px 20px;
        border:1px solid #e5e7eb;
        box-shadow:0 4px 12px rgba(15,23,42,0.03);
    }

    .search-control{
        display:flex;
        align-items:center;
        flex-wrap:wrap;
        gap:12px;
        color:#475569;
        font-weight:600;
    }

    .control-input,
    .entries-select{
        border:1px solid #dbe2ea;
        border-radius:12px;
        padding:10px 14px;
        font-size:14px;
        transition:.3s ease;
        background:#f8fafc;
    }

    .control-input:focus,
    .entries-select:focus{
        outline:none;
        border-color:#3880ff;
        background:#fff;
        box-shadow:0 0 0 4px rgba(56,128,255,.12);
    }

    /* ===== TABLE ===== */

    .data-table{
        width:100%;
        border-collapse:collapse;
        overflow:hidden;
    }

    .data-table thead{
        background:linear-gradient(135deg,#0f172a,#1e293b);
    }

    .data-table thead th{
        color:#424040;
        font-size:12px;
        font-weight:700;
        letter-spacing:.5px;
        text-transform:uppercase;
        padding:18px 20px;
    }

    .amenity-row{
        animation:rowFade .5s ease both;
        transition:all .3s ease;
        border-bottom:1px solid #eef2f7;
    }

    @for ($i = 0; $i < count($amenities ?? []); $i++)
        .amenity-row:nth-child({{ $i + 1 }}) {
            animation-delay: {{ $i * 0.05 }}s;
        }
    @endfor

    .amenity-row:hover{
        background:linear-gradient(90deg,rgba(56,128,255,.06),transparent);
        transform:scale(1.002);
    }

    .data-table td{
        padding:18px 20px;
        color:#334155;
        font-size:14px;
    }

    /* ===== ICON ===== */

    .icon-preview{
        width:42px;
        height:42px;
        display:flex;
        align-items:center;
        justify-content:center;
        border-radius:14px;
        background:linear-gradient(135deg,#eff6ff,#eef2ff);
        color:#3880ff;
        transition:.35s ease;
    }

    .amenity-row:hover .icon-preview{
        transform:scale(1.12) rotate(-6deg);
        background:linear-gradient(135deg,#3880ff,#6c63ff);
        color:#fff;
    }

    /* ===== BADGES ===== */

    .badge{
        padding:8px 16px;
        border-radius:30px;
        font-size:12px;
        font-weight:700;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:90px;
        animation:pulseGlow 2.5s infinite;
    }

    .badge-active,
    .badge-completed{
        background:#dcfce7;
        color:#16a34a;
    }

    .badge-inactive,
    .badge-processing{
        background:#fee2e2;
        color:#dc2626;
    }

    /* ===== ACTION BUTTONS ===== */

    .actions{
        display:flex;
        gap:10px;
        align-items:center;
    }

    .btn-action{
        width:38px;
        height:38px;
        border:none;
        border-radius:12px;
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        transition:all .25s ease;
    }

    .btn-edit{
        background:#e0ecff;
        color:#2563eb;
    }

    .btn-edit:hover{
        transform:translateY(-3px) rotate(8deg);
        background:#2563eb;
        color:#fff;
        box-shadow:0 10px 20px rgba(37,99,235,.25);
    }

    .btn-delete{
        background:#ffe4e6;
        color:#e11d48;
    }

    .btn-delete:hover{
        transform:translateY(-3px) scale(1.08);
        background:#e11d48;
        color:#fff;
        box-shadow:0 10px 20px rgba(225,29,72,.25);
    }

    /* ===== PAGINATION ===== */

    .pagination-footer{
        padding:18px 20px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        background:#f8fafc;
        border-top:1px solid #e2e8f0;
    }

    .pagination-info{
        font-size:13px;
        color:#64748b;
        font-weight:500;
    }

    /* ===== MODAL ===== */

    .modal{
        background:#fff;
        border-radius:24px;
        overflow:hidden;
        border:1px solid #e2e8f0;
        box-shadow:0 25px 60px rgba(15,23,42,.18);
        animation:pageFadeIn .35s ease;
    }

    .modal-header{
        padding:24px;
        border-bottom:1px solid #eef2f7;
        background:linear-gradient(135deg,#f8fbff,#f8faff);
    }

    .modal-title{
        font-size:24px;
        font-weight:800;
        color:#0f172a;
    }

    .modal-subtitle{
        color:#64748b;
        margin-top:5px;
    }

    .modal-body{
        padding:24px;
    }

    .modal-footer{
        padding:20px 24px;
        border-top:1px solid #eef2f7;
        display:flex;
        justify-content:flex-end;
        gap:12px;
        background:#fafcff;
    }

    /* ===== FORM ===== */

    .form-label{
        display:block;
        margin-bottom:8px;
        font-size:14px;
        font-weight:700;
        color:#1e293b;
    }

    .form-control{
        width:100%;
        border:1px solid #dbe2ea;
        border-radius:14px;
        padding:12px 14px;
        font-size:14px;
        background:#f8fafc;
        transition:.3s ease;
    }

    .form-control:focus{
        outline:none;
        border-color:#3880ff;
        background:#fff;
        box-shadow:0 0 0 4px rgba(56,128,255,.12);
    }

    .help-text{
        display:block;
        margin-top:8px;
        color:#64748b;
        font-size:12px;
    }

    /* ===== BUTTONS ===== */

    .btn-primary{
        background:linear-gradient(135deg,#3880ff,#6c63ff);
        color:#fff;
        border:none;
        padding:12px 24px;
        border-radius:12px;
        font-weight:700;
        cursor:pointer;
        transition:.3s ease;
    }

    .btn-primary:hover{
        transform:translateY(-2px);
        box-shadow:0 10px 20px rgba(56,128,255,.25);
    }

    .btn-cancel{
        background:#eef2ff;
        color:#4f46e5;
        border:none;
        padding:12px 24px;
        border-radius:12px;
        font-weight:700;
        cursor:pointer;
        transition:.3s ease;
    }

    .btn-cancel:hover{
        background:#4f46e5;
        color:#fff;
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
                                        <tr class="amenity-row" data-id="{{ $amenity->id }}" data-serial="{{ $amenity->serial_number }}" data-name="{{ $amenity->name }}" data-status="{{ $amenity->status }}" data-icon="{{ base64_encode($amenity->icon) }}">
                                            <td>{{ $amenity->serial_number }}</td>
                                            <td>
                                                <div class="icon-preview" style="color: var(--brand); opacity: 0.8; width: 24px; height: 24px;">
                                                    {!! $amenity->icon !!}
                                                </div>
                                            </td>
                                            <td style="font-weight: 600;" class="amenity-name-cell">{{ $amenity->name }}</td>
                                            <td>
                                                @php
                                                    $statusDisplay = ($amenity->status == '1' || strtolower($amenity->status) == 'active') ? 'Active' : 'Inactive';
                                                    $badgeClass = $statusDisplay === 'Active' ? 'active' : 'inactive';
                                                @endphp
                                                <span class="badge badge-{{ $badgeClass }}">
                                                    {{ $statusDisplay }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="actions">
                                                    <button class="btn-action btn-edit" title="Edit" onclick="editAmenity({{ $amenity->id }})">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                    </button>
                                                    <form method="POST" action="{{ route('admin.amenities.destroy', [ 'amenity' => $amenity->id]) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this amenity?');">
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
                                    <form id="addAmenityForm" action="{{ route('admin.amenities.store') }}" method="POST">
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
                                            <textarea class="form-control font-mono" id="amenityIconCode" name="icon" rows="4" placeholder='<svg viewBox="0 0 24 24">...</svg>' required></textarea>
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
    /* =========================================
       PREMIUM CATEGORIES UI ANIMATIONS + THEME
    ========================================== */

                            .categories-section-wrap{
                                animation: catPageIn .6s cubic-bezier(.22,1,.36,1) both;
                                font-family: 'Inter', 'Poppins', sans-serif;
                            }

                            /* ===== PAGE ENTRY ===== */

                            @keyframes catPageIn{
                                from{
                                    opacity:0;
                                    transform:translateY(24px) scale(.98);
                                }
                                to{
                                    opacity:1;
                                    transform:translateY(0) scale(1);
                                }
                            }

                            /* ===== TABLE ROW SLIDE ===== */

                            @keyframes catRowSlide{
                                from{
                                    opacity:0;
                                    transform:translateX(18px);
                                }
                                to{
                                    opacity:1;
                                    transform:translateX(0);
                                }
                            }

                            /* ===== BUTTON POP ===== */

                            @keyframes catBtnPop{
                                0%{
                                    transform:scale(1);
                                }
                                40%{
                                    transform:scale(1.08);
                                }
                                70%{
                                    transform:scale(.96);
                                }
                                100%{
                                    transform:scale(1);
                                }
                            }

                            /* ===== BUTTON GLOW ===== */

                            @keyframes catGlow{
                                0%,100%{
                                    box-shadow:0 5px 20px rgba(108,99,255,.25);
                                }
                                50%{
                                    box-shadow:0 10px 35px rgba(56,128,255,.4);
                                }
                            }

                            /* ===== TYPE TAG ===== */

                            @keyframes catTypeTagIn{
                                from{
                                    opacity:0;
                                    transform:scale(.8);
                                }
                                to{
                                    opacity:1;
                                    transform:scale(1);
                                }
                            }

                            /* ===== HEADER WAVE ===== */

                            @keyframes catHeaderWave{
                                0%{
                                    background-position:0% 50%;
                                }
                                50%{
                                    background-position:100% 50%;
                                }
                                100%{
                                    background-position:0% 50%;
                                }
                            }

                            /* ===== TABLE HEADER ===== */

                            .data-table thead{
                                background:linear-gradient(135deg,#6c63ff,#3880ff,#7c4dff);
                                background-size:300% 300%;
                                animation:catHeaderWave 8s ease infinite;
                            }

                            .data-table thead th{
                                color:#424040;
                                font-size:13px;
                                font-weight:700;
                                letter-spacing:.5px;
                                text-transform:uppercase;
                                padding:18px 16px;
                            }

                            /* ===== TABLE ROWS ===== */

                            .category-row{
                                animation:catRowSlide .45s cubic-bezier(.22,1,.36,1) both;
                                transition:all .3s ease;
                                border-bottom:1px solid #edf2f7;
                            }

                            @for ($i = 0; $i < count($categories ?? []); $i++)
                                .category-row:nth-child({{ $i + 1 }}) {
                                    animation-delay: {{ $i * 0.055 }}s;
                                }
                            @endfor

                            .category-row:hover{
                                background:linear-gradient(
                                    90deg,
                                    rgba(108,99,255,.07) 0%,
                                    rgba(56,128,255,.03) 100%
                                );
                                box-shadow:inset 4px 0 0 #6c63ff;
                                transform:translateY(-1px);
                            }

                            /* ===== ADD BUTTON ===== */

                            .btn-cat-add{
                                position:relative;
                                overflow:hidden;
                                border:none;
                                border-radius:14px;
                                padding:12px 24px;
                                cursor:pointer;
                                color:#fff !important;
                                font-weight:700;
                                letter-spacing:.3px;
                                background:linear-gradient(135deg,#6c63ff,#3880ff) !important;
                                background-size:200% 200% !important;
                                transition:all .3s cubic-bezier(.4,2,.6,1);
                                animation:catGlow 2.8s ease-in-out infinite;
                            }

                            .btn-cat-add:hover{
                                transform:translateY(-4px) scale(1.04);
                                box-shadow:0 12px 35px rgba(15, 15, 15, 0.35);
                            }

                            .btn-cat-add:active{
                                animation:catBtnPop .35s cubic-bezier(.4,2,.6,1);
                            }

                            .btn-cat-add .cat-plus{
                                display:inline-block;
                                font-size:20px;
                                margin-right:6px;
                                transition:transform .35s cubic-bezier(.4,2,.6,1);
                            }

                            .btn-cat-add:hover .cat-plus{
                                transform:rotate(135deg) scale(1.2);
                            }

                            .btn-cat-add::after{
                                content:'';
                                position:absolute;
                                top:50%;
                                left:50%;
                                width:0;
                                height:0;
                                background:rgba(255,255,255,.25);
                                border-radius:50%;
                                transform:translate(-50%,-50%);
                                opacity:0;
                                transition:
                                    width .5s ease,
                                    height .5s ease,
                                    opacity .5s ease;
                            }

                            .btn-cat-add:active::after{
                                width:220px;
                                height:220px;
                                opacity:1;
                                transition:0s;
                            }

                            /* ===== CATEGORY TYPE ===== */

                            .category-row td:nth-child(2){
                                animation:catTypeTagIn .45s cubic-bezier(.22,1,.36,1);
                                font-weight:600;
                                color:#3a3a3d;
                            }

                            /* ===== CATEGORY NAME ===== */

                            .category-name-cell{
                                position:relative;
                                font-weight:700;
                                transition:all .3s ease;
                            }

                            .category-name-cell::after{
                                content:'';
                                position:absolute;
                                bottom:5px;
                                left:12px;
                                width:0;
                                height:2px;
                                background:#6c63ff;
                                border-radius:10px;
                                transition:width .35s ease;
                            }

                            .category-row:hover .category-name-cell{
                                color:#6c63ff;
                            }

                            .category-row:hover .category-name-cell::after{
                                width:calc(100% - 24px);
                            }

                            /* ===== SEARCH INPUT ===== */

                            .control-input,
                            .entries-select{
                                border:1px solid #dbe4f0;
                                border-radius:12px;
                                padding:10px 14px;
                                background:#fff;
                                transition:all .3s ease;
                                font-size:14px;
                            }

                            .control-input:focus,
                            .entries-select:focus{
                                outline:none;
                                border-color:#6c63ff;
                                box-shadow:0 0 0 4px rgba(108,99,255,.12);
                            }

                            /* ===== TABLE CONTAINER ===== */

                            .data-table-container{
                                border-radius:20px;
                                overflow:hidden;
                                background:#fff;
                                box-shadow:0 15px 40px rgba(15,23,42,.06);
                                border:1px solid rgba(226,232,240,.8);
                            }

                            /* ===== ACTION BUTTONS ===== */

                            .btn-edit,
                            .btn-delete{
                                transition:all .3s cubic-bezier(.4,2,.6,1);
                            }

                            .btn-edit:hover{
                                transform:translateY(-2px) rotate(8deg) scale(1.08);
                                box-shadow:0 8px 18px rgba(108,99,255,.25);
                            }

                            .btn-delete:hover{
                                transform:translateY(-2px) scale(1.1);
                                box-shadow:0 8px 18px rgba(239,68,68,.25);
                            }

                            /* ===== STATUS BADGES ===== */

                            .badge{
                                border-radius:30px;
                                padding:7px 16px;
                                font-size:12px;
                                font-weight:700;
                                letter-spacing:.3px;
                                transition:all .3s ease;
                            }

                            .badge-active{
                                background:#dcfce7;
                                color:#16a34a;
                                border:1px solid #86efac;
                            }

                            .badge-inactive{
                                background:#fee2e2;
                                color:#dc2626;
                                border:1px solid #fca5a5;
                            }

                            .badge:hover{
                                transform:scale(1.05);
                            }

                            /* ===== PAGINATION ===== */

                            .pagination-footer{
                                padding:18px 20px;
                                background:#f8fafc;
                                border-top:1px solid #e2e8f0;
                            }

                            .pagination-info{
                                color:#64748b;
                                font-size:14px;
                                font-weight:500;
                            }

                            /* ===== RESPONSIVE ===== */

                            @media(max-width:768px){

                                .btn-cat-add{
                                    width:100%;
                                    justify-content:center;
                                }

                                .data-table{
                                    min-width:700px;
                                }
                            }
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
                                                    <form method="POST" action="{{ route('admin.categories.destroy', [ 'category' => $category->id]) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                                    <form id="addCategoryForm" action="{{ route('admin.categories.store') }}" method="POST">
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
                                                    <form method="POST" action="{{ route('admin.countries.destroy', [ 'country' => $country->id]) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this country?');">
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
                                    <form id="addCountryForm" action="{{ route('admin.countries.store') }}" method="POST">
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
                                                    <form method="POST" action="{{ route('admin.states.destroy', [ 'state' => $state->id]) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this state?');">
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
                                    <form id="addStateForm" action="{{ route('admin.states.store') }}" method="POST">
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
                                                    <form method="POST" action="{{ route('admin.cities.destroy', [ 'city' => $city->id]) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this city?');">
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
                                    <form id="addCityForm" action="{{ route('admin.cities.store') }}" method="POST">
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
                                        @php
                                            $propertyPlaceImage = $property_place->image ?? '';
                                            $propertyPlaceImageUrl = '';
                                            if (!empty($propertyPlaceImage)) {
                                                if (str_starts_with($propertyPlaceImage, 'http')) {
                                                    $propertyPlaceImageUrl = $propertyPlaceImage;
                                                } else {
                                                    $normalizedImagePath = ltrim(str_replace('\\', '/', $propertyPlaceImage), '/');
                                                    $candidateImagePaths = array_values(array_unique([
                                                        $normalizedImagePath,
                                                        str_replace('property-places/', 'property_places/', $normalizedImagePath),
                                                        str_replace('property_places/', 'property-places/', $normalizedImagePath),
                                                    ]));
                                                    foreach ($candidateImagePaths as $candidateImagePath) {
                                                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($candidateImagePath)) {
                                                            $propertyPlaceImageUrl = \App\Support\MediaPath::url($candidateImagePath);
                                                            break;
                                                        }
                                                    }
                                                    if (!$propertyPlaceImageUrl) {
                                                        $propertyPlaceImageUrl = \App\Support\MediaPath::url($normalizedImagePath);
                                                    }
                                                }
                                            }
                                        @endphp
                                        <tr class="property-place-row" data-id="{{ $property_place->id }}" data-name="{{ $property_place->name }}" data-city="{{ $property_place->city_id }}" data-state="{{ $property_place->state_id }}" data-country="{{ $property_place->country_id }}" data-image="{{ $propertyPlaceImageUrl }}" data-update-url="{{ route('admin.property-places.update', ['property_place' => $property_place->id]) }}">
                                            <td style="text-align: center;">
                                                <div style="display: flex; justify-content: center;">
                                                    @if($propertyPlaceImageUrl)
                                                        <img src="{{ $propertyPlaceImageUrl }}" style="width: 48px; height: 48px; border-radius: 10px; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
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
                                                    <form method="POST" action="{{ route('admin.property-places.destroy', [ 'property_place' => $property_place->id]) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this property place?');">
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
                                    <form id="addPropertyPlaceForm" action="{{ route('admin.property-places.store') }}" method="POST" enctype="multipart/form-data">
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
                                            <input type="file" name="image" id="propertyPlaceImage" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 10px;" onchange="previewPropertyPlaceImage(this)">
                                            <div id="propertyPlaceImagePreview" style="margin-top: 10px; text-align: center; display: none;">
                                                <img src="" alt="Preview" style="max-width: 100%; max-height: 120px; border-radius: 8px; border: 1px solid #e2e8f0; object-fit: cover;">
                                            </div>
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
                                min-width: 100px;
                            }
                            .status-select:focus {
                                outline: none;
                                box-shadow: 0 0 0 2px rgba(255,255,255,0.3);
                            }
                            .status-select option {
                                background-color: #ffffffff;
                                color: #333;
                                padding: 8px; 
                            }
                            .status-select.bg-green { 
                                box-shadow: 0 0 0 2px hsla(180, 91%, 50%, 0.99);
                                color: #1db8a0;
                                border-color: #b3e6de;
                                animation: userStatusPulsed 3s ease-in-out infinite;
                            } 
                            .status-select.bg-red { 
                                box-shadow:0 0 0 2px hsla(22, 91%, 50%, 0.99);
                                color: #ff4800ff;
                                border-color: #b3e6de;
                                animation: userStatusPulsed 3s ease-in-out infinite;
                             }
                            .status-select.bg-pending { 
                                 
                                box-shadow: 0 0 0 2px hsla(231, 91%, 50%, 0.99);
                                color: #0011ffff;
                                border-color: #b3e6de;
                                animation: userStatusPulsed 3s ease-in-out infinite; }
                             .status-select:hover {
                                filter: brightness(1.08);
                                transform: scale(1.02);
                            }
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
                                <a href="{{ route('admin.properties.create') }}" class="btn-add-primary btn-add-property" style="margin-left:auto; background: #1d73d8; color: #fff; border-radius: 4px; border: none; text-decoration:none; display:inline-flex; align-items:center;">
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
                                            <th>Location</th>
                                            <th>Approval Status</th>
                                            <th>Status</th>
                                          
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="managePropertiesBody">
                                        @forelse (($manageProperties ?? []) as $property)
                                            <tr class="manage-property-row" data-title="{{ strtolower($property->title ?? '') }}" data-type="{{ strtolower($property->type ?? '') }}" data-area="{{ strtolower($property->property_area ?? '') }}">
                                                <td style="font-weight:600;">{{ $property->title ?: 'Untitled Property' }}</td>
                                                <td>{{ $property->post_by ?: '-' }}</td>
                                                <td>{{ $property->type ?: '-' }}</td>
                                                <td>
                                                    @if (!empty($property->latitude) && !empty($property->longitude))
                                                        <a href="https://www.google.com/maps?q={{ urlencode($property->latitude . ',' . $property->longitude) }}" target="_blank" style="color:#1d4ed8; text-decoration:underline;">
                                                            {{ $property->property_area ?: ($property->full_address ?: 'View on map') }}
                                                        </a>
                                                    @else
                                                        {{ $property->property_area ?: ($property->full_address ?: '-') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <form method="POST" action="{{ route('admin.properties.approval-status', ['id' => $property->id]) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <select name="approve_status" class="status-select {{ (int)$property->approve_status === 1 ? 'bg-green' : ((int)$property->approve_status === 0 ? 'bg-red' : 'bg-pending') }}" onchange="this.className='status-select ' + (this.value=='1' ? 'bg-green' : (this.value=='0' ? 'bg-red' : 'bg-pending')); this.form.submit();">
                                                            <option value="1" {{ (int)$property->approve_status === 1 ? 'selected' : '' }}>Approve</option>
                                                            <option value="2" {{ (int)$property->approve_status === 2 ? 'selected' : '' }}>Pending</option>
                                                            <option value="0" {{ (int)$property->approve_status === 0 ? 'selected' : '' }}>Rejected</option>
                                                        </select>
                                                    </form>
                                                </td>
                                                <td>
                                                    <select class="status-select {{ (int)$property->status === 1 ? 'bg-green' : 'bg-red' }}" onchange="this.className='status-select ' + (this.value=='1' ? 'bg-green' : 'bg-red')">
                                                        <option value="1" {{ (int)$property->status === 1 ? 'selected' : '' }}>Active</option>
                                                        <option value="0" {{ (int)$property->status === 0 ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </td>
                                                
                                                <td>
                                                    <div class="manage-property-actions">
                                                        <a href="{{ route('admin.properties.edit', ['user' => $user->id, 'id' => $property->id]) }}" class="btn-action btn-edit" title="Edit">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                        </a>
                                                        <form method="POST" action="{{ route('admin.properties.destroy', ['user' => $user->id, 'id' => $property->id]) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this property?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn-action btn-delete" title="Delete">
                                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" style="text-align:center; color:#6b7280;">No properties found in database.</td>
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
                                border-radius: 4px;
                                padding: 24px 40px;
                                min-height: 500px;
                            }

                            .property-stepper {
                                display: flex;
                                list-style: none;
                                padding: 0;
                                margin: 0 0 40px 0;
                                position: relative;
                            }
                            
                            .property-stepper li {
                                flex: 1;
                                text-align: center;
                                padding-bottom: 12px;
                                font-size: 14px;
                                font-weight: 600;
                                color: #111827;
                                cursor: default;
                            }
                            
                            .property-stepper li.active {
                                color: #2563eb;
                            }

                        </style>

                        <div class="choose-type-wrap">
                            

                            <div style="font-size: 14px; color: #111827; margin-bottom: 12px;">Property Type <span style="color: #ef4444;">*</span></div>

                            <div style="display: flex; gap: 40px; max-width: 800px;">
                                <a href="{{ route('admin.properties.create.type', [ 'type' => 'residential']) }}" style="flex: 1; border: 1px solid #3b82f6; background: #eff6ff; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; text-decoration: none;">
                                    <img src="https://img.icons8.com/color/48/home.png" alt="Residential" style="width: 32px; height: 32px; margin-bottom: 8px;">
                                    <div style="font-size: 15px; color: #111827; margin-bottom: 4px;">Residential</div>
                                    <div style="font-size: 13px; color: #111827;">Apartments, Villas, Plots</div>
                                </a>

                                <a href="{{ route('admin.properties.create.type', [ 'type' => 'commercial']) }}" style="flex: 1; border: 1px solid #3b82f6; background: #eff6ff; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; text-decoration: none;">
                                    <img src="https://cdn-icons-png.flaticon.com/512/10809/10809404.png" alt="Commercial" style="width: 32px; height: 32px; margin-bottom: 8px;">
                                    <div style="font-size: 15px; color: #111827; margin-bottom: 4px;">Commercial</div>
                                    <div style="font-size: 13px; color: #111827;">Office, Retail, Warehouse</div>
                                </a>
                            </div>
                        </div>

                    @elseif ($currentPage === 'add-property')
                        <style>
                            @keyframes addPropPageIn {
                                from { opacity: 0; transform: translateY(30px); }
                                to   { opacity: 1; transform: translateY(0); }
                            }
                            @keyframes formCardFadeIn {
                                from { opacity: 0; transform: scale(0.98) translateY(10px); }
                                to   { opacity: 1; transform: scale(1) translateY(0); }
                            }
                            @keyframes saveBtnGlow {
                                0%, 100% { box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3); }
                                50%      { box-shadow: 0 6px 28px rgba(40, 167, 69, 0.5); }
                            }

                            .add-property-wrap { 
                                background: #fff; 
                                border: 1px solid #dde4ee; 
                                border-radius: 12px; 
                                overflow: hidden;
                                animation: addPropPageIn 0.6s cubic-bezier(.22,1,.36,1) both;
                            }
                            .add-property-head { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 18px 24px; border-bottom: 1px solid #e9eef5; background: #fff; }
                            .add-property-title { margin: 0; font-size: 18px; font-weight: 700; color: #202938; }
                            .add-property-subtitle { margin: 6px 0 0; color: #66758b; font-size: 12px; }
                            .add-property-back { display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: #1d4ed8; font-weight: 600; padding: 8px 12px; border-radius: 6px; background: #f8fbff; border: 1px solid #dbe7ff; }
                            .add-property-form { padding: 18px 22px 26px; background: #fff; }
                            .property-form-grid { display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); gap: 18px; }
                            .property-form-card { 
                                grid-column: span 12; 
                                background: #fff; 
                                border: 1px solid #e9eef5; 
                                border-radius: 12px; 
                                padding: 24px;
                                box-shadow: 0 4px 12px rgba(0,0,0,0.02);
                                animation: formCardFadeIn 0.5s cubic-bezier(.22,1,.36,1) both;
                                transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
                            }
                            .property-form-card:hover {
                                transform: translateY(-2px);
                                box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08);
                                border-color: #d9e3f1;
                            }
                            .property-form-card:nth-child(1) { animation-delay: 0.1s; }
                            .property-form-card:nth-child(2) { animation-delay: 0.2s; }
                            .property-form-card:nth-child(3) { animation-delay: 0.3s; }
                            .property-form-card:nth-child(4) { animation-delay: 0.4s; }
                            .property-form-card:nth-child(5) { animation-delay: 0.5s; }
                            .property-form-card:nth-child(6) { animation-delay: 0.6s; }
                            .property-form-card:nth-child(7) { animation-delay: 0.7s; }

                            .property-info-theme {
                                background: linear-gradient(180deg, #f0f7ff 0%, #ffffff 100%);
                                border-left: 5px solid var(--brand);
                                box-shadow: 0 10px 25px -5px rgba(24, 164, 234, 0.1);
                            }
                            .property-location-theme {
                                background: linear-gradient(180deg, #f4fff7 0%, #ffffff 100%);
                                border-left: 5px solid #10b981;
                            }
                            .property-pricing-theme {
                                background: linear-gradient(180deg, #fff9f1 0%, #ffffff 100%);
                                border-left: 5px solid #f59e0b;
                            }
                            .property-content-theme {
                                background: linear-gradient(180deg, #f8f6ff 0%, #ffffff 100%);
                                border-left: 5px solid #8b5cf6;
                            }
                            .property-faq-theme {
                                background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
                                border-left: 5px solid #3b82f6;
                            }

                            .property-form-card h3 { margin: 0 0 6px; font-size: 14px; font-weight: 700; color: #18263a; }
                            .property-form-card p { margin: 0 0 16px; color: #718097; font-size: 12px; }
                            .section-marker-title { display: inline-flex; align-items: center; gap: 10px; margin: 0 0 8px; font-size: 22px; font-weight: 500; color: #111827; }
                            .section-marker-title::before { content: ''; width: 3px; height: 26px; border-radius: 3px; background: #1d4ed8; display: inline-block; }
                            .section-marker-note { display: inline-flex; align-items: center; gap: 10px; margin: 0 0 18px; color: #111827; font-size: 13px; }
                            .section-marker-note::before { content: ''; width: 3px; height: 30px; border-radius: 3px; background: #1d4ed8; display: inline-block; }
                            .property-field { grid-column: span 12; }
                            .property-field.col-6 { grid-column: span 6; }
                            .property-field.col-4 { grid-column: span 4; }
                            .property-field.col-3 { grid-column: span 3; }
                            .property-field label { display: block; margin-bottom: 7px; color: #1f2f46; font-size: 12px; font-weight: 700; }
                            .property-field input, .property-field select, .property-field textarea { 
                                width: 100%; 
                                border: 1px solid #dfe5ee; 
                                border-radius: 8px; 
                                padding: 10px 14px; 
                                font-size: 13px; 
                                color: #223247; 
                                background: #f8fafc;
                                transition: all 0.3s ease;
                            }
                            .property-field input:focus, .property-field select:focus, .property-field textarea:focus {
                                border-color: var(--brand);
                                background: #fff;
                                box-shadow: 0 0 0 4px rgba(24, 164, 234, 0.15);
                                outline: none;
                            }
                            .property-field select { min-height: 42px; }
                            .property-field input[type="number"] { text-align: left; }
                            .property-field textarea { min-height: 100px; resize: vertical; }
                            .property-field .hint { margin-top: 6px; color: #7a889a; font-size: 12px; }
                            .property-check-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; }
                            .property-check { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: 1px solid #dfe7f1; border-radius: 4px; background: #fff; }
                            .property-check input { width: auto; }
                            .property-toggle-row { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
                            .property-chip { display: inline-flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 4px; border: 1px solid #d7e3f2; background: #fff; color: #1f3550; font-weight: 600; }
                            .upload-panel { border: 2px dashed #e2e8f0; border-radius: 16px; min-height: 152px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; color: #64748b; background: #f8fafc; padding: 20px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; }
                            .upload-panel:hover { border-color: var(--brand); background: #f0f9ff; color: var(--brand); transform: translateY(-2px); }
                            .upload-panel.large { min-height: 165px; }
                            .upload-panel .upload-title { font-size: 13px; font-weight: 600; color: #334258; margin-bottom: 6px; }
                            .upload-panel .upload-meta { font-size: 11px; color: #7b8aa0; }
                            .upload-note { margin-top: 8px; font-size: 11px; color: #4385ff; }
                            .media-block { padding: 6px 0 12px; border-bottom: 1px solid #eef2f7; margin-bottom: 14px; }
                            .media-block:last-of-type { border-bottom: none; margin-bottom: 0; }
                            .media-title { display: inline-flex; align-items: center; gap: 8px; font-size: 28px; font-weight: 700; color: #111827; margin-bottom: 10px; }
                            .media-title::before { content: ''; width: 4px; height: 20px; border-radius: 4px; background: #1d4ed8; display: inline-block; }
                            .upload-panel.media-main { border: none; min-height: 140px; background: transparent; }
                            .upload-panel.media-main .upload-plus { font-size: 15px; line-height: 1; color: #2563eb; margin-bottom: 10px; }
                            .upload-panel.media-main .upload-title { font-size: 15px; color: #111827; font-weight: 300; }
                            .upload-panel.media-main .upload-meta { font-size:15px; color: #111827; }
                            .gallery-preview-row { display: flex; align-items: center; gap: 14px; margin-top: 12px; }
                            .gallery-preview-card { width: 74px; height: 74px; border-radius: 8px; background: #e9eef6; position: relative; display: inline-flex; align-items: center; justify-content: center; color: #9bb3cf; font-size: 26px; animation: imagePopIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) both; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
                            .remove-img-btn { position: absolute; top: -8px; right: -8px; width: 18px; height: 18px; border-radius: 50%; background: #ef476f; color: #fff; font-size: 14px; display: grid; place-items: center; cursor: pointer; border: 1px solid #fff; z-index: 10; font-weight: 700; line-height: 1; transition: background 0.2s; }
                            .remove-img-btn:hover { background: #d63031; }
                            .gallery-preview-card .remove-gallery-img:hover { background: #d63031; }
                            .gallery-preview-card .remove-gallery-img { position: absolute; top: -8px; right: -8px; width: 18px; height: 18px; border-radius: 50%; background: #ef476f; color: #fff; font-size: 14px; display: grid; place-items: center; cursor: pointer; border: 1px solid #fff; z-index: 5; font-weight: 700; line-height: 1; transition: background 0.2s; }
                            .gallery-preview-card .remove-gallery-img:hover { background: #d63031; }
                            .gallery-add-card { width: 74px; height: 74px; border: 2px dashed #101010; border-radius: 2px; display: inline-flex; align-items: center; justify-content: center; font-size: 34px; color: #111827; cursor: pointer; background: #fff; }
                            .map-preview { height: 265px; border: 1px solid #60799eff; border-radius: 4px; background: linear-gradient(180deg, rgba(196,239,219,0.75), rgba(181,229,212,0.85)), radial-gradient(circle at 20% 35%, rgba(99,164,120,0.25) 0 13%, transparent 14%), radial-gradient(circle at 63% 40%, rgba(105,155,112,0.22) 0 12%, transparent 13%), linear-gradient(90deg, rgba(97,134,189,0.18) 0 22%, transparent 23% 100%); position: relative; overflow: hidden; }
                            .map-preview::before { content: 'Pin Location on Map'; position: absolute; top: 14px; left: 14px; background: rgba(255,255,255,0.96); border: 1px solid #dce6f3; color: #223247; font-size: 12px; font-weight: 700; padding: 8px 12px; border-radius: 4px; }
                            .map-preview::after { content: ''; position: absolute; inset: 0; background-image: linear-gradient(rgba(255,255,255,0.18) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.18) 1px, transparent 1px); background-size: 44px 44px; mix-blend-mode: soft-light; }
                            .map-select-wrap { border: 1px solid #dfe5ee; border-radius: 4px; min-height: 230px; background: #f4f5f7; display: flex; align-items: center; justify-content: center; text-align: center; padding: 24px; }
                            .map-select-trigger { display: inline-flex; flex-direction: column; align-items: center; justify-content: center; text-decoration: none; color: #111827; gap: 10px; }
                            .map-select-icon { width: 34px; height: 34px; border-radius: 999px; background: #dbeafe; color: #2563eb; display: inline-flex; align-items: center; justify-content: center; }
                            .map-select-title { font-size: 28px; font-weight: 700; line-height: 1.2; }
                            .map-select-note { font-size: 24px; color: #111827; }
                            .map-picker-modal { position: fixed; inset: 0; z-index: 9999; display: none; align-items: center; justify-content: center; background: rgba(15, 23, 42, 0.55); padding: 18px; }
                            .map-picker-modal.is-open { display: flex; }
                            .map-picker-card { width: min(980px, 100%); background: #fff; border-radius: 10px; border: 1px solid #dbe3ef; box-shadow: 0 20px 40px rgba(15, 23, 42, 0.25); overflow: hidden; }
                            .map-picker-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 12px 14px; border-bottom: 1px solid #e5edf7; }
                            .map-picker-title { margin: 0; font-size: 14px; font-weight: 700; color: #142235; }
                            .map-picker-close { border: none; background: #f1f5f9; color: #334155; border-radius: 6px; width: 30px; height: 30px; cursor: pointer; font-size: 18px; line-height: 1; }
                            .map-picker-search { padding: 10px 14px; border-bottom: 1px solid #e5edf7; }
                            .map-picker-search input { width: 100%; border: 1px solid #d1d9e6; border-radius: 6px; padding: 9px 11px; font-size: 13px; }
                            #propertyGoogleMapCanvas { width: 100%; height: 420px; background: #eef2f7; }
                            .map-picker-foot { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 12px 14px; border-top: 1px solid #e5edf7; }
                            .map-picker-status { font-size: 12px; color: #475569; }
                            .map-picker-actions { display: inline-flex; align-items: center; gap: 8px; }
                            .map-picker-btn { border: none; border-radius: 6px; padding: 8px 12px; cursor: pointer; font-size: 12px; font-weight: 600; }
                            .map-picker-btn.secondary { background: #e2e8f0; color: #334155; }
                            .map-picker-btn.primary { background: #2563eb; color: #fff; }
                            .property-rich-toolbar { display: flex; gap: 6px; flex-wrap: wrap; padding: 8px; border: 1px solid #dfe5ee; border-bottom: none; border-radius: 4px 4px 0 0; background: #fafbfd; }
                            .property-rich-toolbar span { min-width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #dbe2ec; border-radius: 4px; font-size: 12px; color: #425066; background: #fff; }
                            .property-rich-editor { border-top-left-radius: 0 !important; border-top-right-radius: 0 !important; min-height: 132px !important; }
                            .faq-item { display: grid; grid-template-columns: 1fr 1fr auto; gap: 12px; margin-bottom: 12px; }
                            .faq-remove-btn, .faq-add-btn, .property-save-btn { border: none; border-radius: 8px; cursor: pointer; font-weight: 700; transition: all 0.3s ease; }
                            .faq-add-btn { padding: 10px 14px; background: #1d73d8; color: #fff; border-radius: 4px; }
                            .faq-add-btn:hover { transform: scale(1.02); background: #155bb5; }
                            .faq-remove-btn { padding: 0 18px; background: #f78d98; color: #fff; border-radius: 4px; }
                            .faq-remove-btn:hover { background: #e05d6a; }
                            .property-form-actions { display: flex; justify-content: center; gap: 12px; margin-top: 22px; }
                            .property-save-btn {
                                padding: 12px 24px;
                                background: linear-gradient(135deg, #28a745 0%, #218838 100%); 
                                color: #fff; 
                                border-radius: 8px;
                                animation: saveBtnGlow 3s ease-in-out infinite;
                            }
                            .property-save-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3); }
                            .property-save-btn.secondary { background: #f1f5f9; color: #475569; box-shadow: none; }
                            .property-save-btn.secondary:hover { background: #e2e8f0; }
                            @media (max-width: 1100px) { .property-field.col-6, .property-field.col-4, .property-field.col-3 { grid-column: span 12; } .property-check-grid, .faq-item { grid-template-columns: 1fr; } .add-property-head { flex-direction: column; align-items: flex-start; } }
                        </style>

                        <div class="add-property-wrap">
                            <div class="add-property-head">
                                <div>
                                    <h2 class="add-property-title">{{ ($mode ?? 'create') === 'edit' ? 'Edit' : ucfirst($selectedPropertyType ?? 'property') }} Property</h2>
                                    <p class="add-property-subtitle">Create the full {{ $selectedPropertyType ?? 'property' }} listing in one page with media, pricing, address, amenities, content, and FAQs.</p>
                                </div>
                                <a href="{{ route('admin.section', [ 'section' => 'manage-properties']) }}" class="add-property-back">
                                    <span>&larr;</span>
                                    <span>Back to Manage Properties</span>
                                </a>
                            </div>

                            <form id="addPropertyForm" class="add-property-form" method="POST" enctype="multipart/form-data" action="{{ ($mode ?? 'create') === 'edit' ? route('admin.properties.update', ['user' => $user->id, 'id' => $property->id]) : route('admin.properties.store') }}">
                                @csrf
                                @if(($mode ?? 'create') === 'edit')
                                    @method('PUT')
                                @endif
                                <input type="hidden" name="property_type" value="{{ old('property_type', $selectedPropertyType ?? 'residential') }}">
                                @if ($errors->any())
                                    <div style="margin-bottom:16px; border:1px solid #fecaca; background:#fef2f2; color:#991b1b; border-radius:6px; padding:12px 14px;">
                                        <strong style="display:block; margin-bottom:6px;">Please fix these errors:</strong>
                                        <ul style="margin:0; padding-left:18px;">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="property-form-grid">
                                    <div class="property-form-card">
                                        
                                        <div class="property-form-grid">
                                            <div class="property-field media-block">
                                                <label class="media-title" for="propertyGalleryImages">Gallery Images</label>
                                                <label class="upload-panel media-main" for="propertyGalleryImages" id="galleryUploadArea">
                                                    <span class="upload-plus">+</span>
                                                    <span class="upload-title">Click to upload gallery images</span><br>
                                                    <span class="upload-meta">PNG, JPG up to 2 MB each · Multiple allowed</span>
                                                </label>
                                                <div class="gallery-preview-row" id="galleryPreviewContainer">
                                                    <label for="propertyGalleryImages" class="gallery-add-card">+</label>
                                                </div>
                                                <input type="file" id="propertyGalleryImages" name="gallery_images[]" accept="image/png, image/jpeg, image/jpg, image/avif" multiple hidden {{ ($mode ?? 'create') === 'edit' ? '' : 'required' }}>
                                            </div>
                                            <div class="property-field media-block">
                                                <label class="media-title" for="propertyDisplayImage">Display Image (Cover)</label>
                                                <label class="upload-panel media-main" for="propertyDisplayImage" id="displayImageUploadArea">
                                                    @if(($mode ?? '') === 'edit' && !empty($property->main_property_image))
                                                        <div style="position: relative; display: inline-block;">
                                                            <img src="{{ \App\Support\MediaPath::url($property->main_property_image) }}" style="max-width:100%; max-height:140px; border-radius:4px; object-fit:cover;">
                                                            <div class="remove-img-btn" id="removeExistingDisplayImage">&times;</div>
                                                        </div>
                                                    @else
                                                    <span class="upload-plus">+</span>
                                                    <span class="upload-title">Upload primary display image</span><br>
                                                    <span class="upload-meta">PNG, JPG up to 2 MB · This appears on listings</span>
                                                    @endif
                                                </label>
                                                <input type="file" id="propertyDisplayImage" name="display_image" accept="image/png, image/jpeg, image/jpg, image/avif" hidden {{ ($mode ?? 'create') === 'edit' ? '' : 'required' }}>
                                            </div>
                                            <div class="property-field media-block">
                                                <label class="media-title" for="propertyFloorPlanImages">Floor Plans</label>
                                                <label class="upload-panel media-main" for="propertyFloorPlanImages" id="floorPlanUploadArea">
                                                    <span class="upload-plus">+</span>
                                                    <span class="upload-title">Upload floor plan images</span><br>
                                                    <span class="upload-meta">PNG, JPG up to 2 MB each</span>
                                                </label>
                                                <div class="gallery-preview-row" id="floorPlanPreviewContainer">
                                                    <label for="propertyFloorPlanImages" class="gallery-add-card">+</label>
                                                </div>
                                                <input type="file" id="propertyFloorPlanImages" name="floor_plan_images[]" accept="image/png, image/jpeg, image/jpg, image/avif" multiple hidden>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="property-form-card property-info-theme">
                                        <h3>Property Information</h3>
                                        <div class="property-form-grid">
                                            <div class="property-field">
                                                <label for="propertyName">Property Name <span class="required-star">*</span></label>
                                                <input type="text" id="propertyName" name="property_name" placeholder="e.g. Prestige Lake Ridge" value="{{ old('property_name', $property->property_name ?? '') }}" required>
                                            </div>
                                            <div class="property-field col-6">
                                                <label for="propertyCategory">Category <span class="required-star">*</span></label>
                                                <select id="propertyCategory" name="category_id" required>
                                                    <option value="">Select category</option>
                                                    @foreach (($categories ?? []) as $category)
                                                        <option value="{{ $category->id }}" {{ (string) old('category_id', $property->category_id ?? '') === (string) $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="property-field col-6">
                                                <label for="propertyStatus">Status <span class="required-star">*</span></label>
                                                <select id="propertyStatus" name="status" required>
                                                    <option value="">Select status</option>
                                                    <option value="1" {{ (string) old('status', $property->status ?? '1') === '1' ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ (string) old('status', $property->status ?? '1') === '0' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                            <div class="property-field col-6">
                                                <label for="propertyConstructionStatus">Construction Status <span class="required-star">*</span></label>
                                                <select id="propertyConstructionStatus" name="construction_status" required>
                                                    <option value="">Select status</option>
                                                    <option value="Ready to Move" {{ old('construction_status', $property->construction_status ?? '') === 'Ready to Move' ? 'selected' : '' }}>Ready to Move</option>
                                                    <option value="Under Construction" {{ old('construction_status', $property->construction_status ?? '') === 'Under Construction' ? 'selected' : '' }}>Under Construction</option>
                                                    <option value="New Launch" {{ old('construction_status', $property->construction_status ?? '') === 'New Launch' ? 'selected' : '' }}>New Launch</option>
                                                </select>
                                            </div>
                                            <div class="property-field col-6">
                                                <label for="propertyPossessionDate">Possession Date</label>
                                                <input type="date" id="propertyPossessionDate" name="possession_date" value="{{ old('possession_date', isset($property->possession_date) ? \Illuminate\Support\Carbon::parse($property->possession_date)->format('Y-m-d') : '') }}">
                                            </div>
                                            <div class="property-field col-6">
                                                <label for="propertyFurnishedStatus">Furnished Status <span class="required-star">*</span></label>
                                                <select id="propertyFurnishedStatus" name="furnished_status" required>
                                                    <option value="">Select</option>
                                                    <option value="Furnished" {{ old('furnished_status', $property->furnished_status ?? '') === 'Furnished' ? 'selected' : '' }}>Furnished</option>
                                                    <option value="Semi-furnished" {{ old('furnished_status', $property->furnished_status ?? '') === 'Semi-furnished' ? 'selected' : '' }}>Semi-furnished</option>
                                                    <option value="Unfurnished" {{ old('furnished_status', $property->furnished_status ?? '') === 'Unfurnished' ? 'selected' : '' }}>Unfurnished</option>
                                                </select>
                                            </div>
                                             <div class="property-field col-6">
                                                <label for="propertyTopPicks">Top Picks (Categories)</label>
                                                <div class="multi-select-wrapper">
                                                    <div id="selectedTopPicksDisplay" class="selected-chips-area"></div>
                                                    <input type="text" id="topPicksSearch" placeholder="Search & select multiple (e.g. Best Deals)..." style="border:none; padding:5px; width:100%; outline:none;">
                                                    <select id="propertyTopPicks" name="top_picks[]" multiple size="5" class="multi-select-toggle" style="margin-top:10px;">
                                                        @php
                                                            $currentTopPicks = collect(old('top_picks', $selectedTopPicks ?? []))->map(fn($id) => (string)$id)->all();
                                                        @endphp
                                                        @foreach($topPicksCategories ?? [] as $tp)
                                                            <option class="top-pick-option" data-name="{{ strtolower($tp->name ?? '') }}" value="{{ $tp->id }}" {{ in_array((string)$tp->id, $currentTopPicks, true) ? 'selected' : '' }}>{{ $tp->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="property-field col-6">
                                                <label>Amenities <span class="required-star">*</span></label>
                                                <div class="multi-select-wrapper">
                                                    <div id="selectedAmenitiesDisplay" class="selected-chips-area"></div>
                                                    <input type="text" id="propertyAmenitySearch" placeholder="Search amenities..." style="border:none; padding:5px; width:100%; outline:none;">
                                                    <select id="propertyAmenitiesSelect" name="amenity_ids[]" multiple size="6" class="multi-select-toggle" style="width:100%; border:none; margin-top:10px; font-size:13px; color:#223247; outline:none;">
                                                        @php
                                                            $selectedAmenityIds = collect(old('amenity_ids', $selectedAmenities ?? []))->map(fn ($id) => (string) $id)->all();
                                                        @endphp
                                                        @foreach (($amenities ?? []) as $amenity)
                                                            <option class="amenity-option" data-amenity="{{ strtolower($amenity->name ?? '') }}" value="{{ $amenity->id }}" {{ in_array((string) $amenity->id, $selectedAmenityIds, true) ? 'selected' : '' }}>{{ $amenity->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="property-field col-6">
                                                <label for="propertyBhk">No. of BHK</label>
                                                @php
                                                    $storedBhk = $property->bhk ?? null;
                                                    $decodedBhk = is_string($storedBhk) ? json_decode($storedBhk, true) : (is_array($storedBhk) ? $storedBhk : null);
                                                    $selectedBhkValues = old('bhk', is_array($decodedBhk)
                                                        ? $decodedBhk
                                                        : (!empty($storedBhk) ? array_map('trim', explode(',', (string) $storedBhk)) : [])
                                                    );
                                                    $selectedBhkValues = collect($selectedBhkValues)->map(fn ($value) => (string) $value)->all();
                                                @endphp
                                                <div class="multi-select-wrapper">
                                                    <div id="selectedBhkDisplay" class="selected-chips-area"></div>
                                                    <input type="text" id="bhkSearch" placeholder="Search BHK..." style="border:none; padding:5px; width:100%; outline:none;">
                                                    <select id="propertyBhk" name="bhk[]" multiple size="5" class="multi-select-toggle" style="margin-top:10px;">
                                                        <option class="bhk-option" data-name="1 bhk" value="1 BHK" {{ in_array('1 BHK', $selectedBhkValues, true) ? 'selected' : '' }}>1 BHK</option>
                                                        <option class="bhk-option" data-name="2 bhk" value="2 BHK" {{ in_array('2 BHK', $selectedBhkValues, true) ? 'selected' : '' }}>2 BHK</option>
                                                        <option class="bhk-option" data-name="3 bhk" value="3 BHK" {{ in_array('3 BHK', $selectedBhkValues, true) ? 'selected' : '' }}>3 BHK</option>
                                                        <option class="bhk-option" data-name="4 bhk" value="4 BHK" {{ in_array('4 BHK', $selectedBhkValues, true) ? 'selected' : '' }}>4 BHK</option>
                                                        <option class="bhk-option" data-name="5+ bhk" value="5+ BHK" {{ in_array('5+ BHK', $selectedBhkValues, true) ? 'selected' : '' }}>5+ BHK</option>
                                                    </select>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="property-form-card property-location-theme">
                                        <h3>Location Details</h3>
                                        <p>Country, state, city, location and pincode will auto fill after Address selected.</p>
                                        <div class="property-form-grid">
                                            <div class="property-field">
                                                <label for="propertyFullAddress">Full Address <span class="required-star">*</span></label>
                                                <input type="text" id="propertyFullAddress" name="full_address" placeholder="Select or enter full address to auto-fill location fields" value="{{ old('full_address', $property->full_address ?? '') }}" required>
                                            </div>
                                            <div class="property-field col-3">
                                                <label for="propertyCountry">Country *</label>
                                                <select id="propertyCountry" name="country_id" required>
                                                    <option value="">Select country</option>
                                                    @foreach (($countries ?? []) as $country)
                                                        <option value="{{ $country->id }}" {{ (string) old('country_id', $propertyCountryId ?? '') === (string) $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="property-field col-3">
                                                <label for="propertyState">State *</label>
                                                <select id="propertyState" name="state_id" required>
                                                    <option value="">Select state</option>
                                                    @foreach (($states ?? []) as $state)
                                                        <option value="{{ $state->id }}" data-country="{{ $state->country_id ?? '' }}" {{ (string) old('state_id', $propertyStateId ?? '') === (string) $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="property-field col-3">
                                                <label for="propertyCity">City *</label>
                                                <select id="propertyCity" name="city_id" required>
                                                    <option value="">Select city</option>
                                                    @foreach (($cities ?? []) as $city)
                                                        <option value="{{ $city->id }}" data-state="{{ $city->state_id ?? '' }}" {{ (string) old('city_id', $propertyCityId ?? '') === (string) $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="property-field col-3">
                                                <label for="propertyLocation">Location *</label>
                                                <select id="propertyLocation" name="property_place_id" required>
                                                    <option value="">Select location</option>
                                                    @foreach (($propertyPlaces ?? []) as $place)
                                                        <option value="{{ $place->id }}" data-country="{{ $place->country_id ?? '' }}" data-state="{{ $place->state_id ?? '' }}" data-city="{{ $place->city_id ?? '' }}" {{ (string) old('property_place_id', $propertyPlaceId ?? '') === (string) $place->id ? 'selected' : '' }}>{{ $place->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="property-field col-3">
                                                <label for="propertyPincode">Pincode *</label>
                                                <input type="text" id="propertyPincode" name="pincode" placeholder="Enter pincode" value="{{ old('pincode', $property->pincode ?? '') }}" required>
                                            </div>
                                            <div class="property-field col-3">
                                                <label for="propertyLatitude">Latitude *</label>
                                                <input type="text" id="propertyLatitude" name="latitude" placeholder="e.g. 13.0827" value="{{ old('latitude', $property->latitude ?? '') }}" required>
                                            </div>
                                            <div class="property-field col-3">
                                                <label for="propertyLongitude">Longitude *</label>
                                                <input type="text" id="propertyLongitude" name="longitude" placeholder="e.g. 80.2707" value="{{ old('longitude', $property->longitude ?? '') }}" required>
                                            </div>
                                            <div class="property-field col-3">
                                                <label for="propertyMapSelected">Map Selected</label>
                                                <input type="text" id="propertyMapSelected" name="map_selected" placeholder="Map URL auto-fills from coordinates" value="{{ old('map_selected', $propertyContent->meta_description ?? '') }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="property-form-card">
                                        <h3 class="section-marker-title">Map Selection</h3>
                                        <div class="map-select-wrap">
                                            <a id="propertyMapPreview" href="#" target="_blank" class="map-select-trigger" style="display:flex;" title="Open selected location on map">
                                                <span class="map-select-icon" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M12 21s7-4.35 7-11a7 7 0 1 0-14 0c0 6.65 7 11 7 11z"></path>
                                                        <circle cx="12" cy="10" r="2.5"></circle>
                                                    </svg>
                                                </span>
                                                <span class="map-select-title">Click to open map</span>
                                                <span class="map-select-note">Pin drops will auto-fill lat, long and address</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="propertyMapPickerModal" class="map-picker-modal" aria-hidden="true">
                                        <div class="map-picker-card" role="dialog" aria-modal="true" aria-labelledby="mapPickerTitle">
                                            <div class="map-picker-head">
                                                <h4 id="mapPickerTitle" class="map-picker-title">Pick Property Location (Google Maps)</h4>
                                                <button type="button" id="mapPickerCloseBtn" class="map-picker-close" aria-label="Close map picker">&times;</button>
                                            </div>
                                            <div class="map-picker-search">
                                                <input type="text" id="propertyMapSearchInput" placeholder="Search location...">
                                            </div>
                                            <div id="propertyGoogleMapCanvas"></div>
                                            <div class="map-picker-foot">
                                                <span id="mapPickerStatus" class="map-picker-status">Click on map to place pin.</span>
                                                <div class="map-picker-actions">
                                                    <button type="button" id="mapPickerCancelBtn" class="map-picker-btn secondary">Cancel</button>
                                                    <button type="button" id="mapPickerUseBtn" class="map-picker-btn primary">Use This Location</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="property-form-card property-pricing-theme">
                                        <h3 class="section-marker-title">Pricing (INR)</h3>
                                        <p class="section-marker-note">At least one price (min or max) is required. Both can be filled for a price range.</p>
                                        <div class="property-form-grid">
                                            <div class="property-field col-6">
                                                <label for="propertyMinPrice">Min Price (Rs) <span class="required-star">*</span></label>
                                                <input type="number" id="propertyMinPrice" name="min_price" placeholder="e.g. 5000000" value="{{ old('min_price', $property->min_price ?? '') }}">
                                                <div class="hint">Leave blank if not applicable</div>
                                            </div>
                                            <div class="property-field col-6">
                                                <label for="propertyMaxPrice">Max Price (Rs) <span class="required-star">*</span></label>
                                                <input type="number" id="propertyMaxPrice" name="max_price" placeholder="e.g. 15000000" value="{{ old('max_price', $property->max_price ?? '') }}">
                                                <div class="hint">Leave blank if not applicable</div>
                                            </div>
                                            <div class="property-field col-12" style="margin-top: 4px;">
                                                <h3 class="section-marker-title" style="font-size: 16px; margin-bottom: 6px;">Area Details</h3>
                                                <p class="section-marker-note" style="margin-bottom: 0;">Fill total area, or a min/max range - at least one is required.</p>
                                            </div>
                                            <div class="property-field col-4">
                                                <label for="propertyArea">Area (sqft)</label>
                                                <input type="number" id="propertyArea" name="area" placeholder="e.g. 1200" value="{{ old('area', $property->area ?? '') }}">
                                            </div>
                                            <div class="property-field col-4">
                                                <label for="propertyMinArea">Min Area (sqft)</label>
                                                <input type="number" id="propertyMinArea" name="min_area" placeholder="e.g. 900" value="{{ old('min_area', $property->min_area ?? '') }}">
                                            </div>
                                            <div class="property-field col-4">
                                                <label for="propertyMaxArea">Max Area (sqft)</label>
                                                <input type="number" id="propertyMaxArea" name="max_area" placeholder="e.g. 1800" value="{{ old('max_area', $property->max_area ?? '') }}">
                                            </div>
                                            <div class="property-field col-12">
                                                <label for="propertyBrochure">Brochure <span class="required-star">*</span></label>
                                                <label class="upload-panel" for="propertyBrochure" id="brochureUploadArea">
                                                    @if(($mode ?? '') === 'edit' && !empty($property->brochure))
                                                        <div style="position: relative; display: inline-flex; align-items: center; gap: 10px; padding: 12px 20px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); animation: imagePopIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) both;">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                                            <span style="font-size: 13px; font-weight: 600; color: #334155;">{{ basename($property->brochure) }}</span>
                                                            <div class="remove-img-btn" id="removeExistingBrochure">&times;</div>
                                                        </div>
                                                    @else
                                                    <span class="upload-title">Upload property brochure (Mandatory)</span>
                                                    <span class="upload-meta">PDF format required up to 10MB</span>
                                                    @endif
                                                </label>
                                                <input type="file" id="propertyBrochure" name="brochure" accept=".pdf" hidden {{ ($mode ?? 'create') === 'edit' ? '' : 'required' }}>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="property-form-card property-content-theme">
                                        <h3>Content & RERA</h3>
                                        <div class="property-form-grid">
                                            <div class="property-field">
                                                <label for="propertyOverview">Overview <span class="required-star">*</span></label>
                                                <textarea id="propertyOverview" name="overview" placeholder="Write a compelling overview..." required>{{ old('overview', $property->overview ?? '') }}</textarea>
                                            </div>
                                            <div class="property-field">
                                                <label for="propertyHighlights">Highlights <span class="required-star">*</span></label>
                                                <div class="property-rich-toolbar">
                                                    <span title="Bold">B</span><span title="Underline">U</span><span title="Italic">I</span><span title="Color">A</span><span>•</span><span>1.</span><span>≡</span><span>+</span><span>&lt;/&gt;</span><span>?</span>
                                                </div>
                                                <textarea id="propertyHighlights" class="property-rich-editor" name="highlights" placeholder="Enter highlights here..." required>{{ old('highlights', $property->highlights ?? '') }}</textarea>
                                            </div>
                                            <div class="property-field">
                                                <label for="propertyAboutProject">About Project <span class="required-star">*</span></label>
                                                <textarea id="propertyAboutProject" name="about_project" placeholder="Detailed project and builder info..." required>{{ old('about_project', $property->about_project ?? '') }}</textarea>
                                            </div>
                                            <div class="property-field">
                                                <label for="propertyReraNumber">Rera Number</label>
                                                <input type="text" id="propertyReraNumber" name="rera_number" placeholder="Enter RERA registration number" value="{{ old('rera_number', $propertyContent->meta_keyword ?? '') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="property-form-card property-faq-theme">
                                        <h3>Frequently Asked Questions</h3>
                                        <p>Add up to 25 FAQs for this project.</p>
                                        @php
                                            $faqRows = old('faqs', collect($propertyFaqs ?? [])->map(fn ($faq) => ['question' => $faq->question ?? '', 'answer' => $faq->answer ?? ''])->all());
                                            if (empty($faqRows)) {
                                                $faqRows = [['question' => '', 'answer' => '']];
                                            }
                                        @endphp
                                        <div id="propertyFaqList">
                                            @foreach ($faqRows as $faqIndex => $faqRow)
                                                <div class="faq-item">
                                                    <div class="property-field"><label>Question {{ $faqIndex + 1 }}</label><input type="text" name="faqs[{{ $faqIndex }}][question]" value="{{ $faqRow['question'] ?? '' }}" placeholder="Enter question"></div>
                                                    <div class="property-field"><label>Answer {{ $faqIndex + 1 }}</label><input type="text" name="faqs[{{ $faqIndex }}][answer]" value="{{ $faqRow['answer'] ?? '' }}" placeholder="Enter answer"></div>
                                                    <button type="button" class="faq-remove-btn" style="{{ $faqIndex === 0 ? 'visibility:hidden;' : '' }}">Remove</button>
                                                </div>
                                            @endforeach
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
                            @keyframes addUserBtnGlow {
                                0%   { box-shadow: 0 4px 15px rgba(56,128,255,0.3), inset 0 0 0 0 rgba(56,128,255,0.1); }
                                50%  { box-shadow: 0 6px 30px rgba(56,128,255,0.6), inset 0 0 0 2px rgba(56,128,255,0.2); }
                                100% { box-shadow: 0 4px 15px rgba(56,128,255,0.3), inset 0 0 0 0 rgba(56,128,255,0.1); }
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
                                padding: 4px 8px;
                                color: inherit;
                                font-weight: 600;
                                outline: none;
                                transition: all 0.3s cubic-bezier(.4,2,.6,1);
                                cursor: pointer;
                            }

                            .status-verified, .status-active { 
                                background-color: #e8f9f6; 
                                color: #1db8a0;
                                border-color: #b3e6de;
                                animation: userStatusPulsed 3s ease-in-out infinite;
                            }
                            .status-unverified, .status-inactive { 
                                background-color: #fff1f3;
                                color: #ef476f;
                                border-color: #ffcbd4;
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

                        <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    .users-section-wrap{
        font-family:'Inter',sans-serif;
        animation:fadePage .6s ease;
    }

    @keyframes fadePage{
        from{
            opacity:0;
            transform:translateY(18px);
        }
        to{
            opacity:1;
            transform:translateY(0);
        }
    }

    @keyframes rowSlide{
        from{
            opacity:0;
            transform:translateX(-18px);
        }
        to{
            opacity:1;
            transform:translateX(0);
        }
    }

    @keyframes pulseBadge{
        0%,100%{
            transform:scale(1);
        }
        50%{
            transform:scale(1.04);
        }
    }

    .amenities-title{
        font-size:1.5rem;
        font-weight:800;
        color:#0f172a;
        letter-spacing:-0.5px;
    }

    .btn-add-primary{
        background:linear-gradient(135deg,#3b82f6,#2563eb) !important;
        box-shadow:0 10px 20px rgba(37,99,235,0.18);
        transition:all .3s ease !important;
    }

    .btn-add-primary:hover{
        transform:translateY(-2px);
        box-shadow:0 14px 28px rgba(37,99,235,0.28);
    }

    .table-controls{
        background:#fff;
        padding:18px 20px;
        border-radius:18px;
        box-shadow:0 8px 25px rgba(15,23,42,0.04);
        border:1px solid #eef2f7;
        display:flex;
        justify-content:space-between;
        align-items:center;
        flex-wrap:wrap;
        gap:16px;
    }

    .control-input,
    .entries-select{
        border:1px solid #dbe4ee;
        border-radius:12px;
        padding:10px 14px;
        font-size:0.9rem;
        transition:.25s ease;
        background:#fff;
    }

    .control-input:focus,
    .entries-select:focus{
        outline:none;
        border-color:#3b82f6;
        box-shadow:0 0 0 4px rgba(59,130,246,0.10);
    }

    .filter-btn{
        border:none;
        background:#f1f5f9;
        color:#475569;
        padding:9px 16px;
        border-radius:12px;
        font-weight:600;
        cursor:pointer;
        transition:.25s ease;
    }

    .filter-btn:hover{
        transform:translateY(-2px);
    }

    .filter-btn.active{
        background:linear-gradient(135deg,#7c3aed,#9333ea);
        color:#fff;
        box-shadow:0 8px 20px rgba(124,58,237,0.20);
    }

    .data-table-container{
        margin-top:24px;
        background:#fff;
        border-radius:24px;
        overflow:hidden;
        border:1px solid #edf2f7;
        box-shadow:0 10px 30px rgba(15,23,42,0.05);
    }

    .data-table{
        width:100%;
        border-collapse:collapse;
    }

    .data-table thead tr{
        background:#f8fafc;
    }

    .data-table th{
        padding:18px;
        text-align:left;
        color:#64748b;
        font-size:.8rem;
        text-transform:uppercase;
        letter-spacing:.8px;
        font-weight:700;
        border-bottom:1px solid #e2e8f0;
    }

    .data-table td{
        padding:18px;
        border-bottom:1px solid #f1f5f9;
        font-size:.92rem;
        color:#1e293b;
    }

    .user-row{
        animation:rowSlide .45s ease both;
        transition:.3s ease;
    }

    @foreach ($registeredUsers ?? [] as $index => $regUser)
        .user-row:nth-child({{ $index + 1 }}){
            animation-delay:{{ $index * 0.04 }}s;
        }
    @endforeach

    .user-row:hover{
        background:linear-gradient(90deg,rgba(59,130,246,0.06),transparent);
        transform:translateX(3px);
        box-shadow:inset 4px 0 0 #3b82f6;
    }

    .status-select{
        border:none;
        border-radius:12px;
        padding:10px 16px;
        font-size:.82rem;
        font-weight:700;
        min-width:130px;
        cursor:pointer;
        transition:.25s ease;
        animation:pulseBadge 2.5s infinite;
    }

    .status-select:hover{
        transform:translateY(-2px);
    }

    .status-select:focus{
        outline:none;
    }

    /* Email Verified */
    .email-verified{
        background:#ecfdf5;
        color:#059669;
        box-shadow:0 0 0 2px rgba(5,150,105,0.10);
    }

    /* Email Unverified */
    .email-unverified{
        background:#fff1f2;
        color:#e11d48;
        box-shadow:0 0 0 2px rgba(225,29,72,0.10);
    }

    /* Active */
    .account-active{
        background:#eff6ff;
        color:#2563eb;
        box-shadow:0 0 0 2px rgba(37,99,235,0.10);
    }

    /* Inactive */
    .account-inactive{
        background:#f3e8ff;
        color:#7e22ce;
        box-shadow:0 0 0 2px rgba(126,34,206,0.10);
    }

    .actions{
        display:flex;
        gap:10px;
        align-items:center;
    }

    .btn-action{
        width:38px;
        height:38px;
        border:none;
        border-radius:12px;
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        transition:.25s ease;
    }

    .btn-action:hover{
        transform:translateY(-3px) scale(1.04);
    }

    .btn-edit{
        background:#eff6ff;
        color:#2563eb;
    }

    .btn-delete{
        background:#fff1f2;
        color:#e11d48;
    }

    .pagination-footer{
        padding:18px 22px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        background:#fafcff;
        flex-wrap:wrap;
        gap:12px;
    }

    .pagination-info{
        color:#64748b;
        font-size:.88rem;
        font-weight:500;
    }

    /* MODAL */

    .modal{
        border-radius:28px;
        overflow:hidden;
        border:none;
        box-shadow:0 25px 60px rgba(15,23,42,0.18);
        animation:fadePage .35s ease;
    }

    .modal-header{
        padding:24px;
        border-bottom:1px solid #eef2f7;
        background:#ffffff;
    }

    .modal-title{
        font-size:1.3rem;
        font-weight:800;
        color:#0f172a;
    }

    .modal-subtitle{
        color:#64748b;
        margin-top:4px;
        font-size:.9rem;
    }

    .modal-body{
        padding:24px;
        background:#fcfcfd;
    }

    .form-row{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:18px;
    }

    .form-group{
        margin-bottom:18px;
    }

    .form-label{
        display:block;
        margin-bottom:8px;
        font-size:.88rem;
        font-weight:700;
        color:#334155;
    }

    .form-control{
        width:100%;
        border:1px solid #dbe4ee;
        border-radius:14px;
        padding:12px 14px;
        font-size:.92rem;
        transition:.25s ease;
        background:#fff;
    }

    .form-control:focus{
        outline:none;
        border-color:#3b82f6;
        box-shadow:0 0 0 4px rgba(59,130,246,0.10);
    }

    .modal-footer{
        padding:22px 24px;
        display:flex;
        justify-content:flex-end;
        gap:12px;
        background:#fff;
        border-top:1px solid #eef2f7;
    }

    .btn-cancel{
        border:none;
        background:#f1f5f9;
        color:#475569;
        padding:12px 18px;
        border-radius:14px;
        font-weight:700;
        cursor:pointer;
        transition:.25s ease;
    }

    .btn-primary{
        border:none;
        background:linear-gradient(135deg,#2563eb,#3b82f6);
        color:#fff;
        padding:12px 20px;
        border-radius:14px;
        font-weight:700;
        cursor:pointer;
        transition:.25s ease;
        box-shadow:0 10px 20px rgba(37,99,235,0.18);
    }

    .btn-primary:hover,
    .btn-cancel:hover{
        transform:translateY(-2px);
    }

    @media(max-width:768px){

        .table-controls{
            flex-direction:column;
            align-items:flex-start;
        }

        .form-row{
            grid-template-columns:1fr;
        }

        .data-table{
            min-width:900px;
        }
    }
</style>

<div class="users-section-wrap">

    <div class="breadcrumb-wrapper">
        <div class="breadcrumb-item"></div>
    </div>

    <div class="amenities-header-row" style="justify-content: space-between; margin-bottom:20px;">
        <h2 class="amenities-title">Registered Users</h2>

        <a href="{{ route('admin.users.create') }}" class="btn-add-primary" style="text-decoration:none;">
            <span style="font-size:1.1rem;font-weight:800;">+</span>
            Add User
        </a>
    </div>

    <div class="table-controls">

        <div class="search-control">
            Search:
            <input type="text" id="userSearch" class="control-input" placeholder="Search users...">

            <span style="margin-left:12px;color:#64748b;font-size:.85rem;">
                Show
            </span>

            <select id="userEntries" class="entries-select">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>

            <span style="color:#64748b;font-size:.85rem;">
                entries
            </span>
        </div>

        <div class="filter-control">
            Filter by:

            <button class="filter-btn active" id="userFilterAll" onclick="setUserFilterButton('all')">
                All
            </button>

            <button class="filter-btn" id="userFilterActive" onclick="setUserFilterButton('active')">
                Active
            </button>

            <button class="filter-btn" id="userFilterInactive" onclick="setUserFilterButton('inactive')">
                Inactive
            </button>
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

                    <tr class="user-row"
                        data-id="{{ $regUser->id }}"
                        data-first-name="{{ $regUser->first_name ?? $regUser->name ?? '' }}"
                        data-last-name="{{ $regUser->last_name ?? '' }}"
                        data-email="{{ $regUser->email }}"
                        data-phone="{{ $regUser->phone ?? $regUser->contact_number ?? '' }}"
                        data-status="{{ $regUser->status ?? '' }}"
                        data-email-status="{{ $regUser->email_verified_at ? 'verified' : 'unverified' }}"
                        data-created="{{ $regUser->created_at }}">

                        <td style="font-weight:700;">
                            {{ $regUser->first_name ?? $regUser->name }}
                        </td>

                        <td>
                            {{ $regUser->email }}
                        </td>

                        <td>

                            <form method="POST"
                                  action="{{ route('admin.users.updateEmailVerification', ['targetUser' => $regUser->id]) }}">

                                @csrf
                                @method('PUT')

                                <select name="email_status"
                                    class="status-select {{ $regUser->email_verified_at ? 'email-verified' : 'email-unverified' }}"
                                    onchange="
                                        this.classList.remove('email-verified','email-unverified');

                                        if(this.value === 'verified'){
                                            this.classList.add('email-verified');
                                        }else{
                                            this.classList.add('email-unverified');
                                        }

                                        this.form.submit();
                                    ">

                                    <option value="verified" {{ $regUser->email_verified_at ? 'selected' : '' }}>
                                        Verified
                                    </option>

                                    <option value="unverified" {{ $regUser->email_verified_at ? '' : 'selected' }}>
                                        Unverified
                                    </option>

                                </select>

                            </form>

                        </td>

                        <td>

                            @if (Schema::hasColumn('users', 'status'))

                                <form method="POST"
                                      action="{{ route('admin.users.updateStatus', ['targetUser' => $regUser->id]) }}">

                                    @csrf
                                    @method('PUT')

                                    <select name="account_status"
                                        class="status-select {{ $regUser->status == 1 ? 'account-active' : 'account-inactive' }}"
                                        onchange="
                                            this.classList.remove('account-active','account-inactive');

                                            if(this.value == '1'){
                                                this.classList.add('account-active');
                                            }else{
                                                this.classList.add('account-inactive');
                                            }

                                            this.form.submit();
                                        ">

                                        <option value="1" {{ $regUser->status == 1 ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="0" {{ $regUser->status == 1 ? '' : 'selected' }}>
                                            Inactive
                                        </option>

                                    </select>

                                </form>

                            @else
                                N/A
                            @endif

                        </td>

                        <td>
                            {{ optional($regUser->created_at)->format('d M Y, H:i') ?? '-' }}
                        </td>

                        <td>

                            <div class="actions">

                                <button type="button"
                                        class="btn-action btn-edit"
                                        title="Edit"
                                        onclick="editUserModal({{ $regUser->id }})">

                                    <svg width="16" height="16" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round">

                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>

                                    </svg>

                                </button>

                                <form method="POST"
                                      action="{{ route('admin.users.destroy', ['targetUser' => $regUser->id]) }}"
                                      style="display:inline;"
                                      onsubmit="return confirm('Remove this user?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn-action btn-delete"
                                            title="Delete">

                                        <svg width="16" height="16" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round">

                                            <path d="M3 6h18"/>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>

                                        </svg>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

        <div class="pagination-footer">
            <div class="pagination-info" id="userPageInfo">
                Showing 0 to 0 of 0 entries
            </div>

            <div class="pagination-controls" id="userPagination"></div>
        </div>

    </div>

</div>
                    @elseif ($currentPage === 'property-enquiries')
                        <style>
                            @keyframes enquiryPageSlideIn {
                                from { opacity: 0; transform: translateY(20px); }
                                to   { opacity: 1; transform: translateY(0); }
                            }
                            @keyframes enquiryRowSlideIn {
                                from { opacity: 0; transform: translateX(-20px) scale(0.97); }
                                to   { opacity: 1; transform: translateX(0) scale(1); }
                            }
                            @keyframes enquiryBadgePop {
                                0%   { transform: scale(0.9); opacity: 0; }
                                80%  { transform: scale(1.05); }
                                100% { transform: scale(1); opacity: 1; }
                            }
                            @keyframes enquiryStatusPulseGreen {
                                0%, 100% { box-shadow: 0 0 0 2px hsla(180, 91%, 50%, 0.99); }
                                50%      { box-shadow: 0 0 0 4px hsla(180, 91%, 50%, 0.55), 0 0 16px hsla(180, 91%, 50%, 0.25); }
                            }
                            @keyframes enquiryStatusPulseRed {
                                0%, 100% { box-shadow: 0 0 0 2px hsla(22, 91%, 50%, 0.99); }
                                50%      { box-shadow: 0 0 0 4px hsla(22, 91%, 50%, 0.55), 0 0 16px hsla(22, 91%, 50%, 0.25); }
                            }
                            .enquiries-section-wrap {
                                animation: enquiryPageSlideIn 0.5s cubic-bezier(.22,1,.36,1) both;
                            }
                            .enquiry-row {
                                animation: enquiryRowSlideIn 0.45s cubic-bezier(.22,1,.36,1) both;
                                transition: all 0.3s cubic-bezier(.4,2,.6,1);
                            }
                            @for ($i = 0; $i < count($enquiries ?? []); $i++)
                                .enquiry-row:nth-child({{ $i + 1 }}) { animation-delay: {{ $i * 0.05 }}s; }
                            @endfor
                            .enquiry-row:hover {
                                background: linear-gradient(90deg, rgba(29,151,219,0.08) 0%, transparent 100%);
                                box-shadow: inset 3px 0 0 #1d97db, 0 2px 8px rgba(0,0,0,0.08);
                                transform: translateX(2px);
                            }
                            .message-preview {
                                max-width: 200px;
                                white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                color: #64748b;
                                font-size: 0.85rem;
                                cursor: help;
                            }

                            /* Enquiry Status Select — matching Manage Properties theme */
                            .enquiry-status-select {
                                padding: 4px 10px;
                                border-radius: 8px;
                                border: none;
                                color: white;
                                font-weight: 600;
                                font-size: 13px;
                                cursor: pointer;
                                min-width: 100px;
                                transition: all 0.2s cubic-bezier(.4,2,.6,1);
                                animation: enquiryBadgePop 0.4s cubic-bezier(.4,2,.6,1) both;
                            }
                            .enquiry-status-select:focus {
                                outline: none;
                                box-shadow: 0 0 0 2px rgba(255,255,255,0.3);
                            }
                            .enquiry-status-select option {
                                background-color: #ffffff;
                                color: #333;
                                padding: 8px;
                            }
                            .enquiry-status-select:hover {
                                transform: translateY(-1px);
                                filter: brightness(1.1);
                                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                            }
                            .enquiry-status-select.eq-received {
                                box-shadow: inset 0 0 0 1px rgba(29, 184, 160, 0.16), 0 0 0 2px rgba(29, 184, 160, 0.25);
                                color: #1db8a0;
                                background-color: #e8f9f6;
                                animation: enquiryStatusPulseGreen 3s ease-in-out infinite;
                            }
                            .enquiry-status-select.eq-closed {
                                box-shadow: inset 0 0 0 1px rgba(239, 71, 111, 0.16), 0 0 0 2px rgba(239, 71, 111, 0.25);
                                color: #ef476f;
                                background-color: #fff1f3;
                                animation: enquiryStatusPulseRed 3s ease-in-out infinite;
                            }
                        </style>

                        <div class="enquiries-section-wrap">
                            <div class="amenities-header-row">
                                <h2 class="amenities-title">Property Enquiries</h2>
                            </div>

                            <div class="table-controls" style="margin-bottom: 24px;">
                                <div class="search-control">
                                    Search: <input type="text" id="enquirySearch" class="control-input" placeholder="Search enquiries...">
                                    <span style="margin-left: 12px; color: #64748b; font-size: 0.85rem;">Show</span>
                                    <select id="enquiryEntries" class="entries-select" style="min-width: 75px;">
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
                                            <th>Name</th>
                                            <th>Contact Info</th>
                                            <th>Property</th>
                                            <th>Message</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($enquiries ?? [] as $enquiry)
                                            <tr class="enquiry-row" 
                                                data-name="{{ $enquiry->name }}" 
                                                data-email="{{ $enquiry->email }}" 
                                                data-mobile="{{ $enquiry->mobile }}"
                                                data-property="{{ $enquiry->property_name }}">
                                                <td style="font-weight: 600;">{{ $enquiry->name }}</td>
                                                <td>
                                                    <div style="font-size: 0.85rem; color: #1e293b; font-weight: 500;">{{ $enquiry->mobile }}</div>
                                                    <div style="font-size: 0.75rem; color: #64748b;">{{ $enquiry->email }}</div>
                                                </td>
                                                <td>
                                                    <span style="color: #1d97db; font-weight: 600; font-size: 0.85rem;">{{ $enquiry->property_name }}</span>
                                                </td>
                                                <td>
                                                    <div class="message-preview" title="{{ $enquiry->message }}">
                                                        {{ $enquiry->message ?: 'No message provided' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $enquiryStatus = strtolower($enquiry->enquiry_status ?? 'received');
                                                    @endphp
                                                    <form method="POST" action="{{ route('admin.enquiries.updateStatus', ['enquiry' => $enquiry->id]) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <select name="account_status" class="enquiry-status-select {{ $enquiryStatus === 'received' ? 'eq-received' : 'eq-closed' }}" onchange="this.className='enquiry-status-select ' + (this.value==='received' ? 'eq-received' : 'eq-closed'); this.form.submit();">
                                                            <option value="received" {{ $enquiryStatus === 'received' ? 'selected' : '' }}>Received</option>
                                                            <option value="closed" {{ $enquiryStatus === 'closed' ? 'selected' : '' }}>Closed</option>
                                                        </select>
                                                    </form>
                                                </td>
                                                <td style="font-size: 0.85rem; color: #64748b;">
                                                    {{ optional($enquiry->created_at)->format('d M Y, H:i') ?? '-' }}
                                                </td>
                                                <td>
                                                    <div class="actions">
                                                        <button type="button" class="btn-action btn-view" title="View Details" onclick="viewEnquiryDetails({{ json_encode($enquiry) }})">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                        </button>
                                                        <form method="POST" action="#" style="display:inline;" onsubmit="return confirm('Delete this enquiry?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn-action btn-delete" title="Delete">
                                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">
                                                    No enquiries found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="pagination-footer">
                                    <div class="pagination-info" id="enquiryPageInfo">Showing 0 to 0 of 0 entries</div>
                                    <div class="pagination-controls" id="enquiryPagination"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Enquiry Details Modal -->
                        <div id="enquiryModal" class="modal-backdrop" onclick="if(event.target === this) closeEnquiryModal()">
                            <div class="modal" style="max-width: 500px;">
                                <div class="modal-header">
                                    <div>
                                        <h3 class="modal-title">Enquiry Details</h3>
                                        <p class="modal-subtitle">Full information for the selected enquiry.</p>
                                    </div>
                                    <button type="button" class="modal-close-btn" onclick="closeEnquiryModal()">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <div class="modal-body" id="enquiryModalContent">
                                    <!-- Dynamic Content -->
                                </div>
                                <div class="modal-footer">
                                    <button class="btn-primary" onclick="closeEnquiryModal()">Close</button>
                                </div>
                            </div>
                        </div>

                    @elseif (str_ends_with($currentPage ?? '', '-pinning'))
                        <style>
                            @keyframes pinningSlideIn {
                                from { opacity: 0; transform: translateY(24px); }
                                to   { opacity: 1; transform: translateY(0); }
                            }
                            @keyframes pinCardPop {
                                from { opacity: 0; transform: scale(0.95) translateY(12px); }
                                to   { opacity: 1; transform: scale(1) translateY(0); }
                            }
                            @keyframes shimmerBg {
                                0%   { background-position: -200% 0; }
                                100% { background-position: 200% 0; }
                            }
                            @keyframes badgeGlow {
                                0%, 100% { box-shadow: 0 0 0 0 rgba(24,164,234,0.4); }
                                50%      { box-shadow: 0 0 0 8px rgba(24,164,234,0); }
                            }

                            .pinning-wrap {
                                animation: pinningSlideIn 0.5s cubic-bezier(.22,1,.36,1) both;
                            }

                            .pinning-section-header {
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                                margin-bottom: 28px;
                                padding-bottom: 20px;
                                border-bottom: 2px solid #f1f5f9;
                            }

                            .pinning-section-header h2 {
                                font-size: 1.4rem;
                                font-weight: 800;
                                color: #0f172a;
                                margin: 0;
                                display: flex;
                                align-items: center;
                                gap: 12px;
                            }

                            .pinning-section-header h2 .pin-icon {
                                width: 36px;
                                height: 36px;
                                background: linear-gradient(135deg, #18a4ea 0%, #0ea5e9 100%);
                                border-radius: 10px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                color: #fff;
                                box-shadow: 0 4px 12px rgba(24,164,234,0.3);
                            }

                            .pinning-section-header h2 .pin-icon svg {
                                width: 20px;
                                height: 20px;
                            }

                            .pinning-page-badge {
                                display: inline-flex;
                                align-items: center;
                                gap: 6px;
                                padding: 6px 16px;
                                border-radius: 20px;
                                background: linear-gradient(135deg, #eff6ff, #dbeafe);
                                color: #1e40af;
                                font-size: 0.82rem;
                                font-weight: 700;
                                border: 1px solid #bfdbfe;
                                animation: badgeGlow 3s ease-in-out infinite;
                            }

                            /* ── Current Pinned Table ── */
                            .pinned-table-card {
                                background: #fff;
                                border-radius: 20px;
                                border: 1px solid #e2e8f0;
                                box-shadow: 0 8px 32px rgba(15,23,42,0.06);
                                overflow: hidden;
                                margin-bottom: 32px;
                                animation: pinCardPop 0.5s cubic-bezier(.22,1,.36,1) 0.1s both;
                            }

                            .pinned-table-card-header {
                                padding: 20px 28px;
                                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                                border-bottom: 1px solid #e2e8f0;
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                            }

                            .pinned-table-card-header h3 {
                                font-size: 1.05rem;
                                font-weight: 700;
                                color: #334155;
                                margin: 0;
                                display: flex;
                                align-items: center;
                                gap: 8px;
                            }

                            .pinned-count-badge {
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                min-width: 28px;
                                height: 28px;
                                padding: 0 8px;
                                border-radius: 14px;
                                background: linear-gradient(135deg, #18a4ea, #0284c7);
                                color: #fff;
                                font-size: 0.8rem;
                                font-weight: 800;
                            }

                            .pinned-table {
                                width: 100%;
                                border-collapse: collapse;
                            }

                            .pinned-table thead th {
                                padding: 14px 20px;
                                text-align: left;
                                font-size: 0.78rem;
                                font-weight: 700;
                                text-transform: uppercase;
                                letter-spacing: 0.05em;
                                color: #64748b;
                                background: #fafbfc;
                                border-bottom: 1px solid #f1f5f9;
                            }

                            .pinned-table tbody tr {
                                transition: background 0.2s ease;
                            }

                            .pinned-table tbody tr:hover {
                                background: linear-gradient(90deg, rgba(24,164,234,0.04) 0%, transparent 100%);
                            }

                            .pinned-table tbody td {
                                padding: 16px 20px;
                                font-size: 0.92rem;
                                color: #334155;
                                border-bottom: 1px solid #f8fafc;
                                vertical-align: middle;
                            }

                            .order-badge {
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                width: 34px;
                                height: 34px;
                                border-radius: 10px;
                                font-weight: 800;
                                font-size: 0.9rem;
                            }

                            .order-badge.order-1 { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; border: 1px solid #fcd34d; }
                            .order-badge.order-2 { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #3730a3; border: 1px solid #a5b4fc; }
                            .order-badge.order-3 { background: linear-gradient(135deg, #fce7f3, #fbcfe8); color: #9d174d; border: 1px solid #f9a8d4; }
                            .order-badge.order-default { background: linear-gradient(135deg, #f1f5f9, #e2e8f0); color: #475569; border: 1px solid #cbd5e1; }

                            .prop-name-cell {
                                font-weight: 700;
                                color: #0f172a;
                            }

                            .location-cell {
                                display: flex;
                                align-items: center;
                                gap: 6px;
                                color: #64748b;
                            }

                            .location-cell svg {
                                width: 14px;
                                height: 14px;
                                color: #94a3b8;
                                flex-shrink: 0;
                            }

                            .builder-chip {
                                display: inline-flex;
                                align-items: center;
                                gap: 6px;
                                padding: 4px 12px;
                                border-radius: 8px;
                                background: #f0fdf4;
                                color: #166534;
                                font-size: 0.82rem;
                                font-weight: 600;
                                border: 1px solid #bbf7d0;
                            }

                            .btn-remove-pin {
                                display: inline-flex;
                                align-items: center;
                                gap: 5px;
                                padding: 7px 14px;
                                border-radius: 8px;
                                border: 1px solid #fecaca;
                                background: #fff;
                                color: #dc2626;
                                font-size: 0.8rem;
                                font-weight: 600;
                                cursor: pointer;
                                transition: all 0.2s ease;
                            }

                            .btn-remove-pin:hover {
                                background: #fef2f2;
                                border-color: #f87171;
                                transform: translateY(-1px);
                                box-shadow: 0 4px 12px rgba(220,38,38,0.15);
                            }

                            .btn-remove-pin svg {
                                width: 14px;
                                height: 14px;
                            }

                            .empty-pinned-state {
                                text-align: center;
                                padding: 48px 24px;
                                color: #94a3b8;
                            }

                            .empty-pinned-state svg {
                                width: 52px;
                                height: 52px;
                                color: #cbd5e1;
                                margin-bottom: 12px;
                            }

                            .empty-pinned-state p {
                                font-size: 0.95rem;
                                margin: 0;
                            }

                            /* ── Add Pin Form ── */
                            .pin-form-card {
                                background: #fff;
                                border-radius: 20px;
                                border: 1px solid #e2e8f0;
                                box-shadow: 0 8px 32px rgba(15,23,42,0.06);
                                overflow: hidden;
                                animation: pinCardPop 0.5s cubic-bezier(.22,1,.36,1) 0.2s both;
                            }

                            .pin-form-card-header {
                                padding: 20px 28px;
                                background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
                                border-bottom: 1px solid #bae6fd;
                                display: flex;
                                align-items: center;
                                gap: 10px;
                            }

                            .pin-form-card-header h3 {
                                font-size: 1.05rem;
                                font-weight: 700;
                                color: #0c4a6e;
                                margin: 0;
                            }

                            .pin-form-card-header svg {
                                width: 20px;
                                height: 20px;
                                color: #0284c7;
                            }

                            .pin-form-body {
                                padding: 28px;
                            }

                            .filter-row {
                                display: grid;
                                grid-template-columns: repeat(3, 1fr);
                                gap: 20px;
                                margin-bottom: 24px;
                            }

                            @media (max-width: 900px) {
                                .filter-row {
                                    grid-template-columns: 1fr;
                                }
                            }

                            .pin-filter-group {
                                display: flex;
                                flex-direction: column;
                                gap: 8px;
                            }

                            .pin-filter-group label {
                                font-size: 0.85rem;
                                font-weight: 700;
                                color: #334155;
                                text-transform: uppercase;
                                letter-spacing: 0.04em;
                            }

                            .pin-filter-group select {
                                width: 100%;
                                padding: 12px 14px;
                                border: 1.5px solid #e2e8f0;
                                border-radius: 12px;
                                font-size: 0.92rem;
                                color: #0f172a;
                                background: #f8fafc;
                                transition: all 0.2s ease;
                                cursor: pointer;
                            };
                                cursor: pointer;
                            }

                            .pin-filter-group select:focus {
                                border-color: #18a4ea;
                                background-color: #fff;
                                outline: 0;
                                box-shadow: 0 0 0 4px rgba(24,164,234,0.12);
                            }

                            .pin-select-row {
                                display: grid;
                                grid-template-columns: 2fr 1fr;
                                gap: 20px;
                                margin-bottom: 24px;
                            }

                            @media (max-width: 900px) {
                                .pin-select-row {
                                    grid-template-columns: 1fr;
                                }
                            }

                            .btn-pin-save {
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                gap: 8px;
                                padding: 14px 32px;
                                border: 0;
                                border-radius: 14px;
                                background: linear-gradient(135deg, #18a4ea 0%, #0284c7 100%);
                                color: #fff;
                                font-size: 0.95rem;
                                font-weight: 700;
                                cursor: pointer;
                                transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
                                box-shadow: 0 6px 20px rgba(24,164,234,0.3);
                            }

                            .btn-pin-save:hover {
                                transform: translateY(-2px);
                                box-shadow: 0 10px 28px rgba(24,164,234,0.4);
                            }

                            .btn-pin-save:active {
                                transform: translateY(0);
                            }

                            .btn-pin-save svg {
                                width: 18px;
                                height: 18px;
                            }

                            .filtered-count {
                                font-size: 0.82rem;
                                color: #64748b;
                                margin-bottom: 16px;
                                display: flex;
                                align-items: center;
                                gap: 6px;
                            }

                            .filtered-count strong {
                                color: #18a4ea;
                            }
                        </style>

                        <div class="pinning-wrap">
                            {{-- Header --}}
                            <div class="pinning-section-header">
                                <h2>
                                    <span class="pin-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 2v10l3.5 3.5"/>
                                            <path d="M16.5 15.5L19 18l-7 4-7-4 2.5-2.5"/>
                                            <path d="M12 12L8.5 15.5"/>
                                        </svg>
                                    </span>
                                    {{ $currentItem['label'] ?? 'Project Pinning' }}
                                </h2>
                                <span class="pinning-page-badge">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                    Page: {{ str_replace(['-pinning', '-'], ['', ' '], ucwords($pinningPageSlug ?? '', '-')) }}
                                </span>
                            </div>

                            {{-- Currently Pinned Projects Table --}}
                            <div class="pinned-table-card">
                                <div class="pinned-table-card-header">
                                    <h3>
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                                        Currently Pinned Projects
                                    </h3>
                                    <span class="pinned-count-badge">{{ ($pinnedProjects ?? collect())->count() }}</span>
                                </div>

                                @if (($pinnedProjects ?? collect())->isNotEmpty())
                                    <table class="pinned-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 80px;">Position</th>
                                                <th>Property Name</th>
                                                <th style="width: 110px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pinnedProjects as $pin)
                                                <tr>
                                                    <td>
                                                        @php
                                                            $orderClass = 'order-default';
                                                            if ($pin->display_order == 1) $orderClass = 'order-1';
                                                            elseif ($pin->display_order == 2) $orderClass = 'order-2';
                                                            elseif ($pin->display_order == 3) $orderClass = 'order-3';
                                                        @endphp
                                                        <span class="order-badge {{ $orderClass }}">{{ $pin->display_order }}</span>
                                                    </td>
                                                    <td class="prop-name-cell">{{ $pin->property_name ?? 'N/A' }}</td>
                                                    <td>
                                                        <div class="location-cell">
                                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                                            {{ $pin->property_location ?? 'N/A' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="builder-chip">
                                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                            {{ $pin->property_builder ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <form method="POST" action="{{ route('admin.pinned-project.remove') }}" onsubmit="return confirm('Remove this pinned project?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="page_slug" value="{{ $pinningPageSlug }}">
                                                            <input type="hidden" name="display_order" value="{{ $pin->display_order }}">
                                                            <button type="submit" class="btn-remove-pin">
                                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                                Remove
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="empty-pinned-state">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="9" x2="15" y2="15"/><line x1="15" y1="9" x2="9" y2="15"/></svg>
                                        <p>No projects pinned yet. Use the form below to pin projects.</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Pin New Project Form --}}
                            <div class="pin-form-card">
                                <div class="pin-form-card-header">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    <h3>Pin a Project to Display Order</h3>
                                </div>
                                <div class="pin-form-body">
                                    {{-- Filter Row --}}
                                    {{-- <div class="filter-row">
                                        <div class="pin-filter-group">
                                            <label for="pinFilterName">Property Name</label>
                                            <select id="pinFilterName" onchange="applyPinFilters()">
                                                <option value="">All Properties</option>
                                                @foreach (($pinningProperties ?? collect()) as $prop)
                                                    <option value="{{ $prop->id }}">{{ $prop->property_name }}</option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                        {{-- <div class="pin-filter-group">
                                            <label for="pinFilterLocation">Location</label>
                                            <select id="pinFilterLocation" onchange="applyPinFilters()">
                                                <option value="">All Locations</option>
                                                @foreach (($pinningLocations ?? collect()) as $loc)
                                                    <option value="{{ $loc }}">{{ $loc }}</option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                        {{-- <div class="pin-filter-group">
                                            <label for="pinFilterBuilder">Builder Name</label>
                                            <select id="pinFilterBuilder" onchange="applyPinFilters()">
                                                <option value="">All Builders</option>
                                                @foreach (($pinningBuilders ?? collect()) as $builder)
                                                    <option value="{{ $builder }}">{{ $builder }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}

                                    <div class="filtered-count" id="pinFilteredCount">
                                        Showing <strong>{{ ($pinningProperties ?? collect())->count() }}</strong> properties
                                    </div>

                                    <form method="POST" action="{{ route('admin.pinned-project.save') }}">
                                        @csrf
                                        <input type="hidden" name="page_slug" value="{{ $pinningPageSlug ?? '' }}">

                                        <div class="pin-select-row">
                                            <div class="pin-filter-group">
                                                <label for="pinPropertySelect">Select Project</label>
                                                <select id="pinPropertySelect" name="property_id" required onchange="onPinProjectChange(this)">
                                                    <option value="">— Choose a project —</option>
                                                    @foreach (($pinningProperties ?? collect()) as $prop)
                                                        <option
                                                            value="{{ $prop->id }}"
                                                            data-name="{{ $prop->property_name }}"
                                                            data-location="{{ $prop->location }}"
                                                            data-builder="{{ $prop->builder }}"
                                                        >
                                                            {{ $prop->property_name }} — {{ $prop->location }} ({{ $prop->builder }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="pin-filter-group">
                                                <label for="pinDisplayOrder">Display Position</label>
                                                <select id="pinDisplayOrder" name="display_order" required>
                                                    @for ($i = 1; $i <= 20; $i++)
                                                        @php
                                                            $suffix = 'th';
                                                            if ($i == 1) $suffix = 'st';
                                                            elseif ($i == 2) $suffix = 'nd';
                                                            elseif ($i == 3) $suffix = 'rd';
                                                            $existing = ($pinnedProjects ?? collect())->firstWhere('display_order', $i);
                                                        @endphp
                                                        <option value="{{ $i }}">
                                                            {{ $i }}{{ $suffix }} Position {{ $existing ? '(Replace: ' . Str::limit($existing->property_name, 20) . ')' : '' }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Autofill preview row --}}
                                        <div id="pinAutofillRow" style="display:none; margin-bottom:20px;">
                                            <div style="display:grid; grid-template-columns: repeat(3,1fr); gap:16px;">
                                                <div class="pin-filter-group">
                                                    <label>Property Name</label>
                                                    <input type="text" id="pinAutoName" readonly
                                                        style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:12px;font-size:0.92rem;color:#0f172a;background:#f1f5f9;cursor:default;">
                                                </div>
                                                <div class="pin-filter-group">
                                                    <label>Location</label>
                                                    <input type="text" id="pinAutoLocation" readonly
                                                        style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:12px;font-size:0.92rem;color:#0f172a;background:#f1f5f9;cursor:default;">
                                                </div>
                                                <div class="pin-filter-group">
                                                    <label>Builder Name</label>
                                                    <input type="text" id="pinAutoBuilder" readonly
                                                        style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:12px;font-size:0.92rem;color:#0f172a;background:#f1f5f9;cursor:default;">
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn-pin-save">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                            Save Pinned Project
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @php
                            $pinningPropsJson = ($pinningProperties ?? collect())->map(function($p) {
                                return ['id' => $p->id, 'name' => $p->property_name, 'location' => $p->location, 'builder' => $p->builder];
                            })->values();
                        @endphp
                        <script>
                            (function() {
                                const allProperties = @json($pinningPropsJson);

                                // Autofill handler: called when user picks a project
                                window.onPinProjectChange = function(select) {
                                    const opt = select.options[select.selectedIndex];
                                    const row = document.getElementById('pinAutofillRow');
                                    const nameEl     = document.getElementById('pinAutoName');
                                    const locationEl = document.getElementById('pinAutoLocation');
                                    const builderEl  = document.getElementById('pinAutoBuilder');

                                    if (opt && opt.value) {
                                        nameEl.value     = opt.dataset.name     || '';
                                        locationEl.value = opt.dataset.location || '';
                                        builderEl.value  = opt.dataset.builder  || '';
                                        row.style.display = 'block';
                                    } else {
                                        nameEl.value = locationEl.value = builderEl.value = '';
                                        row.style.display = 'none';
                                    }
                                };

                                window.applyPinFilters = function() {
                                    const nameFilter = document.getElementById('pinFilterName').value;
                                    const locationFilter = document.getElementById('pinFilterLocation').value;
                                    const builderFilter = document.getElementById('pinFilterBuilder').value;
                                    const projectSelect = document.getElementById('pinPropertySelect');

                                    let filtered = allProperties;

                                    if (nameFilter) {
                                        filtered = filtered.filter(p => String(p.id) === nameFilter);
                                    }
                                    if (locationFilter) {
                                        filtered = filtered.filter(p => p.location === locationFilter);
                                    }
                                    if (builderFilter) {
                                        filtered = filtered.filter(p => p.builder === builderFilter);
                                    }

                                    // Rebuild project select
                                    const currentVal = projectSelect.value;
                                    projectSelect.innerHTML = '<option value="">— Choose a project —</option>';

                                    filtered.forEach(p => {
                                        const opt = document.createElement('option');
                                        opt.value = p.id;
                                        opt.dataset.name     = p.name;
                                        opt.dataset.location = p.location;
                                        opt.dataset.builder  = p.builder;
                                        opt.textContent = p.name + ' — ' + p.location + ' (' + p.builder + ')';
                                        if (String(p.id) === currentVal) opt.selected = true;
                                        projectSelect.appendChild(opt);
                                    });

                                    // If name filter selected a specific property, auto-select it and autofill
                                    if (nameFilter && filtered.length === 1) {
                                        projectSelect.value = filtered[0].id;
                                        onPinProjectChange(projectSelect);
                                    } else {
                                        // reset autofill when filter clears selection
                                        onPinProjectChange(projectSelect);
                                    }

                                    document.getElementById('pinFilteredCount').innerHTML =
                                        'Showing <strong>' + filtered.length + '</strong> of ' + allProperties.length + ' properties';
                                };
                            })();
                        </script>

                    @elseif ($currentPage === 'media-library')
                        <style>
                            .media-lib-wrap {
                                padding: 28px;
                                background: #f8fafc;
                                min-height: 100vh;
                                font-family: 'Inter', sans-serif;
                            }
                            .media-lib-header {
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                                margin-bottom: 24px;
                                flex-wrap: wrap;
                                gap: 12px;
                            }
                            .media-lib-title {
                                font-size: 1.75rem;
                                font-weight: 800;
                                color: #0f172a;
                                margin: 0;
                            }
                            .media-lib-subtitle {
                                font-size: 13px;
                                color: #64748b;
                                margin-top: 4px;
                            }
                            .media-lib-search {
                                display: flex;
                                align-items: center;
                                gap: 8px;
                                background: #fff;
                                border: 1px solid #e2e8f0;
                                border-radius: 10px;
                                padding: 8px 14px;
                                min-width: 260px;
                                box-shadow: 0 1px 4px rgba(15,23,42,0.05);
                            }
                            .media-lib-search input {
                                border: none;
                                outline: none;
                                font-size: 14px;
                                color: #0f172a;
                                background: transparent;
                                width: 100%;
                            }
                            .media-lib-search i { color: #94a3b8; }

                            /* Folder Tabs */
                            .media-lib-tabs {
                                display: flex;
                                gap: 8px;
                                flex-wrap: wrap;
                                margin-bottom: 22px;
                            }
                            .media-lib-tab {
                                padding: 7px 16px;
                                border-radius: 999px;
                                border: 1.5px solid #e2e8f0;
                                background: #fff;
                                color: #475569;
                                font-size: 13px;
                                font-weight: 600;
                                cursor: pointer;
                                transition: all 0.2s;
                            }
                            .media-lib-tab:hover { border-color: #2563eb; color: #2563eb; }
                            .media-lib-tab.active {
                                background: #2563eb;
                                border-color: #2563eb;
                                color: #fff;
                                box-shadow: 0 4px 12px rgba(37,99,235,0.2);
                            }

                            /* Folder Group */
                            .media-folder-group { margin-bottom: 36px; }
                            .media-folder-group.hidden { display: none; }
                            .media-folder-label {
                                display: flex;
                                align-items: center;
                                gap: 10px;
                                font-size: 15px;
                                font-weight: 700;
                                color: #1e293b;
                                margin-bottom: 14px;
                                padding-bottom: 8px;
                                border-bottom: 2px solid #e2e8f0;
                            }
                            .media-folder-count {
                                background: #eff6ff;
                                color: #2563eb;
                                font-size: 11px;
                                font-weight: 700;
                                padding: 3px 8px;
                                border-radius: 999px;
                            }

                            /* Image Grid */
                            .media-img-grid {
                                display: grid;
                                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                                gap: 14px;
                            }
                            .media-img-card {
                                position: relative;
                                border-radius: 12px;
                                overflow: hidden;
                                background: #fff;
                                border: 1px solid #e2e8f0;
                                box-shadow: 0 2px 8px rgba(15,23,42,0.05);
                                cursor: pointer;
                                transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.25s;
                                aspect-ratio: 1;
                            }
                            .media-img-card:hover {
                                transform: translateY(-4px) scale(1.02);
                                box-shadow: 0 12px 28px rgba(15,23,42,0.1);
                                border-color: #2563eb;
                            }
                            .media-img-card img {
                                width: 100%;
                                height: 100%;
                                object-fit: cover;
                                display: block;
                                transition: opacity 0.3s;
                            }
                            .media-img-overlay {
                                position: absolute;
                                inset: 0;
                                background: rgba(15,23,42,0.55);
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                justify-content: center;
                                gap: 8px;
                                opacity: 0;
                                transition: opacity 0.2s;
                                padding: 8px;
                            }
                            .media-img-card:hover .media-img-overlay { opacity: 1; }
                            .media-overlay-btn {
                                background: rgba(255,255,255,0.15);
                                border: 1px solid rgba(255,255,255,0.3);
                                color: #fff;
                                border-radius: 8px;
                                padding: 6px 10px;
                                font-size: 11px;
                                font-weight: 700;
                                cursor: pointer;
                                width: 100%;
                                text-align: center;
                                transition: background 0.2s;
                                backdrop-filter: blur(4px);
                            }
                            .media-overlay-btn:hover { background: rgba(255,255,255,0.3); }
                            .media-img-name {
                                position: absolute;
                                bottom: 0; left: 0; right: 0;
                                background: linear-gradient(transparent, rgba(15,23,42,0.7));
                                color: #fff;
                                font-size: 10px;
                                font-weight: 600;
                                padding: 14px 6px 6px;
                                white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;
                            }

                            /* Lightbox */
                            .media-lightbox {
                                position: fixed;
                                inset: 0;
                                z-index: 9999;
                                background: rgba(0,0,0,0.88);
                                display: none;
                                align-items: center;
                                justify-content: center;
                                backdrop-filter: blur(6px);
                            }
                            .media-lightbox.open { display: flex; }
                            .media-lightbox-inner {
                                position: relative;
                                max-width: 92vw;
                                max-height: 92vh;
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                gap: 14px;
                            }
                            .media-lightbox-inner img {
                                max-width: 80vw;
                                max-height: 78vh;
                                border-radius: 12px;
                                box-shadow: 0 30px 80px rgba(0,0,0,0.5);
                                object-fit: contain;
                            }
                            .media-lightbox-meta {
                                display: flex;
                                gap: 12px;
                                align-items: center;
                                flex-wrap: wrap;
                                justify-content: center;
                            }
                            .media-lightbox-name {
                                color: #fff;
                                font-size: 13px;
                                font-weight: 600;
                                max-width: 400px;
                                white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;
                            }
                            .media-lightbox-size {
                                color: #94a3b8;
                                font-size: 12px;
                            }
                            .media-lightbox-copy {
                                background: #2563eb;
                                color: #fff;
                                border: none;
                                border-radius: 8px;
                                padding: 8px 16px;
                                font-size: 13px;
                                font-weight: 700;
                                cursor: pointer;
                                transition: background 0.2s;
                            }
                            .media-lightbox-copy:hover { background: #1d4ed8; }
                            .media-lightbox-close {
                                position: absolute;
                                top: -14px;
                                right: -14px;
                                width: 36px;
                                height: 36px;
                                background: #fff;
                                border: none;
                                border-radius: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 18px;
                                cursor: pointer;
                                color: #0f172a;
                                box-shadow: 0 4px 14px rgba(0,0,0,0.2);
                            }

                            /* Empty state */
                            .media-empty {
                                text-align: center;
                                padding: 80px 20px;
                                color: #64748b;
                            }
                            .media-empty i { font-size: 48px; margin-bottom: 16px; display: block; color: #cbd5e1; }
                            .media-empty h3 { font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 8px; }

                            /* Toast */
                            .media-toast {
                                position: fixed;
                                bottom: 24px;
                                right: 24px;
                                background: #0f172a;
                                color: #fff;
                                padding: 12px 20px;
                                border-radius: 10px;
                                font-size: 13px;
                                font-weight: 600;
                                z-index: 99999;
                                opacity: 0;
                                transform: translateY(12px);
                                transition: all 0.3s;
                                pointer-events: none;
                            }
                            .media-toast.show { opacity: 1; transform: translateY(0); }

                            @media (max-width: 700px) {
                                .media-img-grid { grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); }
                                .media-lib-header { flex-direction: column; align-items: flex-start; }
                                .media-lib-search { min-width: 100%; }
                            }
                        </style>

                        <div class="media-lib-wrap" id="mediaLibWrap">

                            {{-- Header --}}
                            <div class="media-lib-header">
                                <div>
                                    <h1 class="media-lib-title">
                                        <i class="fas fa-images" style="color:#2563eb; margin-right:8px;"></i>
                                        Media Library
                                    </h1>
                                    <p class="media-lib-subtitle">
                                        {{ collect($mediaFolders)->sum(fn($f) => count($f['images'])) }} images across {{ count($mediaFolders) }} folders
                                    </p>
                                </div>
                                <div class="media-lib-search">
                                    <i class="fas fa-search"></i>
                                    <input type="text" id="mediaSearchInput" placeholder="Search images by name…">
                                </div>
                            </div>

                            @if(empty($mediaFolders))
                                <div class="media-empty">
                                    <i class="fas fa-folder-open"></i>
                                    <h3>No images found</h3>
                                    <p>No image files were found in <code>public/storage/public</code> or <code>storage/app/public</code></p>
                                </div>
                            @else
                                {{-- Folder Tabs --}}
                                <div class="media-lib-tabs" id="mediaFolderTabs">
                                    <button class="media-lib-tab active" data-folder="all">All Images</button>
                                    @foreach($mediaFolders as $folder)
                                        <button class="media-lib-tab" data-folder="{{ $folder['slug'] }}">
                                            {{ $folder['name'] }}
                                            <span style="opacity:0.7; font-weight:500; margin-left:4px;">({{ count($folder['images']) }})</span>
                                        </button>
                                    @endforeach
                                </div>

                                {{-- Image Folders --}}
                                @foreach($mediaFolders as $folder)
                                    <div class="media-folder-group" data-folder-group="{{ $folder['slug'] }}">
                                        <div class="media-folder-label">
                                            <i class="fas fa-folder" style="color:#f59e0b;"></i>
                                            {{ $folder['name'] }}
                                            <span class="media-folder-count">{{ count($folder['images']) }}</span>
                                        </div>
                                        <div class="media-img-grid">
                                            @foreach($folder['images'] as $img)
                                                <div class="media-img-card"
                                                     data-url="{{ $img['url'] }}"
                                                     data-name="{{ $img['filename'] }}"
                                                     data-size="{{ $img['size'] }}"
                                                     data-searchname="{{ strtolower($img['filename']) }}"
                                                     onclick="openMediaLightbox(this)">
                                                    <img src="{{ $img['url'] }}" alt="{{ $img['filename'] }}" loading="lazy">
                                                    <div class="media-img-overlay">
                                                        <span class="media-overlay-btn"><i class="fas fa-expand"></i> Preview</span>
                                                        <span class="media-overlay-btn" onclick="event.stopPropagation(); copyMediaUrl('{{ $img['url'] }}')">
                                                            <i class="fas fa-copy"></i> Copy URL
                                                        </span>
                                                    </div>
                                                    <div class="media-img-name">{{ $img['filename'] }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- Lightbox --}}
                        <div class="media-lightbox" id="mediaLightbox" onclick="closeMediaLightbox(event)">
                            <div class="media-lightbox-inner">
                                <button class="media-lightbox-close" onclick="closeMediaLightbox()">&times;</button>
                                <img src="" id="mediaLightboxImg" alt="Preview">
                                <div class="media-lightbox-meta">
                                    <span class="media-lightbox-name" id="mediaLightboxName"></span>
                                    <span class="media-lightbox-size" id="mediaLightboxSize"></span>
                                    <button class="media-lightbox-copy" onclick="copyMediaUrl(document.getElementById('mediaLightboxImg').src)">
                                        <i class="fas fa-copy"></i> Copy URL
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Toast --}}
                        <div class="media-toast" id="mediaToast">✓ URL copied to clipboard</div>

                        <script>
                        (function() {
                            // Folder tab filter
                            const tabs = document.querySelectorAll('.media-lib-tab');
                            const groups = document.querySelectorAll('.media-folder-group');

                            tabs.forEach(tab => {
                                tab.addEventListener('click', () => {
                                    tabs.forEach(t => t.classList.remove('active'));
                                    tab.classList.add('active');
                                    const folder = tab.dataset.folder;
                                    groups.forEach(g => {
                                        if (folder === 'all' || g.dataset.folderGroup === folder) {
                                            g.classList.remove('hidden');
                                        } else {
                                            g.classList.add('hidden');
                                        }
                                    });
                                    // Reset search
                                    document.getElementById('mediaSearchInput').value = '';
                                    document.querySelectorAll('.media-img-card').forEach(c => c.style.display = '');
                                });
                            });

                            // Search filter
                            document.getElementById('mediaSearchInput')?.addEventListener('input', function() {
                                const term = this.value.trim().toLowerCase();
                                // Show all groups during search
                                groups.forEach(g => g.classList.remove('hidden'));
                                tabs.forEach(t => t.classList.remove('active'));
                                document.querySelector('[data-folder="all"]')?.classList.add('active');

                                document.querySelectorAll('.media-img-card').forEach(card => {
                                    const name = card.dataset.searchname || '';
                                    card.style.display = (term === '' || name.includes(term)) ? '' : 'none';
                                });

                                // Hide empty groups
                                groups.forEach(g => {
                                    const anyVisible = Array.from(g.querySelectorAll('.media-img-card')).some(c => c.style.display !== 'none');
                                    g.classList.toggle('hidden', !anyVisible && term !== '');
                                });
                            });
                        })();

                        // Lightbox
                        function openMediaLightbox(el) {
                            const lb = document.getElementById('mediaLightbox');
                            document.getElementById('mediaLightboxImg').src = el.dataset.url;
                            document.getElementById('mediaLightboxName').textContent = el.dataset.name;
                            document.getElementById('mediaLightboxSize').textContent = el.dataset.size;
                            lb.classList.add('open');
                            document.body.style.overflow = 'hidden';
                        }

                        function closeMediaLightbox(e) {
                            if (!e || e.target === document.getElementById('mediaLightbox') || e.target.classList.contains('media-lightbox-close')) {
                                document.getElementById('mediaLightbox').classList.remove('open');
                                document.body.style.overflow = '';
                            }
                        }

                        document.addEventListener('keydown', e => {
                            if (e.key === 'Escape') closeMediaLightbox();
                        });

                        // Copy URL
                        function copyMediaUrl(url) {
                            navigator.clipboard.writeText(url).then(() => {
                                const toast = document.getElementById('mediaToast');
                                toast.classList.add('show');
                                setTimeout(() => toast.classList.remove('show'), 2500);
                            }).catch(() => {
                                const ta = document.createElement('textarea');
                                ta.value = url;
                                document.body.appendChild(ta);
                                ta.select();
                                document.execCommand('copy');
                                document.body.removeChild(ta);
                            });
                        }
                        </script>

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
                const icon = atob(row.dataset.icon);
                const status = row.dataset.status;
                const serial = row.dataset.serial;

                document.getElementById('amenityModalTitle').textContent = 'Edit Property Amenity';
                document.getElementById('amenityModalSubtitle').textContent = 'Update the details for this amenity.';
                
                document.getElementById('amenityName').value = name;
                document.getElementById('amenityIconCode').value = icon;
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
                form.action = `${baseUrl}/dashboard/categories/${id}`;
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
                form.action = `${baseUrl}/dashboard/countries/${id}`;
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
                form.action = `${baseUrl}/dashboard/states/${id}`;
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
                form.action = `${baseUrl}/dashboard/cities/${id}`;
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
                const img = row.dataset.image || row.querySelector('img')?.src || '';
                const updateUrl = row.dataset.updateUrl || '';

                document.getElementById('propertyPlaceModalTitle').textContent = 'Edit Property Place';
                document.getElementById('propertyPlaceName').value = name;
                document.getElementById('propertyPlaceCountry').value = country;
                
                const previewContainer = document.getElementById('propertyPlaceImagePreview');
                const previewImg = previewContainer.querySelector('img');
                if (img && !img.includes('data:image')) {
                    previewImg.src = img;
                    previewContainer.style.display = 'block';
                } else {
                    previewContainer.style.display = 'none';
                }

                syncSelectOptions(document.getElementById('propertyPlaceState'), option => !country || option.dataset.country === country);
                document.getElementById('propertyPlaceState').value = state;
                syncSelectOptions(document.getElementById('propertyPlaceCity'), option => {
                    const countryMatch = !country || option.dataset.country === country;
                    const stateMatch = !state || option.dataset.state === state;
                    return countryMatch && stateMatch;
                });
                document.getElementById('propertyPlaceCity').value = city;

                const form = document.getElementById('addPropertyPlaceForm');
                form.action = updateUrl || form.action;
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

                const previewContainer = document.getElementById('propertyPlaceImagePreview');
                previewContainer.style.display = 'none';
                previewContainer.querySelector('img').src = '';

                syncSelectOptions(document.getElementById('propertyPlaceState'), () => true);
                syncSelectOptions(document.getElementById('propertyPlaceCity'), () => true);

                const form = document.getElementById('addPropertyPlaceForm');
                const storeUrl = "{{ route('admin.property-places.store') }}";
                form.action = storeUrl;
                document.getElementById('propertyPlaceMethod').value = 'POST';

                document.getElementById('addPropertyPlaceModal').style.display = 'grid';
            }

            function previewPropertyPlaceImage(input) {
                const previewContainer = document.getElementById('propertyPlaceImagePreview');
                const previewImg = previewContainer.querySelector('img');
                
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewContainer.style.display = 'block';
                    }
                    reader.readAsDataURL(input.files[0]);
                }
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
                        const matchTitle = titleTerm === '' || rowTitle.includes(titleTerm) || (row.dataset.area && row.dataset.area.includes(titleTerm));
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
            const propertyLatitude = document.getElementById('propertyLatitude');
            const propertyLongitude = document.getElementById('propertyLongitude');
            const propertyMapSelected = document.getElementById('propertyMapSelected');
            const propertyMapPreview = document.getElementById('propertyMapPreview');
            const propertyAmenitySearch = document.getElementById('propertyAmenitySearch');
            const amenityOptions = document.querySelectorAll('.amenity-option');
            const propertyMapPickerModal = document.getElementById('propertyMapPickerModal');
            const mapPickerCloseBtn = document.getElementById('mapPickerCloseBtn');
            const mapPickerCancelBtn = document.getElementById('mapPickerCancelBtn');
            const mapPickerUseBtn = document.getElementById('mapPickerUseBtn');
            const mapPickerStatus = document.getElementById('mapPickerStatus');
            const propertyGoogleMapCanvas = document.getElementById('propertyGoogleMapCanvas');
            const propertyMapSearchInput = document.getElementById('propertyMapSearchInput');
            const googleMapsApiKey = @json(config('services.google_maps.key'));
            const addPropertyFaqBtn = document.getElementById('addPropertyFaqBtn');
            const propertyFaqList = document.getElementById('propertyFaqList');

            if (propertyCountry && propertyState && propertyCity && propertyLocation && propertyFullAddress) {
                const mapPickerState = {
                    scriptPromise: null,
                    map: null,
                    marker: null,
                    geocoder: null,
                    autocomplete: null,
                    addressAutocomplete: null,
                    lastPicked: null,
                };
                let addressGeocodeTimer = null;
                let lastAutoGeocodedAddress = '';

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
                const normalizeText = (value) => String(value || '').toLowerCase().replace(/\s+/g, ' ').trim();
                const tokenize = (value) => normalizeText(value).replace(/[^a-z0-9 ]/g, ' ').split(' ').filter(Boolean);
                const setSelectByLabel = (select, label, fuzzy = true) => {
                    const wanted = normalizeText(label);
                    if (!wanted) {
                        return false;
                    }

                    const options = Array.from(select.options).filter((_, index) => index > 0);
                    const exactMatch = options.find((option) => normalizeText(option.text) === wanted);
                    if (exactMatch) {
                        select.value = exactMatch.value;
                        return true;
                    }

                    if (!fuzzy) {
                        return false;
                    }

                    const wantedTokens = tokenize(wanted);
                    let best = null;
                    let bestScore = 0;

                    options.forEach((option) => {
                        const optionText = normalizeText(option.text);
                        const optionTokens = tokenize(optionText);
                        let score = 0;

                        if (optionText.includes(wanted) || wanted.includes(optionText)) {
                            score += 3;
                        }
                        wantedTokens.forEach((token) => {
                            if (optionTokens.includes(token)) {
                                score += 2;
                            } else if (optionTokens.some((optToken) => optToken.startsWith(token) || token.startsWith(optToken))) {
                                score += 1;
                            }
                        });

                        const lengthGap = Math.abs(optionText.length - wanted.length);
                        score -= Math.min(lengthGap, 20) * 0.03;

                        if (score > bestScore) {
                            bestScore = score;
                            best = option;
                        }
                    });

                    if (best && bestScore >= 2) {
                        select.value = best.value;
                        return true;
                    }

                    return false;
                };

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

                const geocodeWithNominatim = async (address) => {
                    const endpoint = `https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&addressdetails=1&q=${encodeURIComponent(address)}`;
                    const response = await fetch(endpoint, {
                        headers: {
                            'Accept': 'application/json',
                        },
                    });
                    if (!response.ok) {
                        throw new Error('Nominatim geocoding failed');
                    }
                    const results = await response.json();
                    return Array.isArray(results) && results[0] ? results[0] : null;
                };

                const applyNominatimToAddressFields = (result) => {
                    if (!result) {
                        return;
                    }

                    const address = result.address || {};
                    const countryName = address.country || '';
                    const stateName = address.state || address.state_district || '';
                    const cityName = address.city || address.town || address.village || address.county || '';
                    const pinCode = address.postcode || '';
                    const locationName = address.suburb || address.neighbourhood || address.quarter || address.road || '';

                    if (countryName && setSelectByLabel(propertyCountry, countryName)) {
                        syncStates();
                    }
                    if (stateName && setSelectByLabel(propertyState, stateName)) {
                        syncCities();
                    }
                    if (cityName && setSelectByLabel(propertyCity, cityName)) {
                        syncLocations();
                    }
                    if (locationName) {
                        setSelectByLabel(propertyLocation, locationName);
                    }
                    if (pinCode) {
                        propertyPincode.value = pinCode;
                    }

                    if (result.display_name) {
                        propertyFullAddress.value = result.display_name;
                    }

                    updateAddress();
                };

                const applyAddressToFields = async (forceGeocode = false) => {
                    const raw = propertyFullAddress.value.trim();
                    if (!raw) return;

                    const shouldGeocode = forceGeocode || (raw.length >= 8 && normalizeText(raw) !== lastAutoGeocodedAddress);

                    // Use Google Maps Geocoder to auto-fill latitude, longitude and other fields from manual input
                    if (shouldGeocode && googleMapsApiKey) {
                        try {
                            const mapsApi = await loadGoogleMapsApi();
                            if (!mapPickerState.geocoder) mapPickerState.geocoder = new mapsApi.Geocoder();

                            mapPickerState.geocoder.geocode({ address: raw }, async (results, status) => {
                                if (status === 'OK' && results && results[0]) {
                                    const result = results[0];
                                    if (result.geometry?.location) {
                                        propertyLatitude.value = Number(result.geometry.location.lat()).toFixed(6);
                                        propertyLongitude.value = Number(result.geometry.location.lng()).toFixed(6);
                                        updateMapPreview();
                                    }
                                    applyGeocodeToAddressFields(result);
                                    lastAutoGeocodedAddress = normalizeText(raw);
                                    return;
                                }

                                try {
                                    const fallbackResult = await geocodeWithNominatim(raw);
                                    if (fallbackResult) {
                                        propertyLatitude.value = Number(fallbackResult.lat).toFixed(6);
                                        propertyLongitude.value = Number(fallbackResult.lon).toFixed(6);
                                        updateMapPreview();
                                        applyNominatimToAddressFields(fallbackResult);
                                        lastAutoGeocodedAddress = normalizeText(raw);
                                    }
                                } catch (_) {
                                    // Keep manual parsing below if fallback geocoding also fails.
                                }
                            });
                            return; // Geocoder handles the field population
                        } catch (e) { console.error('Geocoding failed:', e); }
                    }

                    if (shouldGeocode) {
                        try {
                            const fallbackResult = await geocodeWithNominatim(raw);
                            if (fallbackResult) {
                                propertyLatitude.value = Number(fallbackResult.lat).toFixed(6);
                                propertyLongitude.value = Number(fallbackResult.lon).toFixed(6);
                                updateMapPreview();
                                applyNominatimToAddressFields(fallbackResult);
                                lastAutoGeocodedAddress = normalizeText(raw);
                                return;
                            }
                        } catch (_) {
                            // Fall through to manual parsing if network geocoding is unavailable.
                        }
                    }

                    // Manual parsing fallback if Google Maps is unavailable
                    const parts = raw.split(',').map((part) => part.trim()).filter(Boolean);
                    if (parts.length < 4) return;

                    const pincodePart = parts[parts.length - 1] || '';
                    const countryPart = parts[parts.length - 2] || '';
                    const statePart = parts[parts.length - 3] || '';
                    const cityPart = parts[parts.length - 4] || '';
                    const locationPart = parts.slice(0, parts.length - 4).join(', ');

                    if (/^\d{4,10}$/.test(pincodePart) && propertyPincode) {
                        propertyPincode.value = pincodePart;
                    }

                    if (setSelectByLabel(propertyCountry, countryPart)) {
                        syncStates();
                    }

                    if (setSelectByLabel(propertyState, statePart)) {
                        syncCities();
                    }

                    if (setSelectByLabel(propertyCity, cityPart)) {
                        syncLocations();
                    }

                    setSelectByLabel(propertyLocation, locationPart);
                    updateAddress();
                };

                const loadGoogleMapsApi = () => {
                    if (window.google?.maps) {
                        return Promise.resolve(window.google.maps);
                    }
                    if (!googleMapsApiKey) {
                        return Promise.reject(new Error('Google Maps API key missing'));
                    }
                    if (mapPickerState.scriptPromise) {
                        return mapPickerState.scriptPromise;
                    }
                    mapPickerState.scriptPromise = new Promise((resolve, reject) => {
                        const script = document.createElement('script');
                        script.src = `https://maps.googleapis.com/maps/api/js?key=${encodeURIComponent(googleMapsApiKey)}&libraries=places`;
                        script.async = true;
                        script.defer = true;
                        script.onload = () => resolve(window.google.maps);
                        script.onerror = () => reject(new Error('Failed to load Google Maps'));
                        document.head.appendChild(script);
                    });
                    return mapPickerState.scriptPromise;
                };

                const setupAddressAutocomplete = async () => {
                    if (!googleMapsApiKey || !propertyFullAddress || mapPickerState.addressAutocomplete) {
                        return;
                    }
                    try {
                        const mapsApi = await loadGoogleMapsApi();
                        mapPickerState.addressAutocomplete = new mapsApi.places.Autocomplete(propertyFullAddress, {
                            types: [], // Empty types allows all place results (Geocodes & Establishments)
                        });

                        mapPickerState.addressAutocomplete.addListener('place_changed', () => {
                            const place = mapPickerState.addressAutocomplete.getPlace();
                            if (!place) {
                                return;
                            }

                            if (place.formatted_address) {
                                propertyFullAddress.value = place.formatted_address;
                            }

                            if (place.geometry?.location) {
                                propertyLatitude.value = Number(place.geometry.location.lat()).toFixed(6);
                                propertyLongitude.value = Number(place.geometry.location.lng()).toFixed(6);
                                updateMapPreview();
                            }

                            if (place.address_components?.length) {
                                applyGeocodeToAddressFields({
                                    address_components: place.address_components,
                                    formatted_address: place.formatted_address || propertyFullAddress.value,
                                });
                            } else {
                                applyAddressToFields();
                            }
                        });
                    } catch (_) {
                        // Keep manual address parsing behavior if Maps script is unavailable.
                    }
                };

                const applyGeocodeToAddressFields = (result) => {
                    if (!result) {
                        return;
                    }

                    const findComp = (type) => result.address_components?.find((comp) => (comp.types || []).includes(type));
                    const countryName = findComp('country')?.long_name || '';
                    const stateName = findComp('administrative_area_level_1')?.long_name || '';
                    const cityName = findComp('locality')?.long_name || findComp('administrative_area_level_2')?.long_name || '';
                    const pinCode = findComp('postal_code')?.long_name || '';
                    const locationName =
                        findComp('sublocality_level_1')?.long_name ||
                        findComp('sublocality')?.long_name ||
                        findComp('neighborhood')?.long_name ||
                        findComp('route')?.long_name ||
                        '';

                    if (countryName && setSelectByLabel(propertyCountry, countryName)) {
                        syncStates();
                    }
                    if (stateName && setSelectByLabel(propertyState, stateName)) {
                        syncCities();
                    }
                    if (cityName && setSelectByLabel(propertyCity, cityName)) {
                        syncLocations();
                    }
                    if (locationName) {
                        setSelectByLabel(propertyLocation, locationName);
                    }
                    if (pinCode) {
                        propertyPincode.value = pinCode;
                    }

                    const normalizedParts = [locationName, cityName, stateName, countryName, pinCode].filter(Boolean);
                    if (normalizedParts.length >= 4) {
                        propertyFullAddress.value = normalizedParts.join(', ');
                    } else if (result.formatted_address) {
                        propertyFullAddress.value = result.formatted_address;
                    }

                    updateAddress();
                };

                const setMapMarkerAndCoords = (latLng, mapsApi, doGeocode = true) => {
                    if (!latLng || !mapsApi || !mapPickerState.map) {
                        return;
                    }

                    if (!mapPickerState.marker) {
                        mapPickerState.marker = new mapsApi.Marker({
                            position: latLng,
                            map: mapPickerState.map,
                        });
                    } else {
                        mapPickerState.marker.setPosition(latLng);
                    }

                    mapPickerState.map.panTo(latLng);
                    propertyLatitude.value = Number(latLng.lat()).toFixed(6);
                    propertyLongitude.value = Number(latLng.lng()).toFixed(6);
                    mapPickerState.lastPicked = { lat: latLng.lat(), lng: latLng.lng() };
                    updateMapPreview();
                    if (mapPickerStatus) {
                        mapPickerStatus.textContent = `Selected: ${propertyLatitude.value}, ${propertyLongitude.value}`;
                    }

                    if (doGeocode && mapPickerState.geocoder) {
                        mapPickerState.geocoder.geocode({ location: latLng }, (results, status) => {
                            if (status === 'OK' && results && results.length) {
                                applyGeocodeToAddressFields(results[0]);
                            }
                        });
                    }
                };

                const closeMapPicker = () => {
                    if (!propertyMapPickerModal) {
                        return;
                    }
                    propertyMapPickerModal.classList.remove('is-open');
                    propertyMapPickerModal.setAttribute('aria-hidden', 'true');
                };

                const openMapPicker = async () => {
                    if (!propertyMapPickerModal || !propertyGoogleMapCanvas) {
                        return;
                    }

                    try {
                        const mapsApi = await loadGoogleMapsApi();
                        propertyMapPickerModal.classList.add('is-open');
                        propertyMapPickerModal.setAttribute('aria-hidden', 'false');

                        const hasCoords = propertyLatitude.value.trim() && propertyLongitude.value.trim();
                        const initialLat = hasCoords ? Number(propertyLatitude.value) : 20.5937;
                        const initialLng = hasCoords ? Number(propertyLongitude.value) : 78.9629;
                        const initialLatLng = new mapsApi.LatLng(initialLat, initialLng);

                        if (!mapPickerState.map) {
                            mapPickerState.map = new mapsApi.Map(propertyGoogleMapCanvas, {
                                center: initialLatLng,
                                zoom: hasCoords ? 15 : 5,
                                mapTypeControl: false,
                                streetViewControl: false,
                            });

                            mapPickerState.geocoder = new mapsApi.Geocoder();

                            mapPickerState.map.addListener('click', (event) => {
                                if (event.latLng) {
                                    setMapMarkerAndCoords(event.latLng, mapsApi, true);
                                }
                            });

                            if (propertyMapSearchInput) {
                                mapPickerState.autocomplete = new mapsApi.places.Autocomplete(propertyMapSearchInput);
                                mapPickerState.autocomplete.bindTo('bounds', mapPickerState.map);
                                mapPickerState.autocomplete.addListener('place_changed', () => {
                                    const place = mapPickerState.autocomplete.getPlace();
                                    if (!place.geometry) {
                                        return;
                                    }
                                    const latLng = place.geometry.location;
                                    mapPickerState.map.setCenter(latLng);
                                    mapPickerState.map.setZoom(16);
                                    setMapMarkerAndCoords(latLng, mapsApi, false);
                                    if (place.formatted_address) {
                                        propertyFullAddress.value = place.formatted_address;
                                        applyAddressToFields();
                                    }
                                });
                            }
                        } else {
                            mapsApi.event.trigger(mapPickerState.map, 'resize');
                            mapPickerState.map.setCenter(initialLatLng);
                            mapPickerState.map.setZoom(hasCoords ? 15 : 5);
                        }

                        setMapMarkerAndCoords(initialLatLng, mapsApi, false);
                    } catch (error) {
                        if (propertyMapPreview?.href && propertyMapPreview.href !== '#') {
                            window.open(propertyMapPreview.href, '_blank');
                        } else {
                            alert('Google Maps is not configured. Add GOOGLE_MAPS_API_KEY in .env to enable map picker.');
                        }
                    }
                };

                const updateMapPreview = () => {
                    if (!propertyLatitude || !propertyLongitude || !propertyMapPreview || !propertyMapSelected) {
                        return;
                    }

                    const lat = propertyLatitude.value.trim();
                    const lng = propertyLongitude.value.trim();
                    if (lat && lng) {
                        const url = `https://www.google.com/maps?q=${encodeURIComponent(`${lat},${lng}`)}`;
                        propertyMapPreview.href = url;
                        propertyMapSelected.value = url;
                    } else {
                        propertyMapPreview.href = '#';
                        propertyMapSelected.value = '';
                    }
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
                propertyFullAddress.addEventListener('input', () => {
                    if (addressGeocodeTimer) {
                        clearTimeout(addressGeocodeTimer);
                    }
                    addressGeocodeTimer = setTimeout(() => {
                        applyAddressToFields();
                    }, 800);
                });
                propertyFullAddress.addEventListener('change', () => applyAddressToFields(true));
                propertyFullAddress.addEventListener('blur', () => applyAddressToFields(true));
                propertyFullAddress.addEventListener('focus', setupAddressAutocomplete, { once: true });
                propertyPincode?.addEventListener('input', updateAddress);
                propertyLatitude?.addEventListener('input', updateMapPreview);
                propertyLongitude?.addEventListener('input', updateMapPreview);
                propertyMapPreview?.addEventListener('click', (event) => {
                    if (googleMapsApiKey) {
                        event.preventDefault();
                        openMapPicker();
                    } else if (propertyMapPreview.getAttribute('href') === '#') {
                        event.preventDefault();
                    }
                });
                mapPickerCloseBtn?.addEventListener('click', closeMapPicker);
                mapPickerCancelBtn?.addEventListener('click', closeMapPicker);
                mapPickerUseBtn?.addEventListener('click', () => {
                    closeMapPicker();
                });
                propertyMapPickerModal?.addEventListener('click', (event) => {
                    if (event.target === propertyMapPickerModal) {
                        closeMapPicker();
                    }
                });

                syncStates();
                updateMapPreview();
                applyAddressToFields();
            }

            // Multi-select logic
            const updateSelectedDisplay = (selectEl, displayId) => {
                const display = document.getElementById(displayId);
                if (!display) return;
                display.innerHTML = '';
                Array.from(selectEl.selectedOptions).forEach(opt => {
                    const chip = document.createElement('span');
                    chip.className = 'choice-chip';
                    chip.innerHTML = `${opt.text} <span class="remove-chip" title="Remove">&times;</span>`;
                    chip.querySelector('.remove-chip').onclick = (e) => {
                        e.stopPropagation();
                        opt.selected = false;
                        selectEl.dispatchEvent(new Event('change', { bubbles: true }));
                    };
                    display.appendChild(chip);
                });
            };

            document.querySelectorAll('.multi-select-wrapper').forEach(wrapper => {
                const searchInput = wrapper.querySelector('input[type="text"]');
                const select = wrapper.querySelector('select.multi-select-toggle');
                const displayId = select.id === 'propertyTopPicks' ? 'selectedTopPicksDisplay' : 
                                 (select.id === 'propertyBhk' ? 'selectedBhkDisplay' : 'selectedAmenitiesDisplay');

                updateSelectedDisplay(select, displayId);

                searchInput.addEventListener('focus', () => wrapper.classList.add('is-open'));
                document.addEventListener('click', (e) => {
                    if (!wrapper.contains(e.target)) wrapper.classList.remove('is-open');
                });

                select.addEventListener('change', () => updateSelectedDisplay(select, displayId));
                select.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    const option = e.target;
                    if (option.tagName === 'OPTION') {
                        option.selected = !option.selected;
                        this.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                });

                // Search functionality inside dropdown
                searchInput.addEventListener('input', () => {
                    wrapper.classList.add('is-open');
                    const term = searchInput.value.trim().toLowerCase();
                    Array.from(select.options).forEach(opt => {
                        const name = (opt.dataset.name || opt.dataset.amenity || opt.text).toLowerCase();
                        opt.hidden = term !== '' && !name.includes(term);
                    });
                });
            });

            if (propertyAmenitySearch && amenityOptions.length > 0) {
                propertyAmenitySearch.addEventListener('input', () => {
                    const term = propertyAmenitySearch.value.trim().toLowerCase();
                    amenityOptions.forEach((option) => {
                        const label = option.dataset.amenity || '';
                        option.hidden = !!term && !label.includes(term);
                    });
                });
            }

            // Functionality for Image Previews
            const displayImageInput = document.getElementById('propertyDisplayImage');
            const displayImageUploadArea = document.getElementById('displayImageUploadArea');
            const displayPlaceholderHTML = `
                <span class="upload-plus">+</span>
                <span class="upload-title">Upload primary display image</span><br>
                <span class="upload-meta">PNG, JPG up to 2 MB · This appears on listings</span>
            `;

            const bindRemoveDisplayEvent = (btnId) => {
                const btn = document.getElementById(btnId);
                if (btn) {
                    btn.addEventListener('click', function(ev) {
                        ev.preventDefault();
                        ev.stopPropagation();
                        displayImageInput.value = '';
                        displayImageUploadArea.innerHTML = displayPlaceholderHTML;
                    });
                }
            };

            // Handle existing image removal in Edit mode
            bindRemoveDisplayEvent('removeExistingDisplayImage');

            if (displayImageInput && displayImageUploadArea) {
                displayImageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            displayImageUploadArea.innerHTML = `
                                <div style="position: relative; display: inline-block;">
                                    <img src="${event.target.result}" style="max-width:100%; max-height:140px; border-radius:4px; object-fit:cover;">
                                    <div class="remove-img-btn" id="removeSelectedDisplayImage">&times;</div>
                                </div>
                            `;
                            bindRemoveDisplayEvent('removeSelectedDisplayImage');
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            let galleryFileList = [];
            const galleryImagesInput = document.getElementById('propertyGalleryImages');
            const galleryPreviewContainer = document.getElementById('galleryPreviewContainer');

            const syncGalleryInput = () => {
                const dt = new DataTransfer();
                galleryFileList.forEach(file => dt.items.add(file));
                galleryImagesInput.files = dt.files;
            };

            const renderGallery = () => {
                const addCard = galleryPreviewContainer.querySelector('.gallery-add-card');
                galleryPreviewContainer.querySelectorAll('.gallery-preview-card').forEach(el => el.remove());

                galleryFileList.forEach((file, index) => {
                    const card = document.createElement('div');
                    card.className = 'gallery-preview-card';
                    card.style.animationDelay = `${index * 0.05}s`;
                    
                    const removeBtn = document.createElement('div');
                    removeBtn.className = 'remove-gallery-img';
                    removeBtn.innerHTML = '&times;';
                    removeBtn.onclick = (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        galleryFileList.splice(index, 1);
                        renderGallery();
                        syncGalleryInput();
                    };
                    card.appendChild(removeBtn);

                    const reader = new FileReader();
                    reader.onload = (event) => {
                        card.style.backgroundImage = `url(${event.target.result})`;
                        card.style.backgroundSize = 'cover';
                        card.style.backgroundPosition = 'center';
                    };
                    reader.readAsDataURL(file);
                    galleryPreviewContainer.insertBefore(card, addCard);
                });
            };

            if (galleryImagesInput && galleryPreviewContainer) {
                galleryImagesInput.addEventListener('change', function(e) {
                    galleryFileList = [...galleryFileList, ...Array.from(e.target.files)];
                    renderGallery();
                    syncGalleryInput();
                });
            }

            // Floor Plan Functionality
            let floorPlanFileList = [];
            const floorPlanImagesInput = document.getElementById('propertyFloorPlanImages');
            const floorPlanPreviewContainer = document.getElementById('floorPlanPreviewContainer');

            const syncFloorPlanInput = () => {
                const dt = new DataTransfer();
                floorPlanFileList.forEach(file => dt.items.add(file));
                floorPlanImagesInput.files = dt.files;
            };

            const renderFloorPlanGallery = () => {
                const addCard = floorPlanPreviewContainer.querySelector('.gallery-add-card');
                floorPlanPreviewContainer.querySelectorAll('.gallery-preview-card').forEach(el => el.remove());

                floorPlanFileList.forEach((file, index) => {
                    const card = document.createElement('div');
                    card.className = 'gallery-preview-card';
                    card.style.animationDelay = `${index * 0.05}s`;
                    
                    const removeBtn = document.createElement('div');
                    removeBtn.className = 'remove-gallery-img';
                    removeBtn.innerHTML = '&times;';
                    removeBtn.onclick = (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        floorPlanFileList.splice(index, 1);
                        renderFloorPlanGallery();
                        syncFloorPlanInput();
                    };
                    card.appendChild(removeBtn);

                    const reader = new FileReader();
                    reader.onload = (event) => {
                        card.style.backgroundImage = `url(${event.target.result})`;
                        card.style.backgroundSize = 'cover';
                        card.style.backgroundPosition = 'center';
                    };
                    reader.readAsDataURL(file);
                    floorPlanPreviewContainer.insertBefore(card, addCard);
                });
            };

            if (floorPlanImagesInput && floorPlanPreviewContainer) {
                floorPlanImagesInput.addEventListener('change', function(e) {
                    floorPlanFileList = [...floorPlanFileList, ...Array.from(e.target.files)];
                    renderFloorPlanGallery();
                    syncFloorPlanInput();
                });
            }

            // Brochure Upload Functionality
            const brochureInput = document.getElementById('propertyBrochure');
            const brochureUploadArea = document.getElementById('brochureUploadArea');
            const brochurePlaceholderHTML = `
                <span class="upload-title">Upload property brochure (Mandatory)</span>
                <span class="upload-meta">PDF format required up to 10MB</span>
            `;

            const bindRemoveBrochureEvent = (btnId) => {
                const btn = document.getElementById(btnId);
                if (btn) {
                    btn.addEventListener('click', function(ev) {
                        ev.preventDefault();
                        ev.stopPropagation();
                        brochureInput.value = '';
                        brochureUploadArea.innerHTML = brochurePlaceholderHTML;
                    });
                }
            };

            bindRemoveBrochureEvent('removeExistingBrochure');

            if (brochureInput && brochureUploadArea) {
                brochureInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        brochureUploadArea.innerHTML = `
                            <div style="position: relative; display: inline-flex; align-items: center; gap: 10px; padding: 12px 20px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); animation: imagePopIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) both;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                <span style="font-size: 13px; font-weight: 600; color: #334155;">${file.name}</span>
                                <div class="remove-img-btn" id="removeSelectedBrochure">&times;</div>
                            </div>
                        `;
                        bindRemoveBrochureEvent('removeSelectedBrochure');
                    }
                });
            }

            if (addPropertyFaqBtn && propertyFaqList) {
                let faqCount = propertyFaqList.querySelectorAll('.faq-item').length;

                const bindFaqRemove = (button) => {
                    button.addEventListener('click', () => {
                        button.closest('.faq-item')?.remove();
                        faqCount = propertyFaqList.querySelectorAll('.faq-item').length;
                    });
                };

                propertyFaqList.querySelectorAll('.faq-remove-btn').forEach(bindFaqRemove);

                addPropertyFaqBtn.addEventListener('click', () => {
                    if (faqCount >= 25) {
                        return;
                    }

                    const index = propertyFaqList.querySelectorAll('.faq-item').length;
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
                    faqCount = propertyFaqList.querySelectorAll('.faq-item').length;
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
                document.getElementById('userModalForm').action = "{{ route('admin.users.store') }}";
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
                    
                    // Mapping for Enquiries
                    selectEl.classList.toggle('status-received', selectEl.value === 'received');
                    selectEl.classList.toggle('status-closed', selectEl.value === 'closed');
                }
            }

            document.querySelectorAll('.status-select').forEach(select => {
                updateStatusClass(select);
                select.addEventListener('change', () => updateStatusClass(select));
            });

            // Property Enquiries Interactions
            const enquirySearch = document.getElementById('enquirySearch');
            const enquiryEntries = document.getElementById('enquiryEntries');
            const enquiryPageInfo = document.getElementById('enquiryPageInfo');
            const enquiryPagination = document.getElementById('enquiryPagination');

            if (enquirySearch && enquiryEntries) {
                let currentPage = 1;
                let filteredRows = [];

                const filterEnquiries = () => {
                    const term = enquirySearch.value.toLowerCase();
                    const availableRows = Array.from(document.querySelectorAll('.enquiry-row'));

                    filteredRows = availableRows.filter(row => {
                        const name = row.dataset.name ? row.dataset.name.toLowerCase() : '';
                        const email = row.dataset.email ? row.dataset.email.toLowerCase() : '';
                        const property = row.dataset.property ? row.dataset.property.toLowerCase() : '';
                        return name.includes(term) || email.includes(term) || property.includes(term);
                    });

                    availableRows.forEach(row => row.style.display = 'none');

                    const limit = parseInt(enquiryEntries.value, 10);
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
                    if (enquiryPageInfo) {
                        enquiryPageInfo.textContent = `Showing ${start} to ${end} of ${filteredRows.length} entries`;
                    }

                    if (enquiryPagination) {
                        enquiryPagination.innerHTML = '';
                        const renderBtn = (label, disabled, active, page) => {
                            const btn = document.createElement('button');
                            btn.textContent = label;
                            btn.disabled = disabled;
                            btn.className = active ? 'page-btn active' : 'page-btn';
                            if (!disabled && !active) {
                                btn.onclick = () => { currentPage = page; filterEnquiries(); };
                            }
                            return btn;
                        };

                        enquiryPagination.appendChild(renderBtn('Previous', currentPage === 1, false, currentPage - 1));
                        for (let p = 1; p <= totalPages; p++) {
                            if (p === 1 || p === totalPages || (p >= currentPage - 1 && p <= currentPage + 1)) {
                                enquiryPagination.appendChild(renderBtn(p, false, p === currentPage, p));
                            } else if (p === currentPage - 2 || p === currentPage + 2) {
                                const dots = document.createElement('span');
                                dots.style.padding = '0 8px';
                                dots.textContent = '...';
                                enquiryPagination.appendChild(dots);
                            }
                        }
                        enquiryPagination.appendChild(renderBtn('Next', currentPage === totalPages, false, currentPage + 1));
                    }
                };

                enquirySearch.addEventListener('input', () => { currentPage = 1; filterEnquiries(); });
                enquiryEntries.addEventListener('change', () => { currentPage = 1; filterEnquiries(); });

                filterEnquiries();
            }

            function viewEnquiryDetails(enquiry) {
                const modal = document.getElementById('enquiryModal');
                const content = document.getElementById('enquiryModalContent');
                
                content.innerHTML = `
                    <div style="display: grid; gap: 16px;">
                        <div>
                            <label style="font-weight: 700; font-size: 0.75rem; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Enquirer Name</label>
                            <div style="font-weight: 600; font-size: 1rem;">${enquiry.name}</div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div>
                                <label style="font-weight: 700; font-size: 0.75rem; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Email</label>
                                <div style="font-size: 0.9rem;">${enquiry.email}</div>
                            </div>
                            <div>
                                <label style="font-weight: 700; font-size: 0.75rem; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Phone</label>
                                <div style="font-size: 0.9rem;">${enquiry.mobile}</div>
                            </div>
                        </div>
                        <div>
                            <label style="font-weight: 700; font-size: 0.75rem; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Interested In Property</label>
                            <div style="font-weight: 600; color: #1d97db;">${enquiry.property_name || 'N/A'}</div>
                        </div>
                        <div>
                            <label style="font-weight: 700; font-size: 0.75rem; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Message</label>
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; font-size: 0.9rem; line-height: 1.5; min-height: 100px;">
                                ${enquiry.message || '<span style="color: #94a3b8; font-style: italic;">No message provided</span>'}
                            </div>
                        </div>
                        <div>
                            <label style="font-weight: 700; font-size: 0.75rem; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Enquiry Date</label>
                            <div style="font-size: 0.85rem;">${new Date(enquiry.created_at).toLocaleString()}</div>
                        </div>
                    </div>
                `;
                
                modal.style.display = 'grid';
            }

            function closeEnquiryModal() {
                document.getElementById('enquiryModal').style.display = 'none';
            }
        </script>
    </body>
</html>

    
