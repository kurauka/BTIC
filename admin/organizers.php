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
    $stmt = $conn->prepare("DELETE FROM organizers WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: organizers.php?msg=Organizer deleted successfully");
    exit;
}

// Handle Add Organizer
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_organizer'])) {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $bio = $_POST['bio'];
    $image_url = $_POST['image_url']; // For now, text input. Uploads can be added later.

    $stmt = $conn->prepare("INSERT INTO organizers (name, role, image_url, bio, display_order) VALUES (:name, :role, :image, :bio, 0)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':image', $image_url);
    $stmt->bindParam(':bio', $bio);

    if ($stmt->execute()) {
        header("Location: organizers.php?msg=Organizer added successfully");
        exit;
    } else {
        $error = "Failed to add organizer.";
    }
}

// Fetch Organizers
$organizers = $conn->query("SELECT * FROM organizers ORDER BY display_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<style>
    /* Reusing dashboard styles */
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
        vertical-align: middle;
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

    .btn-edit {
        background: rgba(56, 189, 248, 0.15);
        color: var(--sky);
    }

    .btn-delete {
        background: rgba(255, 77, 79, 0.15);
        color: var(--error);
    }

    .btn-edit:hover {
        background: rgba(56, 189, 248, 0.25);
    }

    .btn-delete:hover {
        background: rgba(255, 77, 79, 0.25);
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.8);
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal {
        background: #0a1625;
        width: 100%;
        max-width: 500px;
        padding: 2rem;
        border-radius: 12px;
        border: 1px solid var(--border);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .close-modal {
        background: none;
        border: none;
        color: var(--muted);
        font-size: 1.5rem;
        cursor: pointer;
    }

    .org-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        background: var(--border);
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
        <a href="programs.php" class="menu-link"><i class="ri-code-s-slash-line"></i> Programs</a>
        <a href="events.php" class="menu-link"><i class="ri-calendar-event-line"></i> Events</a>
        <a href="messages.php" class="menu-link"><i class="ri-mail-line"></i> Messages</a>
        <div class="menu-label">Team</div>
        <a href="organizers.php" class="menu-link active"><i class="ri-team-line"></i> Organizers</a>
        <a href="partners.php" class="menu-link"><i class="ri-shake-hands-line"></i> Partners</a>
        <div class="menu-label">System</div>
        <a href="settings.php" class="menu-link"><i class="ri-settings-line"></i> Settings</a>
        <a href="logout.php" class="menu-link" style="margin-top: auto; color: var(--error);"><i
                class="ri-logout-box-line"></i> Logout</a>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <div>
                <h1 class="section-title" style="font-size: 1.8rem; margin: 0;">Organizers</h1>
                <p style="color: var(--muted); margin-top: 0.5rem;">Manage the team members</p>
            </div>
            <button class="btn btn-primary" onclick="openModal()">
                <i class="ri-add-line"></i> Add Member
            </button>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert"
                style="background: rgba(0,201,167,0.1); color: var(--teal); border: 1px solid rgba(0,201,167,0.2);">
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <div class="content-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="60">Img</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Bio</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($organizers as $org): ?>
                        <tr>
                            <td>
                                <?php if ($org['image_url']): ?>
                                    <img src="<?php echo htmlspecialchars($org['image_url']); ?>" class="org-avatar"
                                        alt="Avatar">
                                <?php else: ?>
                                    <div class="org-avatar"
                                        style="display:flex;align-items:center;justify-content:center;color:var(--muted);"><i
                                            class="ri-user-line"></i></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="font-weight: 600;">
                                    <?php echo htmlspecialchars($org['name']); ?>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.9rem; color: var(--teal);">
                                    <?php echo htmlspecialchars($org['role']); ?>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.85rem; color: var(--muted);">
                                    <?php echo substr(htmlspecialchars($org['bio']), 0, 50) . '...'; ?>
                                </div>
                            </td>
                            <td>
                                <a href="#" class="action-btn btn-edit"><i class="ri-pencil-line"></i></a>
                                <a href="organizers.php?delete=<?php echo $org['id']; ?>" class="action-btn btn-delete"
                                    onclick="return confirm('Are you sure?')"><i class="ri-delete-bin-line"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Add Organizer Modal -->
<div class="modal-overlay" id="orgModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Add Team Member</h3>
            <button class="close-modal" onclick="closeModal()"><i class="ri-close-line"></i></button>
        </div>
        <form method="POST">
            <input type="hidden" name="add_organizer" value="1">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-input" required placeholder="Jane Doe">
            </div>
            <div class="form-group">
                <label class="form-label">Role</label>
                <input type="text" name="role" class="form-input" required placeholder="Club Patron / Chairperson">
            </div>
            <div class="form-group">
                <label class="form-label">Image URL</label>
                <input type="text" name="image_url" class="form-input" placeholder="https://...">
                <small style="color: var(--muted); font-size: 0.8rem;">Link to profile photo</small>
            </div>
            <div class="form-group">
                <label class="form-label">Bio</label>
                <textarea name="bio" class="form-input" rows="3" placeholder="Short biography..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Add Member</button>
        </form>
    </div>
</div>

<script>
    function openModal() { document.getElementById('orgModal').classList.add('active'); }
    function closeModal() { document.getElementById('orgModal').classList.remove('active'); }
</script>
</body>

</html>