<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Portal - Bandari Tech & Innovation Club</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap"
        rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        :root {
            --bg-deep: #050812;
            --surface: rgba(13, 24, 45, 0.6);
            --surface-hover: rgba(18, 32, 60, 0.8);
            --accent: #00f2fe;
            --accent-glow: rgba(0, 242, 254, 0.2);
            --teal: #00c9a7;
            --sky: #38bdf8;
            --amber: #f5a623;
            --error: #ff4d4f;
            --white: #f0f6ff;
            --muted: #94a3b8;
            --border: rgba(255, 255, 255, 0.06);
            --glass-border: rgba(255, 255, 255, 0.08);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            --sidebar-width: 270px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--bg-deep);
            color: var(--white);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Premium Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-deep);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--muted);
        }

        /* Global Layout */
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 2.5rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
        }

        /* Glass Components */
        .glass-card {
            background: var(--surface);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .glass-card:hover {
            border-color: rgba(0, 242, 254, 0.3);
        }

        /* Typography & Headings */
        h1,
        h2,
        h3,
        .logo-text {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
        }

        .section-title {
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .section-subtitle {
            color: var(--muted);
            margin-bottom: 2.5rem;
            font-size: 1rem;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.8rem 1.8rem;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--teal));
            color: #050812;
            box-shadow: 0 4px 15px var(--accent-glow);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px var(--accent-glow);
            filter: brightness(1.1);
        }

        .btn-secondary {
            background: var(--border);
            color: var(--white);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        /* Forms */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.75rem;
            color: var(--muted);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 0.9rem 1.2rem;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--white);
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent);
            scale: 1.01;
            background: rgba(0, 0, 0, 0.3);
        }

        /* Tables */
        .premium-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.75rem;
            margin-top: 1rem;
        }

        .premium-table th {
            padding: 1.25rem 1rem;
            text-align: left;
            color: var(--muted);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .premium-table td {
            padding: 1.25rem 1rem;
            background: rgba(255, 255, 255, 0.02);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .premium-table tr td:first-child {
            border-left: 1px solid var(--border);
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        .premium-table tr td:last-child {
            border-right: 1px solid var(--border);
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        .premium-table tr:hover td {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(0, 242, 254, 0.2);
        }

        /* Badges */
        .badge {
            font-size: 0.75rem;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
            background: var(--border);
            color: var(--muted);
        }

        .badge-accent {
            background: rgba(0, 242, 254, 0.1);
            color: var(--accent);
        }

        .badge-success {
            background: rgba(0, 201, 167, 0.1);
            color: var(--teal);
        }

        /* Sidebar Toggle Overlay */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            z-index: 999;
            display: none;
            opacity: 0;
            transition: all 0.4s ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Mobile Admin Header */
        .mobile-admin-header {
            display: none;
            background: rgba(5, 8, 18, 0.8);
            backdrop-filter: blur(20px);
            padding: 1.25rem;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 900;
            align-items: center;
            justify-content: space-between;
        }

        @media (max-width: 1024px) {
            .main-content {
                margin-left: 0;
                padding: 1.5rem;
            }

            .mobile-admin-header {
                display: flex;
            }
        }

        /* Background Bloom */
        .bg-bloom {
            position: fixed;
            width: 60vw;
            height: 60vw;
            background: radial-gradient(circle, var(--accent-glow) 0%, transparent 70%);
            top: -30vw;
            right: -30vw;
            z-index: 0;
            pointer-events: none;
            filter: blur(100px);
        }
    </style>
</head>

<body>
    <div class="bg-bloom"></div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="mobile-admin-header">
        <div class="logo-text" style="font-size: 1.4rem;">
            <i class="ri-anchor-line" style="color:var(--accent)"></i> BTIC<span style="color:var(--accent)">.</span>
        </div>
        <button id="sidebarToggle"
            style="background:none; border:none; color:var(--white); font-size: 1.8rem; cursor:pointer;">
            <i class="ri-menu-5-line"></i>
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (toggle && sidebar) {
                toggle.addEventListener('click', () => {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                    document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
                });

                overlay.addEventListener('click', () => {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                });

                // Auto-close on resize
                window.addEventListener('resize', () => {
                    if (window.innerWidth > 1024) {
                        sidebar.classList.remove('active');
                        overlay.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            }
        });
    </script>