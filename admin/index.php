<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include 'includes/header.php';
?>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <header class="page-header" style="margin-bottom: 3rem;">
            <h1 class="section-title">Club Overview</h1>
            <p class="section-subtitle">Welcome back, <span
                    style="color: var(--accent); font-weight: 600;"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>.
                Here's what's happening today.</p>
        </header>

        <?php
        include '../db_connect.php';

        // Fetch Stats
        $stats_projects = $conn->query("SELECT COUNT(*) FROM projects")->fetchColumn();
        $stats_programs = $conn->query("SELECT COUNT(*) FROM programs")->fetchColumn();
        $stats_events = $conn->query("SELECT COUNT(*) FROM events")->fetchColumn();
        $stats_messages = $conn->query("SELECT COUNT(*) FROM messages WHERE is_read = 0")->fetchColumn();

        // Recent Messages
        $recent_msgs = $conn->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

        // Upcoming Events
        $upcoming_events = $conn->query("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <style>
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 1.5rem;
                margin-bottom: 3rem;
            }

            .stat-card {
                padding: 2rem;
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .stat-value {
                font-size: 2.8rem;
                font-weight: 800;
                font-family: 'Syne', sans-serif;
                background: linear-gradient(135deg, #fff, var(--muted));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .stat-label {
                color: var(--muted);
                text-transform: uppercase;
                letter-spacing: 0.1em;
                font-size: 0.8rem;
                font-weight: 700;
            }

            .content-grid {
                display: grid;
                grid-template-columns: 1.8fr 1fr;
                gap: 2rem;
            }

            @media (max-width: 1200px) {
                .content-grid {
                    grid-template-columns: 1fr;
                }
            }

            .event-card {
                display: flex;
                gap: 1.25rem;
                padding: 1.25rem;
                background: rgba(255, 255, 255, 0.02);
                border-radius: 16px;
                margin-bottom: 1rem;
                transition: all 0.3s ease;
            }

            .event-card:hover {
                background: rgba(255, 255, 255, 0.04);
                transform: translateX(5px);
            }

            .event-date-box {
                min-width: 65px;
                height: 65px;
                background: var(--accent-glow);
                border-radius: 12px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                border: 1px solid var(--accent);
            }

            .ed-day {
                font-size: 1.4rem;
                font-weight: 800;
                color: var(--accent);
                line-height: 1;
            }

            .ed-month {
                font-size: 0.7rem;
                font-weight: 700;
                text-transform: uppercase;
                opacity: 0.8;
            }

            /* Quick Action Grid */
            .qa-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
            }

            .qa-card {
                padding: 1.25rem;
                text-align: center;
                text-decoration: none;
                color: var(--white);
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.75rem;
                background: rgba(255, 255, 255, 0.02);
                border: 1px solid var(--border);
                border-radius: 16px;
                transition: all 0.3s ease;
            }

            .qa-card i {
                font-size: 1.5rem;
                color: var(--accent);
            }

            .qa-card:hover {
                background: var(--surface-hover);
                border-color: var(--accent);
                transform: translateY(-3px);
            }

            @media (max-width: 600px) {
                .premium-table thead {
                    display: none;
                }

                .premium-table tr {
                    display: block;
                    border-bottom: 1px solid var(--border);
                    padding-bottom: 1rem;
                    margin-bottom: 1rem;
                }

                .premium-table td {
                    display: block;
                    border: none !important;
                    padding: 0.5rem 0;
                    width: 100%;
                }

                .premium-table td::before {
                    content: attr(data-label);
                    font-weight: 700;
                    color: var(--accent);
                    margin-right: 1rem;
                    font-size: 0.8rem;
                    text-transform: uppercase;
                }
            }
        </style>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="glass-card stat-card">
                <div class="stat-label">Total Projects</div>
                <div class="stat-value"><?php echo $stats_projects; ?></div>
            </div>
            <div class="glass-card stat-card">
                <div class="stat-label">Active Programs</div>
                <div class="stat-value"><?php echo $stats_programs; ?></div>
            </div>
            <div class="glass-card stat-card">
                <div class="stat-label">Upcoming Events</div>
                <div class="stat-value"><?php echo $stats_events; ?></div>
            </div>
            <div class="glass-card stat-card">
                <div class="stat-label">Inbound Messages</div>
                <div class="stat-value"><?php echo $stats_messages; ?></div>
            </div>
        </div>

        <div class="content-grid">
            <!-- Recent Activity -->
            <div class="glass-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <h3 style="font-size: 1.2rem;"><i class="ri-mail-unread-line" style="color:var(--accent)"></i>
                        Recent Inquiries</h3>
                    <a href="messages.php" class="btn btn-secondary"
                        style="padding: 0.5rem 1rem; font-size: 0.85rem;">Manage All</a>
                </div>

                <?php if (count($recent_msgs) > 0): ?>
                    <table class="premium-table">
                        <thead>
                            <tr>
                                <th>Sender</th>
                                <th>Inquiry Type</th>
                                <th>Received</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_msgs as $msg): ?>
                                <tr>
                                    <td data-label="Sender">
                                        <div style="font-weight: 600;">
                                            <?php echo htmlspecialchars($msg['first_name'] . ' ' . $msg['last_name']); ?></div>
                                    </td>
                                    <td data-label="Type"><?php echo htmlspecialchars($msg['interest_type'] ?? 'Inquiry'); ?>
                                    </td>
                                    <td data-label="Date"><?php echo date('M d', strtotime($msg['created_at'])); ?></td>
                                    <td data-label="Status">
                                        <?php if ($msg['is_read']): ?>
                                            <span class="badge">Viewed</span>
                                        <?php else: ?>
                                            <span class="badge badge-accent">New</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="text-align: center; padding: 3rem; color: var(--muted);">No new messages to display.</div>
                <?php endif; ?>
            </div>

            <!-- Side Cards -->
            <div style="display: flex; flex-direction: column; gap: 2rem;">
                <!-- Events -->
                <div class="glass-card">
                    <h3 style="font-size: 1.2rem; margin-bottom: 1.5rem;"><i class="ri-calendar-todo-line"
                            style="color:var(--accent)"></i> Upcoming</h3>
                    <?php if (count($upcoming_events) > 0): ?>
                        <?php foreach ($upcoming_events as $event): ?>
                            <div class="event-card">
                                <div class="event-date-box">
                                    <span class="ed-day"><?php echo date('d', strtotime($event['event_date'])); ?></span>
                                    <span class="ed-month"><?php echo date('M', strtotime($event['event_date'])); ?></span>
                                </div>
                                <div class="event-info">
                                    <h4 style="font-size: 1rem; margin-bottom: 0.25rem; font-weight: 600;">
                                        <?php echo htmlspecialchars($event['title']); ?></h4>
                                    <p style="font-size: 0.8rem; color: var(--muted);"><i class="ri-map-pin-2-line"></i>
                                        <?php echo htmlspecialchars($event['location']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: var(--muted); font-size: 0.9rem;">No major events scheduled.</p>
                    <?php endif; ?>
                </div>

                <!-- Quick Access -->
                <div class="glass-card">
                    <h3 style="font-size: 1.2rem; margin-bottom: 1.5rem;"><i class="ri-flashlight-line"
                            style="color:var(--accent)"></i> Quick Access</h3>
                    <div class="qa-grid">
                        <a href="projects.php" class="qa-card">
                            <i class="ri-rocket-2-line"></i>
                            <span style="font-size: 0.85rem; font-weight: 600;">Projects</span>
                        </a>
                        <a href="events.php" class="qa-card">
                            <i class="ri-calendar-add-line"></i>
                            <span style="font-size: 0.85rem; font-weight: 600;">Events</span>
                        </a>
                        <a href="programs.php" class="qa-card">
                            <i class="ri-terminal-box-line"></i>
                            <span style="font-size: 0.85rem; font-weight: 600;">Programs</span>
                        </a>
                        <a href="settings.php" class="qa-card">
                            <i class="ri-shield-user-line"></i>
                            <span style="font-size: 0.85rem; font-weight: 600;">Security</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

</body>

</html>