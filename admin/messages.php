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

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header" style="margin-bottom: 3rem;">
            <h1 class="section-title">Communication Center</h1>
            <p class="section-subtitle">Review and manage inbound inquiries from club members and partners.</p>
        </header>

        <div class="glass-card" style="padding: 0; overflow: hidden;">
            <?php if (count($messages) > 0): ?>
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th width="200">Correspondent</th>
                            <th>Topic / Subject</th>
                            <th>Executive Summary</th>
                            <th width="120">Timestamp</th>
                            <th width="140">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $msg): ?>
                            <tr style="<?php echo $msg['is_read'] ? 'opacity: 0.65;' : ''; ?>">
                                <td data-label="Correspondent">
                                    <div style="font-weight: 700; color: var(--white);">
                                        <?php echo htmlspecialchars($msg['first_name'] . ' ' . $msg['last_name']); ?></div>
                                    <div style="font-size: 0.8rem; color: var(--muted); margin-top: 0.1rem;">
                                        <?php echo htmlspecialchars($msg['email']); ?></div>
                                </td>
                                <td data-label="Topic">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <?php if (!$msg['is_read']): ?>
                                            <span class="badge badge-accent">URGENT</span>
                                        <?php endif; ?>
                                        <span
                                            style="font-weight: 600; color: var(--muted);"><?php echo htmlspecialchars($msg['interest_type'] ?? 'Inquiry'); ?></span>
                                    </div>
                                </td>
                                <td data-label="Summary" style="color: var(--muted); line-height: 1.5;">
                                    <?php echo substr(htmlspecialchars($msg['message_body']), 0, 100) . '...'; ?>
                                </td>
                                <td data-label="Timestamp">
                                    <div style="font-size: 0.85rem; color: var(--muted); font-weight: 600;">
                                        <?php echo date('M d, Y', strtotime($msg['created_at'])); ?>
                                    </div>
                                </td>
                                <td data-label="Actions">
                                    <div style="display: flex; gap: 0.75rem;">
                                        <?php if (!$msg['is_read']): ?>
                                            <a href="messages.php?mark_read=<?php echo $msg['id']; ?>" class="btn btn-secondary"
                                                style="padding: 0.5rem; border-radius: 10px;" title="Mark as Read">
                                                <i class="ri-mail-open-line"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="messages.php?delete=<?php echo $msg['id']; ?>" class="btn"
                                            style="padding: 0.5rem; border-radius: 10px; background: rgba(255,107,107,0.1); color: #ff6b6b;"
                                            onclick="return confirm('Archive this message permanently?')" title="Delete">
                                            <i class="ri-delete-bin-7-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="text-align: center; padding: 5rem 2rem;">
                    <i class="ri-chat-history-line"
                        style="font-size: 3rem; color: var(--muted); opacity: 0.3; display: block; margin-bottom: 1rem;"></i>
                    <p style="color: var(--muted); font-weight: 500;">Your inbox is currently empty.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>

</html>
</body>

</html>