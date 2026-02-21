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

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header"
            style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; flex-wrap: wrap; gap: 1.5rem;">
            <div>
                <h1 class="section-title">Portfolio Assets</h1>
                <p class="section-subtitle">Manage and showcase the club's technical milestones.</p>
            </div>
            <button class="btn btn-primary" onclick="openModal()">
                <i class="ri-add-line"></i> Add New Project
            </button>
        </header>

        <?php if (isset($_GET['msg'])): ?>
            <div class="glass-card"
                style="padding: 1rem 1.5rem; margin-bottom: 2rem; border-color: var(--teal); background: rgba(0, 201, 167, 0.05); color: var(--teal); display: flex; align-items: center; gap: 0.75rem;">
                <i class="ri-checkbox-circle-line"></i>
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <div class="glass-card" style="padding: 0; overflow: hidden;">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th width="100">Display</th>
                        <th>Project Identity</th>
                        <th>Classification</th>
                        <th width="160">Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $proj): ?>
                        <tr>
                            <td data-label="Display">
                                <div
                                    style="width: 60px; height: 60px; border-radius: 12px; overflow: hidden; background: #000; border: 1px solid var(--border);">
                                    <?php if ($proj['image_url']): ?>
                                        <img src="<?php echo htmlspecialchars($proj['image_url']); ?>"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <div
                                            style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--muted);">
                                            <i class="ri-image-line" style="font-size: 1.2rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td data-label="Project Identity">
                                <div style="font-weight: 700; font-size: 1.1rem; color: var(--white);">
                                    <?php echo htmlspecialchars($proj['title']); ?>
                                </div>
                                <div style="font-size: 0.85rem; color: var(--muted); margin-top: 0.25rem;">
                                    <?php echo substr(htmlspecialchars($proj['description']), 0, 70) . '...'; ?>
                                </div>
                            </td>
                            <td data-label="Classification">
                                <span class="badge badge-accent"><?php echo htmlspecialchars($proj['category']); ?></span>
                            </td>
                            <td data-label="Control">
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="#" class="btn btn-secondary" style="padding: 0.5rem; border-radius: 10px;"
                                        title="Edit"><i class="ri-pencil-line"></i></a>
                                    <a href="projects.php?delete=<?php echo $proj['id']; ?>" class="btn"
                                        style="padding: 0.5rem; border-radius: 10px; background: rgba(255,107,107,0.1); color: #ff6b6b;"
                                        onclick="return confirm('Are you sure?')" title="Delete"><i
                                            class="ri-delete-bin-line"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Add Project Modal -->
<style>
    .modal-content-glass {
        width: 100%;
        max-width: 550px;
    }
</style>

<div class="modal-overlay" id="projectModal">
    <div class="glass-card modal-content-glass">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h3 style="font-size: 1.5rem;">Register New Project</h3>
            <button onclick="closeModal()"
                style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 1.5rem;">
                <i class="ri-close-circle-line"></i>
            </button>
        </header>

        <form method="POST">
            <input type="hidden" name="add_project" value="1">
            <div class="form-group">
                <label class="form-label">Project Designation</label>
                <input type="text" name="title" class="form-input" required placeholder="e.g. Autonomous Ocean Scout">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-input" style="appearance: none;">
                        <option value="IoT">IoT</option>
                        <option value="AI">AI / ML</option>
                        <option value="App">Software Systems</option>
                        <option value="Robotics">Hardware & Robotics</option>
                        <option value="Green Tech">Sustainable Tech</option>
                        <option value="Research">Academic Research</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Thumbnail URL</label>
                    <input type="text" name="image_url" class="form-input" placeholder="https://...">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Executive Summary</label>
                <textarea name="description" class="form-input" rows="4" required
                    placeholder="Describe the project objective and impact..."></textarea>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button type="button" onclick="closeModal()" class="btn btn-secondary" style="flex: 1;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex: 2;">Deploy Project Record</button>
            </div>
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