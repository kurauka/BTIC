<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include 'includes/header.php';
?>

<style>
    /* Dashboard Specific Layout */
    .dashboard-wrapper {
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
        width: 260px;
        background: rgba(5, 13, 26, 0.9);
        border-right: 1px solid var(--border);
        padding: 2rem 1.5rem;
        display: flex;
        flex-direction: column;
        position: fixed;
        height: 100vh;
    }

    .main-content {
        flex: 1;
        margin-left: 260px;
        padding: 2rem;
    }

    .menu-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--muted);
        margin-bottom: 1rem;
        margin-top: 2rem;
    }

    .menu-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: var(--white);
        text-decoration: none;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        transition: all 0.3s;
    }

    .menu-link:hover,
    .menu-link.active {
        background: rgba(0, 201, 167, 0.1);
        color: var(--teal);
    }

    .menu-link i {
        font-size: 1.1rem;
    }

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
    }

    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
    }

    .dash-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        padding: 1.5rem;
        border-radius: 12px;
    }

    .dash-title {
        color: var(--muted);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .dash-value {
        font-family: 'Syne', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--white);
    }
</style>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="login-logo" style="justify-content: flex-start; margin-bottom: 0;">
            <i class="ri-anchor-line" style="color:var(--teal)"></i> BTIC<span>.</span>
        </div>

        <div class="menu-label">Main</div>
        <a href="index.php" class="menu-link active"><i class="ri-dashboard-line"></i> Dashboard</a>
        <a href="projects.php" class="menu-link"><i class="ri-folder-line"></i> Projects</a>
        <a href="events.php" class="menu-link"><i class="ri-calendar-event-line"></i> Events</a>
        <a href="messages.php" class="menu-link"><i class="ri-mail-line"></i> Messages</a>

        <div class="menu-label">Team</div>
        <a href="organizers.php" class="menu-link"><i class="ri-team-line"></i> Organizers</a>
        <a href="partners.php" class="menu-link"><i class="ri-shake-hands-line"></i> Partners</a>

        <div class="menu-label">System</div>
        <a href="settings.php" class="menu-link"><i class="ri-settings-line"></i> Settings</a>
        <a href="logout.php" class="menu-link" style="margin-top: auto; color: var(--error);"><i
                class="ri-logout-box-line"></i> Logout</a>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="top-bar">
            <h1 class="section-title" style="font-size: 1.8rem; margin: 0;">Dashboard</h1>
            <div class="user-profile">
                Welcome, <span style="color: var(--teal); font-weight: 600;">
                    <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                </span>
            </div>
        </div>

        <?php
        // Fetch Stats
        include '../db_connect.php';

        // Stats
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
            .content-grid {
                display: grid;
                grid-template-columns: 2fr 1fr;
                gap: 1.5rem;
                margin-top: 2rem;
            }

            .section-card {
                background: var(--card-bg);
                border: 1px solid var(--border);
                border-radius: 12px;
                padding: 1.5rem;
                display: flex;
                flex-direction: column;
            }

            .section-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.5rem;
            }

            .section-title-sm {
                font-size: 1rem;
                font-weight: 600;
                color: var(--white);
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            /* Table Styles */
            .msg-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 0.9rem;
            }

            .msg-table th {
                text-align: left;
                color: var(--muted);
                font-weight: 500;
                padding-bottom: 0.75rem;
                border-bottom: 1px solid var(--border);
            }

            .msg-table td {
                padding: 0.75rem 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
                color: var(--white);
            }

            .msg-table tr:last-child td {
                border-bottom: none;
            }

            .badge {
                font-size: 0.75rem;
                padding: 0.2rem 0.5rem;
                border-radius: 4px;
                background: rgba(255, 255, 255, 0.1);
                color: var(--muted);
            }

            .badge.unread {
                background: rgba(0, 201, 167, 0.15);
                color: var(--teal);
            }

            /* Event List */
            .event-item {
                display: flex;
                gap: 1rem;
                padding: 1rem 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }

            .event-item:last-child {
                border-bottom: none;
            }

            .event-date {
                background: rgba(255, 255, 255, 0.05);
                border-radius: 8px;
                padding: 0.5rem;
                text-align: center;
                min-width: 60px;
                height: fit-content;
            }

            .ed-day {
                display: block;
                font-size: 1.1rem;
                font-weight: 700;
                color: var(--teal);
            }

            .ed-month {
                display: block;
                font-size: 0.75rem;
                text-transform: uppercase;
                color: var(--muted);
            }

            .event-info h4 {
                font-size: 0.95rem;
                margin-bottom: 0.25rem;
                font-weight: 500;
            }

            .event-info p {
                font-size: 0.8rem;
                color: var(--muted);
            }

            /* Quick Actions */
            .quick-actions {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
                margin-top: auto;
            }

            .qa-btn {
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid var(--border);
                padding: 0.75rem;
                border-radius: 8px;
                color: var(--muted);
                text-align: center;
                text-decoration: none;
                font-size: 0.85rem;
                transition: all 0.3s;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.5rem;
            }

            .qa-btn:hover {
                background: rgba(0, 201, 167, 0.1);
                color: var(--teal);
                border-color: var(--teal);
            }

            .qa-btn i {
                font-size: 1.25rem;
            }
        </style>

        <!-- Stats -->
        <div class="card-grid">
            <div class="dash-card">
                <div class="dash-title">Total Projects</div>
                <div class="dash-value"><?php echo $stats_projects; ?></div>
            </div>
            <div class="dash-card">
                <div class="dash-title">Active Programs</div>
                <div class="dash-value"><?php echo $stats_programs; ?></div>
            </div>
            <div class="dash-card">
                <div class="dash-title">Upcoming Events</div>
                <div class="dash-value"><?php echo $stats_events; ?></div>
            </div>
            <div class="dash-card">
                <div class="dash-title">Unread Messages</div>
                <div class="dash-value"><?php echo $stats_messages; ?></div>
            </div>
        </div>

        <div class="content-grid">
            <!-- Recent Messages -->
            <div class="section-card">
                <div class="section-header">
                    <div class="section-title-sm"><i class="ri-mail-line"></i> Recent Messages</div>
                    <a href="#" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">View
                        All</a>
                </div>

                <?php if (count($recent_msgs) > 0): ?>
                    <table class="msg-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_msgs as $msg): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($msg['first_name'] . ' ' . $msg['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['interest_type'] ?? 'Inquiry'); ?></td>
                                    <td><?php echo date('M j', strtotime($msg['created_at'])); ?></td>
                                    <td>
                                        <?php if ($msg['is_read']): ?>
                                            <span class="badge">Read</span>
                                        <?php else: ?>
                                            <span class="badge unread">Unread</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="color: var(--muted); text-align: center; padding: 2rem;">No messages found.</p>
                <?php endif; ?>
            </div>

            <!-- Right Column -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <!-- Upcoming Events -->
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-title-sm"><i class="ri-calendar-event-line"></i> Upcoming Events</div>
                    </div>

                    <?php if (count($upcoming_events) > 0): ?>
                        <div class="event-list">
                            <?php foreach ($upcoming_events as $event): ?>
                                <div class="event-item">
                                    <div class="event-date">
                                        <span class="ed-day"><?php echo date('d', strtotime($event['event_date'])); ?></span>
                                        <span class="ed-month"><?php echo date('M', strtotime($event['event_date'])); ?></span>
                                    </div>
                                    <div class="event-info">
                                        <h4><?php echo htmlspecialchars($event['title']); ?></h4>
                                        <p><i class="ri-map-pin-line"></i> <?php echo htmlspecialchars($event['location']); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p style="color: var(--muted); text-align: center; padding: 1rem;">No upcoming events.</p>
                    <?php endif; ?>
                </div>

                <!-- Quick Actions -->
                <div class="section-card" style="flex: 1;">
                    <div class="section-header">
                        <div class="section-title-sm"><i class="ri-flashlight-line"></i> Quick Actions</div>
                    </div>
                    <div class="quick-actions">
                        <a href="projects.php?action=add" class="qa-btn">
                            <i class="ri-add-circle-line"></i>
                            Add Project
                        </a>
                        <a href="events.php?action=add" class="qa-btn">
                            <i class="ri-calendar-check-line"></i>
                            Post Event
                        </a>
                        <a href="programs.php?action=add" class="qa-btn">
                            <i class="ri-code-s-slash-line"></i>
                            Add Program
                        </a>
                        <a href="settings.php" class="qa-btn">
                            <i class="ri-settings-3-line"></i>
                            Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
</div>
</main>
</div>

</body>

</html>