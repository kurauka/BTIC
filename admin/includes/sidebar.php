<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
        .sidebar {
                width: var(--sidebar-width);
                background: rgba(13, 24, 45, 0.85);
                backdrop-filter: blur(25px);
                border-right: 1px solid var(--border);
                padding: 2.5rem 1.5rem;
                display: flex;
                flex-direction: column;
                position: fixed;
                height: 100vh;
                z-index: 1010;
                transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar .logo-area {
                margin-bottom: 3rem;
                padding-left: 1rem;
        }

        .sidebar .logo-area .logo-text {
                font-size: 1.8rem;
                letter-spacing: -0.04em;
        }

        .sidebar .menu-label {
                font-size: 0.7rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.15em;
                color: rgba(255, 255, 255, 0.25);
                margin: 2rem 0 1rem 1rem;
        }

        .sidebar .nav-link {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 0.9rem 1.2rem;
                color: var(--muted);
                text-decoration: none;
                border-radius: 14px;
                font-weight: 600;
                font-size: 0.95rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                margin-bottom: 0.4rem;
                position: relative;
                overflow: hidden;
        }

        .sidebar .nav-link i {
                font-size: 1.2rem;
                transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
                color: var(--white);
                background: rgba(255, 255, 255, 0.03);
        }

        .sidebar .nav-link.active {
                color: var(--accent);
                background: rgba(0, 242, 254, 0.08);
                box-shadow: inset 0 0 20px rgba(0, 242, 254, 0.05);
        }

        .sidebar .nav-link.active i {
                color: var(--accent);
                transform: scale(1.1);
        }

        .sidebar .nav-link.active::before {
                content: '';
                position: absolute;
                left: 0;
                top: 25%;
                height: 50%;
                width: 4px;
                background: var(--accent);
                border-radius: 0 4px 4px 0;
                box-shadow: 0 0 15px var(--accent);
        }

        .logout-area {
                margin-top: auto;
                padding-top: 2rem;
        }

        .btn-logout {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 1rem 1.2rem;
                color: #ff6b6b;
                text-decoration: none;
                border-radius: 14px;
                font-weight: 700;
                transition: all 0.3s ease;
                background: rgba(255, 107, 107, 0.05);
        }

        .btn-logout:hover {
                background: rgba(255, 107, 107, 0.15);
                transform: translateX(5px);
        }

        @media (max-width: 1024px) {
                .sidebar {
                        transform: translateX(-100%);
                }

                .sidebar.active {
                        transform: translateX(0);
                }
        }
</style>

<aside class="sidebar" id="adminSidebar">
        <div class="logo-area">
                <div class="logo-text">
                        <i class="ri-anchor-line" style="color:var(--accent)"></i> BTIC<span
                                style="color:var(--accent)">.</span>
                </div>
        </div>

        <div class="menu-label">Main Console</div>
        <a href="index.php" class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                <i class="ri-dashboard-3-line"></i> Dashboard
        </a>
        <a href="projects.php" class="nav-link <?php echo $current_page == 'projects.php' ? 'active' : ''; ?>">
                <i class="ri-rocket-2-line"></i> Projects
        </a>
        <a href="events.php" class="nav-link <?php echo $current_page == 'events.php' ? 'active' : ''; ?>">
                <i class="ri-calendar-event-line"></i> Events
        </a>
        <a href="programs.php" class="nav-link <?php echo $current_page == 'programs.php' ? 'active' : ''; ?>">
                <i class="ri-code-s-slash-line"></i> Programs
        </a>
        <a href="messages.php" class="nav-link <?php echo $current_page == 'messages.php' ? 'active' : ''; ?>">
                <i class="ri-chat-smile-3-line"></i> Messages
        </a>

        <div class="menu-label">Management</div>
        <a href="organizers.php" class="nav-link <?php echo $current_page == 'organizers.php' ? 'active' : ''; ?>">
                <i class="ri-group-line"></i> Organizers
        </a>
        <a href="partners.php" class="nav-link <?php echo $current_page == 'partners.php' ? 'active' : ''; ?>">
                <i class="ri-shake-hands-line"></i> Partners
        </a>

        <div class="menu-label">Preferences</div>
        <a href="settings.php" class="nav-link <?php echo $current_page == 'settings.php' ? 'active' : ''; ?>">
                <i class="ri-settings-4-line"></i> System Settings
        </a>

        <div class="logout-area">
                <a href="logout.php" class="btn-logout">
                        <i class="ri-logout-circle-r-line"></i> Sign Out
                </a>
        </div>
</aside>