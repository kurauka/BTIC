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
    $stmt = $conn->prepare("DELETE FROM projects WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: projects.php?msg=Project deleted successfully");
    exit;
}

// Handle Add Project
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_project'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    // For now, simple URL input for image. handling file upload is more complex, keeping it simple for MVP.
    // user can put a path or URL.
    $image_url = $_POST['image_url'];

    $stmt = $conn->prepare("INSERT INTO projects (title, category, description, image_url) VALUES (:title, :category, :description, :image_url)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image_url', $image_url);

    if ($stmt->execute()) {
        header("Location: projects.php?msg=Project added successfully");
        exit;
    } else {
        $error = "Failed to add project.";
    }
}

// Fetch Projects
$projects = $conn->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

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

    /* Table & Forms */
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

    /* Form */
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
</style>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="login-logo" style="justify-content: flex-start; margin-bottom: 0;">
            <i class="ri-anchor-line" style="color:var(--teal)"></i> BTIC<span>.</span>
        </div>
        <div class="menu-label">Main</div>
        <a href="index.php" class="menu-link"><i class="ri-dashboard-line"></i> Dashboard</a>
        <a href="projects.php" class="menu-link active"><i class="ri-folder-line"></i> Projects</a>
        <a href="events.php" class="menu-link"><i class="ri-calendar-event-line"></i> Events</a>
        <a href="messages.php" class="menu-link"><i class="ri-mail-line"></i> Messages</a>
        <div class="menu-label">System</div>
        <a href="settings.php" class="menu-link"><i class="ri-settings-line"></i> Settings</a>
        <a href="logout.php" class="menu-link" style="margin-top: auto; color: var(--error);"><i
                class="ri-logout-box-line"></i> Logout</a>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <div>
                <h1 class="section-title" style="font-size: 1.8rem; margin: 0;">Projects</h1>
                <p style="color: var(--muted); margin-top: 0.5rem;">Manage the club's portfolio</p>
            </div>
            <button class="btn btn-primary" onclick="openModal()">
                <i class="ri-add-line"></i> Add Project
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
                        <th width="80">Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $proj): ?>
                        <tr>
                            <td>
                                <div
                                    style="width: 50px; height: 50px; border-radius: 6px; overflow: hidden; background: #000;">
                                    <?php if ($proj['image_url']): ?>
                                        <img src="<?php echo htmlspecialchars($proj['image_url']); ?>"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <div
                                            style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--muted);">
                                            <i class="ri-image-line"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 600;">
                                    <?php echo htmlspecialchars($proj['title']); ?>
                                </div>
                                <div style="font-size: 0.85rem; color: var(--muted);">
                                    <?php echo substr(htmlspecialchars($proj['description']), 0, 50) . '...'; ?>
                                </div>
                            </td>
                            <td><span class="badge">
                                    <?php echo htmlspecialchars($proj['category']); ?>
                                </span></td>
                            <td>
                                <a href="#" class="action-btn btn-edit"><i class="ri-pencil-line"></i></a>
                                <a href="projects.php?delete=<?php echo $proj['id']; ?>" class="action-btn btn-delete"
                                    onclick="return confirm('Are you sure?')"><i class="ri-delete-bin-line"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Add Project Modal -->
<div class="modal-overlay" id="projectModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Add New Project</h3>
            <button class="close-modal" onclick="closeModal()"><i class="ri-close-line"></i></button>
        </div>
        <form method="POST">
            <input type="hidden" name="add_project" value="1">
            <div class="form-group">
                <label class="form-label">Project Title</label>
                <input type="text" name="title" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category" class="form-input">
                    <option value="IoT">IoT</option>
                    <option value="AI">AI</option>
                    <option value="App">App Development</option>
                    <option value="Robotics">Robotics</option>
                    <option value="Green Tech">Green Tech</option>
                    <option value="Research">Research</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Image URL / Path</label>
                <input type="text" name="image_url" class="form-input" placeholder="../assets/images/project.jpg">
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Create Project</button>
        </form>
    </div>
</div>

<script>
    function openModal() { document.getElementById('projectModal').classList.add('active'); }
    function closeModal() { document.getElementById('projectModal').classList.remove('active'); }

    // Auto-open modal if query param exists
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('action') === 'add') {
        openModal();
    }
</script>
</body>

</html>