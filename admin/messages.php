<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include '../db_connect.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: messages.php?msg=Message deleted");
    exit;
}

// Mark as Read (Simple toggle or set to read on view)
if (isset($_GET['mark_read'])) {
    $id = $_GET['mark_read'];
    $stmt = $conn->prepare("UPDATE messages SET is_read = 1 WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: messages.php");
    exit;
}

// Fetch Messages
$messages = $conn->query("SELECT * FROM messages ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<style>
    /* Reusing dashboard styles + specific ones */
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

    /* Sidebar Links (Shared) */
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

    /* Table */
    .content-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 2rem;
        overflow: hidden;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .data-table th {
        text-align: left;
        padding: 1rem;
        color: var(--muted);
        border-bottom: 1px solid var(--border);
        font-weight: 600;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        color: var(--white);
        vertical-align: top;
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .action-btn {
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        font-size: 0.8rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: all 0.2s;
    }

    .btn-view {
        background: rgba(0, 201, 167, 0.15);
        color: var(--teal);
    }

    .btn-delete {
        background: rgba(255, 77, 79, 0.15);
        color: var(--error);
    }

    .btn-view:hover {
        background: rgba(0, 201, 167, 0.25);
    }

    .btn-delete:hover {
        background: rgba(255, 77, 79, 0.25);
    }

    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        background: rgba(255, 255, 255, 0.1);
        color: var(--muted);
    }

    .badge.unread {
        background: rgba(245, 166, 35, 0.15);
        color: var(--amber);
    }
</style>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="login-logo" style="justify-content: flex-start; margin-bottom: 0;">
            <i class="ri-anchor-line" style="color:var(--teal)"></i> BTIC<span>.</span>
        </div>
        <div class="menu-label">Main</div>
        <a href="index.php" class="menu-link"><i class="ri-dashboard-line"></i> Dashboard</a>
        <a href="projects.php" class="menu-link"><i class="ri-folder-line"></i> Projects</a>
        <a href="events.php" class="menu-link"><i class="ri-calendar-event-line"></i> Events</a>
        <a href="messages.php" class="menu-link active"><i class="ri-mail-line"></i> Messages</a>
        <div class="menu-label">System</div>
        <a href="settings.php" class="menu-link"><i class="ri-settings-line"></i> Settings</a>
        <a href="logout.php" class="menu-link" style="margin-top: auto; color: var(--error);"><i
                class="ri-logout-box-line"></i> Logout</a>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <div>
                <h1 class="section-title" style="font-size: 1.8rem; margin: 0;">Messages</h1>
                <p style="color: var(--muted); margin-top: 0.5rem;">Inquiries from the contact form</p>
            </div>
        </div>

        <div class="content-card">
            <?php if (count($messages) > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="150">From</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th width="100">Date</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $msg): ?>
                            <tr style="<?php echo $msg['is_read'] ? 'opacity: 0.7;' : ''; ?>">
                                <td>
                                    <div style="font-weight: 600;">
                                        <?php echo htmlspecialchars($msg['first_name'] . ' ' . $msg['last_name']); ?>
                                    </div>
                                    <div style="font-size: 0.8rem; color: var(--muted);">
                                        <?php echo htmlspecialchars($msg['email']); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!$msg['is_read']): ?><span class="badge unread"
                                            style="margin-right: 0.5rem;">NEW</span>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($msg['interest_type'] ?? 'General'); ?>
                                </td>
                                <td style="color: var(--muted);">
                                    <?php echo substr(htmlspecialchars($msg['message_body']), 0, 80) . '...'; ?>
                                </td>
                                <td style="font-size: 0.85rem; color: var(--muted);">
                                    <?php echo date('M j', strtotime($msg['created_at'])); ?>
                                </td>
                                <td>
                                    <?php if (!$msg['is_read']): ?>
                                        <a href="messages.php?mark_read=<?php echo $msg['id']; ?>" class="action-btn btn-view"
                                            title="Mark Read"><i class="ri-check-line"></i></a>
                                    <?php endif; ?>
                                    <a href="messages.php?delete=<?php echo $msg['id']; ?>" class="action-btn btn-delete"
                                        onclick="return confirm('Delete message?')" title="Delete"><i
                                            class="ri-delete-bin-line"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="text-align: center; padding: 3rem; color: var(--muted);">No messages found.</div>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>

</html>