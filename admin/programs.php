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
    $stmt = $conn->prepare("DELETE FROM programs WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: programs.php?msg=Program deleted successfully");
    exit;
}

// Handle Add Program
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_program'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $icon_class = $_POST['icon_class'];
    // Simple auto-increment for display_order or just default 0

    $stmt = $conn->prepare("INSERT INTO programs (title, description, icon_class, display_order) VALUES (:title, :description, :icon, 0)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':icon', $icon_class);

    if ($stmt->execute()) {
        header("Location: programs.php?msg=Program added successfully");
        exit;
    } else {
        $error = "Failed to add program.";
    }
}

// Fetch Programs
$programs = $conn->query("SELECT * FROM programs ORDER BY display_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header"
            style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; flex-wrap: wrap; gap: 1.5rem;">
            <div>
                <h1 class="section-title">Academic Programs</h1>
                <p class="section-subtitle">Curate and refine the core initiatives and workshop curricula.</p>
            </div>
            <button class="btn btn-primary" onclick="openModal()">
                <i class="ri-code-box-line"></i> Initialize Program
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
                        <th width="80">Symbol</th>
                        <th>Program Designation</th>
                        <th>Curriculum Overview</th>
                        <th width="140">Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($programs as $prog): ?>
                        <tr>
                            <td data-label="Symbol">
                                <div
                                    style="width: 45px; height: 45px; background: rgba(0, 201, 167, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--teal); border: 1px solid rgba(0, 201, 167, 0.2);">
                                    <i class="<?php echo htmlspecialchars($prog['icon_class'] ?: 'ri-code-line'); ?>"
                                        style="font-size: 1.25rem;"></i>
                                </div>
                            </td>
                            <td data-label="Designation">
                                <div style="font-weight: 700; color: var(--white); font-size: 1.1rem;">
                                    <?php echo htmlspecialchars($prog['title']); ?>
                                </div>
                            </td>
                            <td data-label="Overview" style="color: var(--muted); line-height: 1.5; font-size: 0.9rem;">
                                <?php echo substr(htmlspecialchars($prog['description']), 0, 100) . '...'; ?>
                            </td>
                            <td data-label="Control">
                                <div style="display: flex; gap: 0.75rem;">
                                    <a href="#" class="btn btn-secondary" style="padding: 0.5rem; border-radius: 10px;"
                                        title="Modify Syllabus"><i class="ri-terminal-window-line"></i></a>
                                    <a href="programs.php?delete=<?php echo $prog['id']; ?>" class="btn"
                                        style="padding: 0.5rem; border-radius: 10px; background: rgba(255,107,107,0.1); color: #ff6b6b;"
                                        onclick="return confirm('Decommission this academic program?')" title="Delete"><i
                                            class="ri-rest-time-line"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Initialization Modal -->
<style>
    .modal-content-glass {
        width: 100%;
        max-width: 550px;
    }
</style>

<div class="modal-overlay" id="programModal">
    <div class="glass-card modal-content-glass">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h3 style="font-size: 1.5rem;">Initialize Initiative</h3>
            <button onclick="closeModal()"
                style="background: none; border: none; color: var(--muted); cursor: pointer; font-size: 1.5rem;">
                <i class="ri-close-circle-line"></i>
            </button>
        </header>

        <form method="POST">
            <input type="hidden" name="add_program" value="1">
            <div class="form-group">
                <label class="form-label">Program Designation</label>
                <input type="text" name="title" class="form-input" required
                    placeholder="e.g. Advanced Cybersecurity Workshop">
            </div>

            <div class="form-group">
                <label class="form-label">Remix Icon Blueprint</label>
                <input type="text" name="icon_class" class="form-input" placeholder="ri-shield-flash-line">
                <small style="color: var(--muted); font-size: 0.8rem; margin-top: 0.5rem; display: block;">Visit
                    [remixicon.com](https://remixicon.com) for available glyphs.</small>
            </div>

            <div class="form-group">
                <label class="form-label">Syllabus Overview</label>
                <textarea name="description" class="form-input" rows="4" required
                    placeholder="Detail the technical objectives, technologies involved, and expected learning outcomes..."></textarea>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button type="button" onclick="closeModal()" class="btn btn-secondary" style="flex: 1;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex: 2;">Commit Initiative</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() { document.getElementById('programModal').classList.add('active'); }
    function closeModal() { document.getElementById('programModal').classList.remove('active'); }

    // Auto-open modal if query param exists
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('action') === 'add') {
        openModal();
    }
</script>
</body>

</html>