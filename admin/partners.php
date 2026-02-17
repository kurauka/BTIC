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
    $stmt = $conn->prepare("DELETE FROM partners WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: partners.php?msg=Partner deleted successfully");
    exit;
}

// Handle Add Partner
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_partner'])) {
    $name = $_POST['name'];
    $website_url = $_POST['website_url'];
    $logo_url = $_POST['logo_url']; // Text input for now

    $stmt = $conn->prepare("INSERT INTO partners (name, website_url, logo_url, display_order) VALUES (:name, :website, :logo, 0)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':website', $website_url);
    $stmt->bindParam(':logo', $logo_url);

    if ($stmt->execute()) {
        header("Location: partners.php?msg=Partner added successfully");
        exit;
    } else {
        $error = "Failed to add partner.";
    }
}

// Fetch Partners
$partners = $conn->query("SELECT * FROM partners ORDER BY display_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);

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

    .partner-logo {
        width: 80px;
        height: 40px;
        object-fit: contain;
        background: rgba(255, 255, 255, 0.05);
        padding: 5px;
        border-radius: 4px;
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
        <a href="organizers.php" class="menu-link"><i class="ri-team-line"></i> Organizers</a>
        <a href="partners.php" class="menu-link active"><i class="ri-shake-hands-line"></i> Partners</a>
        <div class="menu-label">System</div>
        <a href="settings.php" class="menu-link"><i class="ri-settings-line"></i> Settings</a>
        <a href="logout.php" class="menu-link" style="margin-top: auto; color: var(--error);"><i
                class="ri-logout-box-line"></i> Logout</a>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <div>
                <h1 class="section-title" style="font-size: 1.8rem; margin: 0;">Partners</h1>
                <p style="color: var(--muted); margin-top: 0.5rem;">Manage industry partners and sponsors</p>
            </div>
            <button class="btn btn-primary" onclick="openModal()">
                <i class="ri-add-line"></i> Add Partner
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
                        <th width="100">Logo</th>
                        <th>Name</th>
                        <th>Website</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($partners as $p): ?>
                        <tr>
                            <td>
                                <?php if ($p['logo_url']): ?>
                                    <img src="<?php echo htmlspecialchars($p['logo_url']); ?>" class="partner-logo" alt="Logo">
                                <?php else: ?>
                                    <div class="partner-logo"
                                        style="display:flex;align-items:center;justify-content:center;color:var(--muted);">N/A
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="font-weight: 600;">
                                    <?php echo htmlspecialchars($p['name']); ?>
                                </div>
                            </td>
                            <td>
                                <a href="<?php echo htmlspecialchars($p['website_url']); ?>" target="_blank"
                                    style="color: var(--sky); font-size: 0.9rem; text-decoration: none;">
                                    <?php echo htmlspecialchars($p['website_url']); ?> <i class="ri-external-link-line"></i>
                                </a>
                            </td>
                            <td>
                                <a href="#" class="action-btn btn-edit"><i class="ri-pencil-line"></i></a>
                                <a href="partners.php?delete=<?php echo $p['id']; ?>" class="action-btn btn-delete"
                                    onclick="return confirm('Are you sure?')"><i class="ri-delete-bin-line"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Add Partner Modal -->
<div class="modal-overlay" id="partnerModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Add Partner</h3>
            <button class="close-modal" onclick="closeModal()"><i class="ri-close-line"></i></button>
        </div>
        <form method="POST">
            <input type="hidden" name="add_partner" value="1">
            <div class="form-group">
                <label class="form-label">Partner Name</label>
                <input type="text" name="name" class="form-input" required placeholder="Company Name">
            </div>
            <div class="form-group">
                <label class="form-label">Logo URL</label>
                <input type="text" name="logo_url" class="form-input" placeholder="https://...">
            </div>
            <div class="form-group">
                <label class="form-label">Website URL</label>
                <input type="text" name="website_url" class="form-input" placeholder="https://..." value="#">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Add Partner</button>
        </form>
    </div>
</div>

<script>
    function openModal() { document.getElementById('partnerModal').classList.add('active'); }
    function closeModal() { document.getElementById('partnerModal').classList.remove('active'); }
</script>
</body>

</html>