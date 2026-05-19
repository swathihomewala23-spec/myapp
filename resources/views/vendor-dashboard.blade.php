<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard - Homewala</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito-sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap');

            :root {
                --primary: #1877F2; 
                --primary-hover: #166fe5;
                --purple: #7562D8;
                --bg-color: #f8fafc;    
                --sidebar-bg: #ffffff;
                --text-dark: #0f172a;
                --text-gray: #64748b;
                --border-color: #e2e8f0;
                --shadow-sm: 0 2px 8px rgba(15, 23, 42, 0.04);
                --shadow-md: 0 10px 25px rgba(15, 23, 42, 0.05);
                --shadow-lg: 0 20px 40px rgba(15, 23, 42, 0.08);
                --radius-lg: 16px;
                --radius-md: 12px;
                --transition-ease: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
            }

            body {
                background-color: var(--bg-color);
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                color: var(--text-dark);
                overflow-x: hidden;
            }

            /* --- Animations --- */
            @keyframes slideInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            @keyframes pulseGlow {
                0%, 100% { transform: scale(1); opacity: 0.9; }
                50% { transform: scale(1.05); opacity: 1; }
            }

            @keyframes badgePulse {
                0% { box-shadow: 0 0 0 0 rgba(24, 119, 242, 0.4); }
                70% { box-shadow: 0 0 0 6px rgba(24, 119, 242, 0); }
                100% { box-shadow: 0 0 0 0 rgba(24, 119, 242, 0); }
            }

            .animated-fade-in {
                animation: fadeIn 0.6s ease-out;
            }

            .animated-slide-up {
                animation: slideInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
            }

            /* Top Navbar */
            .top-navbar {
                height: 70px;
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border-bottom: 1px solid var(--border-color);
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding-right: 30px;
                position: sticky;
                top: 0;
                z-index: 100;
                box-shadow: 0 2px 10px rgba(15, 23, 42, 0.02);
            }

            .brand-container {
                width: 260px;
                height: 100%;
                display: flex;
                align-items: center;
                padding: 0 24px;
                border-right: 1px solid var(--border-color);
                flex-shrink: 0;
            }

            .navbar-left {
                display: flex;
                align-items: center;
                gap: 20px;
                height: 100%;
            }

            .navbar-logo {
                height: 24px;
                object-fit: contain;
                transition: transform 0.3s ease;
            }

            .navbar-logo:hover {
                transform: scale(1.04);
            }
            
            .hamburger-menu {
                font-size: 20px;
                color: var(--text-gray);
                cursor: pointer;
                width: 36px;
                height: 36px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: var(--transition-ease);
            }

            .hamburger-menu:hover {
                background: #f1f5f9;
                color: var(--primary);
            }

            .navbar-right {
                display: flex;
                align-items: center;
            }

            .vendor-avatar {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background-color: #e2e8f0;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                border: 2px solid #fff;
                box-shadow: 0 4px 10px rgba(15, 23, 42, 0.08);
                transition: var(--transition-ease);
            }

            .vendor-avatar:hover {
                transform: scale(1.06);
                box-shadow: 0 6px 14px rgba(24, 119, 242, 0.2);
            }

            .vendor-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            /* Layout */
            .layout-container {
                display: flex;
                flex: 1;
                min-width: 0;
            }

            /* Sidebar */
            .sidebar { 
                width: 260px; 
                background: #ffffff;  
                border-right: 1px solid var(--border-color); 
                transition: var(--transition-ease); 
                height: calc(100vh - 70px); 
                position: sticky;
                top: 70px;
                overflow-y: auto; 
                flex-shrink: 0;
                box-shadow: 2px 0 10px rgba(15, 23, 42, 0.01);
            }
            .sidebar.collapsed { width: 0; overflow: hidden; border: none; }
            
            .nav-menu { padding: 24px 16px; display: grid; gap: 6px; }
            .nav-group { display: grid; gap: 4px; }
            
            .nav-item, .nav-link { 
                display: flex; 
                align-items: center; 
                padding: 12px 16px; 
                color: #475569; 
                text-decoration: none; 
                transition: var(--transition-ease); 
                font-weight: 600; 
                font-size: 14px;
                border-radius: var(--radius-md);
                cursor: pointer; 
                border: none;
                background: transparent;
                width: 100%;
                text-align: left;
                position: relative;
            }

            .nav-icon {
                margin-right: 12px;
                font-size: 16px;
                color: #64748b;
                transition: transform 0.3s ease, color 0.3s ease;
            }

            .nav-text {
                transition: transform 0.3s ease;
            }
            
            .nav-item:hover, .nav-link:hover { 
                background: #f1f5f9; 
                color: var(--primary); 
                transform: translateX(6px);
            }

            .nav-item:hover .nav-icon, .nav-link:hover .nav-icon {
                transform: scale(1.1) rotate(4deg);
                color: var(--primary);
            }

            .nav-item.active, .nav-link.active { 
                background: linear-gradient(135deg, #1877F2, #166fe5); 
                color: #ffffff; 
                box-shadow: 0 4px 15px rgba(24, 119, 242, 0.25); 
                transform: translateX(6px);
            }

            .nav-item.active .nav-icon, .nav-link.active .nav-icon {
                color: #ffffff;
                transform: scale(1.1);
            }
            
            /* Dropdown Style (details/summary) */
            .nav-toggle { 
                list-style: none; 
                position: relative; 
                padding-right: 40px; 
            }
            .nav-toggle::-webkit-details-marker { display: none; }
            .nav-toggle::after {
                content: "";
                position: absolute;
                right: 18px;
                top: 50%;
                width: 6px;
                height: 6px;
                border-right: 2px solid #64748b;
                border-bottom: 2px solid #64748b;
                transform: translateY(-65%) rotate(45deg);
                transition: var(--transition-ease);
            }
            details[open] > .nav-toggle,
            .nav-toggle.group-active { 
                background: rgba(24, 119, 242, 0.06); 
                color: var(--primary); 
            }
            
            details[open] > .nav-toggle::after,
            .nav-toggle.group-active::after {
                border-right-color: var(--primary);
                border-bottom-color: var(--primary);
            }

            details[open] > .nav-toggle::after { transform: translateY(-35%) rotate(225deg); }
            
            .nav-subitem {
                display: flex;
                align-items: center;
                padding: 10px 18px 10px 32px;
                font-size: 13px;
                color: #475569;
                text-decoration: none;
                border-radius: 10px;
                font-weight: 500;
                transition: var(--transition-ease);
                margin: 2px 0;
            }
            .nav-subitem i {
                margin-right: 8px;
                font-size: 12px;
                transition: transform 0.3s ease;
            }
            .nav-subitem:hover { 
                background: #f8fafc; 
                color: var(--primary); 
                transform: translateX(4px);
            }
            .nav-subitem:hover i {
                transform: scale(1.1) translateX(2px);
            }
            .nav-subitem.active { 
                background: #eff6ff; 
                color: var(--primary); 
                font-weight: 600; 
                transform: translateX(4px);
            }

            /* Main Content */
            .main-content {
                flex: 1;
                display: flex;
                flex-direction: column;
                overflow-x: auto;
                min-width: 0;
                background-color: #f8fafc;
            }
            
            .page-header {
                padding: 24px 30px;
                background: #ffffff;
                border-bottom: 1px solid var(--border-color);
                box-shadow: 0 1px 3px rgba(15, 23, 42, 0.01);
            }

            .content-area {
                padding: 30px;
                flex: 1;
                background: #f8fafc;
            }

            .welcome-title {
                font-size: 24px;
                font-weight: 700;
                color: var(--text-dark);
                margin-bottom: 24px;
                letter-spacing: -0.5px;
            }

            .cards-container {
                display: flex;
                gap: 24px;
                flex-wrap: wrap;
                margin-bottom: 30px;
            }

            .stat-card {
                background-color: #ffffff;
                border-radius: var(--radius-lg);
                padding: 24px;
                display: flex;
                flex-direction: column;
                gap: 16px;
                flex: 1;
                min-width: 250px;
                box-shadow: 0 4px 15px rgba(15, 23, 42, 0.02);
                border: 1px solid var(--border-color);
                text-decoration: none;
                color: inherit;
                transition: var(--transition-ease);
                cursor: pointer;
                position: relative;
                overflow: hidden;
            }

            .stat-card::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(24, 119, 242, 0.02) 0%, transparent 60%);
                opacity: 0;
                transition: var(--transition-ease);
            }

            .stat-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 15px 30px rgba(15, 23, 42, 0.06);
                border-color: rgba(24, 119, 242, 0.3);
            }

            .stat-card:hover::before {
                opacity: 1;
            }

            .stat-card-top {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                position: relative;
                z-index: 2;
            }

            .stat-card .info {
                display: flex;
                flex-direction: column;
            }
            
            .stat-card .info .label {
                font-size: 13px;
                font-weight: 500;
                color: var(--text-gray);
                margin-bottom: 8px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .stat-card .info .number {
                font-size: 32px;
                font-weight: 500;
                color: var(--text-dark);
                line-height: 1;
                letter-spacing: -1px;
            }

            .stat-card .icon-wrapper {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                transition: var(--transition-ease);
            }

            .stat-card:hover .icon-wrapper {
                transform: scale(1.1) rotate(3deg);
            }

            .icon-wrapper.purple-bg { background-color: #eff6ff; color: #1877F2; }
            .icon-wrapper.yellow-bg { background-color: #fef3c7; color: #d97706; }

            .stat-card-bottom {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 13px;
                color: #10b981;
                font-weight: 600;
                position: relative;
                z-index: 2;
            }
            
            .stat-card-bottom i {
                font-size: 12px;
            }

            /* Forms */
            .form-container {
                max-width: 600px;
                border: 1px solid var(--border-color);
                padding: 30px;
                border-radius: var(--radius-lg);
                background: #ffffff;
                box-shadow: var(--shadow-sm);
            }
            
            .form-group {
                margin-bottom: 20px;
            }
            
            .form-group label {
                display: block;
                margin-bottom: 8px;
                font-size: 14px;
                font-weight: 600;
                color: var(--text-dark);
            }
            
            .form-control {
                width: 100%;
                padding: 12px 16px;
                border: 1px solid #dfe5ee;
                border-radius: 8px;
                font-size: 14px;
                background-color: #f8fafc;
                transition: var(--transition-ease);
            }
            
            .form-control:focus {
                outline: none;
                border-color: var(--primary);
                background-color: #ffffff;
                box-shadow: 0 0 0 4px rgba(24, 119, 242, 0.12);
            }
            
            .btn-primary {
                background: var(--primary);
                color: #fff;
                border: none;
                padding: 12px 24px;
                border-radius: 8px;
                cursor: pointer;
                font-size: 15px;
                font-weight: 600;
                box-shadow: 0 4px 10px rgba(24, 119, 242, 0.2);
                transition: var(--transition-ease);
            }
            
            .btn-primary:hover {
                background: var(--primary-hover);
                transform: translateY(-1px);
                box-shadow: 0 6px 14px rgba(24, 119, 242, 0.3);
            }

            /* General Tables */
            .table-container {
                overflow-x: auto;
                background: #fff;
                border-radius: var(--radius-lg);
                border: 1px solid var(--border-color);
                box-shadow: var(--shadow-sm);
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                padding: 16px 20px;
                text-align: left;
                border-bottom: 1px solid var(--border-color);
                font-size: 14px;
                color: var(--text-dark);
                vertical-align: middle;
            }

            th {
                background-color: #f8fafc;
                font-weight: 700;
                color: #475569;
                font-size: 13px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            tbody tr {
                transition: var(--transition-ease);
            }

            tbody tr:hover {
                background-color: #f8fafc;
            }

            .badge {
                padding: 6px 12px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .badge::before {
                content: '';
                width: 6px;
                height: 6px;
                border-radius: 50%;
                display: inline-block;
            }

            .badge.success { background: #dcfce7; color: #166534; }
            .badge.success::before { background: #166534; }
            
            .badge.warning { background: #fef9c3; color: #854d0e; }
            .badge.warning::before { background: #854d0e; }
            
            .badge.danger { background: #fee2e2; color: #991b1b; }
            .badge.danger::before { background: #991b1b; }

            /* Action Buttons */
            .action-box {
                display: inline-flex;
                gap: 8px;
                padding: 6px;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                background: #ffffff;
            }
            
            .btn-action {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 32px;
                height: 32px;
                border-radius: 6px;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                color: #64748b;
                text-decoration: none;
                transition: var(--transition-ease);
            }
            
            .btn-action:hover {
                background: #eff6ff;
                color: var(--primary);
                border-color: rgba(24, 119, 242, 0.3);
                transform: translateY(-1px);
            }

            /* Error messages */
            .error-msg {
                color: #dc2626;
                font-size: 13px;
                margin-top: 5px;
            }

            .logout-form {
                display: inline;
                margin-top: auto; 
            }

            .logout-btn {
                background: none;
                border: none;
                width: 100%;
                text-align: left;
                cursor: pointer;
                padding: 12px 20px;
                color: var(--text-gray);
                font-size: 15px;
                display: flex;
                align-items: center;
                gap: 15px;
                font-family: inherit;
            }
            .logout-btn:hover {
                background-color: #f1f5f9;
            }

            /* Avatar Dropdown */
            .avatar-dropdown {
                display: none;
                position: absolute;
                top: calc(100% + 12px);
                right: 0;
                width: 240px;
                background: #fff;
                border: 1px solid #e6e9f2;
                border-radius: var(--radius-md);
                box-shadow: 0 16px 32px rgba(15, 23, 42, 0.08);
                overflow: hidden;
                z-index: 1000;
                flex-direction: column;
                animation: slideInUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) both;
            }

            .avatar-dropdown.show {
                display: flex;
            }

            .dropdown-profile-card {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 16px;
                border-bottom: 1px solid #eceff5;
            }

            .dropdown-profile-card .avatar-icon {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: grid;
                place-items: center;
                background: linear-gradient(135deg, #1877F2 0%, #1565D8 100%);
                color: #fff;
                font-weight: 700;
                font-size: 0.88rem;
                box-shadow: 0 4px 10px rgba(24, 119, 242, 0.2);
                overflow: hidden;
            }
            
            .dropdown-profile-card .avatar-icon img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .dropdown-profile-card strong {
                display: block;
                font-size: 0.88rem;
                line-height: 1.2;
                color: var(--text-dark);
            }

            .dropdown-profile-card span {
                display: block;
                margin-top: 3px;
                font-size: 0.76rem;
                color: var(--text-gray);
            }

            .dropdown-link {
                display: block;
                padding: 12px 20px;
                color: #475569;
                text-decoration: none;
                border-bottom: 1px solid #eceff5;
                font-size: 0.88rem;
                border-left: none;
                border-right: none;
                border-top: none;
                background: none;
                width: 100%;
                text-align: left;
                cursor: pointer;
                transition: var(--transition-ease);
                font-family: inherit;
            }

            .dropdown-link:last-child {
                border-bottom: 0;
            }

            .dropdown-link:hover {
                background: #f8fafc;
                color: var(--primary);
                padding-left: 24px;
            }
            
            .dropdown-logout-form {
                margin: 0;
                padding: 0;
            }

            /* Property Form Styles */
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
                border: 1px solid var(--border-color); 
                border-radius: var(--radius-lg); 
                overflow: hidden;
                animation: addPropPageIn 0.6s cubic-bezier(.22,1,.36,1) both;
                margin-top: 20px;
                box-shadow: var(--shadow-sm);
            }
            .add-property-head { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 20px 24px; border-bottom: 1px solid #e9eef5; background: #fff; }
            .add-property-title { margin: 0; font-size: 18px; font-weight: 700; color: var(--text-dark); }
            .add-property-subtitle { margin: 6px 0 0; color: var(--text-gray); font-size: 12px; }
            .add-property-back { display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: #1d4ed8; font-weight: 600; padding: 8px 16px; border-radius: 8px; background: #f8fbff; border: 1px solid #dbe7ff; transition: var(--transition-ease); }
            .add-property-back:hover { background: #eff6ff; transform: translateX(-2px); }
            .add-property-form { padding: 24px; background: #fff; }
            .property-form-grid { display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); gap: 20px; }
            
            .property-form-card { 
                grid-column: span 12; 
                background: #fff; 
                border: 1px solid #e9eef5; 
                border-radius: var(--radius-lg); 
                padding: 24px;
                box-shadow: 0 4px 12px rgba(15, 23, 42, 0.01);
                animation: formCardFadeIn 0.5s cubic-bezier(.22,1,.36,1) both;
                transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
                border-left: 4px solid var(--border-color);
            }
            
            .property-form-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
            }

            .property-info-theme { background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%); border-left: 5px solid #1877F2; }
            .property-location-theme { background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%); border-left: 5px solid #10b981; }
            .property-pricing-theme { background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%); border-left: 5px solid #f59e0b; }
            .property-content-theme { background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%); border-left: 5px solid #8b5cf6; }
            .property-faq-theme { background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%); border-left: 5px solid #3b82f6; }

            .property-form-card h3 { margin: 0 0 6px; font-size: 15px; font-weight: 700; color: var(--text-dark); text-transform: uppercase; letter-spacing: 0.5px; }
            .property-form-card p { margin: 0 0 16px; color: var(--text-gray); font-size: 12px; }
            .property-field { grid-column: span 12; }
            .property-field.col-6 { grid-column: span 6; }
            .property-field.col-4 { grid-column: span 4; }
            .property-field.col-3 { grid-column: span 3; }
            .property-field label { display: block; margin-bottom: 7px; color: var(--text-dark); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
            
            .property-field input, .property-field select, .property-field textarea { 
                width: 100%; 
                border: 1px solid #dfe5ee; 
                border-radius: 8px; 
                padding: 12px 14px; 
                font-size: 13px; 
                color: var(--text-dark); 
                background: #f8fafc;
                transition: var(--transition-ease);
            }
            .property-field input:focus, .property-field select:focus, .property-field textarea:focus {
                border-color: #1877F2;
                background: #fff;
                box-shadow: 0 0 0 4px rgba(24, 119, 242, 0.15);
                outline: none;
            }
            .upload-panel.media-main { border: 2px dashed #dfe5ee; min-height: 140px; background: #f8fafc; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; border-radius: var(--radius-md); transition: var(--transition-ease); }
            .upload-panel.media-main:hover { border-color: #1877F2; background: #eff6ff; }
            .upload-plus { font-size: 24px; color: #1877F2; margin-bottom: 8px; }
            .upload-title { font-size: 14px; font-weight: 700; color: var(--text-dark); }
            .upload-meta { font-size: 12px; color: var(--text-gray); }
            
            .gallery-preview-row { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px; }
            .gallery-preview-card { width: 80px; height: 80px; border-radius: 8px; background-size: cover; background-position: center; position: relative; border: 1px solid #dfe5ee; }
            .remove-gallery-img, .remove-img-btn { position: absolute; top: -8px; right: -8px; width: 20px; height: 20px; border-radius: 50%; background: #ef4444; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 12px; cursor: pointer; border: 2px solid #fff; }
            .gallery-add-card { width: 80px; height: 80px; border-radius: 8px; border: 2px dashed #dfe5ee; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #64748b; cursor: pointer; transition: var(--transition-ease); }
            .gallery-add-card:hover { border-color: #1877F2; color: #1877F2; }

            .multi-select-wrapper { position: relative; border: 1px solid #dfe5ee; border-radius: 8px; background: #f8fafc; padding: 5px; }
            .selected-chips-area { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 5px; }
            .choice-chip { background: #eff6ff; color: #1e40af; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 6px; }
            .remove-chip { cursor: pointer; color: #1e40af; opacity: 0.7; }
            .remove-chip:hover { opacity: 1; }
            .multi-select-toggle { width: 100%; border: none; background: transparent; display: none; }
            .multi-select-wrapper.is-open .multi-select-toggle { display: block; }

            /* Custom Premium Checkbox Dropdown Checklist for Amenities */
            .amenities-dropdown-list {
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                max-height: 250px;
                overflow-y: auto;
                background: #ffffff;
                border: 1px solid #dfe5ee;
                border-radius: 8px;
                box-shadow: var(--shadow-md);
                z-index: 1000;
                display: none;
                padding: 10px;
                margin-top: 5px;
            }
            .multi-select-wrapper.is-open .amenities-dropdown-list {
                display: block;
            }
            .amenities-dropdown-item {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 8px 12px;
                border-radius: 6px;
                cursor: pointer;
                transition: var(--transition-ease);
            }
            .amenities-dropdown-item:hover {
                background: #f1f5f9;
            }
            .amenities-dropdown-item input[type="checkbox"] {
                width: 16px;
                height: 16px;
                cursor: pointer;
                accent-color: var(--primary);
            }
            .amenities-dropdown-item label {
                margin: 0;
                font-size: 13px;
                font-weight: 500;
                color: var(--text-dark);
                cursor: pointer;
                text-transform: none !important;
                letter-spacing: normal !important;
            }

            /* Custom BHK Toggle Buttons Styles */
            .bhk-toggle-btn {
                padding: 10px 20px;
                border: 1.5px solid #dfe5ee;
                background: #f8fafc;
                color: #475569;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: var(--transition-ease);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                user-select: none;
            }
            .bhk-toggle-btn:hover {
                border-color: var(--primary);
                color: var(--primary);
                background: #eff6ff;
            }
            .bhk-toggle-btn.active {
                background: var(--primary);
                color: #ffffff;
                border-color: var(--primary);
                box-shadow: 0 4px 12px rgba(24, 119, 242, 0.2);
            }
            
            .map-select-wrap { border: 1px solid #dfe5ee; border-radius: 8px; min-height: 150px; background: #f8fafc; display: flex; align-items: center; justify-content: center; text-align: center; }
            .map-select-trigger { display: flex; flex-direction: column; align-items: center; gap: 8px; text-decoration: none; color: var(--text-dark); }
            .map-select-icon { width: 40px; height: 40px; border-radius: 50%; background: #eff6ff; color: #1877F2; display: flex; align-items: center; justify-content: center; }
            .map-select-title { font-weight: 600; }
            
            .map-picker-modal { position: fixed; inset: 0; z-index: 9999; display: none; align-items: center; justify-content: center; background: rgba(0,0,0,0.5); }
            .map-picker-modal.is-open { display: flex; }
            .map-picker-card { width: 90%; max-width: 800px; background: #fff; border-radius: 12px; overflow: hidden; }
            .map-picker-head { padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
            #propertyGoogleMapCanvas { width: 100%; height: 400px; }
            .map-picker-foot { padding: 15px 20px; border-top: 1px solid #eee; display: flex; justify-content: flex-end; gap: 10px; }

            .faq-item { display: grid; grid-template-columns: 1fr 1fr auto; gap: 10px; margin-bottom: 10px; }
            .faq-add-btn { padding: 8px 15px; background: #1877F2; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; }
            .faq-remove-btn { padding: 8px 12px; background: #fee2e2; color: #ef4444; border: none; border-radius: 6px; cursor: pointer; }
            
            .property-save-btn { padding: 12px 30px; background: #22c55e; color: #fff; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: var(--transition-ease); animation: saveBtnGlow 3s infinite; }
            .property-save-btn:hover { background: #16a34a; transform: translateY(-2px); }

            .choose-type-wrap { 
                background: #fff; 
                border: 1px solid var(--border-color); 
                border-radius: var(--radius-lg); 
                padding: 30px; 
                margin-top: 20px;
                box-shadow: var(--shadow-sm);
            }
            .choose-type-wrap label {
                display: block;
                font-size: 16px;
                font-weight: 700;
                color: var(--text-dark);
                margin-bottom: 20px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            .type-cards { 
                display: flex; 
                gap: 20px; 
                flex-wrap: wrap;
            }
            .type-card { 
                flex: 1; 
                min-width: 250px;
                background: #f8fafc;
                border: 1.5px solid #e2e8f0; 
                border-radius: var(--radius-md); 
                padding: 40px 20px; 
                text-decoration: none; 
                color: inherit; 
                transition: var(--transition-ease);
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .type-card:hover { 
                background: #eff6ff; 
                border-color: var(--primary);
                transform: translateY(-4px);
                box-shadow: var(--shadow-md);
            }
            .type-card img { 
                width: 48px; 
                height: 48px; 
                margin-bottom: 15px; 
                transition: transform 0.3s ease;
            }
            .type-card:hover img {
                transform: scale(1.1);
            }
            .type-card h3 { 
                font-size: 18px;
                font-weight: 700;
                color: var(--text-dark);
                margin-bottom: 8px; 
            }
            .type-card p { 
                font-size: 14px; 
                color: var(--text-gray); 
                margin: 0;
            }

            /* --- Edit Profile Modern Layout --- */
            .profile-layout-container {
                display: flex;
                gap: 30px;
                flex-wrap: wrap;
                margin-top: 20px;
                animation: slideInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
            }

            .profile-preview-card {
                flex: 1;
                min-width: 300px;
                max-width: 360px;
                background: #ffffff;
                border-radius: var(--radius-lg);
                border: 1px solid var(--border-color);
                padding: 30px;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                box-shadow: var(--shadow-sm);
                height: fit-content;
                position: relative;
                overflow: hidden;
                transition: var(--transition-ease);
            }

            .profile-preview-card:hover {
                transform: translateY(-5px);
                box-shadow: var(--shadow-lg);
                border-color: rgba(24, 119, 242, 0.2);
            }

            .profile-preview-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100px;
                background: linear-gradient(135deg, rgba(24, 119, 242, 0.1) 0%, rgba(117, 98, 216, 0.05) 100%);
            }

            .profile-preview-avatar {
                width: 120px;
                height: 120px;
                border-radius: 50%;
                border: 4px solid #ffffff;
                box-shadow: var(--shadow-md);
                overflow: hidden;
                margin-top: 20px;
                margin-bottom: 20px;
                position: relative;
                z-index: 2;
                background: #f1f5f9;
                transition: var(--transition-ease);
            }

            .profile-preview-card:hover .profile-preview-avatar {
                transform: scale(1.05) rotate(2deg);
                box-shadow: 0 10px 25px rgba(24, 119, 242, 0.15);
            }

            .profile-preview-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .profile-preview-name {
                font-size: 20px;
                font-weight: 700;
                color: var(--text-dark);
                margin-bottom: 6px;
            }

            .profile-preview-role {
                font-size: 13px;
                font-weight: 600;
                color: var(--primary);
                background: rgba(24, 119, 242, 0.08);
                padding: 4px 12px;
                border-radius: 50px;
                margin-bottom: 24px;
                display: inline-block;
            }

            .profile-preview-meta {
                width: 100%;
                border-top: 1px solid var(--border-color);
                padding-top: 20px;
                display: flex;
                flex-direction: column;
                gap: 12px;
                text-align: left;
            }

            .profile-meta-item {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 13px;
                color: var(--text-gray);
            }

            .profile-meta-item i {
                color: var(--primary);
                width: 16px;
                text-align: center;
            }

            .profile-form-wrap {
                flex: 2;
                min-width: 320px;
                background: #ffffff;
                border-radius: var(--radius-lg);
                border: 1px solid var(--border-color);
                padding: 30px;
                box-shadow: var(--shadow-sm);
                transition: var(--transition-ease);
            }

            .profile-form-wrap:hover {
                box-shadow: var(--shadow-md);
            }

            .profile-form-grid {
                display: grid;
                grid-template-columns: repeat(12, minmax(0, 1fr));
                gap: 20px;
            }

            .profile-form-field {
                grid-column: span 12;
            }

            .profile-form-field.col-6 {
                grid-column: span 6;
            }

            @media (max-width: 768px) {
                .profile-form-field.col-6 {
                    grid-column: span 12;
                }
                .profile-layout-container {
                    flex-direction: column;
                }
                .profile-preview-card {
                    max-width: 100%;
                }
            }

            .profile-form-field label {
                display: block;
                margin-bottom: 8px;
                font-size: 12px;
                font-weight: 700;
                color: var(--text-dark);
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .profile-form-field input,
            .profile-form-field textarea,
            .profile-form-field select {
                width: 100%; 
                padding: 12px 16px;
                border: 1px solid #dfe5ee;
                border-radius: 8px;
                font-size: 14px;
                background-color: #f8fafc;
                transition: var(--transition-ease);
                color: var(--text-dark);
            }

            .profile-form-field input:focus,
            .profile-form-field textarea:focus,
            .profile-form-field select:focus {
                outline: none;
                border-color: var(--primary);
                background-color: #ffffff;
                box-shadow: 0 0 0 4px rgba(24, 119, 242, 0.12);
            }

            .profile-photo-upload-box {
                border: 2px dashed #dfe5ee;
                border-radius: 8px;
                padding: 20px;
                text-align: center;
                background: #f8fafc;
                cursor: pointer;
                transition: var(--transition-ease);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }

            .profile-photo-upload-box:hover {
                border-color: var(--primary);
                background: #eff6ff;
            }

            .profile-photo-upload-box i {
                font-size: 24px;
                color: var(--primary);
            }

            .profile-photo-upload-box span {
                font-size: 13px;
                font-weight: 600;
                color: var(--text-dark);
            }

            .profile-photo-upload-box p {
                font-size: 11px;
                color: var(--text-gray);
                margin: 0;
            }
        </style>
    </head>
    <body>

        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="navbar-left">
                <div class="brand-container">
                    <img src="{{ asset('images/homewala-logo.png') }}" onerror="this.src='https://via.placeholder.com/150x40?text=Homewala.com'" alt="Homewala Logo" class="navbar-logo">
                </div>
                <div class="hamburger-menu" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
            <div class="navbar-right" style="position: relative;">
                <div class="vendor-avatar" onclick="toggleAvatarDropdown()" style="cursor: pointer;">
                    @if($vendor->photo)
                        <img src="{{ Storage::url($vendor->photo) }}" alt="Vendor">
                    @else
                        <!-- Fallback if no photo -->
                        <span style="font-size: 16px; font-weight: 600; color: #475569;">{{ substr($vendor->first_name ?? 'V', 0, 1) }}{{ substr($vendor->last_name ?? '', 0, 1) }}</span>
                    @endif
                </div>

                <div class="avatar-dropdown" id="avatarDropdown">
                    <div class="dropdown-profile-card">
                        <div class="avatar-icon">
                            @if($vendor->photo)
                                <img src="{{ Storage::url($vendor->photo) }}" alt="Vendor">
                            @else
                                {{ substr($vendor->first_name ?? 'V', 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <strong>{{ $vendor->first_name ?? 'Vendor' }} {{ $vendor->last_name ?? '' }}</strong>
                            <span>{{ $vendor->email ?? '' }}</span>
                        </div>
                    </div>
                    <a href="{{ route('vendor.section', 'edit-profile') }}" class="dropdown-link">
                        Edit Profile
                    </a>
                    <a href="{{ route('vendor.section', 'change-password') }}" class="dropdown-link">
                        Change Password
                    </a>

                    <form action="{{ route('vendor.logout') }}" method="POST" class="dropdown-logout-form">
                        @csrf
                        <button type="submit" class="dropdown-link">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

    <div class="layout-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="nav-menu">
                <a href="{{ route('vendor.section', 'dashboard') }}" class="nav-link {{ $currentSection === 'dashboard' ? 'active' : '' }}">
                   <span class="nav-text">Dashboard</span>
                </a>
                
                <details class="nav-group" {{ in_array($currentSection, ['manage-properties', 'property-enquiries', 'choose-property-type', 'add-property']) ? 'open' : '' }}>
                    <summary class="nav-item nav-toggle {{ in_array($currentSection, ['manage-properties', 'property-enquiries', 'choose-property-type', 'add-property']) ? 'group-active' : '' }}">
                       <span class="nav-text">Property Management</span>
                    </summary>
                    <div class="nav-children">
                        <a href="{{ route('vendor.section', 'choose-property-type') }}" class="nav-subitem {{ in_array($currentSection, ['choose-property-type', 'add-property']) ? 'active' : '' }}">
                            <i class="fas fa-plus"></i> Add Property
                        </a>
                        <a href="{{ route('vendor.section', 'manage-properties') }}" class="nav-subitem {{ $currentSection === 'manage-properties' ? 'active' : '' }}">
                            <i class="fas fa-list"></i> Manage Property
                        </a>
                        <a href="{{ route('vendor.section', 'property-enquiries') }}" class="nav-subitem {{ $currentSection === 'property-enquiries' ? 'active' : '' }}">
                            <i class="fas fa-envelope"></i> Property Enquiries
                        </a>
                    </div>
                </details>

                <details class="nav-group" {{ in_array($currentSection, ['edit-profile', 'change-password']) ? 'open' : '' }}>
                    <summary class="nav-item nav-toggle {{ in_array($currentSection, ['edit-profile', 'change-password']) ? 'group-active' : '' }}">
                        <span class="nav-text">Profile</span>
                    </summary>
                    <div class="nav-children">
                        <a href="{{ route('vendor.section', 'edit-profile') }}" class="nav-subitem {{ $currentSection === 'edit-profile' ? 'active' : '' }}">
                            <i class="fas fa-user-edit"></i> Edit Profile
                        </a>
                        <a href="{{ route('vendor.section', 'change-password') }}" class="nav-subitem {{ $currentSection === 'change-password' ? 'active' : '' }}">
                            <i class="fas fa-key"></i> Change Password
                        </a>
                        <form action="{{ route('vendor.logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="nav-subitem" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; color: #dc2626;">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </details>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="page-header">
                @if ($currentSection === 'dashboard')
                    <div style="font-weight: 500; font-size: 18px;">Dashboard</div>
                @elseif ($currentSection === 'manage-properties')
                    <div style="font-weight: 500; font-size: 18px;">Manage Properties</div>
                @elseif ($currentSection === 'property-enquiries')
                    <div style="font-weight: 500; font-size: 18px;">Property Enquiries</div>
                @elseif ($currentSection === 'edit-profile')
                    <div style="font-weight: 500; font-size: 18px;">Edit Profile</div>
                @elseif ($currentSection === 'change-password')
                    <div style="font-weight: 500; font-size: 18px;">Change Password</div>
                @endif
            </div>

            <div class="content-area">
                
                @if(session('status'))
                    <div style="padding: 15px; margin-bottom: 20px; background-color: #dcfce7; color: #166534; border-radius: 6px;">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($currentSection === 'dashboard')
                    <h1 class="welcome-title">Welcome back, {{ $vendor->first_name ?? 'Vendor' }}!</h1>

                    <div class="cards-container">
                        <a href="{{ route('vendor.section', 'manage-properties') }}" class="stat-card">
                            <div class="stat-card-top">
                                <div class="info">
                                    <span class="label">Properties</span>
                                    <span class="number">{{ $propertiesCount ?? 0 }}</span>
                                </div>
                                <div class="icon-wrapper purple-bg">
                                    <i class="fas fa-user-friends"></i>
                                </div>
                            </div>
                            <div class="stat-card-bottom">
                                <i class="fas fa-arrow-trend-up"></i> 8.5% Up from Last Month
                            </div>
                        </a>

                        <a href="{{ route('vendor.section', 'property-enquiries') }}" class="stat-card">
                            <div class="stat-card-top">  
                                <div class="info">
                                    <span class="label">Enquiries</span>
                                    <span class="number">{{ $enquiriesCount ?? 0 }}</span>
                                </div>
                                <div class="icon-wrapper yellow-bg">
                                    <i class="fas fa-box"></i>
                                </div>
                            </div>
                            <div class="stat-card-bottom">
                                <i class="fas fa-arrow-trend-up"></i> 8.5% Up from Last Month
                            </div>
                        </a>
                    </div>
                
                @elseif ($currentSection === 'manage-properties')
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px;">
                        <h1 class="welcome-title" style="margin: 0;">Your Properties</h1>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <select id="managePropertyTypeFilter" style="padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px; outline: none;">
                                <option value="all">All Types</option>
                                <option value="residential">Residential</option>
                                <option value="commercial">Commercial</option>
                            </select>
                            <input type="text" id="managePropertySearch" placeholder="Search by name or location..." style="padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px; width: 250px; outline: none;">
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Property Name</th>
                                    <th>Category</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Approval Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="managePropertiesBody">
                                @forelse($properties ?? [] as $prop)
                                    <tr class="manage-property-row" data-title="{{ strtolower($prop->property_name ?? '') }}" data-type="{{ strtolower($prop->type ?? '') }}" data-area="{{ strtolower($prop->property_area ?? '') }}">
                                        <td>{{ $prop->id }}</td>
                                        <td><strong>{{ $prop->property_name }}</strong></td>
                                        <td>{{ $prop->category_name ?? $prop->type ?? 'N/A' }}</td>
                                        <td>{{ $prop->property_area ?? 'N/A' }}</td>
                                        <td>
                                            <select class="property-status-select" data-id="{{ $prop->id }}" style="padding: 6px 10px; border: 1px solid #dfe5ee; border-radius: 6px; font-size: 12px; font-weight: 600; background-color: {{ $prop->status == 1 ? '#dcfce7' : '#fee2e2' }}; color: {{ $prop->status == 1 ? '#166534' : '#991b1b' }}; outline: none; cursor: pointer;" onchange="this.style.backgroundColor = this.value == '1' ? '#dcfce7' : '#fee2e2'; this.style.color = this.value == '1' ? '#166534' : '#991b1b';">
                                                <option value="1" {{ $prop->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ $prop->status == 0 ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </td>
                                        <td>
                                            @if(($prop->approve_status ?? 0) == 1)
                                                <span class="badge success">Approved</span>
                                            @elseif(($prop->approve_status ?? 0) == 2)
                                                <span class="badge warning">Pending</span>
                                            @else
                                                <span class="badge danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-box">
                                                <a href="#" class="btn-action" title="Edit">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                <form action="#" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this property?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action" style="background: none; border: none; cursor: pointer; color: #ef4444;" title="Delete">
                                                        <i class="far fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 30px;">There are no properties yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                  
                    
                @elseif ($currentSection === 'property-enquiries')
                    <h1 class="welcome-title">Property Enquiries</h1>
                    <div class="table-container">
                        <!-- Table remains the same -->
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Property Name</th>
                                    <th>Customer Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($enquiries ?? [] as $enq)
                                    <tr>
                                        <td>{{ $enq->id ?? 'N/A' }}</td>
                                        <td><strong>{{ $enq->property_name ?? 'N/A' }}</strong></td>
                                        <td>{{ $enq->name ?? $enq->customer_name ?? 'N/A' }}</td>
                                        <td>{{ $enq->email ?? 'N/A' }}</td>
                                        <td>{{ $enq->phone ?? $enq->mobile ?? $enq->customer_phone ?? 'N/A' }}</td>
                                        <td>
                                            @if($enq->enquiry_status === 'Received')
                                                <span class="badge warning">Received</span>
                                            @else
                                                <span class="badge success">{{ $enq->enquiry_status ?? 'Closed' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 30px;">There are no enquiries yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                @elseif ($currentSection === 'edit-profile')
                    <h1 class="welcome-title">Edit Profile</h1>
                    
                    @if (session('status'))
                        <div style="background-color: #dcfce7; border: 1px solid #bbf7d0; color: #15803d; padding: 16px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 8px; animation: fadeIn 0.4s ease-out;">
                            <i class="fas fa-check-circle"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="profile-layout-container">
                        <!-- Left Preview Card -->
                        <div class="profile-preview-card">
                            <div class="profile-preview-avatar">
                                @if($vendor->photo)
                                    <img src="{{ Storage::url($vendor->photo) }}" alt="Vendor Photo">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($vendor->first_name . ' ' . $vendor->last_name) }}&background=1877F2&color=fff&size=120" alt="Vendor Photo">
                                @endif
                            </div>
                            <div class="profile-preview-name">{{ $vendor->first_name }} {{ $vendor->last_name }}</div>
                            <span class="profile-preview-role">Vendor Partner</span>
                            
                            <div class="profile-preview-meta">
                                <div class="profile-meta-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>{{ $vendor->email }}</span>
                                </div>
                                @if($vendor->phone)
                                    <div class="profile-meta-item">
                                        <i class="fas fa-phone-alt"></i>
                                        <span>{{ $vendor->phone }}</span>
                                    </div>
                                @endif
                                @if($vendor->city || $vendor->country)
                                    <div class="profile-meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>
                                            {{ implode(', ', array_filter([$vendor->city, $vendor->state, $vendor->country])) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right Form Wrap -->
                        <div class="profile-form-wrap">
                            <form action="{{ route('vendor.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="profile-form-grid">
                                    
                                    <!-- First Name -->
                                    <div class="profile-form-field col-6">
                                        <label>First Name</label>
                                        <input type="text" name="first_name" value="{{ old('first_name', $vendor->first_name) }}" required>
                                        @error('first_name')<div class="error-msg">{{ $message }}</div>@enderror
                                    </div>

                                    <!-- Last Name -->
                                    <div class="profile-form-field col-6">
                                        <label>Last Name</label>
                                        <input type="text" name="last_name" value="{{ old('last_name', $vendor->last_name) }}" required>
                                        @error('last_name')<div class="error-msg">{{ $message }}</div>@enderror
                                    </div>

                                    <!-- Email Address -->
                                    <div class="profile-form-field col-6">
                                        <label>Email Address</label>
                                        <input type="email" name="email" value="{{ old('email', $vendor->email) }}" required>
                                        @error('email')<div class="error-msg">{{ $message }}</div>@enderror
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="profile-form-field col-6">
                                        <label>Phone Number</label>
                                        <input type="text" name="phone" value="{{ old('phone', $vendor->phone) }}">
                                        @error('phone')<div class="error-msg">{{ $message }}</div>@enderror
                                    </div>

                                    <!-- Country -->
                                    <!-- <div class="profile-form-field col-6">
                                        <label>Country</label>
                                        <input type="text" name="country" value="{{ old('country', $vendor->country) }}" placeholder="e.g. India">
                                        @error('country')<div class="error-msg">{{ $message }}</div>@enderror
                                    </div> -->

                                    <!-- State -->
                                    <!-- <div class="profile-form-field col-6">
                                        <label>State</label>
                                        <input type="text" name="state" value="{{ old('state', $vendor->state) }}" placeholder="e.g. Maharashtra">
                                        @error('state')<div class="error-msg">{{ $message }}</div>@enderror
                                    </div> -->

                                    <!-- City -->
                                    <!-- <div class="profile-form-field col-6">
                                        <label>City</label>
                                        <input type="text" name="city" value="{{ old('city', $vendor->city) }}" placeholder="e.g. Mumbai">
                                        @error('city')<div class="error-msg">{{ $message }}</div>@enderror
                                    </div> -->

                                    <!-- Zip Code -->
                                    <!-- <div class="profile-form-field col-6">
                                        <label>Zip Code</label>
                                        <input type="text" name="zip_code" value="{{ old('zip_code', $vendor->zip_code) }}" placeholder="e.g. 400001">
                                        @error('zip_code')<div class="error-msg">{{ $message }}</div>@enderror
                                    </div> -->

                                    <!-- Profile Photo Upload -->
                                    <div class="profile-form-field">
                                        <label>Profile Photo</label>
                                        <div class="profile-photo-upload-box" onclick="document.getElementById('profilePhotoInput').click()">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span id="uploadBoxFileName">Choose a profile image or drag it here</span>
                                            <p>Supports PNG, JPG, JPEG or AVIF (Max 2MB)</p>
                                            <input type="file" name="photo" id="profilePhotoInput" style="display: none;" onchange="updateUploadBoxFileName(this)">
                                        </div>
                                        @error('photo')<div class="error-msg">{{ $message }}</div>@enderror
                                    </div>

                                    <!-- Describe about you -->
                                    <div class="profile-form-field">
                                        <label>Describe about you</label>
                                        <textarea name="details" rows="4" placeholder="Tell us about yourself, your business, or your background...">{{ old('details', $vendor->details) }}</textarea>
                                        @error('details')<div class="error-msg">{{ $message }}</div>@enderror
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="profile-form-field" style="margin-top: 10px;">
                                        <button type="submit" class="btn-primary">Update Profile</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        function updateUploadBoxFileName(input) {
                            const fileName = input.files && input.files[0] ? input.files[0].name : "Choose a profile image or drag it here";
                            document.getElementById('uploadBoxFileName').innerText = fileName;
                        }
                    </script>

                @elseif ($currentSection === 'choose-property-type')
                    <style>
                        .choose-type-wrap {
                            background: #fff;
                            border: 1px solid #dfe5ee;
                            border-radius: 8px;
                            padding: 30px 40px;
                            min-height: 500px;
                            box-shadow: var(--shadow-sm);
                            animation: addPropPageIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
                        }

                        .property-stepper {
                            display: flex;
                            list-style: none;
                            padding: 0;
                            margin: 0 0 40px 0;
                            position: relative;
                            border-bottom: 2px solid #f1f5f9;
                        }
                        
                        .property-stepper li {
                            flex: 1;
                            text-align: center;
                            padding-bottom: 16px;
                            font-size: 14px;
                            font-weight: 600;
                            color: var(--text-gray);
                            cursor: default;
                            position: relative;
                        }
                        
                        .property-stepper li.active {
                            color: var(--primary);
                        }

                        .property-stepper li.active::after {
                            content: '';
                            position: absolute;
                            bottom: -2px;
                            left: 0;
                            right: 0;
                            height: 2px;
                            background: var(--primary);
                            border-radius: 2px;
                        }

                        .type-selection-container {
                            display: flex;
                            gap: 30px;
                            max-width: 800px;
                            margin: 30px auto 0;
                        }

                        .premium-type-card {
                            flex: 1;
                            border: 1.5px solid #e2e8f0;
                            background: #f8fafc;
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            padding: 40px 20px;
                            text-decoration: none;
                            border-radius: 12px;
                            transition: var(--transition-ease);
                            text-align: center;
                            cursor: pointer;
                        }

                        .premium-type-card:hover {
                            background: #eff6ff;
                            border-color: var(--primary);
                            transform: translateY(-4px);
                            box-shadow: var(--shadow-md);
                        }

                        .premium-type-card img {
                            width: 56px;
                            height: 56px;
                            margin-bottom: 16px;
                            transition: transform 0.3s ease;
                        }

                        .premium-type-card:hover img {
                            transform: scale(1.1);
                        }

                        .premium-type-card .card-title {
                            font-size: 16px;
                            font-weight: 700;
                            color: var(--text-dark);
                            margin-bottom: 6px;
                        }

                        .premium-type-card .card-desc {
                            font-size: 13px;
                            color: var(--text-gray);
                        }
                    </style>

                    <div class="choose-type-wrap">
                        
                        <div style="font-size: 15px; font-weight: 700; color: var(--text-dark); text-align: center; margin-bottom: 24px; text-transform: uppercase; letter-spacing: 0.5px;">Select Property Type <span style="color: #ef4444;">*</span></div>

                        <div class="type-selection-container">
                            <a href="{{ route('vendor.section', ['section' => 'add-property', 'type' => 'residential']) }}" class="premium-type-card">
                                <img src="https://img.icons8.com/color/48/home.png" alt="Residential">
                                <div class="card-title">Residential</div>
                                <div class="card-desc">Apartments, Villas, Plots</div>
                            </a>

                            <a href="{{ route('vendor.section', ['section' => 'add-property', 'type' => 'commercial']) }}" class="premium-type-card">
                                <img src="https://cdn-icons-png.flaticon.com/512/10809/10809404.png" alt="Commercial">
                                <div class="card-title">Commercial</div>
                                <div class="card-desc">Office, Retail, Warehouse</div>
                            </a>
                        </div>
                    </div>

                @elseif ($currentSection === 'add-property')
                    <div class="add-property-wrap">
                        <div class="add-property-head">
                            <div>
                                <h2 class="add-property-title">{{ ucfirst($selectedPropertyType ?? 'property') }} Property</h2>
                                <p class="add-property-subtitle">Create the full {{ $selectedPropertyType ?? 'property' }} listing in one page with media, pricing, address, amenities, content, and FAQs.</p>
                            </div>
                            <a href="{{ route('vendor.section', 'manage-properties') }}" class="add-property-back">
                                <span>&larr;</span>
                                <span>Back to Manage Properties</span>
                            </a>
                        </div>

                        <form id="addPropertyForm" class="add-property-form" method="POST" enctype="multipart/form-data" action="{{ route('vendor.property.store') }}">
                            @csrf
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
                                <!-- Media Section -->
                                <div class="property-form-card">
                                    <h3>Property Media</h3>
                                    <div class="property-form-grid">
                                        <div class="property-field media-block" style="grid-column: span 12;">
                                            <label class="media-title" for="propertyGalleryImages">Gallery Images <span class="required-star">*</span></label>
                                            <label class="upload-panel media-main" for="propertyGalleryImages" id="galleryUploadArea">
                                                <span class="upload-plus">+</span>
                                                <span class="upload-title">Click to upload gallery images</span><br>
                                                <span class="upload-meta">PNG, JPG up to 2 MB each · Multiple allowed</span>
                                            </label>
                                            <div class="gallery-preview-row" id="galleryPreviewContainer">
                                                <label for="propertyGalleryImages" class="gallery-add-card">+</label>
                                            </div>
                                            <input type="file" id="propertyGalleryImages" name="gallery_images[]" accept="image/png, image/jpeg, image/jpg, image/avif" multiple hidden required>
                                        </div>
                                         <div class="property-field media-block" style="grid-column: span 6;">
                                            <label class="media-title" for="propertyDisplayImage">Display Image (Cover) <span class="required-star">*</span></label>
                                            <label class="upload-panel media-main" for="propertyDisplayImage" id="displayImageUploadArea">
                                                <span class="upload-plus">+</span>
                                                <span class="upload-title">Upload primary display image</span><br>
                                                <span class="upload-meta">PNG, JPG up to 2 MB · This appears on listings</span>
                                            </label>
                                            <input type="file" id="propertyDisplayImage" name="display_image" accept="image/png, image/jpeg, image/jpg, image/avif" hidden required>
                                        </div>
                                         <div class="property-field media-block" style="grid-column: span 6;">
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

                                <!-- Info Section -->
                                <div class="property-form-card property-info-theme">
                                    <h3>Property Information</h3>
                                    <div class="property-form-grid">
                                        <div class="property-field col-6">
                                            <label for="propertyName">Property Name <span class="required-star">*</span></label>
                                            <input type="text" id="propertyName" name="property_name" placeholder="e.g. Prestige Heights" value="{{ old('property_name') }}" required>
                                        </div>
                                        <div class="property-field col-6">
                                            <label for="propertyCategory">Category <span class="required-star">*</span></label>
                                            <select id="propertyCategory" name="category_id" required>
                                                <option value="">Select category</option>
                                                @foreach ($categories ?? [] as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="property-field col-6">
                                            <label for="propertyStatus">Status <span class="required-star">*</span></label>
                                            <select id="propertyStatus" name="status" required>
                                                <option value="1" {{ (string) old('status', '1') === '1' ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ (string) old('status', '1') === '0' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                        <div class="property-field col-6">
                                            <label for="propertyConstructionStatus">Construction Status <span class="required-star">*</span></label>
                                            <select id="propertyConstructionStatus" name="construction_status" required>
                                                <option value="">Select status</option>
                                                <option value="Ready to Move" {{ old('construction_status') == 'Ready to Move' ? 'selected' : '' }}>Ready to Move</option>
                                                <option value="Under Construction" {{ old('construction_status') == 'Under Construction' ? 'selected' : '' }}>Under Construction</option>
                                                <option value="New Launch" {{ old('construction_status') == 'New Launch' ? 'selected' : '' }}>New Launch</option>
                                            </select>
                                        </div>
                                        <div class="property-field col-6">
                                            <label for="propertyPossessionDate">Possession Date</label>
                                            <input type="date" id="propertyPossessionDate" name="possession_date" value="{{ old('possession_date') }}">
                                        </div>
                                        <div class="property-field col-6">
                                            <label for="propertyFurnishedStatus">Furnished Status <span class="required-star">*</span></label>
                                            <select id="propertyFurnishedStatus" name="furnished_status" required>
                                                <option value="">Select</option>
                                                <option value="Furnished" {{ old('furnished_status') == 'Furnished' ? 'selected' : '' }}>Furnished</option>
                                                <option value="Semi-furnished" {{ old('furnished_status') == 'Semi-furnished' ? 'selected' : '' }}>Semi-furnished</option>
                                                <option value="Unfurnished" {{ old('furnished_status') == 'Unfurnished' ? 'selected' : '' }}>Unfurnished</option>
                                            </select>
                                        </div>
                                        <div class="property-field col-6">
                                            <label for="propertyTopPicks">Top Picks (Categories)</label>
                                            <div class="multi-select-wrapper">
                                                <div id="selectedTopPicksDisplay" class="selected-chips-area"></div>
                                                <input type="text" id="topPicksSearch" placeholder="Search & select multiple (e.g. Best Deals)..." style="border:none; padding:5px; width:100%; outline:none;">
                                                <select id="propertyTopPicks" name="top_picks[]" multiple size="5" class="multi-select-toggle" style="margin-top:10px;">
                                                    @php
                                                        $currentTopPicks = collect(old('top_picks', []))->map(fn($id) => (string)$id)->all();
                                                    @endphp
                                                    @foreach($topPicksCategories ?? [] as $tp)
                                                        <option class="top-pick-option" data-name="{{ strtolower($tp->name ?? '') }}" value="{{ $tp->id }}" {{ in_array((string)$tp->id, $currentTopPicks, true) ? 'selected' : '' }}>{{ $tp->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="property-field col-6">
                                            <label>Amenities <span class="required-star">*</span></label>
                                            <div class="multi-select-wrapper" id="amenitiesWrapper" style="position: relative;">
                                                <div id="selectedAmenitiesDisplay" class="selected-chips-area"></div>
                                                <div class="search-input-container" style="position: relative; display: flex; align-items: center; border: 1px solid #dfe5ee; border-radius: 8px; background: #fff; padding: 4px 10px;">
                                                    <i class="fas fa-search" style="color: #94a3b8; margin-right: 8px;"></i>
                                                    <input type="text" id="propertyAmenitySearch" placeholder="Search amenities..." style="border:none; padding:8px 0; width:100%; outline:none; font-size:14px; background:transparent;">
                                                </div>
                                                
                                                <!-- Custom dropdown scrollable list with checkboxes -->
                                                <div class="amenities-dropdown-list" id="amenitiesDropdownList">
                                                    @php
                                                        $selectedAmenityIds = collect(old('amenity_ids', []))->map(fn ($id) => (string) $id)->all();
                                                    @endphp
                                                    @foreach (($amenities ?? []) as $amenity)
                                                        <div class="amenities-dropdown-item" data-id="{{ $amenity->id }}" data-name="{{ strtolower($amenity->name ?? '') }}">
                                                            <input type="checkbox" id="amenity_check_{{ $amenity->id }}" value="{{ $amenity->id }}" {{ in_array((string) $amenity->id, $selectedAmenityIds, true) ? 'checked' : '' }}>
                                                            <label for="amenity_check_{{ $amenity->id }}">{{ $amenity->name }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <!-- Hidden select to submit values -->
                                                <select id="propertyAmenitiesSelect" name="amenity_ids[]" multiple style="display: none;" required>
                                                    @foreach (($amenities ?? []) as $amenity)
                                                        <option value="{{ $amenity->id }}" {{ in_array((string) $amenity->id, $selectedAmenityIds, true) ? 'selected' : '' }}>{{ $amenity->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @if(($selectedPropertyType ?? 'residential') === 'residential')
                                        <div class="property-field col-6">
                                            <label style="margin-bottom: 12px; display: block;">No. of BHK</label>
                                            @php
                                                $selectedBhkValues = collect(old('bhk', []))->map(fn ($value) => (string) $value)->all();
                                            @endphp
                                            <div class="bhk-toggle-group" style="display: flex; gap: 10px; flex-wrap: wrap;">
                                                @foreach(['1 BHK', '2 BHK', '3 BHK', '4 BHK', '5+ BHK'] as $bhkOption)
                                                    <button type="button" class="bhk-toggle-btn {{ in_array($bhkOption, $selectedBhkValues, true) ? 'active' : '' }}" data-value="{{ $bhkOption }}">
                                                        {{ $bhkOption }}
                                                    </button>
                                                @endforeach
                                            </div>
                                            <!-- Hidden select to submit values -->
                                            <select id="propertyBhk" name="bhk[]" multiple style="display: none;">
                                                @foreach(['1 BHK', '2 BHK', '3 BHK', '4 BHK', '5+ BHK'] as $bhkOption)
                                                    <option value="{{ $bhkOption }}" {{ in_array($bhkOption, $selectedBhkValues, true) ? 'selected' : '' }}>{{ $bhkOption }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif
                                        <div class="property-field col-6">
                                            <label>Area (Sq.Ft) Range</label>
                                            <div style="display:flex; gap:10px;">
                                                <input type="number" name="min_area" placeholder="Min" value="{{ old('min_area') }}">
                                                <input type="number" name="max_area" placeholder="Max" value="{{ old('max_area') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pricing Section -->
                                <div class="property-form-card property-pricing-theme">
                                    <h3 class="section-marker-title">Pricing & Brochure (INR)</h3>
                                    <p class="section-marker-note">At least one price (min or max) is required. Both can be filled for a price range.</p>
                                    <div class="property-form-grid">
                                        <div class="property-field col-6">
                                            <label for="propertyMinPrice">Min Price (₹) <span class="required-star">*</span></label>
                                            <input type="number" id="propertyMinPrice" name="min_price" placeholder="e.g. 5000000" value="{{ old('min_price') }}">
                                            <div class="hint" style="font-size:11px; color:var(--text-gray); margin-top:4px;">Leave blank if not applicable</div>
                                        </div>
                                        <div class="property-field col-6">
                                            <label for="propertyMaxPrice">Max Price (₹) <span class="required-star">*</span></label>
                                            <input type="number" id="propertyMaxPrice" name="max_price" placeholder="e.g. 15000000" value="{{ old('max_price') }}">
                                            <div class="hint" style="font-size:11px; color:var(--text-gray); margin-top:4px;">Leave blank if not applicable</div>
                                        </div>
                                        <div class="property-field col-12" style="margin-top: 4px;">
                                            <h3 class="section-marker-title" style="font-size: 16px; margin-bottom: 6px;">Area Details</h3>
                                            <p class="section-marker-note" style="margin-bottom: 0;">Fill total area, or a min/max range - at least one is required.</p>
                                        </div>
                                        <div class="property-field col-4">
                                            <label for="propertyArea">Area (sqft)</label>
                                            <input type="number" id="propertyArea" name="area" placeholder="e.g. 1200" value="{{ old('area') }}">
                                        </div>
                                        <div class="property-field col-4">
                                            <label for="propertyMinArea">Min Area (sqft)</label>
                                            <input type="number" id="propertyMinArea" name="min_area" placeholder="e.g. 900" value="{{ old('min_area') }}">
                                        </div>
                                        <div class="property-field col-4">
                                            <label for="propertyMaxArea">Max Area (sqft)</label>
                                            <input type="number" id="propertyMaxArea" name="max_area" placeholder="e.g. 1800" value="{{ old('max_area') }}">
                                        </div>
                                        <div class="property-field col-12">
                                            <label for="propertyBrochure">Brochure <span class="required-star">*</span></label>
                                            <label class="upload-panel" for="propertyBrochure" id="brochureUploadArea" style="border: 2px dashed #dfe5ee; min-height: 100px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #f8fafc; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;">
                                                <span class="upload-title" style="font-size: 13px; font-weight: 700; color: var(--text-dark);">Upload property brochure (Mandatory)</span>
                                                <span class="upload-meta" style="font-size: 11px; color: var(--text-gray);">PDF format required up to 10MB</span>
                                            </label>
                                            <input type="file" id="propertyBrochure" name="brochure" accept=".pdf" hidden required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Section -->
                                <div class="property-form-card property-location-theme">
                                    <h3>Location Details</h3>
                                    <p>Country, state, city, location and pincode will auto fill after Address selected.</p>
                                    <div class="property-form-grid">
                                        <div class="property-field" style="grid-column: span 12;">
                                            <label for="propertyFullAddress">Full Address <span class="required-star">*</span></label>
                                            <input type="text" id="propertyFullAddress" name="full_address" placeholder="Select or enter full address to auto-fill location fields" value="{{ old('full_address') }}" required>
                                        </div>
                                        <div class="property-field col-3">
                                            <label for="propertyCountry">Country *</label>
                                            <select id="propertyCountry" name="country_id" required>
                                                <option value="">Select country</option>
                                                @foreach (($countries ?? []) as $country)
                                                    <option value="{{ $country->id }}" {{ (string) old('country_id') === (string) $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="property-field col-3">
                                            <label for="propertyState">State *</label>
                                            <select id="propertyState" name="state_id" required>
                                                <option value="">Select state</option>
                                                @foreach (($states ?? []) as $state)
                                                    <option value="{{ $state->id }}" data-country="{{ $state->country_id ?? '' }}" {{ (string) old('state_id') === (string) $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="property-field col-3">
                                            <label for="propertyCity">City *</label>
                                            <select id="propertyCity" name="city_id" required>
                                                <option value="">Select city</option>
                                                @foreach (($cities ?? []) as $city)
                                                    <option value="{{ $city->id }}" data-state="{{ $city->state_id ?? '' }}" {{ (string) old('city_id') === (string) $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="property-field col-3">
                                            <label for="propertyLocation">Location Area *</label>
                                            <select id="propertyLocation" name="property_place_id" required>
                                                <option value="">Select location</option>
                                                @foreach (($propertyPlaces ?? []) as $place)
                                                    <option value="{{ $place->id }}" data-country="{{ $place->country_id ?? '' }}" data-state="{{ $place->state_id ?? '' }}" data-city="{{ $place->city_id ?? '' }}" {{ (string) old('property_place_id') === (string) $place->id ? 'selected' : '' }}>{{ $place->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="property-field col-3">
                                            <label for="propertyPincode">Pincode *</label>
                                            <input type="text" id="propertyPincode" name="pincode" placeholder="Enter pincode" value="{{ old('pincode') }}" required>
                                        </div>
                                        <div class="property-field col-3">
                                            <label for="propertyLatitude">Latitude *</label>
                                            <input type="text" id="propertyLatitude" name="latitude" placeholder="e.g. 13.0827" value="{{ old('latitude') }}" required>
                                        </div>
                                        <div class="property-field col-3">
                                            <label for="propertyLongitude">Longitude *</label>
                                            <input type="text" id="propertyLongitude" name="longitude" placeholder="e.g. 80.2707" value="{{ old('longitude') }}" required>
                                        </div>
                                        <div class="property-field col-3">
                                            <label for="propertyMapSelected">Map Selected</label>
                                            <input type="text" id="propertyMapSelected" name="map_selected" placeholder="Map URL auto-fills from coordinates" value="{{ old('map_selected') }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <!-- Map Selection Card & Modal -->
                                <div class="property-form-card" style="grid-column: span 12;">
                                    <h3 class="section-marker-title">Map Selection</h3>
                                    <div class="map-select-wrap" style="border: 1px solid #dfe5ee; border-radius: 8px; min-height: 120px; background: #f8fafc; display: flex; align-items: center; justify-content: center; text-align: center;">
                                        <a id="propertyMapPreview" href="#" class="map-select-trigger" style="display:flex; flex-direction:column; align-items:center; gap:8px; text-decoration:none; color:var(--text-dark);" title="Open selected location on map">
                                            <span class="map-select-icon" style="width: 40px; height: 40px; border-radius: 50%; background: #eff6ff; color: #1877F2; display: flex; align-items: center; justify-content: center;">
                                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 21s7-4.35 7-11a7 7 0 1 0-14 0c0 6.65 7 11 7 11z"></path>
                                                    <circle cx="12" cy="10" r="2.5"></circle>
                                                </svg>
                                            </span>
                                            <span class="map-select-title" style="font-weight: 600;">Click to Pick Location on Google Maps</span>
                                            <span class="map-select-note" style="font-size: 11px; color: var(--text-gray);">Pin drops will auto-fill lat, long and address</span>
                                        </a>
                                    </div>
                                </div>

                                <div id="propertyMapPickerModal" class="map-picker-modal" aria-hidden="true" style="position: fixed; inset: 0; z-index: 9999; display: none; align-items: center; justify-content: center; background: rgba(0,0,0,0.5);">
                                    <div class="map-picker-card" role="dialog" aria-modal="true" aria-labelledby="mapPickerTitle" style="width: 90%; max-width: 800px; background: #fff; border-radius: 12px; overflow: hidden;">
                                        <div class="map-picker-head" style="padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                                            <h4 id="mapPickerTitle" class="map-picker-title" style="margin: 0; font-size: 16px; font-weight: 700; color: var(--text-dark);">Pick Property Location (Google Maps)</h4>
                                            <button type="button" id="mapPickerCloseBtn" class="map-picker-close" aria-label="Close map picker" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-gray);">&times;</button>
                                        </div>
                                        <div class="map-picker-search" style="padding: 12px 20px; border-bottom: 1px solid #eee;">
                                            <input type="text" id="propertyMapSearchInput" placeholder="Search location..." style="width: 100%; border: 1px solid #dfe5ee; padding: 10px 14px; border-radius: 8px; font-size: 13px;">
                                        </div>
                                        <div id="propertyGoogleMapCanvas" style="width: 100%; height: 400px; background: #e2e8f0;"></div>
                                        <div class="map-picker-foot" style="padding: 15px 20px; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                            <span id="mapPickerStatus" class="map-picker-status" style="font-size: 12px; color: var(--text-gray);">Click on map to place pin.</span>
                                            <div class="map-picker-actions" style="display: flex; gap: 10px;">
                                                <button type="button" id="mapPickerCancelBtn" class="map-picker-btn secondary" style="padding: 8px 16px; border-radius: 6px; border: 1px solid #dfe5ee; background: #fff; font-size: 13px; font-weight: 600; cursor: pointer;">Cancel</button>
                                                <button type="button" id="mapPickerUseBtn" class="map-picker-btn primary" style="padding: 8px 16px; border-radius: 6px; border: none; background: #1877F2; color: #fff; font-size: 13px; font-weight: 600; cursor: pointer;">Use This Location</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content Section -->
                                <div class="property-form-card property-content-theme">
                                    <h3>Content & RERA</h3>
                                    <div class="property-form-grid">
                                        <div class="property-field" style="grid-column: span 12;">
                                            <label for="propertyOverview">Overview <span class="required-star">*</span></label>
                                            <textarea id="propertyOverview" name="overview" placeholder="Write a compelling overview..." required style="min-height: 100px;">{{ old('overview') }}</textarea>
                                        </div>
                                        <div class="property-field" style="grid-column: span 12;">
                                            <label for="propertyHighlights">Highlights <span class="required-star">*</span></label>
                                            <div class="property-rich-toolbar" style="margin-bottom: 5px; display: flex; gap: 8px; font-size: 11px; color: var(--text-gray); cursor: pointer; user-select: none;">
                                                <span title="Bold" style="font-weight:700; padding:2px 6px; border:1px solid #dfe5ee; border-radius:4px; background:#f8fafc;">B</span>
                                                <span title="Underline" style="text-decoration:underline; padding:2px 6px; border:1px solid #dfe5ee; border-radius:4px; background:#f8fafc;">U</span>
                                                <span title="Italic" style="font-style:italic; padding:2px 6px; border:1px solid #dfe5ee; border-radius:4px; background:#f8fafc;">I</span>
                                            </div>
                                            <textarea id="propertyHighlights" name="highlights" placeholder="Enter highlights here..." required style="min-height: 120px;">{{ old('highlights') }}</textarea>
                                        </div>
                                        <div class="property-field" style="grid-column: span 12;">
                                            <label for="propertyAboutProject">About Project <span class="required-star">*</span></label>
                                            <textarea id="propertyAboutProject" name="about_project" placeholder="Detailed project and builder info..." required style="min-height: 100px;">{{ old('about_project') }}</textarea>
                                        </div>
                                        <div class="property-field" style="grid-column: span 12;">
                                            <label for="propertyReraNumber">Rera Number</label>
                                            <input type="text" id="propertyReraNumber" name="rera_number" placeholder="Enter RERA registration number" value="{{ old('rera_number') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- FAQs Section -->
                                <div class="property-form-card property-faq-theme">
                                    <h3>Frequently Asked Questions</h3>
                                    <p>Add up to 25 FAQs for this project.</p>
                                    @php
                                        $faqRows = old('faqs', [['question' => '', 'answer' => '']]);
                                    @endphp
                                    <div id="propertyFaqList">
                                        @foreach ($faqRows as $faqIndex => $faqRow)
                                            <div class="faq-item" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 12px; margin-bottom: 12px;">
                                                <div class="property-field"><label>Question {{ $faqIndex + 1 }}</label><input type="text" name="faqs[{{ $faqIndex }}][question]" value="{{ $faqRow['question'] ?? '' }}" placeholder="Enter question"></div>
                                                <div class="property-field"><label>Answer {{ $faqIndex + 1 }}</label><input type="text" name="faqs[{{ $faqIndex }}][answer]" value="{{ $faqRow['answer'] ?? '' }}" placeholder="Enter answer"></div>
                                                <button type="button" class="faq-remove-btn" style="padding: 0 18px; background: #fee2e2; color: #ef4444; border: none; border-radius: 4px; cursor: pointer; transition: all 0.2s ease; height: 42px; margin-top: 25px; {{ $faqIndex === 0 ? 'visibility:hidden;' : '' }}">Remove</button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="faq-add-btn" id="addPropertyFaqBtn" style="border: none; border-radius: 8px; cursor: pointer; font-weight: 700; transition: all 0.3s ease; padding: 10px 14px; background: #1d73d8; color: #fff;">+ Add FAQ</button>
                                </div>
                            </div>

                            <div style="text-align:center; margin-top:30px; display:flex; justify-content:center; gap:16px;">
                                <button type="submit" class="property-save-btn">Submit for Approval</button>
                            </div>
                        </form>
                    </div>

                @elseif ($currentSection === 'change-password')
               <h1 class="welcome-title">Change Password</h1>
                    <div class="form-container">
                        <form action="{{ route('vendor.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label>Current Password</label>
                                <input type="password" name="current_password" class="form-control" required>
                                @error('current_password')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="password" class="form-control" required>
                                @error('password')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn-primary">Change Password</button>
                        </form>
                    </div>
                    
                @endif
            </div>
    
            <!-- Footer removed from sticky to static at bottom or keep if long enough -->
        </div>
    </div>

    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            dropdown.classList.toggle('show');
        }
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }

        function toggleAvatarDropdown(event) {
            if (event) {
                event.stopPropagation();
            }
            document.getElementById('avatarDropdown').classList.toggle('show');
        }

        // Close dropdowns if clicked outside
        document.addEventListener('click', function(event) {
            var avatar = document.querySelector('.vendor-avatar');
            var dropdown = document.getElementById('avatarDropdown');
            if (avatar && dropdown && !avatar.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Manage Properties Filtering Logic
        const propertySearch = document.getElementById('managePropertySearch');
        const propertyTypeFilter = document.getElementById('managePropertyTypeFilter');
        const propertyRows = document.querySelectorAll('.manage-property-row');

        if (propertySearch && propertyTypeFilter) {
            const filterProperties = () => {
                const term = propertySearch.value.toLowerCase();
                const type = propertyTypeFilter.value;

                propertyRows.forEach(row => {
                    const title = row.dataset.title || '';
                    const area = row.dataset.area || '';
                    const rowType = row.dataset.type || '';
                    
                    const matchesSearch = title.includes(term) || area.includes(term);
                    const matchesType = type === 'all' || rowType === type;

                    if (matchesSearch && matchesType) {
                        row.style.display = 'table-row';
                    } else {
                        row.style.display = 'none';
                    }
                });
            };

            propertySearch.addEventListener('input', filterProperties);
            propertyTypeFilter.addEventListener('change', filterProperties);
        }


        // Property Form Logic
        document.addEventListener('DOMContentLoaded', function() {
            const googleMapsApiKey = @json(config('services.google_maps.key'));
            
            const propertyFullAddress = document.getElementById('propertyFullAddress');
            const propertyCountry = document.getElementById('propertyCountry');
            const propertyState = document.getElementById('propertyState');
            const propertyCity = document.getElementById('propertyCity');
            const propertyLocation = document.getElementById('propertyLocation');
            const propertyPincode = document.getElementById('propertyPincode');
            const propertyLatitude = document.getElementById('propertyLatitude');
            const propertyLongitude = document.getElementById('propertyLongitude');
            const propertyMapPreview = document.getElementById('propertyMapPreview');
            const propertyMapPickerModal = document.getElementById('propertyMapPickerModal');
            const propertyGoogleMapCanvas = document.getElementById('propertyGoogleMapCanvas');
            const propertyMapSearchInput = document.getElementById('propertyMapSearchInput');
            const mapPickerCloseBtn = document.getElementById('mapPickerCloseBtn');
            const mapPickerCancelBtn = document.getElementById('mapPickerCancelBtn');
            const mapPickerUseBtn = document.getElementById('mapPickerUseBtn');

            if (propertyFullAddress) {
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
                    if (!wanted) return false;

                    const options = Array.from(select.options).filter((_, index) => index > 0);
                    const exactMatch = options.find(opt => normalizeText(opt.text) === wanted);
                    if (exactMatch) {
                        select.value = exactMatch.value;
                        select.dispatchEvent(new Event('change', { bubbles: true }));
                        return true;
                    }

                    if (!fuzzy) return false;

                    const wantedTokens = tokenize(wanted);
                    let best = null;
                    let bestScore = 0;

                    options.forEach(opt => {
                        const optText = normalizeText(opt.text);
                        const optTokens = tokenize(optText);
                        let score = 0;

                        if (optText.includes(wanted) || wanted.includes(optText)) score += 3;
                        wantedTokens.forEach(t => {
                            if (optTokens.includes(t)) score += 2;
                            else if (optTokens.some(ot => ot.startsWith(t) || t.startsWith(ot))) score += 1;
                        });

                        const gap = Math.abs(optText.length - wanted.length);
                        score -= Math.min(gap, 20) * 0.03;

                        if (score > bestScore) {
                            bestScore = score;
                            best = opt;
                        }
                    });

                    if (best && bestScore >= 2) {
                        select.value = best.value;
                        select.dispatchEvent(new Event('change', { bubbles: true }));
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
                    // propertyFullAddress.value = parts.join(', '); // We only update if explicitly wanted
                };

                const geocodeWithNominatim = async (address) => {
                    const endpoint = `https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&addressdetails=1&q=${encodeURIComponent(address)}`;
                    const response = await fetch(endpoint, { headers: { 'Accept': 'application/json' } });
                    if (!response.ok) throw new Error('Nominatim geocoding failed');
                    const res = await response.json();
                    return Array.isArray(res) && res[0] ? res[0] : null;
                };

                const applyNominatimToAddressFields = (result) => {
                    if (!result) return;
                    const addr = result.address || {};
                    const country = addr.country || '';
                    const state = addr.state || addr.state_district || '';
                    const city = addr.city || addr.town || addr.village || addr.county || '';
                    const pin = addr.postcode || '';
                    const loc = addr.suburb || addr.neighbourhood || addr.quarter || addr.road || '';

                    if (country && setSelectByLabel(propertyCountry, country)) syncStates();
                    if (state && setSelectByLabel(propertyState, state)) syncCities();
                    if (city && setSelectByLabel(propertyCity, city)) syncLocations();
                    if (loc) setSelectByLabel(propertyLocation, loc);
                    if (pin) propertyPincode.value = pin;
                };

                const applyAddressToFields = async (forceGeocode = false) => {
                    const raw = propertyFullAddress.value.trim();
                    if (!raw) return;

                    const shouldGeocode = forceGeocode || (raw.length >= 8 && normalizeText(raw) !== lastAutoGeocodedAddress);

                    if (shouldGeocode && googleMapsApiKey) {
                        try {
                            const mapsApi = await loadGoogleMapsApi();
                            if (!mapPickerState.geocoder) mapPickerState.geocoder = new mapsApi.Geocoder();

                            mapPickerState.geocoder.geocode({ address: raw }, async (results, status) => {
                                if (status === 'OK' && results?.[0]) {
                                    const result = results[0];
                                    if (result.geometry?.location) {
                                        propertyLatitude.value = Number(result.geometry.location.lat()).toFixed(6);
                                        propertyLongitude.value = Number(result.geometry.location.lng()).toFixed(6);
                                    }
                                    applyGeocodeToAddressFields(result);
                                    lastAutoGeocodedAddress = normalizeText(raw);
                                    return;
                                }
                                try {
                                    const fb = await geocodeWithNominatim(raw);
                                    if (fb) {
                                        propertyLatitude.value = Number(fb.lat).toFixed(6);
                                        propertyLongitude.value = Number(fb.lon).toFixed(6);
                                        applyNominatimToAddressFields(fb);
                                        lastAutoGeocodedAddress = normalizeText(raw);
                                    }
                                } catch (_) {}
                            });
                            return;
                        } catch (e) { console.error('Geocoding failed:', e); }
                    }

                    if (shouldGeocode) {
                        try {
                            const fb = await geocodeWithNominatim(raw);
                            if (fb) {
                                propertyLatitude.value = Number(fb.lat).toFixed(6);
                                propertyLongitude.value = Number(fb.lon).toFixed(6);
                                applyNominatimToAddressFields(fb);
                                lastAutoGeocodedAddress = normalizeText(raw);
                                return;
                            }
                        } catch (_) {}
                    }

                    // Fallback parsing
                    const parts = raw.split(',').map(p => p.trim()).filter(Boolean);
                    if (parts.length < 4) return;
                    const pin = parts[parts.length - 1] || '';
                    const country = parts[parts.length - 2] || '';
                    const state = parts[parts.length - 3] || '';
                    const city = parts[parts.length - 4] || '';
                    const loc = parts.slice(0, parts.length - 4).join(', ');

                    if (/^\d{4,10}$/.test(pin) && propertyPincode) propertyPincode.value = pin;
                    if (setSelectByLabel(propertyCountry, country)) syncStates();
                    if (setSelectByLabel(propertyState, state)) syncCities();
                    if (setSelectByLabel(propertyCity, city)) syncLocations();
                    setSelectByLabel(propertyLocation, loc);
                };

                const loadGoogleMapsApi = () => {
                    if (window.google?.maps) return Promise.resolve(window.google.maps);
                    if (!googleMapsApiKey) return Promise.reject(new Error('API key missing'));
                    if (mapPickerState.scriptPromise) return mapPickerState.scriptPromise;
                    mapPickerState.scriptPromise = new Promise((resolve, reject) => {
                        const script = document.createElement('script');
                        script.src = `https://maps.googleapis.com/maps/api/js?key=${encodeURIComponent(googleMapsApiKey)}&libraries=places`;
                        script.async = true; script.defer = true;
                        script.onload = () => resolve(window.google.maps);
                        script.onerror = () => reject(new Error('Load failed'));
                        document.head.appendChild(script);
                    });
                    return mapPickerState.scriptPromise;
                };

                const setupAddressAutocomplete = async () => {
                    if (!googleMapsApiKey || !propertyFullAddress || mapPickerState.addressAutocomplete) return;
                    try {
                        const mapsApi = await loadGoogleMapsApi();
                        mapPickerState.addressAutocomplete = new mapsApi.places.Autocomplete(propertyFullAddress, { types: [] });
                        mapPickerState.addressAutocomplete.addListener('place_changed', () => {
                            const place = mapPickerState.addressAutocomplete.getPlace();
                            if (!place) return;
                            if (place.formatted_address) propertyFullAddress.value = place.formatted_address;
                            if (place.geometry?.location) {
                                propertyLatitude.value = Number(place.geometry.location.lat()).toFixed(6);
                                propertyLongitude.value = Number(place.geometry.location.lng()).toFixed(6);
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
                    } catch (_) {}
                };

                const applyGeocodeToAddressFields = (result) => {
                    if (!result) return;
                    const findC = (t) => result.address_components?.find(c => (c.types || []).includes(t));
                    const country = findC('country')?.long_name || '';
                    const state = findC('administrative_area_level_1')?.long_name || '';
                    const city = findC('locality')?.long_name || findC('administrative_area_level_2')?.long_name || '';
                    const pin = findC('postal_code')?.long_name || '';
                    const loc = findC('sublocality_level_1')?.long_name || findC('sublocality')?.long_name || findC('neighborhood')?.long_name || findC('route')?.long_name || '';

                    if (country && setSelectByLabel(propertyCountry, country)) syncStates();
                    if (state && setSelectByLabel(propertyState, state)) syncCities();
                    if (city && setSelectByLabel(propertyCity, city)) syncLocations();
                    if (loc) setSelectByLabel(propertyLocation, loc);
                    if (pin) propertyPincode.value = pin;
                };

                const setMapMarkerAndCoords = (latLng, mapsApi, doGeocode = true) => {
                    if (!latLng || !mapsApi || !mapPickerState.map) return;
                    if (!mapPickerState.marker) {
                        mapPickerState.marker = new mapsApi.Marker({ position: latLng, map: mapPickerState.map });
                    } else {
                        mapPickerState.marker.setPosition(latLng);
                    }
                    mapPickerState.map.panTo(latLng);
                    propertyLatitude.value = Number(latLng.lat()).toFixed(6);
                    propertyLongitude.value = Number(latLng.lng()).toFixed(6);
                    if (doGeocode && mapPickerState.geocoder) {
                        mapPickerState.geocoder.geocode({ location: latLng }, (res, status) => {
                            if (status === 'OK' && res?.[0]) applyGeocodeToAddressFields(res[0]);
                        });
                    }
                };

                const closeMapPicker = () => { propertyMapPickerModal?.classList.remove('is-open'); };

                const openMapPicker = async () => {
                    if (!propertyMapPickerModal || !propertyGoogleMapCanvas) return;
                    try {
                        const mapsApi = await loadGoogleMapsApi();
                        propertyMapPickerModal.classList.add('is-open');
                        const hasCoords = propertyLatitude.value.trim() && propertyLongitude.value.trim();
                        const initialLat = hasCoords ? Number(propertyLatitude.value) : 20.5937;
                        const initialLng = hasCoords ? Number(propertyLongitude.value) : 78.9629;
                        const initialLatLng = new mapsApi.LatLng(initialLat, initialLng);

                        if (!mapPickerState.map) {
                            mapPickerState.map = new mapsApi.Map(propertyGoogleMapCanvas, {
                                center: initialLatLng, zoom: hasCoords ? 15 : 5,
                                mapTypeControl: false, streetViewControl: false,
                            });
                            mapPickerState.geocoder = new mapsApi.Geocoder();
                            mapPickerState.map.addListener('click', (e) => {
                                if (e.latLng) setMapMarkerAndCoords(e.latLng, mapsApi, true);
                            });
                            if (propertyMapSearchInput) {
                                mapPickerState.autocomplete = new mapsApi.places.Autocomplete(propertyMapSearchInput);
                                mapPickerState.autocomplete.bindTo('bounds', mapPickerState.map);
                                mapPickerState.autocomplete.addListener('place_changed', () => {
                                    const place = mapPickerState.autocomplete.getPlace();
                                    if (!place.geometry) return;
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
                        }
                        setMapMarkerAndCoords(initialLatLng, mapsApi, false);
                    } catch (e) { alert('Google Maps is not configured.'); }
                };

                const syncStates = () => {
                    const countryId = propertyCountry.value;
                    toggleOptions(propertyState, opt => !countryId || opt.dataset.country === countryId);
                    if (propertyState.selectedOptions[0]?.hidden) propertyState.value = '';
                    syncCities();
                };

                const syncCities = () => {
                    const stateId = propertyState.value;
                    toggleOptions(propertyCity, opt => !stateId || opt.dataset.state === stateId);
                    if (propertyCity.selectedOptions[0]?.hidden) propertyCity.value = '';
                    syncLocations();
                };

                const syncLocations = () => {
                    const cityId = propertyCity.value;
                    toggleOptions(propertyLocation, opt => !cityId || opt.dataset.city === cityId);
                    if (propertyLocation.selectedOptions[0]?.hidden) propertyLocation.value = '';
                };

                propertyCountry.addEventListener('change', syncStates);
                propertyState.addEventListener('change', syncCities);
                propertyCity.addEventListener('change', syncLocations);
                
                propertyFullAddress.addEventListener('input', () => {
                    if (addressGeocodeTimer) clearTimeout(addressGeocodeTimer);
                    addressGeocodeTimer = setTimeout(() => applyAddressToFields(), 800);
                });
                propertyFullAddress.addEventListener('blur', () => applyAddressToFields(true));
                propertyFullAddress.addEventListener('focus', setupAddressAutocomplete, { once: true });
                
                propertyMapPreview?.addEventListener('click', (e) => { e.preventDefault(); openMapPicker(); });
                mapPickerCloseBtn?.addEventListener('click', closeMapPicker);
                mapPickerCancelBtn?.addEventListener('click', closeMapPicker);
                mapPickerUseBtn?.addEventListener('click', closeMapPicker);

                syncStates();
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
                    if (!select) return;
                    
                    // Avoid binding old handler for custom fields
                    if (select.id === 'propertyAmenitiesSelect' || select.id === 'propertyBhk') return;

                    const displayId = select.id === 'propertyTopPicks' ? 'selectedTopPicksDisplay' : 
                                     (select.id === 'propertyBhk' ? 'selectedBhkDisplay' : 'selectedAmenitiesDisplay');

                    updateSelectedDisplay(select, displayId);

                    if (searchInput) {
                        searchInput.addEventListener('focus', () => wrapper.classList.add('is-open'));
                        document.addEventListener('click', (e) => {
                            if (!wrapper.contains(e.target)) wrapper.classList.remove('is-open');
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
                    }

                    select.addEventListener('change', () => updateSelectedDisplay(select, displayId));
                    select.addEventListener('mousedown', function(e) {
                        e.preventDefault();
                        const option = e.target;
                        if (option.tagName === 'OPTION') {
                            option.selected = !option.selected;
                            this.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    });
                });

                // Custom BHK Toggle Group Logic
                const bhkToggleButtons = document.querySelectorAll('.bhk-toggle-btn');
                const bhkSelect = document.getElementById('propertyBhk');
                if (bhkToggleButtons.length > 0 && bhkSelect) {
                    bhkToggleButtons.forEach(btn => {
                        btn.addEventListener('click', (e) => {
                            e.preventDefault();
                            btn.classList.toggle('active');
                            const val = btn.dataset.value;
                            const option = Array.from(bhkSelect.options).find(opt => opt.value === val);
                            if (option) {
                                option.selected = btn.classList.contains('active');
                                bhkSelect.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        });
                    });
                }

                // Custom Amenities Dropdown Checklist Logic
                const amenitiesWrapper = document.getElementById('amenitiesWrapper');
                const amenitySearch = document.getElementById('propertyAmenitySearch');
                const amenitiesDropdownList = document.getElementById('amenitiesDropdownList');
                const propertyAmenitiesSelect = document.getElementById('propertyAmenitiesSelect');
                const selectedAmenitiesDisplay = document.getElementById('selectedAmenitiesDisplay');

                if (amenitiesWrapper && amenitySearch && amenitiesDropdownList && propertyAmenitiesSelect) {
                    // Show dropdown on focus
                    amenitySearch.addEventListener('focus', () => {
                        amenitiesWrapper.classList.add('is-open');
                    });

                    // Hide dropdown when clicking outside
                    document.addEventListener('click', (e) => {
                        if (!amenitiesWrapper.contains(e.target)) {
                            amenitiesWrapper.classList.remove('is-open');
                        }
                    });

                    // Search filter
                    amenitySearch.addEventListener('input', () => {
                        amenitiesWrapper.classList.add('is-open');
                        const term = amenitySearch.value.trim().toLowerCase();
                        const items = amenitiesDropdownList.querySelectorAll('.amenities-dropdown-item');
                        items.forEach(item => {
                            const name = item.dataset.name;
                            if (name.includes(term)) {
                                item.style.display = 'flex';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    });

                    // Update chips display
                    const updateAmenitiesChips = () => {
                        selectedAmenitiesDisplay.innerHTML = '';
                        const checkedItems = amenitiesDropdownList.querySelectorAll('.amenities-dropdown-item input[type="checkbox"]:checked');
                        
                        checkedItems.forEach(chk => {
                            const id = chk.value;
                            const labelText = chk.nextElementSibling.innerText;
                            
                            const chip = document.createElement('span');
                            chip.className = 'choice-chip';
                            chip.innerHTML = `${labelText} <span class="remove-chip" data-id="${id}" title="Remove">&times;</span>`;
                            
                            chip.querySelector('.remove-chip').onclick = (e) => {
                                e.stopPropagation();
                                chk.checked = false;
                                // Trigger change event
                                chk.dispatchEvent(new Event('change', { bubbles: true }));
                            };
                            
                            selectedAmenitiesDisplay.appendChild(chip);
                        });
                        
                        // Sync options of hidden select
                        Array.from(propertyAmenitiesSelect.options).forEach(opt => {
                            const checkbox = document.getElementById(`amenity_check_${opt.value}`);
                            if (checkbox) {
                                opt.selected = checkbox.checked;
                            }
                        });
                    };

                    // Listen for checkbox changes (direct clicks or custom changes)
                    amenitiesDropdownList.querySelectorAll('.amenities-dropdown-item input[type="checkbox"]').forEach(chk => {
                        chk.addEventListener('change', () => {
                            updateAmenitiesChips();
                        });
                    });

                    // Make clicking the row toggle the checkbox
                    amenitiesDropdownList.querySelectorAll('.amenities-dropdown-item').forEach(item => {
                        item.addEventListener('click', (e) => {
                            if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'LABEL') {
                                const chk = item.querySelector('input[type="checkbox"]');
                                if (chk) {
                                    chk.checked = !chk.checked;
                                    chk.dispatchEvent(new Event('change', { bubbles: true }));
                                }
                            }
                        });
                    });

                    // Initialize chips on page load
                    updateAmenitiesChips();
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
                            if (displayImageInput) displayImageInput.value = '';
                            displayImageUploadArea.innerHTML = displayPlaceholderHTML;
                        });
                    }
                };

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

                // Gallery preview & syncing
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

                // Floor Plan preview & syncing
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
                    <span class="upload-title" style="font-size: 13px; font-weight: 700; color: var(--text-dark);">Upload property brochure (Mandatory)</span>
                    <span class="upload-meta" style="font-size: 11px; color: var(--text-gray);">PDF format required up to 10MB</span>
                `;

                const bindRemoveBrochureEvent = (btnId) => {
                    const btn = document.getElementById(btnId);
                    if (btn) {
                        btn.addEventListener('click', function(ev) {
                            ev.preventDefault();
                            ev.stopPropagation();
                            if (brochureInput) brochureInput.value = '';
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

                // FAQ addition and subtraction
                const propertyFaqList = document.getElementById('propertyFaqList');
                const addPropertyFaqBtn = document.getElementById('addPropertyFaqBtn');
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
                        wrapper.style = 'display: grid; grid-template-columns: 1fr 1fr auto; gap: 12px; margin-bottom: 12px;';
                        wrapper.innerHTML = `
                            <div class="property-field">
                                <label>Question ${index + 1}</label>
                                <input type="text" name="faqs[${index}][question]" placeholder="Enter question">
                            </div>
                            <div class="property-field">
                                <label>Answer ${index + 1}</label>
                                <input type="text" name="faqs[${index}][answer]" placeholder="Enter answer">
                            </div>
                            <button type="button" class="faq-remove-btn" style="padding: 0 18px; background: #fee2e2; color: #ef4444; border: none; border-radius: 4px; cursor: pointer; transition: all 0.2s ease; height: 42px; margin-top: 25px;">Remove</button>
                        `;

                        propertyFaqList.appendChild(wrapper);
                        bindFaqRemove(wrapper.querySelector('.faq-remove-btn'));
                        faqCount = propertyFaqList.querySelectorAll('.faq-item').length;
                    });
                }
            }
        });
    </script>
</body>
</html>


