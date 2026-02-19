<?php
session_start();
// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}
include 'includes/header.php';
?>

<div
    style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; position: relative; z-index: 10;">
    <div class="glass-card" style="width: 100%; max-width: 450px; padding: 3rem;">
        <header style="text-align: center; margin-bottom: 2.5rem;">
            <div
                style="font-size: 2.5rem; color: var(--accent); margin-bottom: 0.5rem; font-family: 'Syne', sans-serif; font-weight: 800; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                <i class="ri-anchor-line"></i>
                <span>BTIC<span style="color: var(--white);">.</span></span>
            </div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--white);">Administrative Gateway</h2>
            <p style="color: var(--muted); font-size: 0.9rem; margin-top: 0.5rem;">Secure access to the command center
            </p>
        </header>

        <?php if (isset($_GET['error'])): ?>
            <div class="glass-card"
                style="padding: 1rem; margin-bottom: 2rem; border-color: #ff6b6b; background: rgba(255, 107, 107, 0.05); color: #ff6b6b; display: flex; align-items: center; gap: 0.75rem; font-size: 0.9rem;">
                <i class="ri-error-warning-line"></i>
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form action="auth.php" method="POST">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label"
                    style="display: block; margin-bottom: 0.75rem; color: var(--muted); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em;">Identity
                    Username</label>
                <div style="position: relative;">
                    <i class="ri-user-6-line"
                        style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: var(--muted); opacity: 0.6;"></i>
                    <input type="text" name="username" class="form-input" required autofocus placeholder="admin_btic_01"
                        style="width: 100%; padding: 1rem 1rem 1rem 3.5rem; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border); border-radius: 12px; color: var(--white); font-family: inherit; transition: all 0.3s ease;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 2.5rem;">
                <label class="form-label"
                    style="display: block; margin-bottom: 0.75rem; color: var(--muted); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em;">Access
                    Cipher</label>
                <div style="position: relative;">
                    <i class="ri-lock-2-line"
                        style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: var(--muted); opacity: 0.6;"></i>
                    <input type="password" name="password" class="form-input" required placeholder="••••••••••••"
                        style="width: 100%; padding: 1rem 1rem 1rem 3.5rem; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border); border-radius: 12px; color: var(--white); font-family: inherit; transition: all 0.3s ease;">
                </div>
            </div>

            <button type="submit" class="btn btn-primary"
                style="width: 100%; padding: 1.1rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.15em; font-family: 'Syne', sans-serif; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                <span>Initialize Session</span>
                <i class="ri-arrow-right-line"></i>
            </button>
        </form>

        <footer style="margin-top: 3rem; text-align: center; border-top: 1px solid var(--border); padding-top: 1.5rem;">
            <a href="../index.php"
                style="color: var(--muted); text-decoration: none; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.5rem; transition: color 0.3s ease;"
                onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--muted)'">
                <i class="ri-arrow-left-s-line"></i>
                <span>Return to Public Terminal</span>
            </a>
        </footer>
    </div>
</div>
</body>

</html>