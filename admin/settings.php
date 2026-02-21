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

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header" style="margin-bottom: 3rem;">
            <h1 class="section-title">Security & Configuration</h1>
            <p class="section-subtitle">Credential management and system-wide administrative overrides.</p>
        </header>

        <div style="max-width: 650px; width: 100%;">
            <?php if (isset($success)): ?>
                <div class="glass-card"
                    style="padding: 1rem 1.5rem; margin-bottom: 2rem; border-color: var(--teal); background: rgba(0, 201, 167, 0.05); color: var(--teal); display: flex; align-items: center; gap: 0.75rem;">
                    <i class="ri-shield-check-line"></i>
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="glass-card"
                    style="padding: 1rem 1.5rem; margin-bottom: 2rem; border-color: #ff6b6b; background: rgba(255, 107, 107, 0.05); color: #ff6b6b; display: flex; align-items: center; gap: 0.75rem;">
                    <i class="ri-error-warning-line"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="glass-card" style="padding: 2.5rem;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                    <div
                        style="width: 45px; height: 45px; background: rgba(56, 189, 248, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--sky); border: 1px solid rgba(56, 189, 248, 0.2);">
                        <i class="ri-lock-password-line" style="font-size: 1.25rem;"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--white); margin: 0;">Authenticator
                            Update</h3>
                        <p style="color: var(--muted); font-size: 0.85rem; margin-top: 0.25rem;">Ensure your
                            administrative access remains resilient.</p>
                    </div>
                </div>

                <form method="POST">
                    <input type="hidden" name="update_password" value="1">
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label"
                            style="display: block; margin-bottom: 0.75rem; color: var(--muted); font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">New
                            Administrative Cipher</label>
                        <input type="password" name="new_password" class="form-input" required
                            placeholder="Enter complex password..."
                            style="width: 100%; padding: 1rem; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border); border-radius: 12px; color: var(--white); font-family: inherit; transition: all 0.3s ease;">
                    </div>

                    <div class="form-group" style="margin-bottom: 2rem;">
                        <label class="form-label"
                            style="display: block; margin-bottom: 0.75rem; color: var(--muted); font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Validate
                            Cipher</label>
                        <input type="password" name="confirm_password" class="form-input" required
                            placeholder="Redundant verification..."
                            style="width: 100%; padding: 1rem; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border); border-radius: 12px; color: var(--white); font-family: inherit; transition: all 0.3s ease;">
                    </div>

                    <button type="submit" class="btn btn-primary"
                        style="width: 100%; padding: 1rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; font-family: 'Syne', sans-serif;">
                        Commit Security Update
                    </button>
                </form>
            </div>

            <div class="glass-card" style="margin-top: 2rem; padding: 2rem; border-style: dashed; opacity: 0.8;">
                <div style="display: flex; align-items: flex-start; gap: 1rem;">
                    <i class="ri-information-line"
                        style="color: var(--accent); font-size: 1.25rem; margin-top: 0.1rem;"></i>
                    <div style="font-size: 0.85rem; color: var(--muted); line-height: 1.6;">
                        <strong style="color: var(--white); display: block; margin-bottom: 0.4rem;">Administrative
                            Guidelines:</strong>
                        Ciphers should exceed 12 characters, including a combination of alphanumeric symbols and
                        specialized character tokens. Frequent updates mitigate vector rotation risks.
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>

</html>