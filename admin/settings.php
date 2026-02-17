<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_password'])) {
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    $admin_id = $_SESSION['admin_id'];

    if ($new_pass === $confirm_pass) {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password_hash = :pass WHERE id = :id");
        $stmt->bindParam(':pass', $hashed_pass);
        $stmt->bindParam(':id', $admin_id);

        if ($stmt->execute()) {
            $success = "Password updated successfully.";
        } else {
            $error = "Failed to update password.";
        }
    } else {
        $error = "Passwords do not match.";
    }
}

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

    .content-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 2rem;
        max-width: 600px;
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
        <a href="messages.php" class="menu-link"><i class="ri-mail-line"></i> Messages</a>
        <div class="menu-label">System</div>
        <a href="settings.php" class="menu-link active"><i class="ri-settings-line"></i> Settings</a>
        <a href="logout.php" class="menu-link" style="margin-top: auto; color: var(--error);"><i
                class="ri-logout-box-line"></i> Logout</a>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <div>
                <h1 class="section-title" style="font-size: 1.8rem; margin: 0;">Settings</h1>
                <p style="color: var(--muted); margin-top: 0.5rem;">Manage your account</p>
            </div>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert"
                style="background: rgba(0,201,167,0.1); color: var(--teal); border: 1px solid rgba(0,201,167,0.2); margin-bottom: 1rem; max-width: 600px;">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-error" style="margin-bottom: 1rem; max-width: 600px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="content-card">
            <h3 style="margin-bottom: 1.5rem; color: var(--white);">Change Password</h3>
            <form method="POST">
                <input type="hidden" name="update_password" value="1">
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-input" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
        </div>
    </main>
</div>
</body>

</html>